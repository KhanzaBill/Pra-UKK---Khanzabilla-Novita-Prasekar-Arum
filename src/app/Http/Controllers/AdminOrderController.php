<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Bahan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $query = Pesanan::with(['meja', 'detailPesanans.menu', 'detailPesanans.tambahans'])
                    ->orderBy('created_at', 'desc');

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $pesanans = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('pesanans', 'statusFilter'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Diproses,Disiapkan,Selesai,Dibatalkan',
            'alasan_pembatalan' => 'required_if:status,Dibatalkan|nullable|string'
        ]);

        $pesanan = Pesanan::with(['detailPesanans.menu.bahans', 'detailPesanans.tambahans.bahans'])->findOrFail($id);

        DB::transaction(function () use ($pesanan, $request) {
            // Jika status diubah menjadi Dibatalkan dari status sebelumnya yang aktif
            if ($request->status === 'Dibatalkan' && $pesanan->status !== 'Dibatalkan') {
                foreach ($pesanan->detailPesanans as $detail) {
                    $qty = $detail->jumlah;

                    // Kembalikan stok bahan dari Menu
                    if ($detail->menu && $detail->menu->bahans) {
                        foreach ($detail->menu->bahans as $bahan) {
                            $kebutuhan = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty;
                            $bahan->increment('stok', $kebutuhan);
                        }
                    }

                    // Kembalikan stok bahan dari Tambahan
                    if ($detail->tambahans) {
                        foreach ($detail->tambahans as $tambahan) {
                            if ($tambahan->bahans) {
                                foreach ($tambahan->bahans as $bahan) {
                                    $kebutuhan = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty;
                                    $bahan->increment('stok', $kebutuhan);
                                }
                            }
                        }
                    }
                }

                $pesanan->alasan_pembatalan = $request->alasan_pembatalan;
            }

            $pesanan->status = $request->status;
            $pesanan->id_admin = session('admin_id');
            $pesanan->save();
        });

        return redirect()->back()->with('success', 'Status pesanan #' . $pesanan->id_pesanan . ' berhasil diperbarui!');
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas',
            'uang_dibayar' => 'nullable|numeric|min:0',
            'auto_selesai' => 'nullable'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status_pembayaran = $request->status_pembayaran;

        if ($request->status_pembayaran === 'Lunas') {
            if ($pesanan->metode_bayar === 'Tunai') {
                $uangDibayar = (int) $request->uang_dibayar;
                $kembalian = max(0, $uangDibayar - $pesanan->total_harga);
                $pesanan->uang_dibayar = $uangDibayar;
                $pesanan->kembalian = $kembalian;
            }
            if ($request->has('auto_selesai') && $pesanan->status !== 'Dibatalkan') {
                $pesanan->status = 'Selesai';
            }
        }

        $pesanan->id_admin = session('admin_id');
        $pesanan->save();

        return redirect()->back()->with('success', 'Status pembayaran & pesanan #' . $pesanan->id_pesanan . ' berhasil diperbarui!');
    }
}
