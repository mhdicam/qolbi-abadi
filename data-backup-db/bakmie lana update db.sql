-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table pos.gaji_karyawan
CREATE TABLE IF NOT EXISTS `gaji_karyawan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL,
  `periode` varchar(7) NOT NULL DEFAULT '',
  `day` int(11) NOT NULL,
  `kddh` double(20,3) NOT NULL DEFAULT '0.000',
  `bonus_omset` double(20,3) NOT NULL DEFAULT '0.000',
  `salary` double(20,3) NOT NULL DEFAULT '0.000',
  `overtime` double(20,3) NOT NULL DEFAULT '0.000',
  `thp` double(20,3) NOT NULL DEFAULT '0.000',
  `laba_bersih_detail_id` int(11) NOT NULL,
  `cabang` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_gaji_karyawan_karyawan` (`id_karyawan`),
  CONSTRAINT `FK_gaji_karyawan_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pos.karyawan
CREATE TABLE IF NOT EXISTS `karyawan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_karyawan` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `cabang` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pos.laba_bersih
CREATE TABLE IF NOT EXISTS `laba_bersih` (
  `lb_id` int(11) NOT NULL AUTO_INCREMENT,
  `lb_pendapatan_lain` int(11) NOT NULL,
  `lb_pengeluaran_gaji` int(11) NOT NULL,
  `lb_pengeluaran_listrik` int(11) NOT NULL,
  `lb_pengeluaran_tlpn_internet` int(11) NOT NULL,
  `lb_pengeluaran_perlengkapan_toko` int(11) NOT NULL,
  `lb_pengeluaran_biaya_penyusutan` int(11) NOT NULL,
  `lb_pengeluaran_bensin` int(11) NOT NULL,
  `lb_pengeluaran_tak_terduga` int(11) NOT NULL,
  `lb_pengeluaran_lain` int(11) NOT NULL,
  `lb_cabang` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lb_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table pos.laba_bersih_detail
CREATE TABLE IF NOT EXISTS `laba_bersih_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `incomes_expenses` enum('pendapatan','pengeluaran') NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` double(20,3) NOT NULL DEFAULT '0.000',
  `qty` int(11) NOT NULL DEFAULT '0',
  `total` double(20,3) NOT NULL DEFAULT '0.000',
  `real_income` double(20,3) NOT NULL DEFAULT '0.000',
  `jenis_pembayaran` varchar(255) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `cabang` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pos.penambahan_aset
CREATE TABLE IF NOT EXISTS `penambahan_aset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_laba_bersih_detail` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `cabang` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
