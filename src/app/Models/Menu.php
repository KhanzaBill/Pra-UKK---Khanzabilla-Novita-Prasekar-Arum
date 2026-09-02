<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';
    protected $fillable = [
        'nama_menu',
        'kategori',
        'harga',
        'deskripsi',
        'status_stok',
        'opsi_pedas',
        'foto'
    ];

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }

    public function bahans()
    {
        return $this->belongsToMany(Bahan::class, 'menu_bahans', 'id_menu', 'id_bahan')
                    ->withPivot('jumlah_dibutuhkan')
                    ->withTimestamps();
    }

    /**
     * Cek apakah menu tersedia untuk dipesan:
     * 1. Toggle status_stok harus 'Tersedia'
     * 2. SEMUA bahan terkait harus memiliki stok >= jumlah_dibutuhkan
     */
    public function isTersedia(): bool
    {
        if ($this->status_stok === 'Habis') {
            return false;
        }

        // Jika relasi bahans sudah di-load, gunakan koleksi untuk efisiensi
        $bahansList = $this->relationLoaded('bahans') ? $this->bahans : $this->bahans()->get();

        foreach ($bahansList as $bahan) {
            $kebutuhan = $bahan->pivot->jumlah_dibutuhkan ?? 1;
            if ($bahan->stok < $kebutuhan) {
                return false;
            }
        }

        return true;
    }

    public function getIsTersediaAttribute(): bool
    {
        return $this->isTersedia();
    }
}
