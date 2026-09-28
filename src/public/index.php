<?php

use Illuminate\Foundation\Application; // Import class Application framework Laravel
use Illuminate\Http\Request;            // Import class Request untuk menangani HTTP request yang masuk

define('LARAVEL_START', microtime(true)); // Catat timestamp mikro awal dimulainya eksekusi aplikasi (untuk pengukur performa)

// Cek apakah aplikasi sedang dalam mode perbaikan/maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance; // Muat file maintenance handler jika aplikasi sedang di-down-kan
}

// Muat autoloader Composer untuk otomatis mendeteksi class-class PHP
require __DIR__.'/../vendor/autoload.php';

// Bootstrap aplikasi Laravel dan dapatkan instance aplikasi
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php'; // Inisialisasi dan konfigurasi dasar aplikasi Laravel

$app->handleRequest(Request::capture()); // Tangkap HTTP request dari browser dan jalankan penanganan request sampai menghasilkan response

