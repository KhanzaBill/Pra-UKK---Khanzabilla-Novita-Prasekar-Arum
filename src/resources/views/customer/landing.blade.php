<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Selamat Datang - Yummy Chicken</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            min-height: 100%;
        }

        body {
            display: flex;
            justify-content: center;
            background: #000;
        }

        .landing-wrapper {
            position: relative;
            width: 100%;
            max-width: 480px;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        /* ===== Background Image ===== */
        .landing-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            max-width: 480px;
            margin: 0 auto;
        }

        .landing-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dark overlay to make text readable */
        .landing-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0.25) 0%,
                rgba(0, 0, 0, 0.5) 40%,
                rgba(0, 0, 0, 0.8) 100%
            );
        }

        /* ===== Content Layer ===== */
        .landing-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 100vh;
            min-height: 100dvh;
            padding: 56px 24px 48px;
        }

        /* ===== Circular Logo ===== */
        .logo-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #D94044;
            border: 4px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .logo-circle:hover {
            transform: scale(1.03);
        }

        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-text {
            color: #fff;
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.2;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ===== Meja Info ===== */
        .meja-badge-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .meja-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .meja-info i {
            color: #FFC107;
        }

        /* ===== Spacer ===== */
        .landing-spacer {
            flex: 1;
            min-height: 36px;
        }

        /* ===== Heading ===== */
        .landing-heading {
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 24px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
            text-align: center;
        }

        /* ===== Action Cards ===== */
        .action-cards {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .action-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 28px 20px;
            border-radius: 18px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .action-card:active {
            transform: scale(0.97);
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
        }

        /* Dine In Card */
        .card-dinein {
            background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 50%, #E57373 100%);
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.45);
            border: 1.5px solid rgba(255,255,255,0.2);
        }

        /* Takeaway Card */
        .card-takeaway {
            background: linear-gradient(135deg, #FFC107 0%, #FFB300 50%, #FFA000 100%);
            box-shadow: 0 8px 24px rgba(255, 193, 7, 0.35);
            border: 1.5px solid rgba(255,255,255,0.3);
        }

        .action-card .card-icon {
            font-size: 2.8rem;
            margin-bottom: 8px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .card-dinein .card-icon { color: #fff; }
        .card-takeaway .card-icon { color: #7A1212; }

        .action-card .card-label {
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card-dinein .card-label { color: #fff; }
        .card-takeaway .card-label { color: #7A1212; }

        /* ===== Flash Messages ===== */
        .landing-alert {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            max-width: 420px;
            width: calc(100% - 40px);
            animation: slideDown 0.4s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .landing-alert-success {
            background: rgba(46, 125, 50, 0.95);
            color: #fff;
            backdrop-filter: blur(10px);
        }

        .landing-alert-error {
            background: rgba(198, 40, 40, 0.95);
            color: #fff;
            backdrop-filter: blur(10px);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        /* ===== Fade-in animation ===== */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.6s ease forwards;
        }

        .fade-up:nth-child(1) { animation-delay: 0.1s; }
        .fade-up:nth-child(2) { animation-delay: 0.25s; }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== No background image placeholder ===== */
        .no-bg-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1A0C0C 0%, #B71C1C 50%, #D32F2F 100%);
        }
    </style>
</head>
<body>

<div class="landing-wrapper">
    {{-- ===== Background Image ===== --}}
    <div class="landing-bg">
        @if(file_exists(public_path('images/landing-bg.jpg')))
            <img src="{{ asset('images/landing-bg.jpg') }}" alt="Yummy Chicken Background" loading="lazy">
        @elseif(file_exists(public_path('images/landing-bg.png')))
            <img src="{{ asset('images/landing-bg.png') }}" alt="Yummy Chicken Background" loading="lazy">
        @else
            <div class="no-bg-placeholder"></div>
        @endif
    </div>

    {{-- ===== Flash Messages ===== --}}
    @if(session('success'))
        <div class="landing-alert landing-alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="landing-alert landing-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ===== Content ===== --}}
    <div class="landing-content">

        {{-- Logo Circle --}}
        <div class="logo-circle">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" loading="lazy">
            @else
                <span class="logo-text">YUMMY<br>CHICKEN</span>
            @endif
        </div>

        {{-- Nomor Meja --}}
        @if($meja || session('nomor_meja'))
            <div class="meja-badge-container">
                <div class="meja-info">
                    {{ $meja->nomor_meja ?? session('nomor_meja') }}
                </div>
            </div>
        @endif

        {{-- Spacer --}}
        <div class="landing-spacer"></div>

        {{-- Heading --}}
        <h2 class="landing-heading">Mau pesan apa hari ini?</h2>

        {{-- Action Cards --}}
        <form action="{{ route('customer.set_order_type') }}" method="POST" class="action-cards">
            @csrf

            <button type="submit" name="tipe_pesanan" value="Dine-In" class="action-card card-dinein fade-up">
                <i class="fa-solid fa-utensils card-icon"></i>
                <span class="card-label">Dine In</span>
            </button>

            <button type="submit" name="tipe_pesanan" value="Take Away" class="action-card card-takeaway fade-up">
                <i class="fa-solid fa-box-open card-icon"></i>
                <span class="card-label">Takeaway</span>
            </button>
        </form>
    </div>
</div>

</body>
</html>