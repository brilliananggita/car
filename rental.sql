-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2024 at 04:00 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_bayar`
--

CREATE TABLE `tb_bayar` (
  `id_bayar` int(11) NOT NULL,
  `id_kembali` int(11) DEFAULT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `total_bayar` decimal(10,2) DEFAULT NULL,
  `status` enum('lunas','belum lunas') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_bayar`
--

INSERT INTO `tb_bayar` (`id_bayar`, `id_kembali`, `tgl_bayar`, `total_bayar`, `status`) VALUES
(4, 3, '2024-10-22', '1200000.00', 'lunas'),
(5, 1, '2024-10-22', '370000.00', 'lunas'),
(6, 5, '2024-10-22', '50.00', 'belum lunas'),
(7, 1, '2024-10-27', '700000.00', 'belum lunas'),
(8, 4, '2024-10-27', '1890000.00', 'lunas'),
(9, 5, '2024-10-27', '1000100.00', 'lunas');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kembali`
--

CREATE TABLE `tb_kembali` (
  `id_kembali` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `kondisi_mobil` text DEFAULT NULL,
  `denda` decimal(10,2) DEFAULT NULL,
  `biaya_tambahan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kembali`
--

INSERT INTO `tb_kembali` (`id_kembali`, `id_transaksi`, `tgl_kembali`, `kondisi_mobil`, `denda`, `biaya_tambahan`) VALUES
(1, 6, '2024-10-26', 'lecet', '370000.00', 50000),
(2, 7, '2024-10-26', 'lecet', '650000.00', 30000),
(3, 8, '2024-10-28', 'aman', '1200000.00', 0),
(4, 9, '2024-10-26', 'retak', '810000.00', 70000),
(5, 12, '2024-10-24', 'lecet', '50.00', 50);

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `nik` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`nik`, `nama`, `jk`, `telp`, `alamat`, `username`, `pass`) VALUES
(100, 'Faizal Nur Rahman', 'L', '085805678', 'Jambon Kidul, Cacaban', 'Faizal', '43c2a5745cd71241f50207cbfa266fd3'),
(101, 'Siti Nur Jannah', 'P', '0856780090', 'Blondo, Mertoyudan, Magelang', 'Siti', 'db04eb4b07e0aaf8d1d477ae342bdff9'),
(102, 'Gibran Rakabuming', 'L', '0909400', 'Solo City', 'fufufafa', 'eec4be30152728e43eadcd34cd3ddc8e'),
(103, 'Ariana Grande', 'P', '0101010', 'New York City', 'Ari', 'fc292bd7df071858c2d0f955545673c1'),
(104, 'Aeri Winter', 'P', '630490', 'Korea Utara\r\n\r\n\r\n', 'Aeri', '49913d08aa43124c519ff885402ba97a'),
(105, 'Bella Bunka', 'P', '0909090', 'Pakis', 'bella', 'e7e9ec3723447a642f762b2b6a15cfd7'),
(701, 'deni', 'L', '9009090', 'pgn', 'deni', '43f41d127a81c54d4c8f5f93daeb7118'),
(705, 'Brillian', 'P', '009900', 'Jepang', 'brilli', '8d309f7a47494150ad915568202e712d');

-- --------------------------------------------------------

--
-- Table structure for table `tb_mobil`
--

CREATE TABLE `tb_mobil` (
  `nopol` varchar(10) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `tahun` date NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `status` enum('tersedia','tidak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_mobil`
--

INSERT INTO `tb_mobil` (`nopol`, `brand`, `type`, `tahun`, `harga`, `foto`, `status`) VALUES
('B 1122 YZA', 'Daihatsu', 'Terios', '2020-09-12', '370000.00', 'Daihatsu_Terios_2018.jpg', 'tidak'),
('B 1234 ABC', 'Toyota', 'Avanza', '2020-01-01', '300000.00', 'avanza2020.jpg', 'tersedia'),
('B 3344 MNO', 'Mitsubishi', 'Xpander', '2021-05-05', '400000.00', 'xpander.jpg', 'tersedia'),
('D 3344 ZBC', 'Honda', 'BR-V', '2021-12-01', '380000.00', 'Honda_BR-V_2019.jpg', 'tersedia'),
('D 5566 PQR', 'Nissan', 'Livina', '2019-11-20', '340000.00', 'nisanlivina.jpg', 'tersedia'),
('D 5678 DEF', 'Honda', 'Mobilio', '2019-06-15', '350000.00', 'hondamobilio.jpg', 'tidak'),
('H 7788 STU', 'Wuling', 'Confero', '2020-03-18', '310000.00', 'confero.png', 'tidak'),
('H 9101 GHI', 'Suzuki', 'Ertiga', '2021-08-25', '320000.00', 'suzukiertiga.jpg', 'tersedia'),
('L 1122 JKL', 'Daihatsu', 'Xenia', '2020-10-10', '280000.00', 'Xenia2019.jpg', 'tersedia'),
('L 9900 VWX', 'Toyota', 'Rush', '2021-07-30', '360000.00', 'rush.png', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `nik` int(11) DEFAULT NULL,
  `nopol` varchar(10) DEFAULT NULL,
  `tgl_booking` date DEFAULT NULL,
  `tgl_ambil` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `supir` tinyint(1) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `downpayment` decimal(10,2) DEFAULT 100000.00,
  `kekurangan` decimal(10,2) DEFAULT NULL,
  `status` enum('booking','approve','ambil','kembali') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `nik`, `nopol`, `tgl_booking`, `tgl_ambil`, `tgl_kembali`, `supir`, `total`, `downpayment`, `kekurangan`, `status`) VALUES
