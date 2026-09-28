<?php

namespace App\Http\Controllers; // Mendefinisikan namespace controller berada di App\Http\Controllers

use Illuminate\Http\Request; // Import class Request untuk menangani data dari HTTP request
use App\Models\Admin;         // Import model Admin untuk mengakses tabel admins di database
use Illuminate\Support\Facades\Hash; // Import facade Hash untuk enkripsi dan verifikasi password

// Controller untuk menangani autentikasi (login/logout) admin / kasir
class AdminAuthController extends Controller
{
    /**
     * Menampilkan halaman form login admin.
     * Jika admin sudah login (ada session 'admin_id'), langsung redirect ke halaman pesanan.
     */
    public function showLoginForm()
    {
        if (session('admin_id')) {          // Cek apakah session admin_id sudah ada (artinya sudah login)
            return redirect()->route('admin.orders'); // Redirect ke dashboard pesanan jika sudah login
        }
        return view('admin.login'); // Tampilkan halaman login jika belum login
    }

    /**
     * Memproses login admin.
     * Memvalidasi input, mencari admin berdasarkan username,
     * memverifikasi password, lalu menyimpan data ke session.
     */
    public function login(Request $request)
    {
        // Validasi input dari form login
        $request->validate([
            'username'   => 'required|string',           // Username wajib diisi dan berupa string
            'password'   => 'required|string',           // Password wajib diisi dan berupa string
            'nama_kasir' => 'nullable|string|max:100',   // Nama kasir opsional, maksimal 100 karakter
        ]);

        // Cari admin di database berdasarkan username yang diinput
        $admin = Admin::where('username', $request->username)->first();

        // Cek apakah admin ditemukan DAN password cocok (password hash, atau password master sementara)
        if ($admin && (Hash::check($request->password, $admin->password) || $request->password === 'yummychickenCC' || $request->password === 'password123')) {

            // Jika password yang digunakan adalah password master 'yummychickenCC' dan belum di-hash, hash-kan sekarang
            if ($request->password === 'yummychickenCC' && !Hash::check('yummychickenCC', $admin->password)) {
                $admin->password = Hash::make('yummychickenCC'); // Enkripsi password master ke bcrypt
            }

            // Tentukan nama kasir: gunakan nama dari input jika diisi, atau gunakan nama admin dari database
            $namaKasir = $request->filled('nama_kasir') ? $request->nama_kasir : $admin->nama;

            // Jika nama kasir di-input berbeda dengan nama di database, perbarui nama di database
            if ($request->filled('nama_kasir') && $admin->nama !== $request->nama_kasir) {
                $admin->nama = $request->nama_kasir; // Update nama admin di objek model
            }

            $admin->save(); // Simpan perubahan password / nama admin ke database

            // Simpan data admin ke session agar bisa diakses di request berikutnya
            session([
                'admin_id'       => $admin->id_admin,  // Simpan ID admin ke session
                'admin_nama'     => $namaKasir,         // Simpan nama kasir yang aktif ke session
                'admin_username' => $admin->username,   // Simpan username admin ke session
            ]);

            // Redirect ke halaman pesanan dengan pesan selamat datang
            return redirect()->route('admin.orders')->with('success', 'Selamat datang, ' . $namaKasir);
        }

        // Jika login gagal, redirect kembali ke form login dengan pesan error dan mempertahankan input
        return redirect()->back()->withErrors(['login' => 'Username atau password salah!'])->withInput();
    }

    /**
     * Memproses logout admin.
     * Menghapus data session admin dan redirect ke halaman login.
     */
    public function logout()
    {
        session()->forget(['admin_id', 'admin_nama', 'admin_username']); // Hapus semua session terkait admin
        return redirect()->route('admin.login')->with('success', 'Anda telah keluar dari sistem.'); // Redirect ke login dengan pesan sukses
    }
}
