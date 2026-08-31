<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu');
            $table->enum('kategori', ['Paket', 'Makanan', 'Minuman']);
            $table->integer('harga');
            $table->text('deskripsi')->nullable();
            $table->enum('status_stok', ['Tersedia', 'Habis'])->default('Tersedia');
            $table->enum('opsi_pedas', ['Ya', 'Tidak'])->default('Tidak');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
