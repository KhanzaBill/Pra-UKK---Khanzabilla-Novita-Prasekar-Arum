<?php

namespace App\Models; // Mendefinisikan namespace model berada di App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import trait HasFactory untuk mendukung pembuatan data dummy
use Illuminate\Database\Eloquent\Model;                 // Import base class Model dari Eloquent ORM Laravel

// Model DetailPesanan merepresentasikan tabel 'detail_pesanans' di database
// Setiap baris mewakili satu item (menu) dalam sebuah pesanan
class DetailPesanan extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk keperluan seeder / unit testing

    protected $table      = 'detail_pesanans'; // Nama tabel database yang digunakan model ini
    protected $primaryKey = 'id_detail';       // Nama kolom primary key (bukan default 'id')
    protected $fillable   = [
        'id_pesanan',   // FK: ID pesanan induk yang terhubung
        'id_menu',      // FK: ID menu yang dipesan
        'jumlah',       // Jumlah porsi yang dipesan
        'level_pedas',  // Level kepedasan yang dipilih (1-5, nullable jika menu tidak ada opsi pedas)
        'catatan',      // Catatan/instruksi khusus dari pelanggan untuk item ini
        'subtotal'      // Total harga item ini (harga_menu + tambahan) × jumlah
    ];

    /**
     * Relasi: DetailPesanan belongs to Pesanan (setiap detail item milik satu pesanan).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan'); // FK=id_pesanan di detail_pesanans, PK=id_pesanan di pesanans
    }

    /**
     * Relasi: DetailPesanan belongs to Menu (setiap detail item merujuk satu menu).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu'); // FK=id_menu di detail_pesanans, PK=id_menu di menus
    }

    /**
     * Relasi Many-to-Many: DetailPesanan dapat memiliki banyak Tambahan (topping/add-on).
     * Menggunakan tabel pivot 'detail_tambahans'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tambahans()
    {
        return $this->belongsToMany(Tambahan::class, 'detail_tambahans', 'id_detail', 'id_tambahan') // Relasi many-to-many ke Tambahan via tabel pivot detail_tambahans
                    ->withTimestamps(); // Sertakan kolom created_at dan updated_at dari tabel pivot
    }
}
