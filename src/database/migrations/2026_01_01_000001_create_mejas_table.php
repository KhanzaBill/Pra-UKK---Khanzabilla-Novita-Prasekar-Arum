<?php

// Import class Migration sebagai parent dari semua file migration Laravel
use Illuminate\Database\Migrations\Migration;
// Import Blueprint untuk mendefinisikan struktur kolom tabel
use Illuminate\Database\Schema\Blueprint;
// Import facade Schema untuk membuat / memodifikasi / menghapus tabel
use Illuminate\Support\Facades\Schema;

// Migration untuk membuat tabel 'mejas' (data meja restoran)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'mejas'.
     * Tabel ini menyimpan data setiap meja di restoran yang dilengkapi QR Code.
     */
    public function up(): void
    {
        Schema::create('mejas', function (Blueprint $table) { // Buat tabel baru bernama 'mejas'
            $table->id('id_meja');           // Kolom primary key auto-increment dengan nama 'id_meja'
            $table->string('nomor_meja', 20); // Kolom string untuk nomor/label meja (contoh: "Meja 01"), maks 20 karakter
            $table->timestamps();             // Kolom created_at dan updated_at otomatis dikelola Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'mejas'.
     * Dipanggil saat menjalankan php artisan migrate:rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('mejas'); // Hapus tabel 'mejas' jika ada (aman dari error jika tabel tidak ditemukan)
    }
};
