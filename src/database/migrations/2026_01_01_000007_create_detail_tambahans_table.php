<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'detail_tambahans' (tabel pivot many-to-many antara detail pesanan dan tambahan)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'detail_tambahans'.
     * Tabel pivot ini menghubungkan satu baris detail_pesanans dengan banyak tambahan (add-on).
     * Contoh: item "Ayam Goreng" bisa memiliki tambahan "Saus Extra" dan "Kerupuk".
     */
    public function up(): void
    {
        Schema::create('detail_tambahans', function (Blueprint $table) { // Buat tabel baru bernama 'detail_tambahans'
            $table->id('id_detail_tambahan'); // Primary key auto-increment

            // FK ke tabel detail_pesanans: jika detail pesanan dihapus, data tambahan terkait ikut terhapus (CASCADE)
            $table->foreignId('id_detail')->constrained('detail_pesanans', 'id_detail')->onDelete('cascade');

            // FK ke tabel tambahans: jika tambahan dihapus, relasinya di tabel ini ikut terhapus (CASCADE)
            $table->foreignId('id_tambahan')->constrained('tambahans', 'id_tambahan')->onDelete('cascade');

            $table->timestamps(); // Kolom created_at dan updated_at otomatis Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'detail_tambahans'.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_tambahans'); // Hapus tabel 'detail_tambahans' jika ada
    }
};
