<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'admins' (data akun kasir / admin restoran)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'admins'.
     * Tabel ini menyimpan data login kasir/admin yang dapat mengakses dashboard admin.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) { // Buat tabel baru bernama 'admins'
            $table->id('id_admin');               // Primary key auto-increment bernama 'id_admin'
            $table->string('nama');               // Nama lengkap kasir/admin
            $table->string('username')->unique(); // Username untuk login, harus unik di seluruh tabel
            $table->string('password');           // Password yang sudah di-hash (bcrypt)
            $table->timestamps();                 // Kolom created_at dan updated_at otomatis Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'admins'.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins'); // Hapus tabel 'admins' jika ada
    }
};
