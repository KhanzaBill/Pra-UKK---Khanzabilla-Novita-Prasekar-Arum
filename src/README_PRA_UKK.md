# Panduan Lengkap Pra-UKK: Sistem Pemesanan Makanan QR Code "Yummy Chicken"

Dokumen ini berisi panduan langkah-demi-langkah pembuatan dan pengujian aplikasi web full-stack Sistem Pemesanan Makanan berbasis QR Code untuk Rumah Makan Ayam Geprek **Yummy Chicken** (*Tagline: Cita Rasa Ayam Geprek Semarang*).

Lokasi Project: `F:\yummy-chicken`

---

## 🛠️ Langkah 1: Persiapan & Pindah ke Folder Project di Drive F:

1. Buka **Command Prompt (CMD)** atau Terminal.
2. Masuk ke drive **F:** dan buka folder project `yummy-chicken`:
   ```cmd
   F:
   cd F:\yummy-chicken
   ```

---

## 🗄️ Langkah 2: Konfigurasi Database MySQL

1. Pastikan **XAMPP / Laragon / MySQL Service** sudah aktif.
2. Buka **phpMyAdmin** atau terminal MySQL, buat database baru bernama `yummy_chicken_db`:
   ```sql
   CREATE DATABASE yummy_chicken_db;
   ```
3. Buka file `.env` di folder project `F:\yummy-chicken`, lalu sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=yummy_chicken_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

---

## 🚀 Langkah 3: Menjalankan Migration & Database Seeder

1. Jalankan perintah untuk membuat tabel dan memasukkan seluruh data awal (Paket 1-12, Makanan, Minuman, Menu Tambahan, 10 Meja, dan Akun Kasir):
   ```cmd
   php artisan migrate:fresh --seed
   ```
2. Data seeder yang otomatis terisi meliputi:
   - **10 Meja** (Meja 01 s/d Meja 10)
   - **Admin Default**: Username `admin` | Password `password123`
   - **12 Menu Paket** (Rp12.000 - Rp20.000, opsi pedas = Ya)
   - **13 Menu Makanan** (Ayam, Indomie, Sup Ceker, Telur, Kol Goreng, Nasi Daun Jeruk, dll)
   - **31 Menu Minuman** (Teh, Jeruk, Extra Joss, Good Day, Kopi, Nutrisari, dll)
   - **9 Menu Tambahan** (Lalapan, Sambal Bawang/Matah/Lombok Ijo, Keju Parut, Saus Keju/Mentai/BBQ, Mozarella)

---

## 💻 Langkah 4: Menjalankan Aplikasi Web

Jalankan server lokal Laravel:
```cmd
php artisan serve
```
Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000`

---

## 📱 Langkah 5: Pengujian Fitur Aplikasi (Uji Kompetensi / Pra-UKK)

### 1. Pelanggan (Sisi QR Code Mobile-First)
- **Simulasi Scan QR Meja 01**: Akses `http://127.0.0.1:8000/?meja=1`
  - Tampil header Yummy Chicken + Nomor Meja 01 + Tombol Dine-In & Take Away.
- **Pilih Dine-In & Tambah Menu**:
  - Klik Dine-In -> Pilih Menu Paket 1 -> Pilih Level Pedas (misal Level 3) -> Centang Sambal Matah & Catatan -> Klik "Tambah ke Keranjang".
  - Tambah menu yang sama (Paket 1 Level 1 tanpa tambahan) -> Buka Keranjang -> Pastikan **terpisah menjadi 2 baris**.
- **Checkout & Pembayaran**:
  - Buka Keranjang -> Lanjut Checkout -> Masukkan Nama Pemesan "Budi" -> Pilih Metode Pembayaran (Tunai/QRIS).
  - Tampil info instruksi kustom sesuai metode bayar.
  - Klik Kirim Pesanan -> Masuk ke Halaman Struk dengan No. Pesanan Besar & Tracker Status real-time.

### 2. Kasir / Admin (Sisi Dashboard Desktop)
- **Login Admin**: Akses `http://127.0.0.1:8000/admin/login`
  - Username: `admin`
  - Password: `password123`
- **Dashboard Pesanan Masuk** (`/admin/orders`):
  - Tampil pesanan "Budi" secara real-time.
  - Klik "Proses Pesanan" -> Status di HP Pelanggan otomatis berubah jadi "Diproses".
  - Klik "Set Lunas (Tunai)" -> Masukkan Uang Dibayar -> Kalkulator otomatis menghitung Kembalian -> Simpan -> Status Pembayaran jadi LUNAS.
  - Klik "Batalkan" -> Masukkan Alasan "Stok Ayam habis" -> Struk Pelanggan menampilkan badge Dibatalkan & alasan pembatalan.
- **Kelola Menu (CRUD)** (`/admin/menus`):
  - Tambah, Ubah, Hapus Menu & Menu Tambahan.
  - Toggle cepat Status Stok (Tersedia/Habis) -> Menu di HP Pelanggan berubah jadi disabled & badge "Habis".
- **Laporan Penjualan** (`/admin/reports`):
  - Filter rentang tanggal -> Rekapitulasi omset & total transaksi **khusus status pembayaran Lunas**.

---

## 📁 Struktur File Utama Dalam Project (Drive F:)
- `F:\yummy-chicken\database\migrations\` : Schema tabel mejas, menus, tambahans, admins, pesanans, detail_pesanans, detail_tambahans.
- `F:\yummy-chicken\database\seeders\DatabaseSeeder.php` : Seeder lengkap data awal.
- `F:\yummy-chicken\app\Models\` : Relasi Eloquent ORM (Meja, Menu, Tambahan, Admin, Pesanan, DetailPesanan).
- `F:\yummy-chicken\app\Http\Controllers\` : Controller Pelanggan, Auth Admin, Order Kasir, Menu CRUD, & Laporan Penjualan.
- `F:\yummy-chicken\resources\views\` : Blade Views (Customer Mobile-First UI & Dashboard Admin Desktop).
