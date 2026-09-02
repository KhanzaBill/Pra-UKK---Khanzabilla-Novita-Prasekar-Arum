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

    public function bahans()
    {
        return $this->belongsToMany(Bahan::class, 'tambahan_bahans', 'id_tambahan', 'id_bahan')
                    ->withPivot('jumlah_dibutuhkan')
                    ->withTimestamps();
    }

    /**
     * Cek apakah menu tambahan tersedia untuk dipesan:
     * 1. Toggle status_stok harus 'Tersedia'
     * 2. SEMUA bahan terkait harus memiliki stok >= jumlah_dibutuhkan
     */
    public function isTersedia(): bool
    {
        if ($this->status_stok === 'Habis') {
            return false;
        }

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
