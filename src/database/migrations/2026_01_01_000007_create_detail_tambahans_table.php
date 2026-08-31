<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_tambahans', function (Blueprint $table) {
            $table->id('id_detail_tambahan');
            $table->foreignId('id_detail')->constrained('detail_pesanans', 'id_detail')->onDelete('cascade');
            $table->foreignId('id_tambahan')->constrained('tambahans', 'id_tambahan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tambahans');
    }
};
