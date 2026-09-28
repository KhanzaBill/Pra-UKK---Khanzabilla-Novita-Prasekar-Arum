<?php

namespace App\Http\Controllers; // Mendefinisikan namespace controller di App\Http\Controllers

use Illuminate\Http\Request; // Import class Request untuk menangani input HTTP
use App\Models\Bahan;         // Import model Bahan untuk mengakses tabel bahans di database

// Controller untuk mengelola data bahan baku (CRUD + manajemen stok) oleh admin
class AdminBahanController extends Controller
{
    /**
     * Menampilkan daftar semua bahan baku dengan fitur pencarian dan filter status stok.
     * Juga menghitung statistik jumlah bahan berdasarkan kategori stok.
     */
    public function index(Request $request)
    {
        $search = trim($request->query('search', '')); // Ambil keyword pencarian dari query string, hapus spasi di tepi
        $status = $request->query('status');           // Ambil filter status stok (aman, menipis, habis) dari query string

        $query = Bahan::with(['menus', 'tambahans']); // Mulai query Bahan, eager load relasi menus dan tambahans

        // Terapkan filter pencarian berdasarkan nama bahan jika keyword tidak kosong
        if ($search !== '') {
            $query->where('nama_bahan', 'like', "%{$search}%"); // Filter nama bahan yang mengandung keyword
        }

        // Terapkan filter berdasarkan status stok yang dipilih
        if ($status === 'habis') {
            $query->where('stok', '<=', 0); // Bahan habis: stok 0 atau kurang
        } elseif ($status === 'menipis') {
            $query->where('stok', '>', 0)->where('stok', '<=', 10); // Bahan menipis: stok antara 1-10
        } elseif ($status === 'aman') {
            $query->where('stok', '>', 10); // Bahan aman: stok lebih dari 10
        }

        // Ambil hasil query dengan urutan abjad nama bahan, paginasi 12 per halaman, pertahankan query string
        $bahans = $query->orderBy('nama_bahan', 'asc')->paginate(12)->withQueryString();

        // Hitung statistik total dan per-kategori stok untuk ditampilkan di dashboard
        $totalBahan      = Bahan::count();                                          // Total semua bahan
        $stokHabisCount  = Bahan::where('stok', '<=', 0)->count();                  // Jumlah bahan yang habis
        $stokMenipisCount = Bahan::where('stok', '>', 0)->where('stok', '<=', 10)->count(); // Jumlah bahan menipis
        $stokAmanCount   = Bahan::where('stok', '>', 10)->count();                   // Jumlah bahan aman

        // Kirim semua data ke view admin.bahans.index
        return view('admin.bahans.index', compact(
            'bahans',          // Data bahan yang sudah difilter dan dipaginasi
            'search',          // Keyword pencarian aktif
            'status',          // Filter status stok aktif
            'totalBahan',      // Total jumlah bahan
            'stokHabisCount',  // Jumlah bahan dengan stok habis
            'stokMenipisCount',// Jumlah bahan dengan stok menipis
            'stokAmanCount'    // Jumlah bahan dengan stok aman
        ));
    }

