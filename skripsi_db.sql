-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2024 pada 05.02
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
-- Struktur dari tabel `letters`
--

CREATE TABLE `letters` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `letters`
--

INSERT INTO `letters` (`id`, `submission_id`, `staff_id`, `file_path`, `created_at`) VALUES
(1, 2, 8, 'uploads/P8-Soal UTS-KELAS A SI-71.pdf', '2024-11-08 07:43:12'),
(2, 2, 8, 'uploads/P8-Soal UTS-KELAS A SI-71.pdf', '2024-11-08 07:48:56'),
(3, 2, 8, 'uploads/P8-Soal UTS-KELAS A SI-71.pdf', '2024-11-08 07:50:23'),
(4, 2, 8, 'uploads/3720-10283-1-PB.pdf', '2024-11-08 07:51:28'),
(5, 2, 15, 'uploads/3720-10283-1-PB.pdf', '2024-11-08 09:09:46'),
(6, 2, 15, 'uploads/3720-10283-1-PB.pdf', '2024-11-08 09:10:15'),
(7, 2, 15, 'uploads/3720-10283-1-PB.pdf', '2024-11-08 09:12:41'),
(8, 2, 15, 'uploads/3720-10283-1-PB.pdf', '2024-11-08 09:14:20'),
(9, 2, 15, 'uploads/3720-10283-1-PB.pdf', '2024-11-08 10:10:33'),
(10, 2, 13, 'uploads/P8-Soal UTS-KELAS A SI-71.pdf', '2024-11-08 10:15:04'),
(11, 9, 15, 'uploads/P8-Soal UTS-KELAS A SI-71.pdf', '2024-11-08 11:11:55'),
(12, 8, 15, 'uploads/3720-10283-1-PB.pdf', '2024-11-08 11:15:20'),
(13, 8, 14, 'uploads/EVALUASI KEAMANAN INFORMASI DENGAN.pdf', '2024-11-08 11:47:07'),
(14, 12, 15, 'uploads/tugs egoverment.pdf', '2024-11-08 12:04:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `receipt_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_receipt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `submissions`
--

INSERT INTO `submissions` (`id`, `user_id`, `title`, `abstract`, `status`, `rejection_reason`, `created_at`, `payment_receipt`) VALUES
(2, 5, 'sistem informasi', 'adalah', 'accepted', NULL, '2024-11-08 06:55:03', 'uploads/tugs egoverment.pdf'),
(3, 5, 'ahahha', 'rdhgjldsrhg', 'accepted', NULL, '2024-11-08 06:55:57', NULL),
(5, 5, 'lfsgnskng;skn', 'lakengkngfg', 'rejected', 'bvhvkhgvkhgvk', '2024-11-08 07:25:43', NULL),
(8, 11, '.nvs.,fnv.', 'akfn;akndcfs', 'accepted', NULL, '2024-11-08 08:28:12', 'uploads/,.png'),
(9, 11, 'adkfakgf;s', 'lkdhgiahg;kshr', 'accepted', NULL, '2024-11-08 09:07:25', NULL),
(12, 18, 'uhuy', 'uhuy\"\"', 'accepted', NULL, '2024-11-08 12:02:29', 'uploads/,.png');

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
(18, 'tata', '2157201025', '$2y$10$EOxCBWB9.R3V9jxEZamry..8G/igt8JdbA/FligNshLM5JyrjFZ1O', 'mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `letters`
--
ALTER TABLE `letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indeks untuk tabel `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT untuk tabel `letters`
--
ALTER TABLE `letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `letters`
--
ALTER TABLE `letters`
  ADD CONSTRAINT `letters_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`),
  ADD CONSTRAINT `letters_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
