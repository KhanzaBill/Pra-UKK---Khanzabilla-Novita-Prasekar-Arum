<?php

namespace App\Models; // Mendefinisikan namespace model berada di App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import trait HasFactory untuk mendukung pembuatan data dummy
use Illuminate\Database\Eloquent\Model;                 // Import base class Model dari Eloquent ORM Laravel

// Model Bahan merepresentasikan tabel 'bahans' di database (bahan baku / inventori)
class Bahan extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk keperluan seeder / unit testing

    protected $table      = 'bahans';               // Nama tabel database yang digunakan model ini
    protected $primaryKey = 'id_bahan';             // Nama kolom primary key (bukan default 'id')
    protected $fillable   = [
        'nama_bahan', // Kolom nama bahan yang boleh diisi via mass-assignment
        'stok'        // Kolom jumlah stok yang boleh diisi via mass-assignment
    ];

    /**
     * Relasi Many-to-Many: Bahan dapat digunakan oleh banyak Menu.
     * Menggunakan tabel pivot 'menu_bahans' dengan kolom tambahan 'jumlah_dibutuhkan'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_bahans', 'id_bahan', 'id_menu') // Relasi many-to-many ke Menu melalui tabel pivot menu_bahans
                    ->withPivot('jumlah_dibutuhkan')  // Sertakan kolom jumlah_dibutuhkan dari tabel pivot
                    ->withTimestamps();               // Sertakan kolom created_at dan updated_at dari tabel pivot
    }

    /**
     * Relasi Many-to-Many: Bahan dapat digunakan oleh banyak Tambahan.
     * Menggunakan tabel pivot 'tambahan_bahans' dengan kolom tambahan 'jumlah_dibutuhkan'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tambahans()
    {
        return $this->belongsToMany(Tambahan::class, 'tambahan_bahans', 'id_bahan', 'id_tambahan') // Relasi many-to-many ke Tambahan melalui tabel pivot tambahan_bahans
                    ->withPivot('jumlah_dibutuhkan') // Sertakan kolom jumlah_dibutuhkan dari tabel pivot
                    ->withTimestamps();              // Sertakan kolom created_at dan updated_at dari tabel pivot
    }
}