    /**
     * Menyimpan data bahan baku baru ke database.
     * Memvalidasi input terlebih dahulu sebelum menyimpan.
     */
    public function store(Request $request)
    {
        // Validasi data input dari form tambah bahan
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahans,nama_bahan', // Nama bahan wajib, unik di tabel bahans
            'stok'       => 'required|integer|min:0'                             // Stok wajib diisi, bilangan bulat, minimal 0
        ], [
            'nama_bahan.unique' => 'Bahan dengan nama tersebut sudah terdaftar.', // Pesan error khusus jika nama duplikat
            'stok.min'          => 'Stok bahan tidak boleh bernilai negatif.'      // Pesan error khusus jika stok negatif
        ]);

        // Simpan bahan baru ke database
        Bahan::create([
            'nama_bahan' => $request->nama_bahan,   // Nama bahan dari input form
            'stok'       => (int) $request->stok    // Stok awal, dikonversi ke integer
        ]);

        // Redirect kembali ke daftar bahan dengan pesan sukses
        return redirect()->route('admin.bahans.index')->with('success', 'Bahan baru "' . $request->nama_bahan . '" berhasil ditambahkan!');
    }

    /**
     * Memperbarui data bahan baku yang sudah ada di database.
     * Memvalidasi input dan mengizinkan nama yang sama jika milik bahan yang sama (ignore self).
     */
    public function update(Request $request, $id)
    {
        // Validasi data input dari form edit bahan, abaikan validasi unique untuk ID bahan ini sendiri
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahans,nama_bahan,' . $id . ',id_bahan', // Unik kecuali untuk bahan itu sendiri
            'stok'       => 'required|integer|min:0' // Stok wajib, bilangan bulat, minimal 0
        ]);

        $bahan = Bahan::findOrFail($id); // Ambil data bahan berdasarkan ID, throw 404 jika tidak ditemukan
        $bahan->update([
            'nama_bahan' => $request->nama_bahan,  // Update nama bahan
            'stok'       => (int) $request->stok   // Update stok, dikonversi ke integer
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data bahan "' . $bahan->nama_bahan . '" berhasil diperbarui!');
    }

    /**
     * Memperbarui stok bahan secara cepat (Quick Stock).
     * Mendukung tiga aksi: tambah stok, kurangi stok, atau set stok ke nilai tertentu.
     */
    public function quickStock(Request $request, $id)
    {
        // Validasi input aksi dan jumlah yang dikirim
        $request->validate([
            'aksi'   => 'required|in:tambah,kurang,set', // Aksi hanya boleh salah satu dari: tambah, kurang, set
            'jumlah' => 'required|integer|min:0'          // Jumlah wajib diisi, bilangan bulat, minimal 0
        ]);

        $bahan  = Bahan::findOrFail($id);     // Ambil data bahan berdasarkan ID, throw 404 jika tidak ditemukan
        $jumlah = (int) $request->jumlah;    // Konversi jumlah input ke integer

        if ($request->aksi === 'tambah') {
            $bahan->increment('stok', $jumlah); // Tambahkan stok sebesar jumlah yang diminta
            $pesan = 'Berhasil menambah ' . $jumlah . ' stok untuk bahan "' . $bahan->nama_bahan . '". Stok sekarang: ' . $bahan->stok; // Buat pesan sukses

        } elseif ($request->aksi === 'kurang') {
            $kurangSebesar = min($bahan->stok, $jumlah); // Batasi pengurangan agar stok tidak minus (maksimal kurangi sebesar stok saat ini)
            $bahan->decrement('stok', $kurangSebesar);   // Kurangi stok
            $pesan = 'Berhasil mengurangi ' . $kurangSebesar . ' stok dari bahan "' . $bahan->nama_bahan . '". Stok sekarang: ' . $bahan->stok; // Buat pesan sukses

        } else {
            // Aksi 'set': langsung set stok ke nilai yang diinput
            $bahan->update(['stok' => $jumlah]); // Update stok ke nilai yang ditentukan
            $pesan = 'Stok bahan "' . $bahan->nama_bahan . '" berhasil disetel menjadi ' . $jumlah . '.'; // Buat pesan sukses
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan hasil aksi
        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Menghapus data bahan baku dari database.
     * Jika bahan terhubung ke menu atau tambahan, putuskan relasi pivot terlebih dahulu sebelum menghapus.
     */
    public function destroy($id)
    {
        // Ambil data bahan beserta jumlah relasinya ke menus dan tambahans
        $bahan = Bahan::withCount(['menus', 'tambahans'])->findOrFail($id);

        // Jika bahan masih terhubung ke menu atau tambahan, lepaskan relasinya terlebih dahulu
        if ($bahan->menus_count > 0 || $bahan->tambahans_count > 0) {
            // Lepaskan relasi pivot lalu hapus
            $bahan->menus()->detach();     // Hapus semua relasi pivot bahan ↔ menu di tabel menu_bahans
            $bahan->tambahans()->detach(); // Hapus semua relasi pivot bahan ↔ tambahan di tabel tambahan_bahans
        }

        $nama = $bahan->nama_bahan; // Simpan nama bahan sebelum dihapus untuk pesan sukses
        $bahan->delete();           // Hapus bahan dari database

        // Redirect ke daftar bahan dengan pesan sukses
        return redirect()->route('admin.bahans.index')->with('success', 'Bahan "' . $nama . '" berhasil dihapus!');
    }
}
