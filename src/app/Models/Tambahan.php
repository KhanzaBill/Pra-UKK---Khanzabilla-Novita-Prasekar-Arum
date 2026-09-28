<?php

namespace App\Models; // Mendefinisikan namespace model berada di App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import trait HasFactory untuk mendukung pembuatan data dummy
use Illuminate\Database\Eloquent\Model;                 // Import base class Model dari Eloquent ORM Laravel

// Model Tambahan merepresentasikan tabel 'tambahans' di database (menu add-on / topping)
class Tambahan extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk keperluan seeder / unit testing

    protected $table      = 'tambahans';                                  // Nama tabel database yang digunakan model ini
    protected $primaryKey = 'id_tambahan';                                // Nama kolom primary key (bukan default 'id')
    protected $fillable   = ['nama_tambahan', 'harga', 'status_stok'];    // Kolom yang boleh diisi via mass-assignment

    /**
     * Relasi Many-to-Many: Tambahan dapat ditambahkan ke banyak DetailPesanan.
     * Menggunakan tabel pivot 'detail_tambahans'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function detailPesanans()
    {
        return $this->belongsToMany(DetailPesanan::class, 'detail_tambahans', 'id_tambahan', 'id_detail') // Relasi many-to-many ke DetailPesanan via tabel pivot detail_tambahans
                    ->withTimestamps(); // Sertakan kolom created_at dan updated_at dari tabel pivot
    }

    /**
     * Relasi Many-to-Many: Tambahan dapat membutuhkan banyak Bahan baku.
     * Menggunakan tabel pivot 'tambahan_bahans' dengan kolom 'jumlah_dibutuhkan'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bahans()
    {
        return $this->belongsToMany(Bahan::class, 'tambahan_bahans', 'id_tambahan', 'id_bahan') // Relasi many-to-many ke Bahan via tabel pivot tambahan_bahans
                    ->withPivot('jumlah_dibutuhkan') // Sertakan kolom jumlah_dibutuhkan dari tabel pivot
                    ->withTimestamps();              // Sertakan kolom created_at dan updated_at dari tabel pivot
    }

    /**
     * Cek apakah menu tambahan tersedia untuk dipesan oleh pelanggan.
     * Tambahan dianggap tersedia jika:
     * 1. Toggle status_stok harus 'Tersedia' (bukan 'Habis')
     * 2. SEMUA bahan terkait harus memiliki stok >= jumlah_dibutuhkan per porsi
     *
     * @return bool  true jika tambahan tersedia, false jika tidak
     */
    public function isTersedia(): bool
    {
        if ($this->status_stok === 'Habis') { // Jika admin sudah men-toggle status tambahan menjadi 'Habis'
            return false;                      // Tambahan langsung tidak tersedia tanpa perlu cek bahan
        }

        // Gunakan koleksi yang sudah di-load jika ada (efisiensi), jika belum baru query database
        $bahansList = $this->relationLoaded('bahans') ? $this->bahans : $this->bahans()->get();

        // Iterasi semua bahan yang dibutuhkan oleh tambahan ini
        foreach ($bahansList as $bahan) {
            $kebutuhan = $bahan->pivot->jumlah_dibutuhkan ?? 1; // Ambil jumlah bahan yang dibutuhkan per porsi (default 1 jika tidak ada)
            if ($bahan->stok < $kebutuhan) {                    // Cek apakah stok bahan mencukupi minimal 1 porsi
                return false;                                    // Tidak tersedia jika salah satu bahan stoknya kurang
            }
        }

        return true; // Tambahan tersedia: semua bahan mencukupi dan status tidak di-set 'Habis'
    }

    /**
     * Accessor Eloquent untuk atribut dinamis 'is_tersedia'.
     * Memungkinkan akses $tambahan->is_tersedia sebagai property (tanpa memanggil fungsi).
     *
     * @return bool  Hasil dari metode isTersedia()
     */
    public function getIsTersediaAttribute(): bool
    {
        return $this->isTersedia(); // Delegasikan ke metode isTersedia()
    }
}
