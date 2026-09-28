<?php

namespace App\Http\Middleware; // Mendefinisikan namespace middleware di App\Http\Middleware

use Closure;                    // Import interface Closure untuk parameter $next
use Illuminate\Http\Request;    // Import class Request untuk menangani HTTP request

// Middleware untuk memproteksi rute admin agar hanya bisa diakses jika sudah login
class AdminAuthMiddleware
{
    /**
     * Menangani setiap request yang masuk ke rute yang dilindungi.
     * Jika admin belum login (tidak ada session 'admin_id'), redirect ke halaman login.
     * Jika sudah login, lanjutkan request ke handler berikutnya.
     *
     * @param  Request  $request  Objek HTTP request yang masuk
     * @param  Closure  $next     Fungsi untuk meneruskan request ke handler berikutnya
     * @return mixed              Response dari handler berikutnya atau redirect ke login
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_id')) { // Cek apakah session 'admin_id' tidak ada (belum login)
            // Redirect ke halaman login dengan pesan error jika belum login
            return redirect()->route('admin.login')->withErrors(['auth' => 'Silakan login terlebih dahulu.']);
        }
        return $next($request); // Lanjutkan request ke handler/controller berikutnya jika sudah login
    }
}
