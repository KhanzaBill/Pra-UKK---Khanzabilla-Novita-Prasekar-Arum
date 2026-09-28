<?php

namespace App\Models; // Mendefinisikan namespace model berada di App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import trait HasFactory untuk mendukung pembuatan data dummy
use Illuminate\Database\Eloquent\Model;                 // Import base class Model dari Eloquent ORM Laravel

// Model Menu merepresentasikan tabel 'menus' di database (data menu makanan/minuman/paket)
class Menu extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk keperluan seeder / unit testing

    protected $table      = 'menus';   // Nama tabel database yang digunakan model ini
    protected $primaryKey = 'id_menu'; // Nama kolom primary key (bukan default 'id')
    protected $fillable   = [
        'nama_menu',   // Nama menu yang ditampilkan (contoh: "Ayam Goreng")
        'kategori',    // Kategori menu: Paket, Makanan, atau Minuman
        'harga',       // Harga menu dalam rupiah (integer)
        'deskripsi',   // Deskripsi menu (bahan, cara penyajian, dll) - nullable
        'status_stok', // Status ketersediaan manual: 'Tersedia' atau 'Habis'
        'opsi_pedas',  // Apakah menu memiliki opsi level pedas: 'Ya' atau 'Tidak'
        'foto'         // Path file foto menu yang disimpan di storage/public - nullable
    ];

    /**
     * Relasi: satu Menu memiliki banyak DetailPesanan.
     * Digunakan untuk melihat semua riwayat pemesanan item menu ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu'); // Menu hasMany DetailPesanan, FK=id_menu di detail_pesanans
    }

    /**
     * Relasi Many-to-Many: Menu membutuhkan banyak Bahan baku.
     * Menggunakan tabel pivot 'menu_bahans' dengan kolom 'jumlah_dibutuhkan'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bahans()
    {
        return $this->belongsToMany(Bahan::class, 'menu_bahans', 'id_menu', 'id_bahan') // Relasi many-to-many ke Bahan via tabel pivot menu_bahans
                    ->withPivot('jumlah_dibutuhkan') // Sertakan kolom jumlah_dibutuhkan dari tabel pivot
                    ->withTimestamps();              // Sertakan kolom created_at dan updated_at dari tabel pivot
    }

    /**
     * Cek apakah menu tersedia untuk dipesan oleh pelanggan.
     * Menu dianggap tersedia jika:
     * 1. Toggle status_stok admin bernilai 'Tersedia' (bukan 'Habis')
     * 2. SEMUA bahan yang dibutuhkan memiliki stok >= jumlah_dibutuhkan per porsi
     *
     * @return bool  true jika menu tersedia, false jika tidak
     */
    public function isTersedia(): bool
    {
        if ($this->status_stok === 'Habis') { // Jika admin sudah men-toggle status menjadi 'Habis'
            return false;                      // Menu langsung tidak tersedia tanpa perlu cek bahan
        }

        // Gunakan koleksi yang sudah di-load jika ada (efisiensi), jika belum baru query database
        $bahansList = $this->relationLoaded('bahans') ? $this->bahans : $this->bahans()->get();

        // Iterasi semua bahan yang dibutuhkan oleh menu ini
        foreach ($bahansList as $bahan) {
            $kebutuhan = $bahan->pivot->jumlah_dibutuhkan ?? 1; // Ambil jumlah bahan yang dibutuhkan per porsi (default 1 jika tidak ada)
            if ($bahan->stok < $kebutuhan) {                    // Cek apakah stok bahan mencukupi minimal 1 porsi
                return false;                                    // Tidak tersedia jika salah satu bahan stoknya kurang
            }
        }

        return true; // Menu tersedia: semua bahan mencukupi dan status tidak di-set 'Habis'
    }

    /**
     * Accessor Eloquent untuk atribut dinamis 'is_tersedia'.
     * Memungkinkan akses $menu->is_tersedia sebagai property (tanpa memanggil fungsi).
     *
     * @return bool  Hasil dari metode isTersedia()
     */
    public function getIsTersediaAttribute(): bool
    {
        return $this->isTersedia(); // Delegasikan ke metode isTersedia()
    }
}
