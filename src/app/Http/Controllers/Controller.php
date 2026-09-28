<?php

namespace App\Http\Controllers; // Mendefinisikan namespace controller berada di App\Http\Controllers

// Base Controller yang diwarisi oleh semua controller di aplikasi ini
// Bersifat abstract sehingga tidak bisa diinstansiasi langsung
abstract class Controller
{
    // Base Controller Class for Laravel
    // Semua controller lain (AdminAuthController, CustomerController, dll) mewarisi class ini
    // Dapat ditambahkan metode atau middleware bersama di sini jika diperlukan di masa mendatang
}
