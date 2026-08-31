<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tambahan extends Model
{
    use HasFactory;

    protected $table = 'tambahans';
    protected $primaryKey = 'id_tambahan';
    protected $fillable = ['nama_tambahan', 'harga', 'status_stok'];

    public function detailPesanans()
    {
        return $this->belongsToMany(DetailPesanan::class, 'detail_tambahans', 'id_tambahan', 'id_detail')
                    ->withTimestamps();
    }
}
