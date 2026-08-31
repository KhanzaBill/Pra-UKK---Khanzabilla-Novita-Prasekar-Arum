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
}
