<?php

namespace App\Http\Controllers; // Mendefinisikan namespace controller berada di App\Http\Controllers

use Illuminate\Http\Request; // Import class Request untuk menangani input HTTP
use App\Models\Pesanan;       // Import model Pesanan untuk mengakses data transaksi pesanan
use Carbon\Carbon;            // Import library Carbon untuk manipulasi tanggal dan waktu

// Controller untuk mengelola halaman laporan penjualan admin
class AdminReportController extends Controller
{
    /**
     * Menampilkan laporan penjualan berdasarkan rentang tanggal yang dipilih.
     * Hanya pesanan dengan status_pembayaran = 'Lunas' yang dihitung.
     * Menyajikan total pendapatan, total transaksi, rekap harian, dan rincian transaksi.
     */
    public function index(Request $request)
    {
        // Ambil tanggal mulai dari query string, default ke awal bulan ini jika tidak ada
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        // Ambil tanggal akhir dari query string, default ke hari ini jika tidak ada
        $endDate   = $request->query('end_date', Carbon::now()->toDateString());

        // CRITICAL REQUIREMENT: Filter hanya pesanan yang status_pembayaran = Lunas
        // Ambil semua pesanan lunas dalam rentang tanggal untuk kalkulasi statistik (tanpa paginasi)
        $allPesanans = Pesanan::with(['meja', 'detailPesanans.menu']) // Eager load relasi meja dan detail pesanan + menu
            ->where('status_pembayaran', 'Lunas')                    // Hanya pesanan yang sudah lunas
            ->whereDate('tanggal_waktu', '>=', $startDate)           // Filter tanggal pesanan >= tanggal mulai
            ->whereDate('tanggal_waktu', '<=', $endDate)             // Filter tanggal pesanan <= tanggal akhir
            ->orderBy('tanggal_waktu', 'desc')                       // Urutkan dari terbaru ke terlama
            ->get();                                                  // Ambil semua data (untuk kalkulasi statistik)

        $totalPendapatan = $allPesanans->sum('total_harga'); // Hitung total pendapatan dari semua pesanan lunas
        $totalTransaksi  = $allPesanans->count();            // Hitung jumlah transaksi lunas dalam rentang tanggal

        // Grouping transaksi per hari untuk tabel rekap harian
        $rekapHarian = $allPesanans->groupBy(function($item) {
            // Kelompokkan pesanan berdasarkan tanggal (format Y-m-d)
            return Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
        })->map(function($dayGroup) {
            // Untuk setiap kelompok tanggal, hitung jumlah transaksi dan total omset
            return [
                'total_transaksi' => $dayGroup->count(),         // Jumlah transaksi pada hari tersebut
                'total_omset'     => $dayGroup->sum('total_harga') // Total omset pada hari tersebut
            ];
        });

        // Paginate rincian transaksi lunas dengan limit 10 per halaman (query terpisah dari yang di atas)
        $pesanans = Pesanan::with(['meja', 'detailPesanans.menu']) // Eager load relasi meja dan detail pesanan + menu
            ->where('status_pembayaran', 'Lunas')                  // Hanya pesanan lunas
            ->whereDate('tanggal_waktu', '>=', $startDate)         // Filter tanggal mulai
            ->whereDate('tanggal_waktu', '<=', $endDate)           // Filter tanggal akhir
            ->orderBy('tanggal_waktu', 'desc')                     // Urutkan terbaru dulu
            ->paginate(10)                                         // Paginasi 10 transaksi per halaman
            ->withQueryString();                                   // Pertahankan query string saat pindah halaman

        // Kirim semua data ke view admin.reports.index
        return view('admin.reports.index', compact('pesanans', 'totalPendapatan', 'totalTransaksi', 'rekapHarian', 'startDate', 'endDate'));
    }
}
