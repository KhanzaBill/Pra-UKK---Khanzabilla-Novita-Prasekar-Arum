<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanans';
    protected $primaryKey = 'id_detail';
    protected $fillable = [
        'id_pesanan',
        'id_menu',
        'jumlah',
        'level_pedas',
        'catatan',
        'subtotal'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function tambahans()
    {
        return $this->belongsToMany(Tambahan::class, 'detail_tambahans', 'id_detail', 'id_tambahan')
                    ->withTimestamps();
    }
}
