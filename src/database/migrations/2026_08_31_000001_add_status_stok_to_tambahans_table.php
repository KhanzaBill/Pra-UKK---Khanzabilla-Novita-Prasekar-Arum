<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tambahans', function (Blueprint $table) {
            $table->enum('status_stok', ['Tersedia', 'Habis'])->default('Tersedia')->after('harga');
        });
    }

    public function down(): void
    {
        Schema::table('tambahans', function (Blueprint $table) {
            $table->dropColumn('status_stok');
        });
    }
};
