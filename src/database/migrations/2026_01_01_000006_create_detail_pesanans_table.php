<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'detail_pesanans' (item-item dalam satu pesanan)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'detail_pesanans'.
     * Setiap baris mewakili satu item menu dalam sebuah pesanan.
     * Relasi: banyak detail bisa dimiliki satu pesanan (one-to-many dari pesanans).
     */
    public function up(): void
    {
        Schema::create('detail_pesanans', function (Blueprint $table) { // Buat tabel baru bernama 'detail_pesanans'
            $table->id('id_detail'); // Primary key auto-increment bernama 'id_detail'

            // FK ke tabel pesanans: jika pesanan dihapus, semua detailnya ikut terhapus (CASCADE)
            $table->foreignId('id_pesanan')->constrained('pesanans', 'id_pesanan')->onDelete('cascade');

            // FK ke tabel menus: jika menu dihapus, detail yang mereferensikan menu tersebut ikut terhapus (CASCADE)
            $table->foreignId('id_menu')->constrained('menus', 'id_menu')->onDelete('cascade');

            $table->integer('jumlah');              // Jumlah porsi menu yang dipesan pada baris detail ini
            $table->integer('level_pedas')->nullable(); // Level kepedasan 1-5 (nullable jika menu tidak ada opsi pedas)
            $table->text('catatan')->nullable();    // Catatan khusus pelanggan untuk item ini (nullable)
            $table->integer('subtotal');            // Total harga item ini: (harga_menu + harga_tambahan) × jumlah

            $table->timestamps(); // Kolom created_at dan updated_at otomatis Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'detail_pesanans'.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans'); // Hapus tabel 'detail_pesanans' jika ada
    }
};
