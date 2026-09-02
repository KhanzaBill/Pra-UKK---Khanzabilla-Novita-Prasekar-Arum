@extends('layouts.customer')

@section('title', 'Keranjang Saya - Yummy Chicken')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<style>
    /* ===== Override layout ===== */
    .app-header { display: none !important; }
    .content { padding: 0 !important; }
    .alert-success, .alert-error { display: none !important; } /* hide layout alert because handled in cart-body */

    /* ===== Header ===== */
    .cart-header-bar {
        background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 55%, #e57373 100%);
        padding: 20px 20px 24px;
        border-radius: 0 0 28px 28px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }

    .back-btn {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        font-size: 1.1rem;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .back-btn:active {
        transform: scale(0.92);
        background: rgba(255,255,255,0.4);
    }

    .cart-header-bar h1 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
    }

    /* ===== Body ===== */
    .cart-body {
        padding: 20px 16px 190px;
    }

    .cart-count-label {
        font-size: 0.82rem;
        color: var(--text-body, #616161);
        margin-bottom: 14px;
        font-weight: 500;
    }

    /* ===== Cart Item Card ===== */
    .cart-item {
        display: flex;
        gap: 14px;
        margin-bottom: 16px;
        position: relative;
        background: #FFF;
        padding: 12px;
        border-radius: 16px;
        border: 1px solid var(--border-color, #EEEEEE);
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .cart-item-img {
        width: 90px;
        height: 90px;
        min-width: 90px;
        border-radius: 12px;
        border: 1.5px solid #212121;
        overflow: hidden;
        background: #F4F6F9;
    }

    .cart-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-body {
        flex: 1;
        padding-top: 2px;
        padding-right: 95px;
        min-width: 0;
    }

    .cart-item-name {
        font-family: 'Playfair Display', serif;
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text-title, #212121);
        margin-bottom: 2px;
        line-height: 1.3;
        word-break: break-word;
    }

    .cart-item-variant {
        font-size: 0.8rem;
        color: #2E7D32;
        font-weight: 600;
        margin-bottom: 2px;
        word-break: break-word;
    }

    .cart-item-note {
        font-style: italic;
        color: #9E9E9E;
        font-size: 0.76rem;
        margin-bottom: 6px;
        line-height: 1.3;
        word-break: break-word;
    }

    .cart-item-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .cart-item-price {
        font-weight: 700;
        color: var(--primary-color, #D32F2F);
        font-size: 0.95rem;
    }

    .mini-stepper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #F4F6F9;
        padding: 2px 8px;
        border-radius: 16px;
    }

    .mini-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: #FFC107;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
        color: #7A1212;
        transition: transform 0.15s ease;
    }

    .mini-btn:active {
        transform: scale(0.9);
    }

    .mini-qty {
        font-weight: 700;
        font-size: 0.9rem;
        min-width: 18px;
        text-align: center;
    }

    .cart-item-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 2;
    }

    .cart-item-edit {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        background: #FFF3E0;
        color: #E65100;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 600;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .cart-item-edit:active {
        transform: scale(0.94);
        background: #FFE0B2;
    }

    .cart-item-trash {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: #FFEBEE;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #C62828;
        text-decoration: none;
        font-size: 0.75rem;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .cart-item-trash:active {
        transform: scale(0.9);
        background: #C62828;
        color: #FFF;
    }

    /* ===== Empty State ===== */
    .cart-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted, #9E9E9E);
    }

    .cart-empty > i {
        font-size: 3.8rem;
        margin-bottom: 16px;
        color: #DDD;
        display: block;
    }

    .cart-empty h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-title, #212121);
        margin-bottom: 6px;
    }

    .cart-empty p {
        font-size: 0.85rem;
        margin-bottom: 20px;
    }

    .cart-empty .btn-empty-cart {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 24px;
        width: auto;
        color: #ffffff;
        text-decoration: none;
    }

    .cart-empty .btn-empty-cart i {
        color: #ffffff !important;
        font-size: 0.9rem;
        display: inline-block;
        margin: 0;
        line-height: 1;
    }

    /* ===== Footer Summary ===== */
    .cart-summary {
        background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 100%);
        padding: 20px 20px 24px;
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 150;
        box-shadow: 0 -6px 24px rgba(0,0,0,0.18);
        border-radius: 20px 20px 0 0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
        margin-bottom: 6px;
    }

    .summary-row.subtotal {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .summary-row.total {
        font-size: 1.1rem;
        font-weight: 700;
        border-top: 1px dashed rgba(255,255,255,0.2);
        padding-top: 8px;
        margin-top: 6px;
    }

    .summary-row.total .summary-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #FFC107;
    }

    .btn-checkout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        margin-top: 16px;
        padding: 15px;
        border-radius: 30px;
        background: #FFC107;
        color: #7A1212;
        font-weight: 700;
        font-size: 1.05rem;
        text-decoration: none;
        border: none;
        box-shadow: 0 4px 14px rgba(255, 193, 7, 0.4);
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .btn-checkout:active {
        transform: scale(0.97);
        background: #FFB300;
    }

    .btn-checkout:hover {
        background: #FFB300;
        box-shadow: 0 6px 18px rgba(255, 193, 7, 0.55);
    }
