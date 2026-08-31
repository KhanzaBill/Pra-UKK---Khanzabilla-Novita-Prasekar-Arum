<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kasir - Yummy Chicken')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #D32F2F;
            --primary-dark: #B71C1C;
            --secondary: #FFC107;
            --secondary-hover: #FFB300;
            --dark-bg: #1A0C0C;
            --sidebar-bg: #1F0D0D;
            --light-bg: #F4F6F9;
            --card-bg: #FFFFFF;
            --text-main: #212121;
            --text-sub: #616161;
            --text-muted: #9E9E9E;
            --border: #EEEEEE;
            --success: #2E7D32;
            --danger: #C62828;
            --warning: #F57F17;
            --info: #0288D1;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.1);
            --radius-card: 16px;
            --radius-btn: 12px;
            --radius-pill: 30px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 55%, #E57373 100%);
            text-align: center;
            position: relative;
        }

        .sidebar-logo-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #D94044;
            border: 3px solid #FFF;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .sidebar-logo-circle:hover {
            transform: scale(1.05);
        }

        .sidebar-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-logo-circle span {
            color: #fff;
            font-size: 0.55rem;
            font-weight: 800;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .sidebar-brand h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: white;
            margin-bottom: 2px;
        }

        .sidebar-brand p {
            font-size: 0.73rem;
            color: rgba(255,255,255,0.85);
            font-weight: 400;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            flex: 1;
        }

        .sidebar-menu li {
            margin-bottom: 4px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: #D1B3B3;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-menu a:hover {
            color: white;
            background: rgba(211, 47, 47, 0.2);
            padding-left: 28px;
        }

        .sidebar-menu a.active {
            color: white;
            background: linear-gradient(90deg, rgba(211, 47, 47, 0.4) 0%, rgba(211, 47, 47, 0.05) 100%);
            border-left: 4px solid var(--secondary);
            font-weight: 600;
        }

        .sidebar-menu a.active i {
            color: var(--secondary);
        }

        .sidebar-menu i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
            transition: transform 0.2s;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 0.75rem;
            color: #A08080;
            text-align: center;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            min-width: 0;
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            border-bottom: 1px solid var(--border);
            gap: 16px;
        }

        .sidebar-toggle-btn {
            display: none;
            background: #FAFAFA;
            border: 1.5px solid var(--border);
            color: var(--text-main);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s ease;
        }
        .sidebar-toggle-btn:active { transform: scale(0.95); }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-title i {
            color: var(--primary);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #B71C1C, #D32F2F);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            border: 2px solid var(--secondary);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .user-name {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-role {
            font-size: 0.74rem;
            color: var(--text-sub);
        }

        .btn-logout {
            background: #FFEBEE;
            color: var(--danger);
            border: 1px solid #FFCDD2;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
            box-shadow: 0 2px 8px rgba(198,40,40,0.3);
        }
        .btn-logout:active {
            transform: scale(0.95);
        }

        /* Container */
        .container {
            padding: 28px 30px;
            flex: 1;
        }

        /* Cards & Tables */
        .card {
            background: white;
            border-radius: var(--radius-card);
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            margin-bottom: 24px;
            transition: box-shadow 0.2s ease;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1.5px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .table th, .table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table th {
            background: #FAF7F7;
            font-weight: 700;
            color: var(--text-sub);
            text-transform: uppercase;
            font-size: 0.74rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: background-color 0.15s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(211, 47, 47, 0.03);
        }

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-warning { background: #FFF8E1; color: var(--warning); border: 1.5px solid #FFE082; }
        .badge-info { background: #E3F2FD; color: #0277BD; border: 1.5px solid #BBDEFB; }
        .badge-success { background: #E8F5E9; color: var(--success); border: 1.5px solid #A5D6A7; }
        .badge-danger { background: #FFEBEE; color: var(--danger); border: 1.5px solid #FFCDD2; }
        .badge-secondary { background: #F5F5F5; color: #616161; border: 1.5px solid #E0E0E0; }

     
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.86rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }
        .btn:active {
            transform: scale(0.97);
        }
        .btn-primary { 
            background: var(--primary); 
            color: white; 
            box-shadow: 0 3px 8px rgba(211,47,47,0.2);
        }
        .btn-primary:hover { 
            background: var(--primary-dark); 
            box-shadow: 0 4px 12px rgba(211,47,47,0.35);
        }
        .btn-accent {
            background: var(--secondary);
            color: #7A1212;
            font-weight: 700;
            box-shadow: 0 3px 8px rgba(255,193,7,0.3);
        }
        .btn-accent:hover {
            background: var(--secondary-hover);
            box-shadow: 0 4px 12px rgba(255,193,7,0.45);
        }
        .btn-success { 
            background: var(--success); 
            color: white; 
            box-shadow: 0 3px 8px rgba(46,125,50,0.2);
        }
        .btn-success:hover {
            background: #1B5E20;
            box-shadow: 0 4px 12px rgba(46,125,50,0.35);
        }
        .btn-danger { 
            background: var(--danger); 
            color: white; 
            box-shadow: 0 3px 8px rgba(198,40,40,0.2);
        }
        .btn-danger:hover {
            background: #B71C1C;
            box-shadow: 0 4px 12px rgba(198,40,40,0.35);
        }
        .btn-sm { 
            padding: 6px 12px; 
            font-size: 0.78rem; 
            border-radius: 8px;
        }
        .btn-secondary { 
            background: #FAFAFA; 
            color: #424242; 
            border: 1.5px solid #E0E0E0;
        }
        .btn-secondary:hover {
            background: #F0F0F0;
            border-color: #BDBDBD;
        }

        
        nav[role="navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding: 10px 0;
            font-size: 0.85rem;
        }

        nav[role="navigation"] svg {
            width: 1rem !important;
            height: 1rem !important;
            max-width: 16px !important;
            max-height: 16px !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }

        nav[role="navigation"] p {
            font-size: 0.82rem;
            color: var(--text-sub);
            margin: 0;
        }

        nav[role="navigation"] span.relative,
        nav[role="navigation"] a.relative {
            padding: 6px 14px !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            border: 1.5px solid var(--border) !important;
            text-decoration: none !important;
            color: var(--text-main) !important;
            background: white !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            transition: all 0.2s ease !important;
        }

        nav[role="navigation"] a.relative:hover {
            background: var(--light-bg) !important;
            border-color: var(--primary) !important;
            color: var(--primary) !important;
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background: var(--primary) !important;
            color: white !important;
            border-color: var(--primary) !important;
        }

        
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            gap: 6px;
            align-items: center;
        }
        .pagination li a, .pagination li span {
            padding: 6px 12px;
            font-size: 0.82rem;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            color: var(--text-main);
            background: white;
            text-decoration: none;
        }
        .pagination li.active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }


        .custom-pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding: 16px 20px;
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            margin-top: 16px;
        }

        .pagination-info {
            font-size: 0.85rem;
            color: var(--text-sub);
            font-weight: 500;
        }

        .pagination-info span {
            font-weight: 700;
            color: var(--text-main);
        }

        .custom-pagination {
            display: flex;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 6px;
        }

        .custom-pagination .page-item {
            display: inline-flex;
        }

        .custom-pagination .page-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 0.86rem;
            font-weight: 600;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            color: var(--text-main);
            background: #FAFAFA;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .custom-pagination .page-link:hover {
            background: #FFF;
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(211, 47, 47, 0.15);
        }

        .custom-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, #B71C1C 100%);
            color: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.35);
        }

        .custom-pagination .page-item.disabled .page-link {
            background: #F5F5F5;
            color: #BDBDBD;
            border-color: #EEEEEE;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 576px) {
            .custom-pagination-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }

        /* Forms & Inline Error */
        .form-group { margin-bottom: 16px; position: relative; }
        .form-label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.85rem; color: var(--text-main); }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.88rem;
            outline: none;
            transition: all 0.2s ease;
            background: #FAFAFA;
            font-family: 'Poppins', sans-serif;
            color: var(--text-main);
        }
        .form-control:focus {
            border-color: var(--primary);
            background: #FFF;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
        }
        .form-control.is-invalid {
            border-color: var(--danger) !important;
            background: #FFF8F8 !important;
        }
        .invalid-feedback {
            color: var(--danger);
            font-size: 0.78rem;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }

        
        .alert-banner {
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }
        .alert-banner-success { background: #E8F5E9; color: #2E7D32; border: 1.5px solid #A5D6A7; }
        .alert-banner-error { background: #FFEBEE; color: #C62828; border: 1.5px solid #FFCDD2; }

       
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
            padding: 16px;
        }
        .modal-card {
            background: white;
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            border: 1px solid var(--border);
            animation: slideUpModal 0.25s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUpModal {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

       
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(2px);
            z-index: 99;
        }

      
        @media (max-width: 992px) {
            .sidebar-toggle-btn { display: flex; }
            .sidebar {
                position: fixed;
                top: 0; bottom: 0; left: 0;
                transform: translateX(-100%);
                z-index: 1000;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-backdrop.show {
                display: block;
            }
            .container {
                padding: 20px 16px;
            }
            .topbar {
                padding: 12px 16px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo-circle">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                @else
                    <span>YUMMY<br>CHICKEN</span>
                @endif
            </div>
            <h2>YUMMY CHICKEN</h2>
            <p>Cita Rasa Ayam Geprek Semarang</p>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i> Pesanan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.menus.index') }}" class="{{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
                    <i class="fa-solid fa-utensils"></i> Kelola Menu
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Laporan Penjualan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.qrcodes') }}" class="{{ request()->routeIs('admin.qrcodes*') ? 'active' : '' }}">
                    <i class="fa-solid fa-qrcode"></i> Generator QR Code
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            &copy; {{ date('Y') }} Yummy Chicken Semarang
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle-btn" onclick="toggleSidebar()" aria-label="Toggle Navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title">
                    Manajemen Kasir Yummy Chicken
                </div>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('admin_nama', 'A'), 0, 1)) }}
                </div>
                <div>
                    <span class="user-name">{{ session('admin_nama', 'Kasir') }}</span>
                    <span class="user-role"> Admin</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin-left: 10px;">
                    @csrf
                    <button type="submit" class="btn-logout">
                      Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Container -->
        <main class="container">
            @if(session('success'))
                <div class="alert-banner alert-banner-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-banner alert-banner-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('show');
            }
        }

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

