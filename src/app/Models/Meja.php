<?php

namespace App\Models; // Mendefinisikan namespace model berada di App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import trait HasFactory untuk mendukung pembuatan data dummy
use Illuminate\Database\Eloquent\Model;                 // Import base class Model dari Eloquent ORM Laravel

// Model Meja merepresentasikan tabel 'mejas' di database (data meja restoran)
class Meja extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk keperluan seeder / unit testing

    protected $table      = 'mejas';        // Nama tabel database yang digunakan model ini
    protected $primaryKey = 'id_meja';      // Nama kolom primary key (bukan default 'id')
    protected $fillable   = ['nomor_meja']; // Kolom yang boleh diisi via mass-assignment (hanya nomor_meja)

    /**
     * Relasi: satu Meja memiliki banyak Pesanan.
     * Digunakan untuk melacak semua pesanan yang dibuat dari meja tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'id_meja', 'id_meja'); // Meja hasMany Pesanan, FK=id_meja di pesanans, PK=id_meja di mejas
    }
}
