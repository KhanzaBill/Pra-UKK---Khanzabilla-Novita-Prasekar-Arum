<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahans';
    protected $primaryKey = 'id_bahan';
    protected $fillable = [
        'nama_bahan',
        'stok'
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_bahans', 'id_bahan', 'id_menu')
                    ->withPivot('jumlah_dibutuhkan')
                    ->withTimestamps();
    }

    public function tambahans()
    {
        return $this->belongsToMany(Tambahan::class, 'tambahan_bahans', 'id_bahan', 'id_tambahan')
                    ->withPivot('jumlah_dibutuhkan')
                    ->withTimestamps();
    }
}
