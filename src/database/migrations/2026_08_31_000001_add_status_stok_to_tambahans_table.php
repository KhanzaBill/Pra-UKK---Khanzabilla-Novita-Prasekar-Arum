<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk menambah kolom 'status_stok' ke tabel 'tambahans' yang sudah ada
return new class extends Migration
{
    /**
     * Jalankan migration: tambah kolom 'status_stok' ke tabel 'tambahans'.
     * Fitur ini ditambahkan belakangan agar admin bisa secara manual toggle ketersediaan tambahan,
     * mirip dengan kolom status_stok di tabel menus.
     */
    public function up(): void
    {
        Schema::table('tambahans', function (Blueprint $table) { // Modifikasi tabel 'tambahans' yang sudah ada
            // Tambah kolom status_stok dengan tipe enum (Tersedia/Habis), default 'Tersedia'
            // Kolom ditempatkan setelah kolom 'harga' menggunakan ->after()
            $table->enum('status_stok', ['Tersedia', 'Habis'])->default('Tersedia')->after('harga');
        });
    }

    /**
     * Batalkan migration: hapus kolom 'status_stok' dari tabel 'tambahans'.
     */
    public function down(): void
    {
        Schema::table('tambahans', function (Blueprint $table) { // Modifikasi tabel 'tambahans'
            $table->dropColumn('status_stok'); // Hapus kolom 'status_stok' dari tabel
        });
    }
};
