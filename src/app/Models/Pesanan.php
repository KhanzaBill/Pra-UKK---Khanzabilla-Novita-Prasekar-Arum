<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';
    protected $fillable = [
        'id_meja',
        'id_admin',
        'tipe_pesanan',
        'nama_pemesan',
        'status',
        'status_pembayaran',
        'metode_bayar',
        'uang_dibayar',
        'kembalian',
        'alasan_pembatalan',
        'tanggal_waktu',
        'total_harga'
    ];

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'id_meja', 'id_meja');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
