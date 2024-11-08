-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Nov 2024 pada 10.16
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nim_nidn` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','prodi','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `nim_nidn`, `password`, `role`) VALUES
(5, 'rahma', '2157201022', '$2y$10$WxO2y.VQxA5WqosS8xmf9OxaGMKPosBT5Hv5RuV8D15AXtVGOmaQe', 'mahasiswa'),
(8, 'redo', '2157201077', '$2y$10$IJjgOKdu02jfsYb40DY.Ee6OdTvERkE46GociGruBGH32SUXMlxnK', 'staff'),
(11, 'ani', '2157201011', '$2y$10$hSR0Hehl6F.Dmhvm3hzJ6.MoolgBdy45GyQCpdtMm4416.FXA0qci', 'mahasiswa'),
(12, 'ani', '2157201033', '$2y$10$7BEpAGhe7ok9MmnSKGgOaOUeIPhyN6EEuvf1PQC3BNEdLJn0aqcqe', 'prodi'),
(13, 'ano', '2157201044', '$2y$10$gp4QoQuw/Xs3nlk/mG60NOh1Ka.mbQ2TXns/02Z5oUypWFbmijHe.', 'staff'),
(14, 'mama', '2157201055', '$2y$10$jdf7M5kpalOo3soDZtzwDeuQD31oLDKNNKBTMUwIxTOyyU5H/djJu', 'staff'),
(15, 'babu', '2157201010', '$2y$10$Aydu3.OLXJj7EL44rZLO6.7hC94kHw/yM/OfcW2J5YEhG8QY.15lW', 'staff'),
(16, 'bimo', '2157201021', '$2y$10$GpfwmBq/kNT10wOyVbUu/OJU/Kd.McqueEC6cLddhiXJyfoLD9d.S', 'prodi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim_nidn` (`nim_nidn`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
