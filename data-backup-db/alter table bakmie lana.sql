CREATE TABLE `karyawan` (
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

CREATE TABLE `laba_bersih_detail` (
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `gaji_karyawan` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;