<?php

use App\Providers\AppServiceProvider; // Import AppServiceProvider aplikasi

// Daftar service provider yang dimuat secara otomatis oleh Laravel saat bootstraping
return [
    AppServiceProvider::class, // Daftarkan AppServiceProvider utama aplikasi
];

