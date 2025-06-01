-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Bulan Mei 2025 pada 08.16
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_management`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `created_at`) VALUES
(1, 15, 'Login', 'User logged in successfully', '2025-05-29 14:16:21'),
(2, 15, 'Logout', 'User logged out', '2025-05-29 14:16:34'),
(3, 15, 'Login', 'User logged in successfully', '2025-05-29 18:41:36'),
(4, 15, 'Logout', 'User logged out', '2025-05-29 18:42:22'),
(5, 15, 'Login', 'User logged in successfully', '2025-05-29 18:43:14'),
(6, 15, 'Logout', 'User logged out', '2025-05-29 18:44:11'),
(7, 16, 'Login', 'User logged in successfully', '2025-05-30 11:03:04'),
(8, 16, 'Logout', 'User logged out', '2025-05-30 12:30:12'),
(9, 16, 'Login', 'User logged in successfully', '2025-05-30 12:30:24'),
(10, 16, 'Create Profile', 'Created student profile with NIM: 11220910000113', '2025-05-30 12:43:01'),
(11, 16, 'Logout', 'User logged out', '2025-05-30 12:44:52'),
(12, 16, 'Login', 'User logged in successfully', '2025-05-30 12:45:15'),
(13, 16, 'Logout', 'User logged out', '2025-05-30 12:57:32'),
(14, 16, 'Login', 'User logged in successfully', '2025-05-30 12:57:34'),
(15, 16, 'Logout', 'User logged out', '2025-05-30 13:02:45'),
(16, 16, 'Login', 'User logged in successfully', '2025-05-30 13:02:53'),
(17, 16, 'Logout', 'User logged out', '2025-05-30 13:18:45'),
(18, 16, 'Login', 'User logged in successfully', '2025-05-30 13:29:23'),
(19, 16, 'Logout', 'User logged out', '2025-05-30 13:30:44'),
(20, 16, 'Login', 'User logged in successfully', '2025-05-30 14:26:01'),
(21, 16, 'Logout', 'User logged out', '2025-05-30 14:28:59'),
(22, 16, 'Login', 'User logged in successfully', '2025-05-30 14:29:01'),
(23, 16, 'Logout', 'User logged out', '2025-05-30 15:02:28'),
(24, 15, 'Login', 'User logged in successfully', '2025-05-30 15:02:33'),
(25, 15, 'Logout', 'User logged out', '2025-05-30 15:03:38'),
(26, 16, 'Login', 'User logged in successfully', '2025-05-30 15:03:42'),
(27, 16, 'Logout', 'User logged out', '2025-05-30 15:07:37'),
(28, 15, 'Login', 'User logged in successfully', '2025-05-30 15:08:16'),
(29, 15, 'Logout', 'User logged out', '2025-05-30 15:17:02'),
(30, 16, 'Login', 'User logged in successfully', '2025-05-30 15:17:06'),
(31, 16, 'Logout', 'User logged out', '2025-05-30 15:17:12'),
(32, 16, 'Login', 'User logged in successfully', '2025-05-30 15:17:16'),
(33, 16, 'Logout', 'User logged out', '2025-05-30 15:20:07'),
(34, 15, 'Login', 'User logged in successfully', '2025-05-30 15:20:11'),
(35, 15, 'Logout', 'User logged out', '2025-05-30 15:21:24'),
(36, 15, 'Login', 'User logged in successfully', '2025-05-30 15:21:26'),
(37, 15, 'Logout', 'User logged out', '2025-05-30 16:05:47'),
(38, 15, 'Login', 'User logged in successfully', '2025-05-30 16:05:48'),
(39, 15, 'Logout', 'User logged out', '2025-05-30 16:05:53'),
(40, 16, 'Login', 'User logged in successfully', '2025-05-30 16:05:56'),
(41, 16, 'Logout', 'User logged out', '2025-05-30 16:08:41'),
(42, 15, 'Login', 'User logged in successfully', '2025-05-30 16:08:45'),
(43, 15, 'Logout', 'User logged out', '2025-05-30 16:19:45'),
(44, 16, 'Login', 'User logged in successfully', '2025-05-30 16:19:50'),
(45, 16, 'Logout', 'User logged out', '2025-05-30 16:35:58'),
(46, 16, 'Login', 'User logged in successfully', '2025-05-30 16:56:25'),
(47, 16, 'Logout', 'User logged out', '2025-05-30 17:00:31'),
(48, 16, 'Login', 'User logged in successfully', '2025-05-30 17:04:38'),
(49, 16, 'Logout', 'User logged out', '2025-05-30 17:09:02'),
(50, 15, 'Login', 'User logged in successfully', '2025-05-30 17:10:58'),
(51, 15, 'Logout', 'User logged out', '2025-05-30 17:21:09'),
(52, 15, 'Login', 'User logged in successfully', '2025-05-30 17:30:19'),
(53, 15, 'Logout', 'User logged out', '2025-05-30 17:30:30'),
(54, 16, 'Login', 'User logged in successfully', '2025-05-30 17:30:38'),
(55, 16, 'Logout', 'User logged out', '2025-05-30 17:33:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `birth_date` date NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `hobbies` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id`, `user_id`, `name`, `nim`, `birth_date`, `address`, `phone`, `hobbies`, `created_at`, `updated_at`) VALUES
(1, 16, 'Kayla Nazelika', '11220910000113', '2004-04-20', 'Jl. Kalisuren', '087848706402', 'Menyanyi', '2025-05-30 12:43:01', '2025-05-30 12:43:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$WqyZyAhVMn6UaKiKBUGy7eCPeXKMY5nYKG5XGuWnxnJFAtJTSkswy', 'admin', '2025-05-29 10:39:11'),
(15, 'Kaylanazelika', '$2y$10$wPLo455UpR/KwYP92TWFFOtTZA/DCk4T209tO1TmF/TgHL/tjM0oS', 'admin', '2025-05-29 14:16:03'),
(16, 'Kayla', '$2y$10$F.xvbX.usA1P6TmRpxkgY.c7impMg6HQoDRcCsqsNaEcKw0k5Cpny', 'student', '2025-05-30 11:02:30'),
(17, 'Kayla@gmail.com', '$2y$10$Cx/fFKrnQAEVWm5nfoMu8eJiR1dXhhcR6gdLLSsWapU0138pIOLqG', 'student', '2025-05-30 17:21:46'),
(18, 'Alif', '$2y$10$DEh9sYQWED5jXfb3aiFr4OGgxIm0O72tZAh5WU/3kjNT0bQK600KG', 'student', '2025-05-30 17:27:43');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
