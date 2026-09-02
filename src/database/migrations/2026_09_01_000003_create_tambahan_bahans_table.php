<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tambahan_bahans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tambahan');
            $table->unsignedBigInteger('id_bahan');
            $table->integer('jumlah_dibutuhkan')->default(1);
            $table->timestamps();

            $table->foreign('id_tambahan')->references('id_tambahan')->on('tambahans')->onDelete('cascade');
            $table->foreign('id_bahan')->references('id_bahan')->on('bahans')->onDelete('cascade');
            $table->unique(['id_tambahan', 'id_bahan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambahan_bahans');
    }
};
