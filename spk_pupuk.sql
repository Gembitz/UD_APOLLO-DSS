-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2026 at 07:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_pupuk`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_alternatif` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `keterangan`) VALUES
(1, 'NPK Mutiara', NULL),
(2, 'Amapos', NULL),
(3, 'TSP', NULL),
(4, 'PHONSKA', NULL),
(5, 'NPK Grand Ular', NULL),
(6, 'NPK Bas', NULL),
(7, 'RP', NULL),
(8, 'SP-36', NULL),
(10, 'SEHAT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(5) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` double NOT NULL,
  `atribut` enum('benefit','cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `nama_kriteria`, `bobot`, `atribut`) VALUES
(1, 'C1', 'Jenis Tanah', 0.45, 'benefit'),
(2, 'C2', 'Harga', 0.25, 'cost'),
(3, 'C3', 'Kadar Air', 0.2, 'benefit'),
(4, 'C4', 'Iklim', 0.1, 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `matriks_keputusan`
--

CREATE TABLE `matriks_keputusan` (
  `id_matriks` int(11) NOT NULL,
  `id_alternatif` int(11) DEFAULT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `nilai` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matriks_keputusan`
--

INSERT INTO `matriks_keputusan` (`id_matriks`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(1, 1, 1, 3),
(2, 1, 2, 4),
(3, 1, 3, 3),
(4, 1, 4, 3),
(5, 2, 1, 4),
(6, 2, 2, 3),
(7, 2, 3, 3),
(8, 2, 4, 5),
(9, 3, 1, 4),
(10, 3, 2, 3),
(11, 3, 3, 3),
(12, 3, 4, 5),
(13, 4, 1, 5),
(14, 4, 2, 5),
(15, 4, 3, 2),
(16, 4, 4, 5),
(17, 5, 1, 3),
(18, 5, 2, 3),
(19, 5, 3, 4),
(20, 5, 4, 3),
(21, 6, 1, 4),
(22, 6, 2, 3),
(23, 6, 3, 3),
(24, 6, 4, 5),
(25, 7, 1, 5),
(26, 7, 2, 3),
(27, 7, 3, 3),
(28, 7, 4, 5),
(29, 8, 1, 3),
(30, 8, 2, 3),
(31, 8, 3, 5),
(32, 8, 4, 3),
(37, 10, 1, 3),
(38, 10, 2, 5),
(39, 10, 3, 2),
(40, 10, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD UNIQUE KEY `kode_kriteria` (`kode_kriteria`);

--
-- Indexes for table `matriks_keputusan`
--
ALTER TABLE `matriks_keputusan`
  ADD PRIMARY KEY (`id_matriks`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `matriks_keputusan`
--
ALTER TABLE `matriks_keputusan`
  MODIFY `id_matriks` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matriks_keputusan`
--
ALTER TABLE `matriks_keputusan`
  ADD CONSTRAINT `matriks_keputusan_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriks_keputusan_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
