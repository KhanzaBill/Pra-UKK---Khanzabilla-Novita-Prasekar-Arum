@extends('layouts.customer')

@section('title', 'Daftar Menu - Yummy Chicken')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<style>
    /* ===== Override layout for menu page ===== */
    .app-header { display: none !important; }
    .content { padding: 0 !important; }
    .alert-success { display: none !important; }

    /* ===== Custom Menu Header ===== */
    .menu-header {
        background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 55%, #e57373 100%);
        padding: 16px 20px 20px;
        border-radius: 0 0 28px 28px;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }

    .menu-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .menu-logo-small {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #D94044;
        border: 2.5px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .menu-logo-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .menu-logo-small span {
        color: #fff;
        font-size: 0.45rem;
        font-weight: 800;
        text-align: center;
        line-height: 1.1;
        text-transform: uppercase;
    }

    .cart-btn {
        position: relative;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #fff;
        background: rgba(0, 0, 0, 0.2);
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.3);
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .cart-btn:active {
        transform: scale(0.95);
    }

    .cart-btn i {
        font-size: 1.2rem;
        color: #FFC107;
    }

    .cart-btn .cart-count {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
    }

    /* ===== Search Bar ===== */
    .search-wrapper {
        margin-bottom: 14px;
        position: relative;
    }

    .search-form {
        margin: 0;
    }

    .search-input-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input-box i.search-icon {
        position: absolute;
        left: 14px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 9px 36px 9px 38px;
        border-radius: 22px;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.15);
        color: #FFFFFF;
        font-size: 0.88rem;
        outline: none;
        backdrop-filter: blur(4px);
        transition: all 0.2s ease;
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.75);
    }

    .search-input:focus {
        background: rgba(255, 255, 255, 0.25);
        border-color: #FFC107;
        box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.35);
    }

    .search-clear-btn {
        position: absolute;
        right: 12px;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(0, 0, 0, 0.25);
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .search-clear-btn:hover {
        background: rgba(0, 0, 0, 0.4);
        color: #fff;
    }

    /* ===== Category Tabs ===== */
    .category-tabs {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        scrollbar-width: none;
        padding-bottom: 2px;
    }
    .category-tabs::-webkit-scrollbar { display: none; }

    .tab-item {
        padding: 8px 20px;
        border-radius: 24px;
        border: 1.5px solid rgba(255,255,255,0.7);
        background: transparent;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.25s ease;
    }

    .tab-item:active {
        transform: scale(0.96);
    }

    .tab-item.active {
        background: #fff;
        color: #B71C1C;
        font-weight: 700;
        border-color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* ===== Menu Content Area ===== */
    .menu-body {
        background: #fff;
        padding: 20px 16px 30px;
        min-height: 65vh;
    }

    /* ===== Menu Grid (2 columns) ===== */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    /* ===== Menu Card ===== */
    .menu-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
        border: 1px solid var(--border-color, #EEEEEE);
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        padding: 8px;
        box-sizing: border-box;
    }

    .menu-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .menu-card:active {
        transform: scale(0.96);
    }

    .menu-card.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Wadah Gambar (1:1 Ratio) */
    .menu-card-img {
        position: relative;
        width: 100%;
        padding-top: 100%;
        border-radius: 12px;
        border: 1.5px solid #212121;
        overflow: hidden;
        background: #F4F6F9;
    }

    .menu-card-img img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover !important;
        object-position: center;
        display: block;
        transition: transform 0.3s ease;
    }

    .menu-card:hover .menu-card-img img {
        transform: scale(1.05);
    }

    .menu-card-img .img-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2.5rem;
        color: #CCC;
    }

    /* Habis badge */
    .badge-habis {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(66, 66, 66, 0.9);
        color: white;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        z-index: 5;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Favorit badge */
    .badge-favorit {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #FFC107;
        color: #7A1212;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        z-index: 5;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    /* Card info below image */
    .menu-card-info {
        padding: 10px 4px 4px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .menu-card-name {
        font-family: 'Playfair Display', serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #212121;
        margin-bottom: 2px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .menu-card-price {
        font-size: 0.95rem;
        font-weight: 700;
        color: #D32F2F;
        margin-top: auto;
        padding-top: 4px;
    }

    /* Hover Overlay */
    .cart-overlay {
        position: absolute;
        inset: 0;
        background: rgba(211, 47, 47, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 6px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
        z-index: 10;
    }

    .cart-overlay i {
        font-size: 1.8rem;
        color: #FFC107;
    }

    .cart-overlay span {
        color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .menu-card:hover .cart-overlay {
        opacity: 1;
    }

    .menu-card.tapped .cart-overlay {
        opacity: 1;
    }



    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9E9E9E;
        grid-column: 1 / -1;
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 12px;
        display: block;
        color: #DDD;
    }

    .empty-state h3 {
        font-size: 1.05rem;
        font-weight: 600;
        color: #212121;
        margin-bottom: 6px;
    }

    .empty-state p {
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    /* Toast */
    .toast-success {
        margin: 14px 16px 0;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        background: #E8F5E9;
        color: #2E7D32;
        border: 1px solid #A5D6A7;
        display: flex;
        align-items: center;
        gap: 8px;
        overflow: hidden;
        animation: toastCollapse 0.3s ease 2.3s forwards;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    @keyframes toastCollapse {
        from { opacity: 1; max-height: 60px; margin-top: 14px; padding-top: 12px; padding-bottom: 12px; }
        to   { opacity: 0; max-height: 0; margin-top: 0; padding-top: 0; padding-bottom: 0; border-width: 0; }
    }
</style>
@endsection

@section('content')
{{-- ===== Custom Header ===== --}}
<div class="menu-header">
    <div class="menu-header-top">
        {{-- Small Logo --}}
        <div class="menu-logo-small">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" loading="lazy">
            @else
                <span>YUMMY<br>CHICKEN</span>
            @endif
        </div>

        {{-- Cart Icon --}}
        <a href="{{ route('customer.cart') }}" class="cart-btn">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-count">{{ $cartCount }}</span>
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="search-wrapper">
        <form action="{{ route('customer.menu') }}" method="GET" class="search-form">
            @if($kategoriActive && $kategoriActive !== 'Semua')
                <input type="hidden" name="kategori" value="{{ $kategoriActive }}">
            @endif
            <div class="search-input-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" class="search-input" placeholder="Cari nama menu favoritmu..." value="{{ $search }}">
                @if($search)
                    <a href="{{ route('customer.menu', $kategoriActive && $kategoriActive !== 'Semua' ? ['kategori' => $kategoriActive] : []) }}" class="search-clear-btn" title="Hapus pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Category Tabs --}}
    <div class="category-tabs">
        <a href="{{ route('customer.menu', array_filter(['kategori' => 'Semua', 'search' => $search])) }}" 
           class="tab-item {{ $kategoriActive === 'Semua' ? 'active' : '' }}">
            Semua
        </a>
        <a href="{{ route('customer.menu', array_filter(['kategori' => 'Paket', 'search' => $search])) }}" 
           class="tab-item {{ $kategoriActive === 'Paket' ? 'active' : '' }}">
            Menu Paket
        </a>
        <a href="{{ route('customer.menu', array_filter(['kategori' => 'Makanan', 'search' => $search])) }}" 
           class="tab-item {{ $kategoriActive === 'Makanan' ? 'active' : '' }}">
            Makanan
        </a>
        <a href="{{ route('customer.menu', array_filter(['kategori' => 'Minuman', 'search' => $search])) }}" 
           class="tab-item {{ $kategoriActive === 'Minuman' ? 'active' : '' }}">
            Minuman
        </a>
    </div>
</div>

{{-- ===== Menu Body ===== --}}
<div class="menu-body">
    @if(session('success'))
        <div class="toast-success" id="flashToast">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="menu-grid">
        @forelse($menus as $menu)
            @php $tersedia = $menu->isTersedia(); @endphp
            <a href="{{ $tersedia ? route('customer.detail_menu', $menu->id_menu) : '#' }}" 
               class="menu-card {{ !$tersedia ? 'disabled' : '' }}"
               @if($tersedia) onclick="showTapped(event, this)" @endif>

                {{-- Habis / Favorit Badge --}}
                @if(!$tersedia)
                    <span class="badge-habis">HABIS</span>
                @elseif(Str::contains(strtolower($menu->nama_menu), ['geprek', 'spesial', 'komplit', 'paket']))
                    <span class="badge-favorit"><i class="fa-solid fa-fire"></i> Favorit</span>
                @endif

                {{-- Image --}}
                <div class="menu-card-img">
                    @if($tersedia)
                        <div class="cart-overlay">
                            <i class="fa-solid fa-cart-plus"></i>
                            <span>Pilih Menu</span>
                        </div>
                    @endif

                    @if($menu->foto)
                        <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" loading="lazy">
                    @else
                        <i class="fa-solid fa-utensils img-placeholder"></i>
                    @endif
                </div>

                {{-- Info --}}
                <div class="menu-card-info">
                    <h4 class="menu-card-name">{{ $menu->nama_menu }}</h4>
                    <div class="menu-card-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-utensils"></i>
                <h3>Menu Tidak Ditemukan</h3>
                <p>Maaf, saat ini belum ada pilihan menu untuk kategori ini.</p>
                <a href="{{ route('customer.menu') }}" class="btn btn-primary btn-sm" style="width: auto; display: inline-flex;">
                    <i class="fa-solid fa-rotate-left"></i> Lihat Semua Menu
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showTapped(event, card) {
        event.preventDefault();
        card.classList.add('tapped');
        const href = card.getAttribute('href');
        setTimeout(() => {
            card.classList.remove('tapped');
            if (href && href !== '#') {
                window.location.href = href;
            }
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.getElementById('flashToast');
        if (toast) {
            setTimeout(() => toast.remove(), 2800);
        }
    });
</script>
@endsection