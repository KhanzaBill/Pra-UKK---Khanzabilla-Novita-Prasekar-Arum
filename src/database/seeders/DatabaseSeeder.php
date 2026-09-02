<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meja;
use App\Models\Admin;
use App\Models\Menu;
use App\Models\Tambahan;
use App\Models\Bahan;
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

        // 3. Seed Master Bahan
        $bahansList = [
            'Ayam Sayap' => 30,
            'Ayam Paha Bawah' => 30,
            'Ayam Paha Atas' => 30,
            'Ayam Dada' => 30,
            'Ceker Ayam' => 25,
            'Kulit/Usus' => 25,
            'Indomie' => 40,
            'Telur' => 40,
            'Tempe' => 50,
            'Kol' => 25,
            'Petai' => 20,
            'Nasi Putih' => 100,
            'Nasi Daun Jeruk' => 30,
            'Lalapan' => 35,
            'Sambal Bawang' => 50,
            'Sambal Matah' => 30,
            'Sambal Lombok Ijo' => 30,
            'Keju Parut' => 30,
            'Saus Keju' => 30,
            'Saus Mentai' => 30,
            'Saus BBQ' => 30,
            'Keju Mozarella' => 25,
            'Bumbu Bakar' => 25,
            'Teh' => 100,
            'Jeruk' => 30,
            'Susu' => 30,
            'Extra Joss' => 30,
            'Energen' => 30,
            'Adem Sari' => 30,
            'Segar Dingin' => 30,
            'Kopi Hitam' => 30,
            'Kopi Jahe' => 30,
            'Jahe Wangi' => 30,
            'Milo/Hilo' => 30,
            'Dancow' => 30,
            'Beng Beng' => 30,
            'Chocolatos' => 30,
            'Coffeemix' => 30,
            'Kapal Api' => 30,
            'Nescafe' => 30,
            'Luwak White Coffee' => 30,
            'Caffino' => 30,
            'Torabika' => 30,
            'Top Coffee' => 30,
            'Good Day Cappucino' => 30,
            'Good Day Freeze' => 30,
            'Good Day Latte' => 30,
            'Good Day Mocacinno' => 30,
            'Good Day Coolin' => 30,
            'Good Day Vanila' => 30,
            'Good Day Chococinno' => 30,
            'Nutrisari' => 30,
            'Air Mineral 600ml' => 50,
        ];

        $bahans = [];
        foreach ($bahansList as $nama => $stok) {
            $bahans[$nama] = Bahan::create([
                'nama_bahan' => $nama,
                'stok' => $stok,
            ]);
        }

        // 4. Seed Menu Paket
        $pakets = [
            [
                'nama_menu' => 'Paket 1 - Nasi + Geprek Sayap + Teh',
                'harga' => 12000,
                'foto' => 'menu/menu paket/sayap2.jpg',
                'bahans' => [
                    'Ayam Sayap' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 2 - Nasi + Geprek Paha Bawah + Teh',
                'harga' => 14000,
                'foto' => 'menu/menu paket/pahabawah.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 3 - Nasi + Geprek Paha Atas + Teh',
                'harga' => 14000,
                'foto' => 'menu/menu paket/pahatas.jpg',
                'bahans' => [
                    'Ayam Paha Atas' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 4 - Nasi + Geprek Dada + Teh',
                'harga' => 14000,
                'foto' => 'menu/menu paket/dada2.jpg',
                'bahans' => [
                    'Ayam Dada' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 5 - Nasi + Geprek Bakar + Teh',
                'harga' => 17000,
                'foto' => 'menu/menu paket/bakar.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Bumbu Bakar' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 6 - Nasi + Geprek Matah + Teh',
                'harga' => 19000,
                'foto' => 'menu/menu paket/matah.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Matah' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 7 - Nasi + Geprek Lombok Ijo + Teh',
                'harga' => 19000,
                'foto' => 'menu/menu paket/lombokijo.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Lombok Ijo' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 8 - Nasi + Geprek Keju + Teh',
                'harga' => 19000,
                'foto' => 'menu/menu paket/keju.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                    'Keju Parut' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 9 - Nasi + Geprek Saus Keju + Teh',
                'harga' => 19000,
                'foto' => 'menu/menu paket/sauskeju.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                    'Saus Keju' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 10 - Nasi + Geprek Saus BBQ + Teh',
                'harga' => 19000,
                'foto' => 'menu/menu paket/bbq.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                    'Saus BBQ' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 11 - Nasi + Geprek Saus Mentai + Teh',
                'harga' => 19000,
                'foto' => 'menu/menu paket/mentai.jpg',
                'bahans' => [
                    'Ayam Paha Bawah' => 1,
                    'Nasi Putih' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                    'Saus Mentai' => 1,
                ]
            ],
            [
                'nama_menu' => 'Paket 12 - Mie + Geprek Level + Teh',
                'harga' => 20000,
                'foto' => 'menu/menu paket/miegeprek.jpg',
                'bahans' => [
                    'Ayam Sayap' => 1,
                    'Indomie' => 1,
                    'Teh' => 1,
                    'Sambal Bawang' => 1,
                ]
            ],
        ];

        foreach ($pakets as $p) {
            $menu = Menu::create([
                'nama_menu' => $p['nama_menu'],
                'kategori' => 'Paket',
                'harga' => $p['harga'],
                'deskripsi' => 'Paket komplit hemat sudah termasuk Nasi/Mie dan Teh segar dengan sambal geprek khas Semarang.',
                'status_stok' => 'Tersedia',
                'opsi_pedas' => 'Ya',
                'foto' => $p['foto'] ?? null,
            ]);

            if (isset($p['bahans'])) {
                foreach ($p['bahans'] as $bName => $qty) {
                    if (isset($bahans[$bName])) {
                        $menu->bahans()->attach($bahans[$bName]->id_bahan, ['jumlah_dibutuhkan' => $qty]);
                    }
                }
            }
        }

        // 5. Seed Menu Makanan (opsi_pedas = Tidak)
        $makanans = [
            ['nama_menu' => 'Ayam Sayap', 'harga' => 8000, 'foto' => 'menu/makanan/sayap.jpg', 'bahans' => ['Ayam Sayap' => 1]],
            ['nama_menu' => 'Ayam Paha Bawah', 'harga' => 10000, 'foto' => 'menu/makanan/bawah.jpg', 'bahans' => ['Ayam Paha Bawah' => 1]],
            ['nama_menu' => 'Ayam Paha Atas', 'harga' => 10000, 'foto' => 'menu/makanan/atas.jpg', 'bahans' => ['Ayam Paha Atas' => 1]],
            ['nama_menu' => 'Ayam Dada', 'harga' => 10000, 'foto' => 'menu/makanan/dada.jpg', 'bahans' => ['Ayam Dada' => 1]],
            ['nama_menu' => 'Sup Ceker', 'harga' => 3000, 'foto' => 'menu/makanan/sup.jpg', 'bahans' => ['Ceker Ayam' => 1]],
            ['nama_menu' => 'Kulit/Usus Kriyuk', 'harga' => 13000, 'foto' => 'menu/makanan/kriuk.jpg', 'bahans' => ['Kulit/Usus' => 1]],
            ['nama_menu' => 'Indomie Goreng/Rebus', 'harga' => 9000, 'foto' => 'menu/makanan/mie.jpg', 'bahans' => ['Indomie' => 1]],
            ['nama_menu' => 'Telur Dadar/Ceplok', 'harga' => 6000, 'foto' => 'menu/makanan/telur.jpg', 'bahans' => ['Telur' => 1]],
            ['nama_menu' => 'Tempe Goreng (isi 2)', 'harga' => 3000, 'foto' => 'menu/makanan/tempe.jpg', 'bahans' => ['Tempe' => 2]],
            ['nama_menu' => 'Kol Goreng', 'harga' => 8000, 'foto' => 'menu/makanan/kol.jpg', 'bahans' => ['Kol' => 1]],
            ['nama_menu' => 'Petai Goreng', 'harga' => 8000, 'foto' => 'menu/makanan/pete.jpg', 'bahans' => ['Petai' => 1]],
            ['nama_menu' => 'Nasi Putih', 'harga' => 3000, 'foto' => 'menu/makanan/nasi.jpg', 'bahans' => ['Nasi Putih' => 1]],
            ['nama_menu' => 'Nasi Daun Jeruk', 'harga' => 6000, 'foto' => 'menu/makanan/daun.jpg', 'bahans' => ['Nasi Daun Jeruk' => 1]],
        ];

        foreach ($makanans as $m) {
            $menu = Menu::create([
                'nama_menu' => $m['nama_menu'],
                'kategori' => 'Makanan',
                'harga' => $m['harga'],
                'deskripsi' => 'Menu alacarte gurih dan lezat siap melengkapi hidangan Anda.',
                'status_stok' => 'Tersedia',
                'opsi_pedas' => 'Tidak',
                'foto' => $m['foto'] ?? null,
            ]);

            if (isset($m['bahans'])) {
                foreach ($m['bahans'] as $bName => $qty) {
                    if (isset($bahans[$bName])) {
                        $menu->bahans()->attach($bahans[$bName]->id_bahan, ['jumlah_dibutuhkan' => $qty]);
                    }
                }
            }
        }

        // 6. Seed Menu Minuman (opsi_pedas = Tidak)
        $minumans = [
            ['nama_menu' => 'Teh Dingin/Panas', 'harga' => 4000, 'foto' => 'menu/minuman/teh.jpg', 'bahans' => ['Teh' => 1]],
            ['nama_menu' => 'Jeruk Dingin/Panas', 'harga' => 5000, 'foto' => 'menu/minuman/jeruk.jpg', 'bahans' => ['Jeruk' => 1]],
            ['nama_menu' => 'Susu Putih/Coklat', 'harga' => 5000, 'foto' => 'menu/minuman/susu.jpg', 'bahans' => ['Susu' => 1]],
            ['nama_menu' => 'Extra Joss', 'harga' => 5000, 'foto' => 'menu/minuman/extrajoss.jpg', 'bahans' => ['Extra Joss' => 1]],
            ['nama_menu' => 'Extra Joss Susu', 'harga' => 8000, 'foto' => 'menu/minuman/extrasusu.jpg', 'bahans' => ['Extra Joss' => 1, 'Susu' => 1]],
            ['nama_menu' => 'Energen', 'harga' => 7000, 'foto' => 'menu/minuman/energen.jpg', 'bahans' => ['Energen' => 1]],
            ['nama_menu' => 'Adem Sari', 'harga' => 7000, 'foto' => 'menu/minuman/ademsari.jpg', 'bahans' => ['Adem Sari' => 1]],
            ['nama_menu' => 'Segar Dingin', 'harga' => 5000, 'foto' => 'menu/minuman/segardingin.jpg', 'bahans' => ['Segar Dingin' => 1]],
            ['nama_menu' => 'Kopi Hitam', 'harga' => 5000, 'foto' => 'menu/minuman/kopi.jpg', 'bahans' => ['Kopi Hitam' => 1]],
            ['nama_menu' => 'Kopi Jahe', 'harga' => 5000, 'foto' => 'menu/minuman/kopi jahe.jpg', 'bahans' => ['Kopi Jahe' => 1]],
            ['nama_menu' => 'Jahe Wangi/Anget Sari', 'harga' => 5000, 'foto' => 'menu/minuman/jahe.jpg', 'bahans' => ['Jahe Wangi' => 1]],
            ['nama_menu' => 'Milo/Hilo', 'harga' => 7000, 'foto' => 'menu/minuman/milo.jpg', 'bahans' => ['Milo/Hilo' => 1]],
            ['nama_menu' => 'Dancow', 'harga' => 8000, 'foto' => 'menu/minuman/susu.jpg', 'bahans' => ['Dancow' => 1]],
            ['nama_menu' => 'Beng Beng', 'harga' => 7000, 'foto' => 'menu/minuman/beng.jpg', 'bahans' => ['Beng Beng' => 1]],
            ['nama_menu' => 'Chocolatos', 'harga' => 5000, 'foto' => 'menu/minuman/chocolatos.jpg', 'bahans' => ['Chocolatos' => 1]],
            ['nama_menu' => 'Coffeemix', 'harga' => 5000, 'foto' => 'menu/minuman/coffemix.jpg', 'bahans' => ['Coffeemix' => 1]],
            ['nama_menu' => 'Kapal Api', 'harga' => 5000, 'foto' => 'menu/minuman/kopi.jpg', 'bahans' => ['Kapal Api' => 1]],
            ['nama_menu' => 'Nescafe', 'harga' => 5000, 'foto' => 'menu/minuman/nescafe.jpg', 'bahans' => ['Nescafe' => 1]],
            ['nama_menu' => 'Luwak White Coffee', 'harga' => 5000, 'foto' => 'menu/minuman/luwak.jpg', 'bahans' => ['Luwak White Coffee' => 1]],
            ['nama_menu' => 'Caffino', 'harga' => 5000, 'foto' => 'menu/minuman/caffino.jpg', 'bahans' => ['Caffino' => 1]],
            ['nama_menu' => 'Torabika', 'harga' => 5000, 'foto' => 'menu/minuman/torabika.jpg', 'bahans' => ['Torabika' => 1]],
            ['nama_menu' => 'Top Coffee', 'harga' => 5000, 'foto' => 'menu/minuman/top.jpg', 'bahans' => ['Top Coffee' => 1]],
            ['nama_menu' => 'Good Day Cappucino', 'harga' => 7000, 'foto' => 'menu/minuman/capucino.jpg', 'bahans' => ['Good Day Cappucino' => 1]],
            ['nama_menu' => 'Good Day Freeze', 'harga' => 5000, 'foto' => 'menu/minuman/freeze.jpg', 'bahans' => ['Good Day Freeze' => 1]],
            ['nama_menu' => 'Good Day Latte', 'harga' => 7000, 'foto' => 'menu/minuman/latte.jpg', 'bahans' => ['Good Day Latte' => 1]],
            ['nama_menu' => 'Good Day Mocacinno', 'harga' => 5000, 'foto' => 'menu/minuman/moka.jpg', 'bahans' => ['Good Day Mocacinno' => 1]],
            ['nama_menu' => 'Good Day Coolin', 'harga' => 5000, 'foto' => 'menu/minuman/colin.jpg', 'bahans' => ['Good Day Coolin' => 1]],
            ['nama_menu' => 'Good Day Vanila', 'harga' => 5000, 'foto' => 'menu/minuman/vanila.jpg', 'bahans' => ['Good Day Vanila' => 1]],
            ['nama_menu' => 'Good Day Chococinno', 'harga' => 5000, 'foto' => 'menu/minuman/choco.jpg', 'bahans' => ['Good Day Chococinno' => 1]],
            ['nama_menu' => 'Nutrisari Aneka Rasa', 'harga' => 5000, 'foto' => 'menu/minuman/nutrisari.jpg', 'bahans' => ['Nutrisari' => 1]],
            ['nama_menu' => 'Air Mineral 600ml', 'harga' => 5000, 'foto' => 'menu/minuman/mineral.jpg', 'bahans' => ['Air Mineral 600ml' => 1]],
        ];

        foreach ($minumans as $min) {
            $menu = Menu::create([
                'nama_menu' => $min['nama_menu'],
                'kategori' => 'Minuman',
                'harga' => $min['harga'],
                'deskripsi' => 'Minuman segar penawar pedas khas Yummy Chicken.',
                'status_stok' => 'Tersedia',
                'opsi_pedas' => 'Tidak',
                'foto' => $min['foto'] ?? null,
            ]);

            if (isset($min['bahans'])) {
                foreach ($min['bahans'] as $bName => $qty) {
                    if (isset($bahans[$bName])) {
                        $menu->bahans()->attach($bahans[$bName]->id_bahan, ['jumlah_dibutuhkan' => $qty]);
                    }
                }
            }
        }

        // 7. Seed Menu Tambahan
        $tambahans = [
            ['nama_tambahan' => 'Lalapan', 'harga' => 2000, 'bahans' => ['Lalapan' => 1]],
            ['nama_tambahan' => 'Sambal Bawang', 'harga' => 2000, 'bahans' => ['Sambal Bawang' => 1]],
            ['nama_tambahan' => 'Sambal Matah', 'harga' => 5000, 'bahans' => ['Sambal Matah' => 1]],
            ['nama_tambahan' => 'Sambal Lombok Ijo', 'harga' => 5000, 'bahans' => ['Sambal Lombok Ijo' => 1]],
            ['nama_tambahan' => 'Keju Parut', 'harga' => 5000, 'bahans' => ['Keju Parut' => 1]],
            ['nama_tambahan' => 'Saus Keju', 'harga' => 5000, 'bahans' => ['Saus Keju' => 1]],
            ['nama_tambahan' => 'Saus Mentai', 'harga' => 5000, 'bahans' => ['Saus Mentai' => 1]],
            ['nama_tambahan' => 'Saus BBQ', 'harga' => 5000, 'bahans' => ['Saus BBQ' => 1]],
            ['nama_tambahan' => 'Keju Mozarella', 'harga' => 8000, 'bahans' => ['Keju Mozarella' => 1]],
        ];

        foreach ($tambahans as $t) {
            $tambahanModel = Tambahan::create([
                'nama_tambahan' => $t['nama_tambahan'],
                'harga' => $t['harga'],
                'status_stok' => 'Tersedia',
            ]);

            if (isset($t['bahans'])) {
                foreach ($t['bahans'] as $bName => $qty) {
                    if (isset($bahans[$bName])) {
                        $tambahanModel->bahans()->attach($bahans[$bName]->id_bahan, ['jumlah_dibutuhkan' => $qty]);
                    }
                }
            }
        }

        // 8. Seed 12 Dummy Pesanan & Detail untuk Dashboard & Laporan Penjualan
        $allMenus = Menu::all()->keyBy('nama_menu');
        $allTambahans = Tambahan::all()->keyBy('nama_tambahan');
        $admin = Admin::first();

        $dummyOrders = [
            [
                'id_meja' => 1,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Andi Pratama',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'Tunai',
                'uang_dibayar' => 50000,
                'kembalian' => 12000,
                'tanggal_waktu' => now()->subHours(5),
                'items' => [
                    [
                        'menu' => 'Paket 1 - Nasi + Geprek Sayap + Teh',
                        'jumlah' => 2,
                        'level_pedas' => 3,
                        'catatan' => 'Es teh manis',
                        'tambahans' => ['Sambal Bawang']
                    ],
                    [
                        'menu' => 'Tempe Goreng (isi 2)',
                        'jumlah' => 2,
                        'level_pedas' => null,
                        'catatan' => 'Goreng garing',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 2,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Siti Rahmawati',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'QRIS',
                'uang_dibayar' => 45000,
                'kembalian' => 0,
                'tanggal_waktu' => now()->subHours(4),
                'items' => [
                    [
                        'menu' => 'Paket 8 - Nasi + Geprek Keju + Teh',
                        'jumlah' => 1,
                        'level_pedas' => 2,
                        'catatan' => 'Keju banyakin',
                        'tambahans' => ['Keju Mozarella']
                    ],
                    [
                        'menu' => 'Paket 2 - Nasi + Geprek Paha Bawah + Teh',
                        'jumlah' => 1,
                        'level_pedas' => 4,
                        'catatan' => 'Pedas banget',
                        'tambahans' => ['Lalapan']
                    ]
                ]
            ],
            [
                'id_meja' => null,
                'tipe_pesanan' => 'Take Away',
                'nama_pemesan' => 'Budi Santoso',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'Tunai',
                'uang_dibayar' => 100000,
                'kembalian' => 42000,
                'tanggal_waktu' => now()->subHours(3)->subMinutes(30),
                'items' => [
                    [
                        'menu' => 'Paket 4 - Nasi + Geprek Dada + Teh',
                        'jumlah' => 3,
                        'level_pedas' => 3,
                        'catatan' => 'Bungkus terpisah sambalnya',
                        'tambahans' => ['Sambal Matah', 'Lalapan']
                    ],
                    [
                        'menu' => 'Jeruk Dingin/Panas',
                        'jumlah' => 1,
                        'level_pedas' => null,
                        'catatan' => 'Dingin',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 3,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Dewi Lestari',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'QRIS',
                'uang_dibayar' => 38000,
                'kembalian' => 0,
                'tanggal_waktu' => now()->subHours(3),
                'items' => [
                    [
                        'menu' => 'Paket 11 - Nasi + Geprek Saus Mentai + Teh',
                        'jumlah' => 2,
                        'level_pedas' => 2,
                        'catatan' => 'Es teh tawar 1, manis 1',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 4,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Rizky Kurniawan',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'Tunai',
                'uang_dibayar' => 50000,
                'kembalian' => 18000,
                'tanggal_waktu' => now()->subHours(2)->subMinutes(45),
                'items' => [
                    [
                        'menu' => 'Paket 12 - Mie + Geprek Level + Teh',
                        'jumlah' => 1,
                        'level_pedas' => 5,
                        'catatan' => 'Mie goreng pedas nampol',
                        'tambahans' => ['Telur Ceplok' => false]
                    ],
                    [
                        'menu' => 'Extra Joss Susu',
                        'jumlah' => 1,
                        'level_pedas' => null,
                        'catatan' => 'Dingin ekstra es',
                        'tambahans' => []
                    ],
                    [
                        'menu' => 'Kulit/Usus Kriyuk',
                        'jumlah' => 1,
                        'level_pedas' => null,
                        'catatan' => '',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 5,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Maya Putri',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'QRIS',
                'uang_dibayar' => 29000,
                'kembalian' => 0,
                'tanggal_waktu' => now()->subHours(2),
                'items' => [
                    [
                        'menu' => 'Paket 6 - Nasi + Geprek Matah + Teh',
                        'jumlah' => 1,
                        'level_pedas' => 3,
                        'catatan' => 'Matah pisah mangkok',
                        'tambahans' => ['Lalapan']
                    ],
                    [
                        'menu' => 'Kol Goreng',
                        'jumlah' => 1,
                        'level_pedas' => null,
                        'catatan' => 'Goreng kering',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 6,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Eko Prasetyo',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'Tunai',
                'uang_dibayar' => 100000,
                'kembalian' => 36000,
                'tanggal_waktu' => now()->subHours(1)->subMinutes(30),
                'items' => [
                    [
                        'menu' => 'Paket 5 - Nasi + Geprek Bakar + Teh',
                        'jumlah' => 2,
                        'level_pedas' => 2,
                        'catatan' => 'Bumbu bakar manis gurih',
                        'tambahans' => ['Sambal Lombok Ijo']
                    ],
                    [
                        'menu' => 'Paket 1 - Nasi + Geprek Sayap + Teh',
                        'jumlah' => 1,
                        'level_pedas' => 1,
                        'catatan' => '',
                        'tambahans' => []
                    ],
                    [
                        'menu' => 'Sup Ceker',
                        'jumlah' => 2,
                        'level_pedas' => null,
                        'catatan' => 'Hangat kuah banyak',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => null,
                'tipe_pesanan' => 'Take Away',
                'nama_pemesan' => 'Nurul Hidayah',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'QRIS',
                'uang_dibayar' => 62000,
                'kembalian' => 0,
                'tanggal_waktu' => now()->subHour(1),
                'items' => [
                    [
                        'menu' => 'Paket 3 - Nasi + Geprek Paha Atas + Teh',
                        'jumlah' => 3,
                        'level_pedas' => 3,
                        'catatan' => 'Bungkus rapi',
                        'tambahans' => ['Sambal Bawang', 'Keju Parut']
                    ],
                    [
                        'menu' => 'Air Mineral 600ml',
                        'jumlah' => 2,
                        'level_pedas' => null,
                        'catatan' => 'Dingin',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 7,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Fajar Ramadhan',
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'Tunai',
                'uang_dibayar' => 50000,
                'kembalian' => 12000,
                'tanggal_waktu' => now()->subMinutes(45),
                'items' => [
                    [
                        'menu' => 'Paket 10 - Nasi + Geprek Saus BBQ + Teh',
                        'jumlah' => 2,
                        'level_pedas' => 2,
                        'catatan' => '',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 8,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Dina Kartika',
                'status' => 'Diproses',
                'status_pembayaran' => 'Lunas',
                'metode_bayar' => 'QRIS',
                'uang_dibayar' => 35000,
                'kembalian' => 0,
                'tanggal_waktu' => now()->subMinutes(25),
                'items' => [
                    [
                        'menu' => 'Paket 9 - Nasi + Geprek Saus Keju + Teh',
                        'jumlah' => 1,
                        'level_pedas' => 3,
                        'catatan' => 'Saus keju lumer',
                        'tambahans' => ['Saus Keju']
                    ],
                    [
                        'menu' => 'Kulit/Usus Kriyuk',
                        'jumlah' => 1,
                        'level_pedas' => null,
                        'catatan' => '',
                        'tambahans' => []
                    ]
                ]
            ],
            [
                'id_meja' => 9,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Rendra Wijaya',
                'status' => 'Diterima',
                'status_pembayaran' => 'Belum Lunas',
                'metode_bayar' => 'Tunai',
                'uang_dibayar' => null,
                'kembalian' => null,
                'tanggal_waktu' => now()->subMinutes(10),
                'items' => [
                    [
                        'menu' => 'Paket 2 - Nasi + Geprek Paha Bawah + Teh',
                        'jumlah' => 2,
                        'level_pedas' => 4,
                        'catatan' => 'Es teh tawar',
                        'tambahans' => ['Lalapan']
                    ]
                ]
            ],
            [
                'id_meja' => 10,
                'tipe_pesanan' => 'Dine-In',
                'nama_pemesan' => 'Salsa Bella',
                'status' => 'Dibatalkan',
                'status_pembayaran' => 'Belum Lunas',
                'metode_bayar' => 'Tunai',
                'uang_dibayar' => null,
                'kembalian' => null,
                'tanggal_waktu' => now()->subMinutes(5),
                'alasan_pembatalan' => 'Pelanggan membatalkan pesanan karena mendadak ada keperluan',
                'items' => [
                    [
                        'menu' => 'Paket 1 - Nasi + Geprek Sayap + Teh',
                        'jumlah' => 1,
                        'level_pedas' => 2,
                        'catatan' => '',
                        'tambahans' => []
                    ]
                ]
            ]
        ];

        foreach ($dummyOrders as $orderData) {
            $totalHarga = 0;
            $orderItemsCalculated = [];

            foreach ($orderData['items'] as $item) {
                $menuModel = $allMenus->get($item['menu']);
                if (!$menuModel) continue;

                $tambahanModels = [];
                $tambahanTotal = 0;
                if (!empty($item['tambahans'])) {
                    foreach ($item['tambahans'] as $tName) {
                        $tModel = $allTambahans->get($tName);
                        if ($tModel) {
                            $tambahanModels[] = $tModel;
                            $tambahanTotal += $tModel->harga;
                        }
                    }
                }

                $subtotal = ($menuModel->harga + $tambahanTotal) * $item['jumlah'];
                $totalHarga += $subtotal;

                $orderItemsCalculated[] = [
                    'menu_id' => $menuModel->id_menu,
                    'jumlah' => $item['jumlah'],
                    'level_pedas' => $item['level_pedas'],
                    'catatan' => $item['catatan'],
                    'subtotal' => $subtotal,
                    'tambahans' => $tambahanModels,
                ];
            }

            $pesanan = \App\Models\Pesanan::create([
                'id_meja' => $orderData['id_meja'],
                'id_admin' => $admin ? $admin->id_admin : null,
                'tipe_pesanan' => $orderData['tipe_pesanan'],
                'nama_pemesan' => $orderData['nama_pemesan'],
                'status' => $orderData['status'],
                'status_pembayaran' => $orderData['status_pembayaran'],
                'metode_bayar' => $orderData['metode_bayar'],
                'uang_dibayar' => $orderData['uang_dibayar'] ?? ($orderData['status_pembayaran'] === 'Lunas' ? $totalHarga : null),
                'kembalian' => $orderData['kembalian'] ?? 0,
                'alasan_pembatalan' => $orderData['alasan_pembatalan'] ?? null,
                'tanggal_waktu' => $orderData['tanggal_waktu'],
                'total_harga' => $totalHarga,
            ]);

            foreach ($orderItemsCalculated as $calcItem) {
                $detail = \App\Models\DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_menu' => $calcItem['menu_id'],
                    'jumlah' => $calcItem['jumlah'],
                    'level_pedas' => $calcItem['level_pedas'],
                    'catatan' => $calcItem['catatan'],
                    'subtotal' => $calcItem['subtotal'],
                ]);

                foreach ($calcItem['tambahans'] as $tModel) {
                    \Illuminate\Support\Facades\DB::table('detail_tambahans')->insert([
                        'id_detail' => $detail->id_detail,
                        'id_tambahan' => $tModel->id_tambahan,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
