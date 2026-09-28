<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'bahans' (data bahan baku inventori restoran)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'bahans'.
     * Tabel ini menyimpan daftar bahan baku yang digunakan oleh menu dan tambahan.
     * Stok bahan dikurangi otomatis setiap kali pesanan berhasil disimpan,
     * dan dikembalikan jika pesanan dibatalkan.
     */
    public function up(): void
    {
        Schema::create('bahans', function (Blueprint $table) { // Buat tabel baru bernama 'bahans'
            $table->id('id_bahan');          // Primary key auto-increment bernama 'id_bahan'
            $table->string('nama_bahan');    // Nama bahan baku (contoh: "Ayam Dada", "Tepung Bumbu")
            $table->integer('stok')->default(0); // Jumlah stok tersisa (integer), default 0 saat baru ditambahkan
            $table->timestamps();            // Kolom created_at dan updated_at otomatis Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'bahans'.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahans'); // Hapus tabel 'bahans' jika ada
    }
};
