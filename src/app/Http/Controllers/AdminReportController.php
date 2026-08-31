<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());

        // CRITICAL REQUIREMENT: Filter hanya pesanan yang status_pembayaran = Lunas
        $allPesanans = Pesanan::with(['meja', 'detailPesanans.menu'])
            ->where('status_pembayaran', 'Lunas')
            ->whereDate('tanggal_waktu', '>=', $startDate)
            ->whereDate('tanggal_waktu', '<=', $endDate)
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

        $totalPendapatan = $allPesanans->sum('total_harga');
        $totalTransaksi = $allPesanans->count();

        // Grouping transaksi per hari untuk tabel rekap harian
        $rekapHarian = $allPesanans->groupBy(function($item) {
            return Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
        })->map(function($dayGroup) {
            return [
                'total_transaksi' => $dayGroup->count(),
                'total_omset' => $dayGroup->sum('total_harga')
            ];
        });

        // Paginate rincian transaksi lunas dengan limit 10 per halaman
        $pesanans = Pesanan::with(['meja', 'detailPesanans.menu'])
            ->where('status_pembayaran', 'Lunas')
            ->whereDate('tanggal_waktu', '>=', $startDate)
            ->whereDate('tanggal_waktu', '<=', $endDate)
            ->orderBy('tanggal_waktu', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.reports.index', compact('pesanans', 'totalPendapatan', 'totalTransaksi', 'rekapHarian', 'startDate', 'endDate'));
    }
}
