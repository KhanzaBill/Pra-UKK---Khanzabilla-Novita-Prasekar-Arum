-- Database Export for Sistem Pemesanan Makanan QR Code "Yummy Chicken"
-- pra-UKK Task School Exam
-- Database: yummy_chicken_db

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `detail_tambahans`;
DROP TABLE IF EXISTS `detail_pesanans`;
DROP TABLE IF EXISTS `pesanans`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `tambahans`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `mejas`;
SET FOREIGN_KEY_CHECKS=1;

-- 1. TABEL MEJAS
CREATE TABLE `mejas` (
  `id_meja` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_meja` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_meja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `mejas` (`id_meja`, `nomor_meja`) VALUES
(1, 'Meja 01'), (2, 'Meja 02'), (3, 'Meja 03'), (4, 'Meja 04'), (5, 'Meja 05'),
(6, 'Meja 06'), (7, 'Meja 07'), (8, 'Meja 08'), (9, 'Meja 09'), (10, 'Meja 10');

-- 2. TABEL MENUS
CREATE TABLE `menus` (
  `id_menu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(255) NOT NULL,
  `kategori` enum('Paket','Makanan','Minuman') NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_stok` enum('Tersedia','Habis') NOT NULL DEFAULT 'Tersedia',
  `opsi_pedas` enum('Ya','Tidak') NOT NULL DEFAULT 'Tidak',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu Paket
INSERT INTO `menus` (`id_menu`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `status_stok`, `opsi_pedas`) VALUES
(1, 'Paket 1 - Nasi + Geprek Sayap + Teh', 'Paket', 12000, 'Paket komplit hemat Nasi + Geprek Sayap + Teh segar', 'Tersedia', 'Ya'),
(2, 'Paket 2 - Nasi + Geprek Paha Bawah + Teh', 'Paket', 14000, 'Paket Nasi + Geprek Paha Bawah + Teh', 'Tersedia', 'Ya'),
(3, 'Paket 3 - Nasi + Geprek Paha Atas + Teh', 'Paket', 14000, 'Paket Nasi + Geprek Paha Atas + Teh', 'Tersedia', 'Ya'),
(4, 'Paket 4 - Nasi + Geprek Dada + Teh', 'Paket', 14000, 'Paket Nasi + Geprek Dada + Teh', 'Tersedia', 'Ya'),
(5, 'Paket 5 - Nasi + Geprek Bakar + Teh', 'Paket', 17000, 'Paket Nasi + Geprek Bakar + Teh', 'Tersedia', 'Ya'),
(6, 'Paket 6 - Nasi + Geprek Matah + Teh', 'Paket', 19000, 'Paket Nasi + Geprek Matah + Teh', 'Tersedia', 'Ya'),
(7, 'Paket 7 - Nasi + Geprek Lombok Ijo + Teh', 'Paket', 19000, 'Paket Nasi + Geprek Lombok Ijo + Teh', 'Tersedia', 'Ya'),
(8, 'Paket 8 - Nasi + Geprek Keju + Teh', 'Paket', 19000, 'Paket Nasi + Geprek Keju + Teh', 'Tersedia', 'Ya'),
(9, 'Paket 9 - Nasi + Geprek Saus Keju + Teh', 'Paket', 19000, 'Paket Nasi + Geprek Saus Keju + Teh', 'Tersedia', 'Ya'),
(10, 'Paket 10 - Nasi + Geprek Saus BBQ + Teh', 'Paket', 19000, 'Paket Nasi + Geprek Saus BBQ + Teh', 'Tersedia', 'Ya'),
(11, 'Paket 11 - Nasi + Geprek Saus Mentai + Teh', 'Paket', 19000, 'Paket Nasi + Geprek Saus Mentai + Teh', 'Tersedia', 'Ya'),
(12, 'Paket 12 - Mie + Geprek Level + Teh', 'Paket', 20000, 'Paket Mie + Geprek Level + Teh', 'Tersedia', 'Ya');

-- Menu Makanan
INSERT INTO `menus` (`id_menu`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `status_stok`, `opsi_pedas`) VALUES
(13, 'Ayam Sayap', 'Makanan', 8000, 'Ayam geprek bagian sayap renyah', 'Tersedia', 'Tidak'),
(14, 'Ayam Paha Bawah', 'Makanan', 10000, 'Ayam geprek paha bawah', 'Tersedia', 'Tidak'),
(15, 'Ayam Paha Atas', 'Makanan', 10000, 'Ayam geprek paha atas', 'Tersedia', 'Tidak'),
(16, 'Ayam Dada', 'Makanan', 10000, 'Ayam geprek dada empuk', 'Tersedia', 'Tidak'),
(17, 'Sup Ceker', 'Makanan', 3000, 'Sup ceker gurih hangat', 'Tersedia', 'Tidak'),
(18, 'Kulit/Usus Kriyuk', 'Makanan', 13000, 'Kulit/usus goreng garing kriyuk', 'Tersedia', 'Tidak'),
(19, 'Indomie Goreng/Rebus', 'Makanan', 9000, 'Indomie lezat', 'Tersedia', 'Tidak'),
(20, 'Telur Dadar/Ceplok', 'Makanan', 6000, 'Telur dadar atau ceplok goreng', 'Tersedia', 'Tidak'),
(21, 'Tempe Goreng (isi 2)', 'Makanan', 3000, 'Tempe goreng isi 2 pcs', 'Tersedia', 'Tidak'),
(22, 'Kol Goreng', 'Makanan', 8000, 'Kol goreng manis manis gurih', 'Tersedia', 'Tidak'),
(23, 'Petai Goreng', 'Makanan', 8000, 'Petai goreng hangat', 'Tersedia', 'Tidak'),
(24, 'Nasi Putih', 'Makanan', 3000, 'Nasi putih hangat', 'Tersedia', 'Tidak'),
(25, 'Nasi Daun Jeruk', 'Makanan', 6000, 'Nasi daun jeruk harum gurih', 'Tersedia', 'Tidak');

-- Menu Minuman
INSERT INTO `menus` (`id_menu`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `status_stok`, `opsi_pedas`) VALUES
(26, 'Teh Dingin/Panas', 'Minuman', 4000, 'Teh manis segar', 'Tersedia', 'Tidak'),
(27, 'Jeruk Dingin/Panas', 'Minuman', 5000, 'Es jeruk manis', 'Tersedia', 'Tidak'),
(28, 'Susu Putih/Coklat', 'Minuman', 5000, 'Susu manis', 'Tersedia', 'Tidak'),
(29, 'Extra Joss', 'Minuman', 5000, 'Extra joss segar', 'Tersedia', 'Tidak'),
(30, 'Extra Joss Susu', 'Minuman', 8000, 'Extra joss susu', 'Tersedia', 'Tidak'),
(31, 'Energen', 'Minuman', 7000, 'Energen sereal hangat', 'Tersedia', 'Tidak'),
(32, 'Adem Sari', 'Minuman', 7000, 'Adem sari penawar dahaga', 'Tersedia', 'Tidak'),
(33, 'Segar Dingin', 'Minuman', 5000, 'Segar dingin rasa buah', 'Tersedia', 'Tidak'),
(34, 'Kopi Hitam', 'Minuman', 5000, 'Kopi hitam nikmat', 'Tersedia', 'Tidak'),
(35, 'Kopi Jahe', 'Minuman', 5000, 'Kopi jahe penghangat', 'Tersedia', 'Tidak'),
(36, 'Jahe Wangi/Anget Sari', 'Minuman', 5000, 'Jahe wangi', 'Tersedia', 'Tidak'),
(37, 'Milo/Hilo', 'Minuman', 7000, 'Milo coklat manis', 'Tersedia', 'Tidak'),
(38, 'Dancow', 'Minuman', 8000, 'Susu dancow', 'Tersedia', 'Tidak'),
(39, 'Beng Beng', 'Minuman', 7000, 'Beng beng drink', 'Tersedia', 'Tidak'),
(40, 'Chocolatos', 'Minuman', 5000, 'Chocolatos drink', 'Tersedia', 'Tidak'),
(41, 'Coffeemix', 'Minuman', 5000, 'Kopi coffeemix', 'Tersedia', 'Tidak'),
(42, 'Kapal Api', 'Minuman', 5000, 'Kopi kapal api', 'Tersedia', 'Tidak'),
(43, 'Nescafe', 'Minuman', 5000, 'Kopi nescafe', 'Tersedia', 'Tidak'),
(44, 'Luwak White Coffee', 'Minuman', 5000, 'White coffee', 'Tersedia', 'Tidak'),
(45, 'Caffino', 'Minuman', 5000, 'Kopi caffino', 'Tersedia', 'Tidak'),
(46, 'Torabika', 'Minuman', 5000, 'Kopi torabika', 'Tersedia', 'Tidak'),
(47, 'Top Coffee', 'Minuman', 5000, 'Kopi top coffee', 'Tersedia', 'Tidak'),
(48, 'Good Day Cappucino', 'Minuman', 7000, 'Good day cappucino', 'Tersedia', 'Tidak'),
(49, 'Good Day Freeze', 'Minuman', 5000, 'Good day freeze', 'Tersedia', 'Tidak'),
(50, 'Good Day Latte', 'Minuman', 7000, 'Good day latte', 'Tersedia', 'Tidak'),
(51, 'Good Day Mocacinno', 'Minuman', 5000, 'Good day mocacinno', 'Tersedia', 'Tidak'),
(52, 'Good Day Coolin', 'Minuman', 5000, 'Good day coolin', 'Tersedia', 'Tidak'),
(53, 'Good Day Vanila', 'Minuman', 5000, 'Good day vanila', 'Tersedia', 'Tidak'),
(54, 'Good Day Chococinno', 'Minuman', 5000, 'Good day chococinno', 'Tersedia', 'Tidak'),
(55, 'Nutrisari Aneka Rasa', 'Minuman', 5000, 'Nutrisari segar', 'Tersedia', 'Tidak'),
(56, 'Air Mineral 600ml', 'Minuman', 5000, 'Air mineral botol', 'Tersedia', 'Tidak');

-- 3. TABEL TAMBAHANS
CREATE TABLE `tambahans` (
  `id_tambahan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_tambahan` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tambahan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tambahans` (`id_tambahan`, `nama_tambahan`, `harga`) VALUES
(1, 'Lalapan', 2000),
(2, 'Sambal Bawang', 2000),
(3, 'Sambal Matah', 5000),
(4, 'Sambal Lombok Ijo', 5000),
(5, 'Keju Parut', 5000),
(6, 'Saus Keju', 5000),
(7, 'Saus Mentai', 5000),
(8, 'Saus BBQ', 5000),
(9, 'Keju Mozarella', 8000);

-- 4. TABEL ADMINS
CREATE TABLE `admins` (
  `id_admin` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password hashed for 'password123'
INSERT INTO `admins` (`id_admin`, `nama`, `username`, `password`) VALUES
(1, 'Kasir Yummy Chicken', 'admin', '$2y$12$4m0L4/FmDkHlR64bB26xduQ/E9K01uJ1XmNq7Ym8eG01X1.N2R6S6');

-- 5. TABEL PESANANS
CREATE TABLE `pesanans` (
  `id_pesanan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_meja` bigint(20) UNSIGNED DEFAULT NULL,
  `id_admin` bigint(20) UNSIGNED DEFAULT NULL,
  `tipe_pesanan` enum('Dine-In','Take Away') NOT NULL,
  `nama_pemesan` varchar(255) DEFAULT NULL,
  `status` enum('Diterima','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Diterima',
  `status_pembayaran` enum('Lunas','Belum Lunas') NOT NULL DEFAULT 'Belum Lunas',
  `metode_bayar` enum('Tunai','QRIS') NOT NULL,
  `uang_dibayar` int(11) DEFAULT NULL,
  `kembalian` int(11) DEFAULT NULL,
  `alasan_pembatalan` text DEFAULT NULL,
  `tanggal_waktu` datetime NOT NULL,
  `total_harga` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesanan`),
  KEY `fk_pesanans_meja` (`id_meja`),
  KEY `fk_pesanans_admin` (`id_admin`),
  CONSTRAINT `fk_pesanans_meja` FOREIGN KEY (`id_meja`) REFERENCES `mejas` (`id_meja`) ON DELETE SET NULL,
  CONSTRAINT `fk_pesanans_admin` FOREIGN KEY (`id_admin`) REFERENCES `admins` (`id_admin`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. TABEL DETAIL_PESANANS
CREATE TABLE `detail_pesanans` (
  `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pesanan` bigint(20) UNSIGNED NOT NULL,
  `id_menu` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `level_pedas` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detail`),
  KEY `fk_detail_pesanan` (`id_pesanan`),
  KEY `fk_detail_menu` (`id_menu`),
  CONSTRAINT `fk_detail_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanans` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_menu` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. TABEL DETAIL_TAMBAHANS
CREATE TABLE `detail_tambahans` (
  `id_detail_tambahan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_detail` bigint(20) UNSIGNED NOT NULL,
  `id_tambahan` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detail_tambahan`),
  KEY `fk_detail_tambahan_detail` (`id_detail`),
  KEY `fk_detail_tambahan_tambahan` (`id_tambahan`),
  CONSTRAINT `fk_detail_tambahan_detail` FOREIGN KEY (`id_detail`) REFERENCES `detail_pesanans` (`id_detail`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_tambahan_tambahan` FOREIGN KEY (`id_tambahan`) REFERENCES `tambahans` (`id_tambahan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
