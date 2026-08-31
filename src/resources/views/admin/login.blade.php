<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Yummy Chicken</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; -webkit-tap-highlight-color: transparent; }
        body {
            background: linear-gradient(135deg, #1C0D0D 0%, #2A0C0C 50%, #150606 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            padding: 40px 32px 36px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-logo-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #D94044;
            border: 3px solid #FFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 6px 20px rgba(217, 64, 68, 0.4);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .brand-logo-circle:hover {
            transform: scale(1.05);
        }
        .brand-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .brand-logo-circle span {
            color: #fff;
            font-size: 0.8rem;
            font-weight: 800;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #212121;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .brand-subtitle {
            font-size: 0.82rem;
            color: #616161;
        }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: #212121; margin-bottom: 6px; }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #E0E0E0;
            border-radius: 10px;
            font-size: 0.9rem;
            outline: none;
            background: #FAFAFA;
            transition: all 0.2s ease;
        }
        .form-control:focus { 
            border-color: #D32F2F; 
            background: #FFF;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #FFC107;
            color: #7A1212;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
            box-shadow: 0 4px 14px rgba(255, 193, 7, 0.35);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:active { transform: scale(0.98); }
        .btn-submit:hover { 
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.5);
            background: #FFB300;
        }
        .alert-error {
            background: #FFEBEE;
            color: #C62828;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: 1px solid #FFCDD2;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-header">
        <div class="brand-logo-circle">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" loading="lazy">
            @else
                <span>YUMMY<br>CHICKEN</span>
            @endif
        </div>
        <h2 class="brand-title">LOGIN ADMIN</h2>
        <p class="brand-subtitle">Hanya untuk Karyawan Yummy Chicken</p>
    </div>

    @if($errors->has('login') || session('error'))
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('login') ?? session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm">
        @csrf
        <div class="form-group">
            <label class="form-label" for="role"><i class="fa-solid fa-user-shield" style="color: #D32F2F;"></i> Role</label>
            <input type="text" name="username" id="role" class="form-control" value="admin" readonly style="background: #F5F5F5; cursor: not-allowed;" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="nama_kasir"><i class="fa-solid fa-id-card" style="color: #D32F2F;"></i> Nama Kasir</label>
            <input type="text" name="nama_kasir" id="nama_kasir" class="form-control" placeholder="Masukkan nama kasir" value="{{ old('nama_kasir') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password"><i class="fa-solid fa-lock" style="color: #D32F2F;"></i> Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" value="yummychickenCC" required>
        </div>

        <button type="submit" id="btnLoginSubmit" class="btn-submit">
            <p>Masuk</p>
        </button>
    </form>

<script>
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnLoginSubmit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
        }
    });
</script>

</body>
</html>

