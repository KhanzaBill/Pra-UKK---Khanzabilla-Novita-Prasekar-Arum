<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Yummy Chicken - Cita Rasa Ayam Geprek Semarang')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #D32F2F;
            --primary-hover: #B71C1C;
            --secondary-color: #FFC107;
            --secondary-hover: #FFB300;
            --bg-color: #F8F9FA;
            --card-bg: #FFFFFF;
            --text-title: #212121;
            --text-body: #616161;
            --text-muted: #9E9E9E;
            --border-color: #EEEEEE;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 14px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: #121212;
            color: var(--text-title);
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        /* Mobile Viewport Container */
        .mobile-container {
            width: 100%;
            max-width: 480px;
            background: var(--card-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
            animation: fadeInPage 0.3s ease-out;
        }

        @keyframes fadeInPage {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Top Header */
        .app-header {
            background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 55%, #E57373 100%);
            color: white;
            padding: 18px 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
            border-radius: 0 0 28px 28px;
        }

        .app-header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .app-header p {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Flash Messages */
        .alert {
            padding: 12px 16px;
            margin: 12px 16px 0 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: slideDown 0.3s ease;
        }
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Main Content */
        .content {
            flex: 1;
            padding: 16px;
        }

        /* Buttons & Forms */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            width: 100%;
        }
        .btn:active {
            transform: scale(0.97);
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.25);
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 6px 16px rgba(211, 47, 47, 0.35);
        }
        .btn-cta {
            background-color: var(--secondary-color);
            color: #7A1212;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(255, 193, 7, 0.35);
        }
        .btn-cta:hover {
            background-color: var(--secondary-hover);
            box-shadow: 0 6px 18px rgba(255, 193, 7, 0.45);
        }
        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
            border-radius: 20px;
        }

        .form-group {
            margin-bottom: 16px;
            position: relative;
        }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-title);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s ease;
            background: #FAFAFA;
            color: var(--text-title);
        }
        .form-control:focus {
            border-color: var(--primary-color);
            background: #FFF;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
        }
        .form-control.is-invalid {
            border-color: #C62828 !important;
            background: #FFF8F8 !important;
        }
        .invalid-feedback {
            color: #C62828;
            font-size: 0.78rem;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="mobile-container">
    <!-- Header -->
    <header class="app-header">
        <h1>YUMMY CHICKEN</h1>
        <p>Cita Rasa Ayam Geprek Semarang</p>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Content Body -->
    <main class="content">
        @yield('content')
    </main>

</div>

<script>
    // Universal Submit Button Spinner & Double Submit Prevention
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const btn = form.querySelector('button[type="submit"]');
                if (btn && !btn.dataset.noSpinner) {
                    btn.disabled = true;
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    }, 8000);
                }
            });
        });
    });
</script>

@yield('scripts')
</body>
</html>