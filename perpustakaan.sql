-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2023 at 02:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `email`, `password`) VALUES
('385e33f741', 'test admin', 'admin@admin.com', 'c484731969b767820934f6fb74a803f05b382e4b5b10383f6819a5374fcda0fb8d0d7e14f9cf6ef8f76d406669c081821fc6f32d068e98b3f831b163c8a0f84b');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `judulBuku` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `idUser` varchar(255) DEFAULT NULL,
  `idKategori` int(11) NOT NULL,
  `deskripsi` longtext NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `linkPdfBuku` text NOT NULL,
  `linkCoverBuku` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judulBuku`, `slug`, `idUser`, `idKategori`, `deskripsi`, `jumlah`, `linkPdfBuku`, `linkCoverBuku`, `created_at`, `updated_at`) VALUES
(1, 'Contoh buku 1', 'contoh-buku-1', '49f4c6e816', 2, 'test', 5, 'test-aja-deh.pdf', 'test-aja-deh.png', '2023-07-21 17:12:16', '2023-07-23 12:18:30'),
(8, 'Test Buku', 'test-buku', '9b1f971de9', 1, 'Test Buku', 5, 'test-buku.pdf', 'test-buku.jpeg', '2023-07-23 12:06:42', '2023-07-23 12:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `namaKategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `namaKategori`) VALUES
(1, 'Education'),
(2, 'History'),
(3, 'Kesehatan'),
(4, 'Romance'),
(5, 'Komedi');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`) VALUES
('49f4c6e816', 'Felix Ferdianto ', 'felixferdianto13@gmail.com', '11437a6ee53af8c57e9373f7806639b01ceb37ae90cb3a9bbb8031a21d1f4b91ae9a382c75ffd0b08b62d699bae6c0ec179c885df6ad2873d6d38d7d25aad1fb'),
('9b1f971de9', 'Test User', 'user@gmail.com', '584222f5fcc844297d56261f377514ac3e9042fd66c345d67ff189e30bd6180c0d6f71b7f54acf38e7a500c077de81776006a249d731a61ca6e80a1514f60da8');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idKategori` (`idKategori`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`idKategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buku_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