(2, 101, 'B 1234 ABC', '2024-10-21', '2024-10-22', '2024-10-26', 1, '400000.00', '350000.00', '50000.00', 'kembali'),
(4, 101, 'B 1234 ABC', '2024-10-21', '2024-10-22', '2024-10-22', 0, '300000.00', '100000.00', '200000.00', 'kembali'),
(5, 102, 'D 3344 ZBC', '2024-10-21', '2024-10-22', '2024-10-25', 1, '480000.00', '300000.00', '180000.00', 'kembali'),
(6, 102, 'H 9101 GHI', '2024-10-22', '2024-10-22', '2024-10-25', 0, '1280000.00', '1000000.00', '280000.00', 'kembali'),
(7, 103, 'H 7788 STU', '2024-10-22', '2024-10-22', '2024-10-24', 1, '1230000.00', '1000000.00', '230000.00', 'kembali'),
(8, 104, 'B 1234 ABC', '2024-10-22', '2024-10-22', '2024-10-24', 0, '900000.00', '400000.00', '500000.00', 'kembali'),
(9, 103, 'B 1122 YZA', '2024-10-22', '2024-10-22', '2024-10-24', 1, '1410000.00', '400000.00', '1010000.00', 'kembali'),
(10, 105, 'B 1122 YZA', '2024-10-22', '2024-10-22', '2024-10-24', 1, '1410000.00', '410000.00', '1000000.00', 'booking'),
(11, 103, 'H 7788 STU', '2024-10-22', '2024-10-22', '2024-10-24', 1, '1230000.00', '40000.00', '1190000.00', 'ambil'),
(12, 105, 'B 3344 MNO', '2024-10-22', '2024-10-22', '2024-10-24', 0, '1200000.00', '200000.00', '1000000.00', 'kembali'),
(13, 100, 'D 5678 DEF', '2024-10-27', '2024-10-27', '2024-10-29', 1, '1350000.00', '50000.00', '1300000.00', 'kembali');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('admin','petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `pass`, `role`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'petugas', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'petugas'),
(3, 'karen', 'ba952731f97fb058035aa399b1cb3d5c', 'petugas'),
(4, 'Billa', 'billa', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_bayar`
--
ALTER TABLE `tb_bayar`
  ADD PRIMARY KEY (`id_bayar`),
  ADD KEY `id_kembali` (`id_kembali`);

--
-- Indexes for table `tb_kembali`
--
ALTER TABLE `tb_kembali`
  ADD PRIMARY KEY (`id_kembali`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`nik`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_mobil`
--
ALTER TABLE `tb_mobil`
  ADD PRIMARY KEY (`nopol`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `nopol` (`nopol`),
  ADD KEY `nik` (`nik`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_bayar`
--
ALTER TABLE `tb_bayar`
  MODIFY `id_bayar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_kembali`
--
ALTER TABLE `tb_kembali`
  MODIFY `id_kembali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_bayar`
--
ALTER TABLE `tb_bayar`
  ADD CONSTRAINT `tb_bayar_ibfk_1` FOREIGN KEY (`id_kembali`) REFERENCES `tb_kembali` (`id_kembali`);

--
-- Constraints for table `tb_kembali`
--
ALTER TABLE `tb_kembali`
  ADD CONSTRAINT `tb_kembali_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id_transaksi`);

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_ibfk_1` FOREIGN KEY (`nopol`) REFERENCES `tb_mobil` (`nopol`),
  ADD CONSTRAINT `tb_transaksi_ibfk_2` FOREIGN KEY (`nik`) REFERENCES `tb_member` (`nik`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
