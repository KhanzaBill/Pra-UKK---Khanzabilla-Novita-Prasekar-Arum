<?php

namespace App\Http\Controllers; // Mendefinisikan namespace controller berada di App\Http\Controllers

use Illuminate\Http\Request;              // Import class Request untuk menangani input HTTP
use App\Models\Pesanan;                   // Import model Pesanan untuk akses data transaksi pesanan
use App\Models\Bahan;                     // Import model Bahan untuk akses data bahan baku (keperluan pengembalian stok)
use Illuminate\Support\Facades\DB;        // Import facade DB untuk transaksi database
use Carbon\Carbon;                        // Import library Carbon untuk manipulasi tanggal dan waktu

// Controller untuk mengelola pesanan masuk oleh admin/kasir
class AdminOrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan masuk.
     * Mendukung filter berdasarkan status pesanan dan paginasi.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status'); // Ambil filter status pesanan dari query string (opsional)

        // Mulai query Pesanan dengan eager load relasi meja, detail pesanan, menu, dan tambahan
        $query = Pesanan::with(['meja', 'detailPesanans.menu', 'detailPesanans.tambahans'])
                    ->orderBy('created_at', 'desc'); // Urutkan berdasarkan waktu dibuat, terbaru di atas

        // Terapkan filter status jika ada
        if ($statusFilter) {
            $query->where('status', $statusFilter); // Filter pesanan berdasarkan status yang dipilih
        }

        $pesanans = $query->paginate(10)->withQueryString(); // Paginasi 10 pesanan per halaman, pertahankan query string

        // Kirim data ke view dengan status filter aktif
        return view('admin.orders.index', compact('pesanans', 'statusFilter'));
    }

    /**
     * Memperbarui status pesanan (misal: dari Diterima → Diproses → Disiapkan → Selesai / Dibatalkan).
     * Jika status diubah ke 'Dibatalkan', stok bahan dikembalikan secara otomatis dalam transaksi database.
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status baru dan alasan pembatalan (wajib jika status = Dibatalkan)
        $request->validate([
            'status'              => 'required|in:Diterima,Diproses,Disiapkan,Selesai,Dibatalkan', // Status harus salah satu dari nilai yang valid
            'alasan_pembatalan'   => 'required_if:status,Dibatalkan|nullable|string'               // Alasan wajib diisi jika status Dibatalkan
        ]);

        // Ambil pesanan beserta semua relasinya yang diperlukan untuk proses pengembalian stok
        $pesanan = Pesanan::with(['detailPesanans.menu.bahans', 'detailPesanans.tambahans.bahans'])->findOrFail($id);

        // Jalankan dalam transaksi database agar perubahan stok dan status pesanan atomik (semua berhasil atau semua gagal)
        DB::transaction(function () use ($pesanan, $request) {

            // Proses pengembalian stok hanya jika status baru adalah 'Dibatalkan' dan pesanan sebelumnya belum dibatalkan
            if ($request->status === 'Dibatalkan' && $pesanan->status !== 'Dibatalkan') {

                // Iterasi setiap item detail dalam pesanan
                foreach ($pesanan->detailPesanans as $detail) {
                    $qty = $detail->jumlah; // Jumlah porsi item ini yang harus dikembalikan stoknya

                    // Kembalikan stok bahan dari Menu
                    if ($detail->menu && $detail->menu->bahans) {
                        foreach ($detail->menu->bahans as $bahan) { // Iterasi setiap bahan yang dibutuhkan menu ini
                            $targetBahan = $bahan; // Default: kembalikan stok ke bahan ini

                            // Khusus untuk bahan "Ayam", cek bagian spesifik dari catatan (Dada/Paha Atas/Paha Bawah/Sayap)
                            if (str_starts_with($bahan->nama_bahan, 'Ayam ') && $detail->catatan && preg_match('/Bagian:\s*(Dada|Paha Atas|Paha Bawah|Sayap)/i', $detail->catatan, $matches)) {
                                $bagian = $matches[1]; // Ambil bagian ayam dari catatan (misal: "Dada")
                                $specificBahan = Bahan::where('nama_bahan', 'Ayam ' . $bagian)->first(); // Cari bahan spesifik (misal: "Ayam Dada")
                                if ($specificBahan) {
                                    $targetBahan = $specificBahan; // Ganti target bahan ke yang spesifik
                                }
                            }

                            $kebutuhan = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty; // Hitung total kebutuhan bahan: jumlah_dibutuhkan × porsi
                            $targetBahan->increment('stok', $kebutuhan);                  // Tambahkan kembali stok bahan sebesar kebutuhan
                        }
                    }

                    // Kembalikan stok bahan dari Tambahan yang dipilih di item ini
                    if ($detail->tambahans) {
                        foreach ($detail->tambahans as $tambahan) { // Iterasi setiap tambahan di item ini
                            if ($tambahan->bahans) {
                                foreach ($tambahan->bahans as $bahan) { // Iterasi setiap bahan dari tambahan
                                    $kebutuhan = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty; // Hitung total kebutuhan bahan tambahan
                                    $bahan->increment('stok', $kebutuhan);                        // Kembalikan stok bahan tambahan
                                }
                            }
                        }
                    }
                }

                $pesanan->alasan_pembatalan = $request->alasan_pembatalan; // Simpan alasan pembatalan ke objek pesanan
            }

            $pesanan->status   = $request->status;       // Update status pesanan ke nilai yang baru
            $pesanan->id_admin = session('admin_id');     // Catat ID admin yang memproses perubahan ini
            $pesanan->save();                             // Simpan semua perubahan ke database
        });

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status pesanan #' . $pesanan->id_pesanan . ' berhasil diperbarui!');
    }

    /**
     * Memperbarui status pembayaran pesanan.
     * Jika pembayaran Lunas dengan metode Tunai, hitung dan simpan kembalian.
     * Jika opsi auto_selesai diaktifkan, status pesanan juga diubah ke 'Selesai'.
     */
    public function updatePayment(Request $request, $id)
    {
        // Validasi input status pembayaran, uang dibayar (opsional), dan opsi auto selesai
        $request->validate([
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas', // Status pembayaran hanya dua pilihan
            'uang_dibayar'      => 'nullable|numeric|min:0',         // Uang dibayar opsional, harus angka positif
            'auto_selesai'      => 'nullable'                         // Flag untuk auto-set status pesanan ke Selesai
        ]);

        $pesanan = Pesanan::findOrFail($id);                              // Ambil pesanan berdasarkan ID, throw 404 jika tidak ada
        $pesanan->status_pembayaran = $request->status_pembayaran;       // Update status pembayaran

        // Proses tambahan khusus jika pembayaran dinyatakan Lunas
        if ($request->status_pembayaran === 'Lunas') {

            // Hitung kembalian untuk pembayaran tunai
            if ($pesanan->metode_bayar === 'Tunai') {
                $uangDibayar = (int) $request->uang_dibayar;              // Konversi uang dibayar ke integer
                $kembalian   = max(0, $uangDibayar - $pesanan->total_harga); // Hitung kembalian (minimal 0, tidak boleh negatif)
                $pesanan->uang_dibayar = $uangDibayar;                   // Simpan uang yang dibayar ke database
                $pesanan->kembalian    = $kembalian;                      // Simpan kembalian ke database
            }

            // Jika opsi auto_selesai diaktifkan dan pesanan belum dibatalkan, ubah status ke 'Selesai'
            if ($request->has('auto_selesai') && $pesanan->status !== 'Dibatalkan') {
                $pesanan->status = 'Selesai'; // Set status pesanan ke Selesai
            }
        }

        $pesanan->id_admin = session('admin_id'); // Catat ID admin yang memproses pembayaran ini
        $pesanan->save();                         // Simpan semua perubahan ke database

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status pembayaran & pesanan #' . $pesanan->id_pesanan . ' berhasil diperbarui!');
    }
}
