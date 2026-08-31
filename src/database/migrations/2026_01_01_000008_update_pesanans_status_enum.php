<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('Diterima', 'Diproses', 'Disiapkan', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Diterima'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('Diterima', 'Diproses', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Diterima'");
    }
};
