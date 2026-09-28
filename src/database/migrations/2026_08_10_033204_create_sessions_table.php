<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'sessions' (penyimpanan session berbasis database)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'sessions'.
     * Tabel ini digunakan Laravel sebagai driver penyimpanan session di database
     * (dikonfigurasi di config/session.php dengan driver = 'database').
     * Menyimpan data session pelanggan seperti keranjang belanja, tipe pesanan, dan data meja.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) { // Buat tabel baru bernama 'sessions'
            $table->string('id')->primary();           // Session ID sebagai primary key (string unik Laravel)
            $table->foreignId('user_id')->nullable()->index(); // FK opsional ke tabel users (nullable karena aplikasi ini tidak pakai auth bawaan)
            $table->string('ip_address', 45)->nullable(); // IP address pengguna (maks 45 karakter untuk mendukung IPv6)
            $table->text('user_agent')->nullable();       // User-agent browser pengguna (nullable)
            $table->longText('payload');                  // Data session yang di-serialize dan di-enkripsi
            $table->integer('last_activity')->index();    // Unix timestamp terakhir session aktif (digunakan untuk garbage collection)
        });
    }

    /**
     * Batalkan migration: hapus tabel 'sessions'.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions'); // Hapus tabel 'sessions' jika ada
    }
};
