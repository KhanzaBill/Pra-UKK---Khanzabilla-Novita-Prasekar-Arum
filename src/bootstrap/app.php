<?php

use Illuminate\Foundation\Application;                // Import class Application untuk inisialisasi aplikasi Laravel
use Illuminate\Foundation\Configuration\Exceptions;   // Import class Exceptions untuk mengkonfigurasi exception handler
use Illuminate\Foundation\Configuration\Middleware;   // Import class Middleware untuk mengkonfigurasi middleware pipeline

// Konfigurasi instance utama aplikasi Laravel (Laravel 11 Bootstraping)
return Application::configure(basePath: dirname(__DIR__)) // Set path direktori utama (root) aplikasi
    ->withRouting(
        web: __DIR__.'/../routes/web.php',       // Daftarkan file rute web utama aplikasi
        commands: __DIR__.'/../routes/console.php', // Daftarkan file rute perintah konsol (Artisan)
        health: '/up',                            // Endpoint health check aplikasi (/up)
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*'); // Percayai semua HTTP proxy (berguna untuk deployment cloud/reverse proxy)
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Tempat mendaftarkan custom exception handler atau penanganan error khusus
    })->create(); // Buat dan kembalikan instance Application yang telah dikonfigurasi

