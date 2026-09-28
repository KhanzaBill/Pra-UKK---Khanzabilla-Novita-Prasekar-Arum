<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel pivot 'tambahan_bahans' (many-to-many antara tambahans dan bahans)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'tambahan_bahans'.
     * Tabel pivot ini menghubungkan setiap menu tambahan dengan bahan-bahan yang dibutuhkan.
     * Mirip dengan menu_bahans, tapi untuk tabel tambahans.
     * Setiap baris menyatakan: "Tambahan X membutuhkan N unit Bahan Y".
     */
    public function up(): void
    {
        Schema::create('tambahan_bahans', function (Blueprint $table) { // Buat tabel pivot baru bernama 'tambahan_bahans'
            $table->id();                              // Primary key auto-increment (standar)
            $table->unsignedBigInteger('id_tambahan'); // FK ke tabel tambahans (tipe unsignedBigInteger)
            $table->unsignedBigInteger('id_bahan');    // FK ke tabel bahans (tipe unsignedBigInteger)
            $table->integer('jumlah_dibutuhkan')->default(1); // Berapa unit bahan dibutuhkan per satu porsi tambahan (default: 1)
            $table->timestamps();                      // Kolom created_at dan updated_at otomatis Laravel

            // Definisi foreign key dengan cascade delete
            $table->foreign('id_tambahan')->references('id_tambahan')->on('tambahans')->onDelete('cascade'); // Jika tambahan dihapus, relasi pivot ikut terhapus
            $table->foreign('id_bahan')->references('id_bahan')->on('bahans')->onDelete('cascade');          // Jika bahan dihapus, relasi pivot ikut terhapus

            // Constraint unik: satu tambahan hanya boleh memiliki satu baris untuk bahan yang sama
            $table->unique(['id_tambahan', 'id_bahan']); // Mencegah duplikasi pasangan tambahan-bahan
        });
    }

    /**
     * Batalkan migration: hapus tabel 'tambahan_bahans'.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambahan_bahans'); // Hapus tabel 'tambahan_bahans' jika ada
    }
};
