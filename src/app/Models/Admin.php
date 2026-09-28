<?php

namespace App\Models; // Mendefinisikan namespace model berada di App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import trait HasFactory untuk mendukung factory data seeder
use Illuminate\Foundation\Auth\User as Authenticatable;  // Import class Authenticatable sebagai base class untuk model autentikasi

// Model Admin merepresentasikan tabel 'admins' di database
// Mewarisi Authenticatable agar kompatibel dengan sistem autentikasi Laravel
class Admin extends Authenticatable
{
    use HasFactory; // Mengaktifkan fitur factory untuk generate data dummy (seeder/testing)

    protected $table      = 'admins';                      // Nama tabel database yang digunakan oleh model ini
    protected $primaryKey = 'id_admin';                    // Nama kolom primary key tabel (bukan default 'id')
    protected $fillable   = ['nama', 'username', 'password']; // Kolom yang boleh diisi secara mass-assignment
    protected $hidden     = ['password'];                  // Kolom yang disembunyikan dari output JSON / array (keamanan)

    /**
     * Relasi: satu Admin memiliki banyak Pesanan.
     * Menghubungkan Admin ke tabel pesanans melalui foreign key id_admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'id_admin', 'id_admin'); // Admin hasMany Pesanan, FK=id_admin di pesanans, PK=id_admin di admins
    }
}
