@extends('layouts.customer')

@section('title', 'Checkout Pesanan - Yummy Chicken')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<style>
    /* ===== Override layout ===== */
    .app-header { display: none !important; }
    .content { padding: 0 !important; }
    .alert-success, .alert-error { display: none !important; }

    /* ===== Header ===== */
    .checkout-header-bar {
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

    .checkout-header-bar h1 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
    }

    /* ===== Body ===== */
    .checkout-body {
        padding: 20px 16px 30px;
    }

    /* ===== Checkout Cards ===== */
    .checkout-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        border: 1px solid var(--border-color, #EEEEEE);
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
    }

    .info-line {
        font-size: 0.9rem;
        color: var(--text-title, #212121);
        line-height: 1.7;
    }

    .info-line strong {
        font-weight: 600;
    }

    /* ===== Input Pemesan ===== */
    .checkout-input-group {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--border-color, #EEEEEE);
    }

    .checkout-input-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-title, #212121);
        margin-bottom: 6px;
    }

    .checkout-input-group label span {
        font-weight: 400;
        color: var(--text-muted, #9E9E9E);
        font-size: 0.78rem;
    }

    .checkout-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border-color, #EEEEEE);
        border-radius: 10px;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #FAFAFA;
    }

    .checkout-input:focus {
        border-color: var(--primary-color, #D32F2F);
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
        background: #fff;
    }

    /* ===== Ringkasan Ticket ===== */
    .ringkasan-title {
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-title, #212121);
        margin-bottom: 8px;
    }

    .ringkasan-dashes {
        text-align: center;
        color: #BDBDBD;
        font-weight: 700;
        letter-spacing: 4px;
        font-size: 0.9rem;
        margin-bottom: 16px;
        overflow: hidden;
        white-space: nowrap;
    }

    .ringkasan-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px dashed #EEEEEE;
        font-size: 0.88rem;
        color: var(--text-title, #212121);
    }

    .ringkasan-item-details {
        flex: 1;
        min-width: 0;
    }

    .ringkasan-item-name {
        font-weight: 600;
        line-height: 1.35;
        word-break: break-word;
        color: var(--text-title, #212121);
    }

    .ringkasan-item-sub {
        font-size: 0.78rem;
        color: var(--text-sub, #616161);
        margin-top: 2px;
        word-break: break-word;
    }

    .ringkasan-item-price {
        white-space: nowrap;
        font-weight: 700;
        color: var(--text-title, #212121);
        text-align: right;
        flex-shrink: 0;
    }

    .ringkasan-section-label {
        font-weight: 700;
        font-size: 0.85rem;
        color: #2E7D32;
        margin-top: 14px;
        margin-bottom: 8px;
        padding-top: 8px;
        border-top: 1px solid #E8F5E9;
    }

    .ringkasan-tambahan-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1px dotted #F0F0F0;
        font-size: 0.84rem;
        color: #2E7D32;
        padding-left: 10px;
    }

    .ringkasan-separator {
        border: none;
        border-top: 1.5px dashed #E0E0E0;
        margin: 14px 0;
    }

    .ringkasan-total {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--text-title, #212121);
    }

    .ringkasan-total-price {
        font-size: 1.2rem;
        color: var(--primary-color, #D32F2F);
        font-weight: 800;
    }

    /* ===== Metode Pembayaran ===== */
    .metode-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-title, #212121);
        margin-bottom: 16px;
    }

    .payment-option {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
        cursor: pointer;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1.5px solid var(--border-color, #EEEEEE);
        background: #FAFAFA;
        transition: all 0.2s ease;
    }

    .payment-option:hover {
        background: #FFF;
        border-color: #BDBDBD;
    }

    .payment-option input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: var(--primary-color, #D32F2F);
        cursor: pointer;
        flex-shrink: 0;
    }

    .payment-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-title, #212121);
    }

    .payment-notice {
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.82rem;
        margin-top: 8px;
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        line-height: 1.4;
        animation: fadeIn 0.3s ease;
    }

    .payment-notice.tunai {
        background: #E8F5E9;
        color: #2E7D32;
        border: 1px solid #A5D6A7;
    }

    .payment-notice.qris-info {
        background: #E3F2FD;
        color: #0277BD;
        border: 1px solid #BBDEFB;
    }

    /* ===== E-Wallet Section ===== */
    .ewallet-section {
        margin-top: 14px;
        display: none;
    }

    .ewallet-section.show {
        display: block;
        animation: fadeSlideIn 0.3s ease;
    }

    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .ewallet-label {
        font-size: 0.85rem;
        color: var(--text-body, #616161);
        margin-bottom: 10px;
        font-weight: 500;
    }

    .ewallet-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .ewallet-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 10px;
        border-radius: 12px;
        border: 2px solid #E0E0E0;
        background: #FAFAFA;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-title, #212121);
        transition: all 0.2s ease;
    }

    .ewallet-btn:active {
        transform: scale(0.96);
    }

    .ewallet-btn:hover {
        border-color: #BDBDBD;
        background: #FFF;
    }

    .ewallet-btn.selected {
        border-color: var(--primary-color, #D32F2F);
        background: #FFEBEE;
        color: var(--primary-color, #D32F2F);
    }

    .ewallet-btn .ewallet-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: #fff;
        flex-shrink: 0;
    }

    .ewallet-icon.dana    { background: #108ee9; }
    .ewallet-icon.gopay   { background: #00aed6; }
    .ewallet-icon.ovo     { background: #4c3494; }
    .ewallet-icon.shopee  { background: #ee4d2d; }

    /* ===== QR Code Section ===== */
    .qr-section {
        margin-top: 16px;
        text-align: center;
        display: none;
    }

    .qr-section.show {
        display: block;
        animation: fadeSlideIn 0.35s ease;
    }

    .qr-wrapper {
        display: inline-block;
        background: #fff;
        border: 2px solid #E0E0E0;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .qr-wrapper img {
        width: 180px;
        height: 180px;
        object-fit: contain;
        border-radius: 8px;
    }

    .qr-text {
        font-size: 0.82rem;
        color: var(--text-body, #616161);
        line-height: 1.5;
        margin-bottom: 8px;
    }

    .qr-text strong {
        color: var(--primary-color, #D32F2F);
        font-weight: 700;
    }

    .qr-change-btn {
        font-size: 0.8rem;
        color: var(--primary-color, #D32F2F);
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: underline;
        font-family: 'Poppins', sans-serif;
        padding: 4px 0;
        font-weight: 500;
    }

    /* ===== Submit Button ===== */
    .btn-submit-order {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        margin-top: 12px;
        padding: 16px;
        border-radius: 30px;
        background: #FFC107;
        color: #7A1212;
        font-weight: 700;
        font-size: 1.05rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        box-shadow: 0 4px 14px rgba(255, 193, 7, 0.4);
    }

    .btn-submit-order:active {
        transform: scale(0.97);
        background: #FFB300;
    }

    .btn-submit-order:hover {
        background: #FFB300;
        box-shadow: 0 6px 20px rgba(255, 193, 7, 0.55);
    }
</style>
@endsection

@section('content')
{{-- ===== Header ===== --}}
<div class="checkout-header-bar">
    <a href="{{ route('customer.cart') }}" class="back-btn">
        <i class="fa-solid fa-chevron-left"></i>
    </a>
    <h1>Konfirmasi Pesanan</h1>
</div>

<div class="checkout-body">
    <form id="checkoutForm" action="{{ route('customer.store_order') }}" method="POST">
        @csrf

        {{-- Card 1: Info Pesanan --}}
        <div class="checkout-card">
            <div class="info-line">
                Tipe Pesanan : <strong>{{ $tipePesanan }}</strong>
            </div>
            @if($tipePesanan === 'Dine-In')
                <div class="info-line">
                    Meja : <strong>{{ $nomorMeja ?? 'General' }}</strong>
                </div>
            @endif

            <div class="checkout-input-group">
                <label for="nama_pemesan">
                    Nama Pemesan
                </label>
                <input type="text" name="nama_pemesan" id="nama_pemesan" class="checkout-input" placeholder="Masukkan nama Anda" maxlength="50">
            </div>
        </div>

        {{-- Card 2: Ringkasan Pesanan --}}
        <div class="checkout-card">
            <div class="ringkasan-title">Ringkasan Pesanan</div>
            <div class="ringkasan-dashes">— — — — — — — — — — — — — — — — — — — — — — — — — —</div>

            @php $hasTambahan = false; @endphp

            @foreach($cart as $item)
                <div class="ringkasan-item">
                    <div class="ringkasan-item-details">
                        <div class="ringkasan-item-name">
                            {{ $item['jumlah'] }}x {{ $item['nama_menu'] }}
                        </div>
                        @if(!empty($item['level_pedas']))
                            <div class="ringkasan-item-sub" style="color: #D32F2F; font-weight: 500;">
                                Level {{ $item['level_pedas'] }}
                            </div>
                        @endif
                        @if(!empty($item['catatan']))
                            <div class="ringkasan-item-sub" style="font-style: italic;">
                                "{{ $item['catatan'] }}"
                            </div>
                        @endif
                    </div>
                    <span class="ringkasan-item-price">Rp {{ number_format($item['harga_menu'] * $item['jumlah'], 0, ',', '.') }}</span>
                </div>
                @if(!empty($item['tambahans']))
                    @php $hasTambahan = true; @endphp
                @endif
            @endforeach

            {{-- Kumpulkan semua tambahan --}}
            @if($hasTambahan)
                <div class="ringkasan-section-label">Menu Tambahan :</div>
                @foreach($cart as $item)
                    @if(!empty($item['tambahans']))
                        @foreach($item['tambahans'] as $t)
                            <div class="ringkasan-tambahan-item">
                                <span>+ {{ $t['nama_tambahan'] }}</span>
                                <span class="ringkasan-item-price">Rp {{ number_format($t['harga'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif

            <hr class="ringkasan-separator">

            <div class="ringkasan-total">
                <span>Total Tagihan :</span>
                <span class="ringkasan-total-price">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Card 3: Metode Pembayaran --}}
        <div class="checkout-card">
            <div class="metode-title">Metode Pembayaran</div>

            <label class="payment-option" id="opt-tunai">
                <input type="radio" name="metode_bayar" value="Tunai" checked onchange="switchPayment('Tunai')">
                <span class="payment-label">Bayar Tunai di Kasir</span>
            </label>
            <div id="notice-tunai" class="payment-notice tunai">
                <i class="fa-solid fa-circle-info"></i>
                <span>Siapkan uang tunai untuk pembayaran di kasir saat mengambil pesanan.</span>
            </div>

            <label class="payment-option" id="opt-qris">
                <input type="radio" name="metode_bayar" value="QRIS" onchange="switchPayment('QRIS')">
                <span class="payment-label">QRIS / E-Wallet</span>
            </label>
            <div id="notice-qris" class="payment-notice qris-info" style="display: none;">
                <i class="fa-solid fa-circle-info"></i>
                <span>Tunjukkan bukti pembayaran di aplikasi E-Wallet ke kasir untuk verifikasi.</span>
            </div>

            {{-- QRIS: Pilihan E-Wallet --}}
            <div id="ewallet-section" class="ewallet-section">
                <div class="ewallet-label">Pilih aplikasi pembayaran Anda :</div>
                <div class="ewallet-grid">
                    <button type="button" class="ewallet-btn" onclick="selectEwallet('DANA', this)">
                        <span class="ewallet-icon dana"><i class="fa-solid fa-wallet"></i></span>
                        DANA
                    </button>
                    <button type="button" class="ewallet-btn" onclick="selectEwallet('GoPay', this)">
                        <span class="ewallet-icon gopay"><i class="fa-solid fa-wallet"></i></span>
                        GoPay
                    </button>
                    <button type="button" class="ewallet-btn" onclick="selectEwallet('OVO', this)">
                        <span class="ewallet-icon ovo"><i class="fa-solid fa-wallet"></i></span>
                        OVO
                    </button>
                    <button type="button" class="ewallet-btn" onclick="selectEwallet('ShopeePay', this)">
                        <span class="ewallet-icon shopee"><i class="fa-solid fa-wallet"></i></span>
                        ShopeePay
                    </button>
                </div>
            </div>

            {{-- QRIS: QR Code display --}}
            <div id="qr-section" class="qr-section">
                <div class="qr-wrapper">
                    <img src="{{ asset('images/qris-code.jpg') }}" alt="QRIS Code" loading="lazy">
                </div>
                <div class="qr-text">
                    Scan QR di atas menggunakan aplikasi<br>
                    <strong id="selected-ewallet-name">—</strong>
                </div>
                <button type="button" class="qr-change-btn" onclick="backToEwalletList()">
                    <i class="fa-solid fa-arrow-left"></i> Ganti aplikasi pembayaran
                </button>
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" id="btnSubmit" class="btn-submit-order">
           Konfirmasi Pesanan
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function switchPayment(method) {
        const noticeTunai = document.getElementById('notice-tunai');
        const noticeQris  = document.getElementById('notice-qris');
        const ewalletSec  = document.getElementById('ewallet-section');
        const qrSec       = document.getElementById('qr-section');
        const btnSubmit   = document.getElementById('btnSubmit');

        if (method === 'Tunai') {
            noticeTunai.style.display = 'flex';
            noticeQris.style.display  = 'none';
            ewalletSec.classList.remove('show');
            qrSec.classList.remove('show');
            btnSubmit.innerHTML = 'Konfirmasi Pesanan';
        } else {
            noticeTunai.style.display = 'none';
            noticeQris.style.display  = 'flex';
            ewalletSec.classList.add('show');
            qrSec.classList.remove('show');
            btnSubmit.innerHTML = 'Konfirmasi Pesanan';

            document.querySelectorAll('.ewallet-btn').forEach(b => b.classList.remove('selected'));
        }
    }

    function selectEwallet(name, btn) {
        document.querySelectorAll('.ewallet-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');

        const ewalletSec = document.getElementById('ewallet-section');
        const qrSec      = document.getElementById('qr-section');
        const btnSubmit   = document.getElementById('btnSubmit');

        ewalletSec.classList.remove('show');
        qrSec.classList.add('show');

        document.getElementById('selected-ewallet-name').textContent = name;
        btnSubmit.innerHTML = '<i class="fa-solid fa-circle-check"></i> Saya Sudah Bayar';
    }

    function backToEwalletList() {
        const ewalletSec = document.getElementById('ewallet-section');
        const qrSec      = document.getElementById('qr-section');
        const btnSubmit   = document.getElementById('btnSubmit');

        qrSec.classList.remove('show');
        ewalletSec.classList.add('show');
        btnSubmit.innerHTML = 'Konfirmasi Pesanan';

        document.querySelectorAll('.ewallet-btn').forEach(b => b.classList.remove('selected'));
    }
</script>
@endsection

