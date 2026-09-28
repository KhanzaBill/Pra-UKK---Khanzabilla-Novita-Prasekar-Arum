<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'tambahans' (data menu tambahan / add-on / topping)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'tambahans'.
     * Tabel ini menyimpan daftar menu tambahan yang bisa dipilih pelanggan saat memesan.
     * Contoh: "Saus Extra", "Kerupuk", "Es Teh Manis".
     */
    public function up(): void
    {
        Schema::create('tambahans', function (Blueprint $table) { // Buat tabel baru bernama 'tambahans'
            $table->id('id_tambahan');   // Primary key auto-increment bernama 'id_tambahan'
            $table->string('nama_tambahan'); // Nama menu tambahan (contoh: "Saus Pedas Extra")
            $table->integer('harga');        // Harga tambahan dalam rupiah
            $table->timestamps();            // Kolom created_at dan updated_at otomatis Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'tambahans'.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambahans'); // Hapus tabel 'tambahans' jika ada
    }
};
