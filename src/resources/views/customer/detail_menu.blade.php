@extends('layouts.customer')

@section('title', $menu->nama_menu . ' - Detail Menu')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<style>
    /* ===== Override layout ===== */
    .app-header { display: none !important; }
    .content { padding: 0 !important; }
    .bottom-nav { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }

    /* ===== Detail Page ===== */
    .detail-page {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background: #fff;
    }

    /* ===== Top Image ===== */
    .detail-image-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        background: #F4F6F9;
        overflow: hidden;
    }

    .detail-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .detail-image-wrapper .img-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 4rem;
        color: #CCC;
        background: linear-gradient(135deg, #F4F6F9, #E0E0E0);
    }

    /* Back button */
    .back-btn {
        position: absolute;
        top: 14px;
        left: 14px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #212121;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .back-btn:active {
        transform: scale(0.92);
        background: #fff;
    }

    /* ===== Info Section ===== */
    .detail-info {
        padding: 20px 20px 16px;
    }

    .detail-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.65rem;
        font-weight: 700;
        color: #212121;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .detail-price {
        font-size: 1.35rem;
        font-weight: 700;
        color: #D32F2F;
        margin-bottom: 8px;
    }

    .detail-desc {
        font-size: 0.9rem;
        color: #616161;
        line-height: 1.5;
    }

    /* ===== Sections ===== */
    .detail-section {
        padding: 18px 20px;
        border-top: 8px solid #F8F9FA;
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #212121;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .badge-wajib {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #C62828;
        background: #FFEBEE;
        border: 1.5px solid #EF9A9A;
        padding: 4px 12px;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(198, 40, 40, 0.08);
    }

    .badge-wajib i {
        font-size: 0.72rem;
    }

    /* ===== Option Pill Cards (Varian & Suhu) ===== */
    .option-pill-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .option-pill-card {
        cursor: pointer;
        position: relative;
        user-select: none;
    }

    .option-pill-card input[type="radio"] {
        display: none;
    }

    .option-pill-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 16px;
        border: 2px solid #E0E0E0;
        border-radius: 16px;
        background: #FAFAFA;
        transition: all 0.2s ease;
        text-align: center;
    }

    .option-pill-content i {
        font-size: 1.2rem;
        color: #757575;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .option-pill-title {
        font-size: 0.92rem;
        font-weight: 600;
        color: #424242;
        transition: color 0.2s ease;
    }

    /* Selected state */
    .option-pill-card input[type="radio"]:checked + .option-pill-content {
        border-color: #D32F2F;
        background: #FFEBEE;
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.12);
    }

    .option-pill-card input[type="radio"]:checked + .option-pill-content i {
        color: #D32F2F;
        transform: scale(1.1);
    }

    .option-pill-card input[type="radio"]:checked + .option-pill-content .option-pill-title {
        color: #B71C1C;
        font-weight: 700;
    }

    /* Specific accent for dingin */
    .option-dingin input[type="radio"]:checked + .option-pill-content {
        border-color: #1976D2;
        background: #E3F2FD;
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.12);
    }
    .option-dingin input[type="radio"]:checked + .option-pill-content i,
    .option-dingin input[type="radio"]:checked + .option-pill-content .option-pill-title {
        color: #0D47A1;
    }

    /* Specific accent for panas */
    .option-panas input[type="radio"]:checked + .option-pill-content {
        border-color: #E65100;
        background: #FFF3E0;
        box-shadow: 0 4px 12px rgba(230, 81, 0, 0.12);
    }
    .option-panas input[type="radio"]:checked + .option-pill-content i,
    .option-panas input[type="radio"]:checked + .option-pill-content .option-pill-title {
        color: #BF360C;
    }

    /* ===== Level Pedas - Radio circles ===== */
    .level-selector {
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }

    .level-option {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        user-select: none;
    }

    .level-option input[type="radio"] {
        display: none;
    }

    .level-radio-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #BDBDBD;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.2s, background 0.2s;
    }

    .level-radio-circle::after {
        content: '';
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: transparent;
        transition: background 0.2s;
    }

    .level-option input[type="radio"]:checked + .level-radio-circle {
        border-color: #D32F2F;
    }

    .level-option input[type="radio"]:checked + .level-radio-circle::after {
        background: #D32F2F;
    }

    .level-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #212121;
    }

    /* ===== Tambahan ===== */
    .addon-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #F0F0F0;
        cursor: pointer;
        user-select: none;
    }

    .addon-item:last-child {
        border-bottom: none;
    }

    .addon-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .addon-name {
        font-size: 0.92rem;
        font-weight: 500;
        color: #212121;
    }

    .addon-price {
        font-size: 0.85rem;
        font-weight: 600;
        color: #2E7D32;
        margin-right: 14px;
        white-space: nowrap;
    }

    .addon-checkbox {
        width: 22px;
        height: 22px;
        accent-color: #D32F2F;
        cursor: pointer;
        flex-shrink: 0;
        border-radius: 6px;
    }

    /* ===== Catatan ===== */
    .catatan-input {
        width: 100%;
        padding: 12px 18px;
        border: 1.5px solid #F5C6C6;
        border-radius: 30px;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        color: #212121;
        background: #FFF;
    }

    .catatan-input::placeholder {
        color: #9E9E9E;
    }

    .catatan-input:focus {
        border-color: #D32F2F;
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
    }

    .catatan-count {
        font-size: 0.75rem;
        color: #9E9E9E;
        margin-top: 6px;
        text-align: right;
    }

    /* ===== Bottom Fixed Bar ===== */
    .bottom-bar {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 100%);
        box-shadow: 0 -6px 24px rgba(0,0,0,0.18);
        z-index: 100;
        padding: 16px 20px 20px;
        border-radius: 20px 20px 0 0;
    }

    .qty-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .qty-label {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
    }

    .qty-controls {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(0,0,0,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #D32F2F;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: transform 0.15s ease, background 0.15s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .qty-btn:active {
        transform: scale(0.9);
    }

    .qty-value {
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        min-width: 24px;
        text-align: center;
    }

    /* Submit button: pill kuning */
    .submit-btn {
        width: 100%;
        padding: 15px 22px;
        border: none;
        border-radius: 30px;
        background: #FFC107;
        color: #7A1212;
        font-size: 1rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 14px rgba(255, 193, 7, 0.4);
    }

    .submit-btn:active {
        transform: scale(0.97);
        background: #FFB300;
    }

    .submit-btn:hover {
        background: #FFB300;
        box-shadow: 0 6px 18px rgba(255, 193, 7, 0.55);
    }

    .submit-btn-price {
        font-weight: 800;
        font-size: 1.05rem;
    }

    /* ===== Spacer ===== */
    .bottom-spacer {
        height: 160px;
    }

    /* ===== Validation error ===== */
    .validation-error {
        background: #FFEBEE;
        color: #C62828;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.85rem;
        margin: 12px 20px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #FFCDD2;
    }
</style>
@endsection

@section('content')
@php
    $namaClean = strtolower(trim($menu->nama_menu));
    $isMinuman = $menu->kategori === 'Minuman';

    // Daftar minuman dengan pilihan Suhu (Dingin / Panas):
    // 1. Teh dan Jeruk
    // 2. Extra Joss, Extra Joss Susu, Energen, Adem Sari, Segar Dingin, Susu Putih/Coklat, Dancow, Milo/Hilo, Beng Beng, Chocolatos, Coffeemix, Nescafe, Luwak White Coffee, Caffino, Torabika, Top Coffee, varian Good Day, Nutrisari Aneka Rasa
    $suhuOptionsList = [
        'teh dingin/panas', 'jeruk dingin/panas',
        'extra joss', 'extra joss susu', 'energen', 'adem sari', 'segar dingin',
        'susu putih/coklat', 'dancow', 'milo/hilo', 'beng beng', 'chocolatos',
        'coffeemix', 'nescafe', 'luwak white coffee', 'caffino', 'torabika',
        'top coffee', 'nutrisari aneka rasa'
    ];

    $hasSuhuOption = false;
    if ($isMinuman) {
        if (in_array($namaClean, $suhuOptionsList) || str_starts_with($namaClean, 'good day') || str_contains($namaClean, 'teh') || str_contains($namaClean, 'jeruk')) {
            $hasSuhuOption = true;
        }
    }

    // Pilihan varian:
    $varianType = null; // 'susu' | 'milohilo' | 'indomie' | 'telur' | null
    if ($namaClean === 'susu putih/coklat' || str_contains($namaClean, 'susu putih/coklat') || str_contains($namaClean, 'susu')) {
        $varianType = 'susu';
    } elseif ($namaClean === 'milo/hilo' || str_contains($namaClean, 'milo/hilo') || str_contains($namaClean, 'milo') || str_contains($namaClean, 'hilo')) {
        $varianType = 'milohilo';
    } elseif (str_contains($namaClean, 'indomie')) {
        $varianType = 'indomie';
    } elseif (str_contains($namaClean, 'telur')) {
        $varianType = 'telur';
    }

    // Catatan disembunyikan untuk: Makanan dan Air Mineral 600ml
    $hideCatatan = $menu->kategori === 'Makanan' || $namaClean === 'air mineral 600ml' || str_contains($namaClean, 'air mineral');
@endphp

<form action="{{ route('customer.add_to_cart') }}" method="POST" id="form-add-to-cart" class="detail-page">
    @csrf
    <input type="hidden" name="id_menu" value="{{ $menu->id_menu }}">
    @if(!empty($editHash))
        <input type="hidden" name="old_hash" value="{{ $editHash }}">
    @endif

    {{-- ===== Top Image ===== --}}
    <div class="detail-image-wrapper">
        <a href="{{ !empty($editHash) ? route('customer.cart') : route('customer.menu', ['kategori' => $menu->kategori]) }}" class="back-btn">
            <i class="fa-solid fa-chevron-left"></i>
        </a>

        @if($menu->foto)
            <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" loading="lazy">
        @else
            <div class="img-placeholder">
                @if($menu->kategori === 'Paket')
                    <i class="fa-solid fa-box-open"></i>
                @elseif($menu->kategori === 'Makanan')
                    <i class="fa-solid fa-drumstick-bite"></i>
                @else
                    <i class="fa-solid fa-glass-water"></i>
                @endif
            </div>
        @endif
    </div>

    {{-- ===== Validation Errors ===== --}}
    @if($errors->any())
        <div class="validation-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ===== Info ===== --}}
    <div class="detail-info">
        <h1 class="detail-title">{{ $menu->nama_menu }}</h1>
        <div class="detail-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
        <p class="detail-desc">{{ $menu->deskripsi }}</p>
    </div>

    {{-- ===== Level Pedas (hanya jika opsi_pedas = Ya) ===== --}}
    @if($menu->opsi_pedas === 'Ya')
        @php
            $selectedLevel = $editItem['level_pedas'] ?? 2;
        @endphp
        <div class="detail-section">
            <div class="section-title">
                <span>Pilih Level Pedas</span>
                <span class="badge-wajib"><i class="fa-solid fa-circle-exclamation"></i> Wajib</span>
            </div>
            <div class="level-selector">
                @for($lvl = 1; $lvl <= 5; $lvl++)
                    <label class="level-option">
                        <input type="radio" name="level_pedas" value="{{ $lvl }}" {{ $lvl == $selectedLevel ? 'checked' : '' }} onchange="calculateSubtotal()">
                        <span class="level-radio-circle"></span>
                        <span class="level-label">Level {{ $lvl }}</span>
                    </label>
                @endfor
            </div>
        </div>
    @endif


    @if($varianType)
        <div class="detail-section">
            <div class="section-title">
                <span>
                    @if($varianType === 'indomie')
                        Pilih Jenis Indomie
                    @elseif($varianType === 'telur')
                        Pilih Opsi Telur
                    @else
                        Pilih Varian Rasa
                    @endif
                </span>
                <span class="badge-wajib"><i class="fa-solid fa-circle-exclamation"></i> Wajib</span>
            </div>
            <div class="option-pill-grid">
                @if($varianType === 'susu')
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Susu Putih" checked>
                        <div class="option-pill-content">
                            <span class="option-pill-title">Susu Putih</span>
                        </div>
                    </label>
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Susu Coklat">
                        <div class="option-pill-content">
                            <span class="option-pill-title">Susu Coklat</span>
                        </div>
                    </label>
                @elseif($varianType === 'milohilo')
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Milo" checked>
                        <div class="option-pill-content">
                            <span class="option-pill-title">Milo</span>
                        </div>
                    </label>
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Hilo">
                        <div class="option-pill-content">
                            <span class="option-pill-title">Hilo</span>
                        </div>
                    </label>
                @elseif($varianType === 'indomie')
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Indomie Goreng" checked>
                        <div class="option-pill-content">
                            <span class="option-pill-title">Goreng</span>
                        </div>
                    </label>
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Indomie Rebus">
                        <div class="option-pill-content">
                            <span class="option-pill-title">Rebus</span>
                        </div>
                    </label>
                @elseif($varianType === 'telur')
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Telur Ceplok" checked>
                        <div class="option-pill-content">
                            <span class="option-pill-title">Telur Ceplok</span>
                        </div>
                    </label>
                    <label class="option-pill-card">
                        <input type="radio" name="varian" value="Telur Dadar">
                        <div class="option-pill-content">
                            <span class="option-pill-title">Telur Dadar</span>
                        </div>
                    </label>
                @endif
            </div>
        </div>
    @endif

    {{-- ===== Pilihan Suhu (Dingin / Panas) ===== --}}
    @if($hasSuhuOption)
        <div class="detail-section">
            <div class="section-title">
                <span>Pilihan Penyajian</span>
                <span class="badge-wajib"><i class="fa-solid fa-circle-exclamation"></i> Wajib</span>
            </div>
            <div class="option-pill-grid">
                <label class="option-pill-card option-dingin">
                    <input type="radio" name="suhu" value="Dingin" checked>
                    <div class="option-pill-content">
                        <span class="option-pill-title">Dingin</span>
                    </div>
                </label>
                <label class="option-pill-card option-panas">
                    <input type="radio" name="suhu" value="Panas">
                    <div class="option-pill-content">
                        <span class="option-pill-title">Panas / Hangat</span>
                    </div>
                </label>
            </div>
        </div>
    @endif


    @if($menu->kategori === 'Paket')
        @php
            $editTambahanIds = isset($editItem['tambahans']) ? array_column($editItem['tambahans'], 'id_tambahan') : [];
        @endphp
        <div class="detail-section">
            <div class="section-title">
                <span>Menu Tambahan (Opsional)</span>
            </div>

            <div id="tambahans-list">
                @foreach($tambahans as $tambahan)
                    @php $isHabis = ($tambahan->status_stok === 'Habis'); @endphp
                    <label class="addon-item" style="{{ $isHabis ? 'opacity: 0.55; cursor: not-allowed;' : '' }}">
                        <span class="addon-name">
                            {{ $tambahan->nama_tambahan }}
                            @if($isHabis)
                                <span style="font-size: 0.72rem; color: #D32F2F; font-weight: 700; background: #FFEBEE; padding: 2px 6px; border-radius: 4px; margin-left: 4px;">Habis</span>
                            @endif
                        </span>
                        <div style="display: flex; align-items: center;">
                            <span class="addon-price">+ Rp {{ number_format($tambahan->harga, 0, ',', '.') }}</span>
                            <input type="checkbox" name="tambahans[]" value="{{ $tambahan->id_tambahan }}" data-harga="{{ $tambahan->harga }}" class="addon-checkbox" {{ in_array($tambahan->id_tambahan, $editTambahanIds) && !$isHabis ? 'checked' : '' }} {{ $isHabis ? 'disabled' : '' }} onchange="calculateSubtotal()">
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    
    @if(!$hideCatatan)
        <div class="detail-section">
            <div class="section-title">
                <span>Catatan (Opsional)</span>
            </div>
            <input type="text" name="catatan" id="catatan" class="catatan-input" placeholder="Contoh: Manis sedang, tanpa sedotan..." maxlength="300" value="{{ old('catatan', $editItem['catatan'] ?? '') }}" oninput="updateCharCount()">
            <div class="catatan-count"><span id="char-count">0</span>/300 Karakter</div>
        </div>
    @endif

    {{-- ===== Bottom Spacer ===== --}}
    <div class="bottom-spacer"></div>

    {{-- ===== Fixed Bottom Bar ===== --}}
    @php
        $initQty = $editItem['jumlah'] ?? 1;
    @endphp
    <div class="bottom-bar">
        {{-- Qty Row --}}
        <div class="qty-row">
            <span class="qty-label">Jumlah Pesanan</span>
            <div class="qty-controls">
                <button type="button" class="qty-btn" onclick="changeQty(-1)">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <span class="qty-value" id="qty-display">{{ $initQty }}</span>
                <input type="hidden" name="jumlah" id="jumlah-input" value="{{ $initQty }}">
                <button type="button" class="qty-btn" onclick="changeQty(1)">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="submit-btn">
            <span>{{ !empty($editHash) ? 'Perbarui Keranjang' : 'Tambah ke Keranjang' }}</span>
            <span class="submit-btn-price" id="subtotal-display">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const baseHarga = {{ $menu->harga }};
    let currentQty = {{ $editItem['jumlah'] ?? 1 }};

    function changeQty(delta) {
        currentQty += delta;
        if (currentQty < 1) currentQty = 1;
        if (currentQty > 99) currentQty = 99;
        document.getElementById('qty-display').innerText = currentQty;
        document.getElementById('jumlah-input').value = currentQty;
        calculateSubtotal();
    }

    function calculateSubtotal() {
        let totalTambahan = 0;
        const checkboxes = document.querySelectorAll('.addon-checkbox:checked');
        checkboxes.forEach(cb => {
            totalTambahan += parseInt(cb.getAttribute('data-harga') || 0);
        });

        const subtotal = (baseHarga + totalTambahan) * currentQty;
        document.getElementById('subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    function updateCharCount() {
        const input = document.getElementById('catatan');
        if (input) {
            document.getElementById('char-count').innerText = input.value.length;
        }
    }

    updateCharCount();
    calculateSubtotal();
</script>
@endsection