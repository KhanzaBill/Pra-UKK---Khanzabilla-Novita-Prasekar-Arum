@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Yummy Chicken')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Laporan Penjualan</h2>
            <p style="font-size: 0.8rem; color: var(--text-sub); margin-top: 2px;">
                Perhitungan transaksi lunas per periode
            </p>
        </div>

       
        <form action="{{ route('admin.reports') }}" method="GET" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
            <div>
                <label style="font-size: 0.78rem; font-weight: 600; display: block; margin-bottom: 4px; color: var(--text-main);">Dari Tanggal:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" style="padding: 8px 12px;">
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 600; display: block; margin-bottom: 4px; color: var(--text-main);">Sampai Tanggal:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" style="padding: 8px 12px;">
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 9px 20px;">
                Filter Laporan
            </button>
        </form>
    </div>

    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div style="background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 100%); color: white; padding: 24px; border-radius: 16px; box-shadow: 0 6px 20px rgba(211,47,47,0.25); position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.2);">
            <span style="font-size: 0.85rem; opacity: 0.9; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Omset</span>
            <h3 style="font-family: 'Playfair Display', serif; font-size: 2.1rem; font-weight: 800; margin-top: 6px;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <span style="font-size: 0.76rem; opacity: 0.9; display: flex; align-items: center; gap: 6px; margin-top: 8px;">
                Transaksi lunas
            </span>
        </div>

        <div style="background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%); color: #7A1212; padding: 24px; border-radius: 16px; box-shadow: 0 6px 20px rgba(255,193,7,0.3); position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.3);">
            <span style="font-size: 0.85rem; opacity: 0.9; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Transaksi Lunas</span>
            <h3 style="font-family: 'Playfair Display', serif; font-size: 2.1rem; font-weight: 800; margin-top: 6px;">{{ $totalTransaksi }} Transaksi</h3>
            <span style="font-size: 0.76rem; opacity: 0.9; display: flex; align-items: center; gap: 6px; margin-top: 8px;">
                Terverifikasi lunas
            </span>
        </div>
    </div>

    
    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; margin-bottom: 14px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
      Rekap Pendapatan Per Hari
    </h3>
    <div class="table-responsive" style="margin-bottom: 36px;">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi Lunas</th>
                    <th>Total Omset Harian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapHarian as $tgl => $data)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</strong></td>
                        <td>{{ $data['total_transaksi'] }} Transaksi</td>
                        <td><strong style="color: var(--success); font-size: 0.95rem;">Rp {{ number_format($data['total_omset'], 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 36px 20px; color: var(--text-sub);">
                            <i class="fa-solid fa-calendar-xmark" style="font-size: 2.5rem; color: #DDD; margin-bottom: 8px; display: block;"></i>
                            Tidak ada transaksi lunas pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; margin-bottom: 14px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
       Rincian Transaksi Lunas
    </h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Waktu Lunas</th>
                    <th>Tipe & Meja</th>
                    <th>Pemesan</th>
                    <th>Metode Pembayaran</th>
                    <th>Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $p)
                    <tr>
                        <td><strong style="color: var(--primary);">#{{ str_pad($p->id_pesanan, 4, '0', STR_PAD_LEFT) }}</strong></td>
                        <td><i class="fa-regular fa-clock" style="font-size: 0.75rem; color: var(--text-sub);"></i> {{ \Carbon\Carbon::parse($p->tanggal_waktu)->format('d/m/Y H:i') }} WIB</td>
                        <td>{{ $p->tipe_pesanan }} {{ $p->meja ? '(' . $p->meja->nomor_meja . ')' : '' }}</td>
                        <td><strong>{{ $p->nama_pemesan }}</strong></td>
                        <td><span class="badge badge-info">{{ $p->metode_bayar }}</span></td>
                        <td><strong style="color: var(--success); font-size: 0.95rem;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 36px 20px; color: var(--text-sub);">
                            <i class="fa-solid fa-receipt" style="font-size: 2.5rem; color: #DDD; margin-bottom: 8px; display: block;"></i>
                            Belum ada rincian transaksi lunas pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 10px;">
        {{ $pesanans->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

