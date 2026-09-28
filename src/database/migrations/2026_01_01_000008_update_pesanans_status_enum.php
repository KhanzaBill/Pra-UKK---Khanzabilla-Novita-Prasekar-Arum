<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Support\Facades\DB;             // Import facade DB untuk menjalankan raw SQL statement

// Migration untuk memperbarui enum kolom 'status' di tabel 'pesanans'
// Menambahkan nilai 'Disiapkan' ke dalam enum yang sudah ada
return new class extends Migration
{
    /**
     * Jalankan migration: ubah definisi enum kolom 'status' di tabel pesanans.
     * Menambahkan status 'Disiapkan' (antara 'Diproses' dan 'Selesai') untuk alur pesanan yang lebih detail.
     * Alur lengkap: Diterima → Diproses → Disiapkan → Selesai / Dibatalkan
     */
    public function up(): void
    {
        // Jalankan raw SQL untuk memodifikasi tipe ENUM kolom status (MODIFY COLUMN MySQL)
        // Menambahkan nilai 'Disiapkan' ke dalam daftar enum yang sebelumnya hanya 4 nilai
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('Diterima', 'Diproses', 'Disiapkan', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Diterima'");
    }

    /**
     * Batalkan migration: kembalikan enum kolom 'status' ke definisi sebelumnya (tanpa 'Disiapkan').
     */
    public function down(): void
    {
        // Kembalikan definisi enum ke 4 nilai sebelum migrasi ini diterapkan (hapus 'Disiapkan')
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('Diterima', 'Diproses', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Diterima'");
    }
};