</style>
@endsection

@section('content')
{{-- ===== Header ===== --}}
<div class="cart-header-bar">
    <a href="{{ route('customer.menu') }}" class="back-btn">
        <i class="fa-solid fa-chevron-left"></i>
    </a>
    <h1>Keranjang Saya</h1>
</div>

<div class="cart-body">
    @if(session('error'))
        <div style="background: #FFEBEE; border: 1.5px solid #FFCDD2; color: #C62828; padding: 14px 16px; border-radius: 14px; margin-bottom: 16px; font-size: 0.88rem; display: flex; align-items: flex-start; gap: 10px; line-height: 1.4; box-shadow: 0 2px 8px rgba(198,40,40,0.08);">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem; margin-top: 2px; color: #D32F2F;"></i>
            <div style="flex: 1;">
                <strong>Perhatian:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div style="background: #E8F5E9; border: 1.5px solid #A5D6A7; color: #2E7D32; padding: 12px 16px; border-radius: 14px; margin-bottom: 16px; font-size: 0.88rem; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 8px rgba(46,125,50,0.08);">
            <i class="fa-solid fa-circle-check" style="font-size: 1.1rem; color: #2E7D32;"></i>
            <div style="flex: 1;">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(empty($cart))
        <div class="cart-empty">
            <i class="fa-solid fa-cart-flatbed"></i>
            <h3>Keranjang Masih Kosong</h3>
            <p>Silakan pilih menu geprek lezat kesukaan Anda terlebih dahulu!</p>
            <a href="{{ route('customer.menu') }}" class="btn btn-primary btn-empty-cart">
                <i class="fa-solid fa-plus"></i>
                <span>Lihat Daftar Menu</span>
            </a>
        </div>
    @else
        <p class="cart-count-label">{{ count($cart) }} jenis menu di keranjang</p>

        @foreach($cart as $hash => $item)
            <div class="cart-item">
                <div class="cart-item-actions">
                    <a href="{{ route('customer.detail_menu', $item['id_menu']) }}?edit_hash={{ $hash }}" class="cart-item-edit" title="Edit Opsi Menu">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <a href="{{ route('customer.remove_cart', $hash) }}" class="cart-item-trash" title="Hapus menu" onclick="return confirm('Hapus menu ini dari keranjang?')">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </div>

                <div class="cart-item-img">
                    @if(!empty($item['foto']))
                        <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['nama_menu'] }}" loading="lazy">
                    @else
                        <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#CCC; font-size:1.8rem;">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                    @endif
                </div>

                <div class="cart-item-body">
                    <div class="cart-item-name">{{ $item['nama_menu'] }}</div>

                    @if(!empty($item['level_pedas']) || !empty($item['tambahans']))
                        <div class="cart-item-variant">
                            @if(!empty($item['level_pedas']))
                                Level {{ $item['level_pedas'] }}
                            @endif
                            @if(!empty($item['tambahans']))
                                {{ !empty($item['level_pedas']) ? '+ ' : '' }}{{ implode(', ', array_map(fn($t) => $t['nama_tambahan'], $item['tambahans'])) }}
                            @endif
                        </div>
                    @endif

                    @if(!empty($item['catatan']))
                        <div class="cart-item-note">"{{ $item['catatan'] }}"</div>
                    @endif

                    <div class="cart-item-footer">
                        <span class="cart-item-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>

                        <div class="mini-stepper">
                            <form action="{{ route('customer.update_cart') }}" method="POST" style="margin: 0; display: flex; align-items: center;">
                                @csrf
                                <input type="hidden" name="item_hash" value="{{ $hash }}">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="mini-btn"><i class="fa-solid fa-minus"></i></button>
                            </form>

                            <span class="mini-qty">{{ $item['jumlah'] }}</span>

                            <form action="{{ route('customer.update_cart') }}" method="POST" style="margin: 0; display: flex; align-items: center;">
                                @csrf
                                <input type="hidden" name="item_hash" value="{{ $hash }}">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="mini-btn"><i class="fa-solid fa-plus"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@if(!empty($cart))
    <div class="cart-summary">
        <div class="summary-row subtotal">
            <span>Subtotal Menu</span>
            <span class="summary-value">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row total">
            <span>Total Tagihan</span>
            <span class="summary-value">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
        </div>

        <a href="{{ route('customer.checkout') }}" class="btn-checkout">
            Lanjut ke Checkout
        </a>
    </div>
@endif
@endsection