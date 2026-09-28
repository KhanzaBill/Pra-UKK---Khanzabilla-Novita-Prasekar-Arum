<?php

namespace App\Models; // Mendefinisikan namespace model berada di App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import trait HasFactory untuk mendukung pembuatan data dummy
use Illuminate\Database\Eloquent\Model;                 // Import base class Model dari Eloquent ORM Laravel

// Model Pesanan merepresentasikan tabel 'pesanans' di database (data transaksi pesanan pelanggan)
class Pesanan extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk keperluan seeder / unit testing

    protected $table      = 'pesanans';    // Nama tabel database yang digunakan model ini
    protected $primaryKey = 'id_pesanan';  // Nama kolom primary key (bukan default 'id')
    protected $fillable   = [
        'id_meja',            // FK: ID meja yang melakukan pesanan (null jika Take Away)
        'id_admin',           // FK: ID admin/kasir yang memproses pesanan (null jika baru dibuat pelanggan)
        'tipe_pesanan',       // Jenis pesanan: 'Dine-In' atau 'Take Away'
        'nama_pemesan',       // Nama pelanggan yang memesan
        'status',             // Status pesanan: Diterima, Diproses, Disiapkan, Selesai, Dibatalkan
        'status_pembayaran',  // Status pembayaran: 'Lunas' atau 'Belum Lunas'
        'metode_bayar',       // Metode pembayaran: 'Tunai' atau 'QRIS'
        'bukti_pembayaran',   // Path file foto bukti pembayaran QRIS (nullable)
        'uang_dibayar',       // Nominal uang yang dibayar pelanggan (untuk pembayaran tunai)
        'kembalian',          // Nominal kembalian yang harus diberikan ke pelanggan
        'alasan_pembatalan',  // Alasan jika pesanan dibatalkan oleh admin (nullable)
        'tanggal_waktu',      // Tanggal dan waktu pesanan dibuat
        'total_harga'         // Total harga semua item dalam pesanan (dalam rupiah)
    ];

    /**
     * Relasi: Pesanan belongs to Meja (pesanan Dine-In terhubung ke satu meja).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meja()
    {
        return $this->belongsTo(Meja::class, 'id_meja', 'id_meja'); // FK=id_meja di pesanans, PK=id_meja di mejas
    }

    /**
     * Relasi: Pesanan belongs to Admin (pesanan yang sudah diproses terhubung ke satu admin/kasir).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin'); // FK=id_admin di pesanans, PK=id_admin di admins
    }

    /**
     * Relasi: satu Pesanan memiliki banyak DetailPesanan (item-item yang dipesan).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan'); // Pesanan hasMany DetailPesanan, FK=id_pesanan di detail_pesanans
    }
}
