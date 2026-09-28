<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel pivot 'menu_bahans' (many-to-many antara menus dan bahans)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'menu_bahans'.
     * Tabel pivot ini menghubungkan setiap menu dengan bahan-bahan yang dibutuhkan.
     * Setiap baris menyatakan: "Menu X membutuhkan N unit Bahan Y".
     * Constraint UNIQUE pada (id_menu, id_bahan) mencegah duplikasi pasangan menu-bahan.
     */
    public function up(): void
    {
        Schema::create('menu_bahans', function (Blueprint $table) { // Buat tabel pivot baru bernama 'menu_bahans'
            $table->id();                              // Primary key auto-increment (standar)
            $table->unsignedBigInteger('id_menu');     // FK ke tabel menus (tipe unsignedBigInteger untuk kompatibilitas)
            $table->unsignedBigInteger('id_bahan');    // FK ke tabel bahans (tipe unsignedBigInteger untuk kompatibilitas)
            $table->integer('jumlah_dibutuhkan')->default(1); // Berapa unit bahan yang dibutuhkan per satu porsi menu (default: 1)
            $table->timestamps();                      // Kolom created_at dan updated_at otomatis Laravel

            // Definisi foreign key dengan cascade delete agar data pivot bersih saat induk dihapus
            $table->foreign('id_menu')->references('id_menu')->on('menus')->onDelete('cascade');   // Jika menu dihapus, relasi pivot ikut terhapus
            $table->foreign('id_bahan')->references('id_bahan')->on('bahans')->onDelete('cascade'); // Jika bahan dihapus, relasi pivot ikut terhapus

            // Constraint unik: satu menu hanya boleh memiliki satu baris untuk bahan yang sama
            $table->unique(['id_menu', 'id_bahan']); // Mencegah duplikasi pasangan menu-bahan
        });
    }

    /**
     * Batalkan migration: hapus tabel 'menu_bahans'.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_bahans'); // Hapus tabel 'menu_bahans' jika ada
    }
};
