@extends('layouts.customer')

@section('title', 'Struk Pesanan #' . $pesanan->id_pesanan)

@section('styles')
<style>
    /* ===== Override layout ===== */
    .app-header { display: none !important; }
    .content { padding: 0 !important; }
    .alert-success, .alert-error { display: none !important; }

    .receipt-page {
        background: #EFEFEF;
        padding: 24px 16px 40px;
        min-height: 100vh;
    }

    .receipt-sheet {
        background: #fff;
        border-radius: 12px;
        padding: 28px 22px 26px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border: 1px solid #E0E0E0;
        position: relative;
    }

    /* ===== Logo ===== */
    .receipt-logo {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: #D32F2F;
        border: 3px solid #212121;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .receipt-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .receipt-logo span {
        color: #fff;
        font-weight: 800;
        font-size: 1rem;
        text-align: center;
        line-height: 1.2;
        text-transform: uppercase;
    }

    /* Live sync dot badge */
    .live-sync-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #2E7D32;
        background: #E8F5E9;
        padding: 4px 12px;
        border-radius: 20px;
        margin: 0 auto 16px;
        width: fit-content;
        border: 1px solid #A5D6A7;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2E7D32;
        box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.7);
        animation: pulseDot 1.5s infinite;
    }

    @keyframes pulseDot {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(46, 125, 50, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 125, 50, 0); }
    }

    /* ===== Progress Tracker ===== */
    .status-tracker {
        display: flex;
        justify-content: space-between;
        margin: 0 0 24px;
        position: relative;
    }

    .status-tracker::before {
        content: '';
        position: absolute;
        top: 13px;
        left: 12.5%;
        right: 12.5%;
        height: 3px;
        background: #E0E0E0;
        z-index: 1;
        border-radius: 3px;
    }

    .tracker-line-fill {
        position: absolute;
        top: 13px;
        left: 12.5%;
        height: 3px;
        background: #D32F2F;
        z-index: 1;
        transition: width 0.4s ease;
        border-radius: 3px;
    }

    .status-step {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 25%;
    }

    .step-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #fff;
        border: 2.5px solid #E0E0E0;
        color: #E0E0E0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        margin-bottom: 6px;
        transition: all 0.3s ease;
    }

    .status-step.active:not(.completed) .step-icon {
        border-color: #D32F2F;
        color: #D32F2F;
        background: #fff;
    }

    .status-step.completed .step-icon,
    .status-step.completed.active .step-icon {
        background: #D32F2F !important;
        border-color: #D32F2F !important;
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(211,47,47,0.3);
    }

    .step-label {
        font-size: 0.72rem;
        font-weight: 500;
        color: var(--text-muted, #9E9E9E);
    }

    .status-step.completed .step-label,
    .status-step.active .step-label {
        color: #212121;
        font-weight: 600;
    }

    /* ===== Cancelled State ===== */
    .cancelled-box {
        text-align: center;
        color: #C62828;
        padding: 10px 0 24px;
    }

    .cancelled-box i {
        font-size: 3rem;
        margin-bottom: 8px;
        display: block;
    }

    /* ===== Order Number & Meta ===== */
    .order-number {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 800;
        color: #212121;
        text-align: center;
        margin-bottom: 2px;
    }

    .order-subtitle {
        font-size: 0.95rem;
        font-weight: 700;
        color: #212121;
        text-align: center;
        margin-bottom: 18px;
    }

    .dashed-line {
        border: none;
        border-top: 1.5px dashed #CCC;
        margin: 16px 0;
    }

    .meta-cols {
        display: flex;
        justify-content: space-between;
        font-size: 0.86rem;
        color: #212121;
    }

    .meta-cols .meta-left,
    .meta-cols .meta-right {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .meta-cols .meta-right {
        align-items: flex-end;
        text-align: right;
    }

    .meta-line-label { color: #9E9E9E; }
    .meta-line-value { font-weight: 600; }

    /* ===== Items ===== */
    .receipt-item {
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px dashed #EEEEEE;
    }

    .receipt-item:last-of-type {
        border-bottom: none;
        padding-bottom: 0;
    }

    .receipt-item-main {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.9rem;
        color: #212121;
        font-weight: 600;
    }

    .receipt-item-name {
        flex: 1;
        min-width: 0;
        word-break: break-word;
        line-height: 1.35;
    }

    .receipt-item-price {
        white-space: nowrap;
        font-weight: 700;
        color: #212121;
        text-align: right;
        flex-shrink: 0;
    }

    .receipt-item-addon {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        font-size: 0.82rem;
        color: #2E7D32;
        margin-top: 4px;
        padding-left: 12px;
    }

    .receipt-item-note {
        font-size: 0.78rem;
        color: #9E9E9E;
        margin-top: 4px;
        line-height: 1.4;
        font-style: italic;
        padding-left: 12px;
        word-break: break-word;
    }

    /* ===== Totals ===== */
    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.92rem;
        color: #212121;
        margin-bottom: 4px;
    }

    .total-row.grand {
        font-weight: 800;
        font-size: 1.1rem;
        color: #D32F2F;
    }

    /* ===== Payment ===== */
    .payment-section-title {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 8px;
        color: #212121;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        color: #212121;
        margin-bottom: 6px;
    }

    .payment-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        margin-top: 6px;
    }

    .payment-status-badge.lunas { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
    .payment-status-badge.belum { background: #FFF8E1; color: #F57F17; border: 1px solid #FFE082; }

    /* ===== Footer ===== */
    .receipt-footer {
        text-align: center;
        font-size: 0.82rem;
        color: #9E9E9E;
        margin-top: 6px;
        line-height: 1.5;
    }

    /* ===== Tombol pesan lagi ===== */
    .btn-order-more {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-align: center;
        margin: 20px auto 0;
        max-width: 320px;
        padding: 14px;
        border-radius: 30px;
        border: 2px solid #D32F2F;
        color: #D32F2F;
        font-weight: 700;
        text-decoration: none;
        background: #fff;
        font-size: 0.92rem;
        transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
        box-shadow: 0 4px 12px rgba(211,47,47,0.15);
    }

    .btn-order-more:active {
        transform: scale(0.97);
        background: #FFEBEE;
    }

    .btn-order-more:hover {
        background: #D32F2F;
        color: #FFF;
    }
</style>
@endsection

@section('content')

@php
    session(['last_order_id' => $pesanan->id_pesanan]);
    $urutanStatus = ['Diterima', 'Diproses', 'Disiapkan', 'Selesai'];
    $indexAktif = array_search($pesanan->status, $urutanStatus);
    if ($indexAktif === false) $indexAktif = -1;

    $totalSteps = count($urutanStatus) - 1;
    $fillPercent = $indexAktif >= 0 ? round(($indexAktif / $totalSteps) * 75) : 0;
@endphp

<div class="receipt-page">
    <div class="receipt-sheet">

        {{-- ===== Logo ===== --}}
        <div class="receipt-logo">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" loading="lazy">
            @else
                <span>YUMMY<br>CHICKEN</span>
            @endif
        </div>

        @if($pesanan->status === 'Dibatalkan')
            {{-- ===== Status Dibatalkan ===== --}}
            <div class="cancelled-box">
                <i class="fa-solid fa-circle-xmark"></i>
                <h3 style="font-size: 1.1rem; font-weight: 700;">Pesanan Dibatalkan</h3>
                <p style="font-size: 0.82rem; color: #616161; margin-top: 6px;">
                    <strong>Alasan Pembatalan:</strong><br>
                    <span style="font-style: italic; background: #EF5350; color: #FFFFFF; padding: 6px 14px; border-radius: 8px; display: inline-block; margin-top: 6px; border: 1px solid #E53935; font-weight: 500;">
                        "{{ $pesanan->alasan_pembatalan ?? 'Stok bahan tidak tersedia' }}"
                    </span>
                </p>
            </div>
        @else
            {{-- ===== Progress Tracker ===== --}}
            <div class="status-tracker" id="live-status-tracker">
                <div class="tracker-line-fill" style="width: {{ $fillPercent }}%;"></div>

                @foreach($urutanStatus as $i => $label)
                    <div class="status-step {{ $i <= $indexAktif ? 'completed' : '' }} {{ $i === $indexAktif ? 'active' : '' }}">
                        <div class="step-icon">
                            @if($i <= $indexAktif)
                                <i class="fa-solid fa-check"></i>
                            @endif
                        </div>
                        <span class="step-label">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ===== Nomor Pesanan ===== --}}
        <div class="order-number">Pesanan #{{ str_pad($pesanan->id_pesanan, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="order-subtitle">
            {{ $pesanan->tipe_pesanan === 'Dine-In' ? 'Dine In' : 'Take Away' }}
            @if($pesanan->tipe_pesanan === 'Dine-In' && $pesanan->meja)
                / {{ $pesanan->meja->nomor_meja }}
            @endif
        </div>

        <div class="dashed-line"></div>

        {{-- ===== Meta: Tanggal / Kasir / Customer ===== --}}
        <div class="meta-cols">
            <div class="meta-left">
                <span class="meta-line-label">Tanggal</span>
                <span class="meta-line-value">{{ \Carbon\Carbon::parse($pesanan->tanggal_waktu)->translatedFormat('d M Y') }}</span>
            </div>
            <div class="meta-right">
                <span class="meta-line-label">Kasir</span>
                <span class="meta-line-value">{{ $pesanan->admin->nama ?? session('admin_nama') ?? 'Kasir Yummy' }}</span>
                <span class="meta-line-label" style="margin-top: 4px;">Customer</span>
                <span class="meta-line-value">{{ $pesanan->nama_pemesan ?? '-' }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        {{-- ===== Rincian Item ===== --}}
        @foreach($pesanan->detailPesanans as $detail)
            <div class="receipt-item">
                <div class="receipt-item-main">
                    <span class="receipt-item-name">{{ $detail->jumlah }}x {{ $detail->menu->nama_menu }}{{ $detail->level_pedas ? ' (Lvl ' . $detail->level_pedas . ')' : '' }}</span>
                    <span class="receipt-item-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>

                @foreach($detail->tambahans as $t)
                    <div class="receipt-item-addon">
                        <span style="flex: 1; min-width: 0; word-break: break-word;">+ {{ $t->nama_tambahan }}</span>
                        <span style="white-space: nowrap; flex-shrink: 0;">Rp {{ number_format($t->harga, 0, ',', '.') }}</span>
                    </div>
                @endforeach

                @if($detail->catatan)
                    <div class="receipt-item-note">Catatan : "{{ $detail->catatan }}"</div>
                @endif
            </div>
        @endforeach

        <div class="dashed-line"></div>

        {{-- ===== Subtotal & Total ===== --}}
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
        </div>
        <div class="total-row grand">
            <span>Total Tagihan</span>
            <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
        </div>

        <div class="dashed-line"></div>

        {{-- ===== Payment Method ===== --}}
        <div class="payment-section-title">Metode Pembayaran</div>

        @if($pesanan->metode_bayar === 'Tunai')
            <div class="payment-row">
                <span>Tunai</span>
                <span>Rp {{ number_format($pesanan->uang_dibayar ?? $pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
            @if($pesanan->kembalian)
                <div class="payment-row">
                    <span style="color: #9E9E9E; font-size: 0.82rem;">Kembalian</span>
                    <span style="font-size: 0.82rem;">Rp {{ number_format($pesanan->kembalian, 0, ',', '.') }}</span>
                </div>
            @endif
        @else
            <div class="payment-row">
                <span>QRIS / E-Wallet</span>
                <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        @endif

        {{-- Status Pembayaran badge --}}
        @if($pesanan->status_pembayaran === 'Lunas')
            <span class="payment-status-badge lunas"><i class="fa-solid fa-circle-check"></i> PEMBAYARAN LUNAS</span>
        @else
            <span class="payment-status-badge belum"><i class="fa-solid fa-hourglass-half"></i> BELUM DIBAYAR</span>
        @endif

        <div class="dashed-line"></div>

        {{-- ===== Footer ===== --}}
        <div class="receipt-footer">
            {{ \Carbon\Carbon::parse($pesanan->tanggal_waktu)->translatedFormat('d M Y H:i') }} WIB<br>
            Terima kasih atas kunjungan Anda!<br>
            Ayam Geprek Yummy Chicken Semarang
        </div>
    </div>

    {{-- ===== Tombol pesan menu tambahan ===== --}}
    <a href="{{ route('customer.menu') }}" class="btn-order-more">
        <i class="fa-solid fa-plus"></i> Pesan Menu Tambahan Lagi
    </a>
</div>
@endsection

@section('scripts')
<script>
    // Polling status pesanan secara real-time setiap 5 detik (DIPERTAHANKAN)
    const orderId = {{ $pesanan->id_pesanan }};
    setInterval(function() {
        fetch('/api/order-status/' + orderId)
            .then(res => res.json())
            .then(data => {
                if (data.status !== "{{ $pesanan->status }}" || data.status_pembayaran !== "{{ $pesanan->status_pembayaran }}") {
                    window.location.reload();
                }
            })
            .catch(err => console.log(err));
    }, 5000);
</script>
@endsection

