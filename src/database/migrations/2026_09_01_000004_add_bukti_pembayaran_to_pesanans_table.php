<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk menambah kolom 'bukti_pembayaran' ke tabel 'pesanans' yang sudah ada
return new class extends Migration
{
    /**
     * Jalankan migration: tambah kolom 'bukti_pembayaran' ke tabel 'pesanans'.
     * Kolom ini menyimpan path file foto bukti pembayaran yang diunggah pelanggan
     * saat memilih metode pembayaran QRIS.
     */
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) { // Modifikasi tabel 'pesanans' yang sudah ada
            // Tambah kolom string nullable untuk path file bukti pembayaran
            // Ditempatkan setelah kolom 'metode_bayar' agar urutan kolom logis
            $table->string('bukti_pembayaran')->nullable()->after('metode_bayar');
        });
    }

    /**
     * Batalkan migration: hapus kolom 'bukti_pembayaran' dari tabel 'pesanans'.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) { // Modifikasi tabel 'pesanans'
            $table->dropColumn('bukti_pembayaran'); // Hapus kolom 'bukti_pembayaran' dari tabel
        });
    }
};
