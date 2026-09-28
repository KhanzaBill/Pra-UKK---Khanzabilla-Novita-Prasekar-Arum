<!DOCTYPE html>
<html lang="id"> {{-- Mengeset bahasa dokumen ke Bahasa Indonesia --}}
<head>
    <meta charset="UTF-8"> {{-- Mengeset enkoding karakter ke UTF-8 --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> {{-- Mengatur tampilan responsif mobile & mencegah zoom pengguna --}}
    <title>@yield('title', 'Yummy Chicken - Cita Rasa Ayam Geprek Semarang')</title> {{-- Judul halaman dinamis dari child view --}}
    
    <!-- Google Fonts: Import font Poppins untuk tampilan modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons: Import pustaka ikon FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #D32F2F;   /* Warna utama tema merah khas Yummy Chicken */
            --primary-hover: #B71C1C;   /* Warna hover tombol utama */
            --secondary-color: #FFC107; /* Warna aksen kuning emas */
            --secondary-hover: #FFB300; /* Warna hover tombol aksen */
            --bg-color: #F8F9FA;        /* Warna latar belakang umum */
            --card-bg: #FFFFFF;         /* Warna latar belakang kartu/kontainer */
            --text-title: #212121;      /* Warna teks judul utama */
            --text-body: #616161;       /* Warna teks konten deskripsi */
            --text-muted: #9E9E9E;      /* Warna teks sekunder/redup */
            --border-color: #EEEEEE;    /* Warna garis pembatas halus */
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);   /* Bayangan kecil */
            --shadow-md: 0 4px 14px rgba(0,0,0,0.1);   /* Bayangan sedang */
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);  /* Bayangan besar */
        }

        * {
            box-sizing: border-box; /* Menghitung padding dan border dalam total lebar/tinggi elemen */
            margin: 0;             /* Reset margin default browser */
            padding: 0;            /* Reset padding default browser */
            font-family: 'Poppins', sans-serif; /* Menggunakan font Poppins untuk seluruh elemen */
            -webkit-tap-highlight-color: transparent; /* Menghapus sorotan tap pada layar sentuh mobile */
        }

        body {
            background-color: #121212; /* Latar belakang luar kontainer (gelap untuk tampilan mobile mockup) */
            color: var(--text-title);  /* Warna teks bawaan */
            display: flex;             /* Menggunakan flexbox untuk memosisikan kontainer di tengah */
            justify-content: center;   /* Meratakan kontainer mobile di tengah horizontal */
            min-height: 100vh;         /* Tinggi minimal setinggi layar browser */
        }

        /* Mobile Viewport Container - Membatasi lebar tampilan seperti layar smartphone */
        .mobile-container {
            width: 100%;
            max-width: 480px;          /* Batas lebar maksimal 480px (standar smartphone) */
            background: var(--card-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;    /* Menyusun header, pesan, dan konten secara vertikal */
            position: relative;
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
            animation: fadeInPage 0.3s ease-out; /* Animasi kemunculan halaman */
        }

        @keyframes fadeInPage {
            from { opacity: 0; transform: translateY(6px); }  /* Titik awal animasi: agak transparan & turun */
            to   { opacity: 1; transform: translateY(0); }    /* Titik akhir animasi: muncul penuh */
        }

        /* Top Header - Header atas aplikasi pelanggan */
        .app-header {
            background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 55%, #E57373 100%); /* Gradien merah */
            color: white;
            padding: 18px 20px;
            text-align: center;
            position: sticky;          /* Tetap menempel di atas saat di-scroll */
            top: 0;
            z-index: 100;              /* Berada di atas elemen konten lainnya */
            box-shadow: var(--shadow-sm);
            border-radius: 0 0 28px 28px; /* Lengkungan melengkung di sudut bawah header */
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

        /* Flash Messages - Notifikasi pesan sukses atau error dari session */
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
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; } /* Alert sukses hijau */
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }   /* Alert error merah */

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Main Content - Wadah utama tempat isi halaman ditampilkan */
        .content {
            flex: 1;           /* Mengisi sisa ruang kosong dalam flex container */
            padding: 16px;
        }

        /* Buttons & Forms - Styling standar untuk tombol dan input form */
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
            transform: scale(0.97); /* Efek membal saat tombol ditekan */
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
    @yield('styles') {{-- Section tempat memuat style CSS khusus halaman anak --}}
</head>
<body>

<div class="mobile-container">
    <!-- Header Aplikasi Pelanggan -->
    <header class="app-header">
        <h1>YUMMY CHICKEN</h1>
        <p>Cita Rasa Ayam Geprek Semarang</p>
    </header>

    <!-- Notifikasi Flash Message dari Session -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }} {{-- Tampilkan pesan sukses jika ada --}}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }} {{-- Tampilkan pesan error jika ada --}}
        </div>
    @endif

    <!-- Area Konten Utama Halaman -->
    <main class="content">
        @yield('content') {{-- Tempat konten spesifik halaman anak disisipkan --}}
    </main>

</div>

<script>
    // Universal Submit Button Spinner & Double Submit Prevention (Mencegah submit ganda saat tombol diklik)
    document.addEventListener('DOMContentLoaded', function() {
        // Cari semua form pada halaman
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const btn = form.querySelector('button[type="submit"]'); // Cari tombol submit di dalam form
                if (btn && !btn.dataset.noSpinner) { // Jika tombol ditemukan dan tidak ada atribut data-no-spinner
                    btn.disabled = true; // Nonaktifkan tombol agar tidak diklik dua kali
                    const originalHTML = btn.innerHTML; // Simpan teks/HTML asli tombol
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...'; // Ubah tombol menampilkan ikon pemrosesan
                    setTimeout(() => { // Safety fallback jika submit tertunda lama
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    }, 8000);
                }
            });
        });
    });
</script>

@yield('scripts') {{-- Section tempat memuat script JavaScript khusus halaman anak --}}
</body>
</html>