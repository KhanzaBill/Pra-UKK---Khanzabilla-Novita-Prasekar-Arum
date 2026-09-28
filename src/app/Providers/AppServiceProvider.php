<?php

namespace App\Providers; // Mendefinisikan namespace Service Provider di App\Providers

use Illuminate\Support\ServiceProvider; // Import base class ServiceProvider dari framework Laravel

// Service provider utama aplikasi untuk mendaftarkan dan mengkonfigurasi service/fitur global
class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan service aplikasi ke dalam service container.
     * Tempat untuk mendaftarkan binding, singleton, atau dependensi custom.
     */
    public function register(): void
    {
        // Tempat mendaftarkan service atau binding dependensi custom aplikasi
    }

    /**
     * Mengkonfigurasi service aplikasi setelah semua service terdaftar (bootstrap).
     * Tempat untuk mendaftarkan event listener, pengatur skema database, validator custom, dll.
     */
    public function boot(): void
    {
        // Tempat menjalankan logika inisialisasi aplikasi saat aplikasi di-boot
    }
}

