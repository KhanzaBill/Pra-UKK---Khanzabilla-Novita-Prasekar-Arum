<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_meja')->nullable()->constrained('mejas', 'id_meja')->onDelete('set null');
            $table->foreignId('id_admin')->nullable()->constrained('admins', 'id_admin')->onDelete('set null');
            $table->enum('tipe_pesanan', ['Dine-In', 'Take Away']);
            $table->string('nama_pemesan')->nullable();
            $table->enum('status', ['Diterima', 'Diproses', 'Disiapkan', 'Selesai', 'Dibatalkan'])->default('Diterima');
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');
            $table->enum('metode_bayar', ['Tunai', 'QRIS']);
            $table->integer('uang_dibayar')->nullable();
            $table->integer('kembalian')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->dateTime('tanggal_waktu');
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
