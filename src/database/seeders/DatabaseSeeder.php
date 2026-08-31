<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meja;
use App\Models\Admin;
use App\Models\Menu;
use App\Models\Tambahan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Meja
        for ($i = 1; $i <= 10; $i++) {
            Meja::create([
                'nomor_meja' => 'Meja ' . str_pad($i, 2, '0', STR_PAD_LEFT)
            ]);
        }

        // 2. Seed Admin Default
        Admin::create([
            'nama' => 'Kasir Yummy Chicken',
            'username' => 'admin',
            'password' => Hash::make('yummychickenCC')
        ]);

        // 3. Seed Menu Paket (Semua opsi_pedas = Ya)
        $pakets = [
            ['nama_menu' => 'Paket 1 - Nasi + Geprek Sayap + Teh', 'harga' => 12000],
            ['nama_menu' => 'Paket 2 - Nasi + Geprek Paha Bawah + Teh', 'harga' => 14000],
            ['nama_menu' => 'Paket 3 - Nasi + Geprek Paha Atas + Teh', 'harga' => 14000],
            ['nama_menu' => 'Paket 4 - Nasi + Geprek Dada + Teh', 'harga' => 14000],
            ['nama_menu' => 'Paket 5 - Nasi + Geprek Bakar + Teh', 'harga' => 17000],
            ['nama_menu' => 'Paket 6 - Nasi + Geprek Matah + Teh', 'harga' => 19000],
            ['nama_menu' => 'Paket 7 - Nasi + Geprek Lombok Ijo + Teh', 'harga' => 19000],
            ['nama_menu' => 'Paket 8 - Nasi + Geprek Keju + Teh', 'harga' => 19000],
            ['nama_menu' => 'Paket 9 - Nasi + Geprek Saus Keju + Teh', 'harga' => 19000],
            ['nama_menu' => 'Paket 10 - Nasi + Geprek Saus BBQ + Teh', 'harga' => 19000],
            ['nama_menu' => 'Paket 11 - Nasi + Geprek Saus Mentai + Teh', 'harga' => 19000],
            ['nama_menu' => 'Paket 12 - Mie + Geprek Level + Teh', 'harga' => 20000],
        ];

        foreach ($pakets as $p) {
            Menu::create([
                'nama_menu' => $p['nama_menu'],
                'kategori' => 'Paket',
                'harga' => $p['harga'],
                'deskripsi' => 'Paket komplit hemat sudah termasuk Nasi/Mie dan Teh segar dengan sambal geprek khas Semarang.',
                'status_stok' => 'Tersedia',
                'opsi_pedas' => 'Ya',
            ]);
        }

        // 4. Seed Menu Makanan (opsi_pedas = Tidak)
        $makanans = [
            ['nama_menu' => 'Ayam Sayap', 'harga' => 8000],
            ['nama_menu' => 'Ayam Paha Bawah', 'harga' => 10000],
            ['nama_menu' => 'Ayam Paha Atas', 'harga' => 10000],
            ['nama_menu' => 'Ayam Dada', 'harga' => 10000],
            ['nama_menu' => 'Sup Ceker', 'harga' => 3000],
            ['nama_menu' => 'Kulit/Usus Kriyuk', 'harga' => 13000],
            ['nama_menu' => 'Indomie Goreng/Rebus', 'harga' => 9000],
            ['nama_menu' => 'Telur Dadar/Ceplok', 'harga' => 6000],
            ['nama_menu' => 'Tempe Goreng (isi 2)', 'harga' => 3000],
            ['nama_menu' => 'Kol Goreng', 'harga' => 8000],
            ['nama_menu' => 'Petai Goreng', 'harga' => 8000],
            ['nama_menu' => 'Nasi Putih', 'harga' => 3000],
            ['nama_menu' => 'Nasi Daun Jeruk', 'harga' => 6000],
        ];

        foreach ($makanans as $m) {
            Menu::create([
                'nama_menu' => $m['nama_menu'],
                'kategori' => 'Makanan',
                'harga' => $m['harga'],
                'deskripsi' => 'Menu alacarte gurih dan lezat siap melengkapi hidangan Anda.',
                'status_stok' => 'Tersedia',
                'opsi_pedas' => 'Tidak',
            ]);
        }

        // 5. Seed Menu Minuman (opsi_pedas = Tidak)
        $minumans = [
            ['nama_menu' => 'Teh Dingin/Panas', 'harga' => 4000],
            ['nama_menu' => 'Jeruk Dingin/Panas', 'harga' => 5000],
            ['nama_menu' => 'Susu Putih/Coklat', 'harga' => 5000],
            ['nama_menu' => 'Extra Joss', 'harga' => 5000],
            ['nama_menu' => 'Extra Joss Susu', 'harga' => 8000],
            ['nama_menu' => 'Energen', 'harga' => 7000],
            ['nama_menu' => 'Adem Sari', 'harga' => 7000],
            ['nama_menu' => 'Segar Dingin', 'harga' => 5000],
            ['nama_menu' => 'Kopi Hitam', 'harga' => 5000],
            ['nama_menu' => 'Kopi Jahe', 'harga' => 5000],
            ['nama_menu' => 'Jahe Wangi/Anget Sari', 'harga' => 5000],
            ['nama_menu' => 'Milo/Hilo', 'harga' => 7000],
            ['nama_menu' => 'Dancow', 'harga' => 8000],
            ['nama_menu' => 'Beng Beng', 'harga' => 7000],
            ['nama_menu' => 'Chocolatos', 'harga' => 5000],
            ['nama_menu' => 'Coffeemix', 'harga' => 5000],
            ['nama_menu' => 'Kapal Api', 'harga' => 5000],
            ['nama_menu' => 'Nescafe', 'harga' => 5000],
            ['nama_menu' => 'Luwak White Coffee', 'harga' => 5000],
            ['nama_menu' => 'Caffino', 'harga' => 5000],
            ['nama_menu' => 'Torabika', 'harga' => 5000],
            ['nama_menu' => 'Top Coffee', 'harga' => 5000],
            ['nama_menu' => 'Good Day Cappucino', 'harga' => 7000],
            ['nama_menu' => 'Good Day Freeze', 'harga' => 5000],
            ['nama_menu' => 'Good Day Latte', 'harga' => 7000],
            ['nama_menu' => 'Good Day Mocacinno', 'harga' => 5000],
            ['nama_menu' => 'Good Day Coolin', 'harga' => 5000],
            ['nama_menu' => 'Good Day Vanila', 'harga' => 5000],
            ['nama_menu' => 'Good Day Chococinno', 'harga' => 5000],
            ['nama_menu' => 'Nutrisari Aneka Rasa', 'harga' => 5000],
            ['nama_menu' => 'Air Mineral 600ml', 'harga' => 5000],
        ];

        foreach ($minumans as $min) {
            Menu::create([
                'nama_menu' => $min['nama_menu'],
                'kategori' => 'Minuman',
                'harga' => $min['harga'],
                'deskripsi' => 'Minuman segar penawar pedas khas Yummy Chicken.',
                'status_stok' => 'Tersedia',
                'opsi_pedas' => 'Tidak',
            ]);
        }

        // 6. Seed Menu Tambahan
        $tambahans = [
            ['nama_tambahan' => 'Lalapan', 'harga' => 2000],
            ['nama_tambahan' => 'Sambal Bawang', 'harga' => 2000],
            ['nama_tambahan' => 'Sambal Matah', 'harga' => 5000],
            ['nama_tambahan' => 'Sambal Lombok Ijo', 'harga' => 5000],
            ['nama_tambahan' => 'Keju Parut', 'harga' => 5000],
            ['nama_tambahan' => 'Saus Keju', 'harga' => 5000],
            ['nama_tambahan' => 'Saus Mentai', 'harga' => 5000],
            ['nama_tambahan' => 'Saus BBQ', 'harga' => 5000],
            ['nama_tambahan' => 'Keju Mozarella', 'harga' => 8000],
        ];

        foreach ($tambahans as $t) {
            Tambahan::create($t);
        }
    }
}
