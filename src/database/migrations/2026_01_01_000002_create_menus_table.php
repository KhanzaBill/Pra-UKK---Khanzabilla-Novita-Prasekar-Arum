<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'menus' (data menu makanan/minuman/paket)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'menus'.
     * Tabel ini menyimpan seluruh data menu yang tersedia di restoran.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) { // Buat tabel baru bernama 'menus'
            $table->id('id_menu');                                               // Primary key auto-increment bernama 'id_menu'
            $table->string('nama_menu');                                          // Nama menu (contoh: "Ayam Goreng Crispy")
            $table->enum('kategori', ['Paket', 'Makanan', 'Minuman']);            // Kategori menu, hanya 3 nilai yang diperbolehkan
            $table->integer('harga');                                             // Harga menu dalam rupiah (integer)
            $table->text('deskripsi')->nullable();                                // Deskripsi menu yang bisa kosong (nullable)
            $table->enum('status_stok', ['Tersedia', 'Habis'])->default('Tersedia'); // Status ketersediaan manual, default 'Tersedia'
            $table->enum('opsi_pedas', ['Ya', 'Tidak'])->default('Tidak');       // Apakah menu memiliki pilihan level pedas, default 'Tidak'
            $table->string('foto')->nullable();                                   // Path file foto menu di storage, bisa kosong
            $table->timestamps();                                                 // Kolom created_at dan updated_at otomatis Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'menus'.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus'); // Hapus tabel 'menus' jika ada
    }
};
