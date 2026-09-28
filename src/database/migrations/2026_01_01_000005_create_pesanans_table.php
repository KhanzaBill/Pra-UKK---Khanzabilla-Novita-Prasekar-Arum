<?php

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai parent migration
use Illuminate\Database\Schema\Blueprint;      // Import Blueprint untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;         // Import facade Schema untuk operasi DDL tabel

// Migration untuk membuat tabel 'pesanans' (data transaksi pesanan pelanggan)
return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel 'pesanans'.
     * Tabel utama yang menyimpan setiap transaksi pesanan dari pelanggan.
     * Memiliki FK ke tabel mejas dan admins.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) { // Buat tabel baru bernama 'pesanans'
            $table->id('id_pesanan');  // Primary key auto-increment bernama 'id_pesanan'

            // FK ke tabel mejas: ID meja yang memesan (nullable karena Take Away tidak butuh meja)
            $table->foreignId('id_meja')->nullable()->constrained('mejas', 'id_meja')->onDelete('set null');

            // FK ke tabel admins: ID admin yang memproses (nullable karena awalnya belum diproses admin)
            $table->foreignId('id_admin')->nullable()->constrained('admins', 'id_admin')->onDelete('set null');

            $table->enum('tipe_pesanan', ['Dine-In', 'Take Away']); // Tipe pesanan: makan di tempat atau dibawa pulang

            $table->string('nama_pemesan')->nullable(); // Nama pelanggan yang memesan (opsional)

            // Status alur pesanan dengan 5 tahap, default dimulai dari 'Diterima'
            $table->enum('status', ['Diterima', 'Diproses', 'Disiapkan', 'Selesai', 'Dibatalkan'])->default('Diterima');

            // Status pembayaran, default 'Belum Lunas' hingga dikonfirmasi kasir
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');

            $table->enum('metode_bayar', ['Tunai', 'QRIS']); // Metode pembayaran yang dipilih pelanggan

            $table->integer('uang_dibayar')->nullable();      // Nominal uang tunai yang dibayar (nullable, hanya untuk Tunai)
            $table->integer('kembalian')->nullable();          // Nominal kembalian (nullable, hanya untuk Tunai)
            $table->text('alasan_pembatalan')->nullable();     // Alasan jika pesanan dibatalkan (nullable)
            $table->dateTime('tanggal_waktu');                 // Tanggal dan waktu pesanan dibuat
            $table->integer('total_harga');                    // Total harga semua item dalam pesanan (rupiah)

            $table->timestamps(); // Kolom created_at dan updated_at otomatis Laravel
        });
    }

    /**
     * Batalkan migration: hapus tabel 'pesanans'.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans'); // Hapus tabel 'pesanans' jika ada
    }
};
