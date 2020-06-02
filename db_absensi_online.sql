-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 02 Jun 2020 pada 11.50
-- Versi server: 10.3.23-MariaDB
-- Versi PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absen` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_absen` date NOT NULL,
  `tanggal_absen_masuk` date DEFAULT NULL,
  `tanggal_absen_pulang` date DEFAULT NULL,
  `jam_absen_masuk` time NOT NULL,
  `jam_absen_pulang` time DEFAULT NULL,
  `durasi_absen` time DEFAULT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `durasi_lembur` time DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data untuk tabel `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absen`, `id_user`, `tanggal_absen`, `tanggal_absen_masuk`, `tanggal_absen_pulang`, `jam_absen_masuk`, `jam_absen_pulang`, `durasi_absen`, `status`, `durasi_lembur`, `created_date`, `update_date`) VALUES
(20, 19, '2020-05-10', '2020-05-10', '2020-05-10', '07:55:00', '21:22:45', '13:27:45', 'o', '04:22:45', '2020-05-10 19:43:51', '2020-05-10 21:22:45'),
(22, 19, '2020-05-11', '2020-05-11', '2020-05-11', '21:15:10', '21:15:10', '00:00:00', 'a', '00:00:00', '2020-05-11 21:15:10', NULL),
(24, 19, '2020-05-12', '2020-05-12', '2020-05-12', '21:13:01', '21:13:01', '00:00:00', 'a', '00:00:00', '2020-05-12 21:13:01', NULL),
(25, 19, '2020-05-13', '2020-05-13', '2020-05-13', '22:05:23', '22:05:23', '00:00:00', 'a', '00:00:00', '2020-05-13 22:05:23', NULL),
(28, 19, '2020-05-14', '2020-05-14', '2020-05-14', '21:09:31', '21:09:31', '00:00:00', 'a', '00:00:00', '2020-05-14 21:09:31', NULL),
(29, 28, '2020-05-14', '2020-05-14', '2020-05-14', '21:26:55', '21:26:55', '00:00:00', 'a', '00:00:00', '2020-05-14 21:26:55', NULL),
(32, 19, '2020-04-30', '2020-04-30', '2020-04-30', '08:00:00', '17:00:00', '09:00:00', 'o', NULL, '2020-05-16 21:39:20', NULL),
(36, 28, '2020-05-19', '2020-05-19', NULL, '08:16:27', NULL, NULL, 't', NULL, '2020-05-19 08:16:27', NULL),
(37, 31, '2020-05-27', '2020-05-27', '2020-05-27', '20:28:34', '20:28:34', '00:00:00', 'a', '00:00:00', '2020-05-27 20:28:34', NULL),
(38, 32, '2020-05-28', '2020-05-28', '2020-05-28', '16:46:15', '16:47:24', '00:01:09', 't', '00:00:00', '2020-05-28 16:46:15', '2020-05-28 16:47:24'),
(39, 35, '2020-05-29', '2020-05-29', '2020-05-29', '17:51:56', '17:51:56', '00:00:00', 'a', '00:00:00', '2020-05-29 17:51:56', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_destinasi`
--

CREATE TABLE `tbl_destinasi` (
  `id_destinasi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_destinasi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `keterangan` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data untuk tabel `tbl_destinasi`
--

INSERT INTO `tbl_destinasi` (`id_destinasi`, `id_user`, `nama_destinasi`, `latitude`, `longitude`, `keterangan`, `image`, `status`) VALUES
(4, 27, 'SPBU Pondok Ungu Permai', -6.175956, 106.999354, NULL, NULL, 't'),
(14, 28, 'SMK Negeri 1 Kota Bekasi', -6.228726844164294, 106.95684056729078, NULL, NULL, NULL),
(15, 28, 'SPBU Pondok Ungu Permai', -6.176535963865034, 106.99949808418751, NULL, NULL, NULL),
(16, 28, 'Rumahku', -6.173556319635327, 106.998146250844, NULL, NULL, NULL),
(17, 19, 'Lapangan Kampung Kepu', -6.173557319630597, 106.99814792722464, NULL, NULL, 't'),
(22, 31, 'Testing', -6.1734216535867485, 106.99868336319923, NULL, NULL, NULL),
(23, 31, 'Testing lapangan Kampung Kepu', -6.173544653023552, 106.99816703796387, NULL, NULL, 't'),
(24, 28, 'Jalanan Rumahku', -6.173379320440655, 106.9982448220253, NULL, NULL, 't'),
(25, 32, 'Kantor', -6.173591986132543, 106.99812714010477, NULL, NULL, 't'),
(27, 33, 'Lapanganku', -6.173605986065386, 106.99805840849876, NULL, NULL, 't'),
(28, 35, 'Lapanganku', -6.173586319492969, 106.9981237873435, NULL, NULL, 't'),
(30, 35, 'Marakash', -6.182445536412832, 107.01360817998648, NULL, NULL, NULL),
(31, 36, 'Lapanganku', -6.173543986360016, 106.99803292751312, NULL, NULL, 't'),
(33, 43, 'SMKN 1 Bekasi', -6.228726844164294, 106.95684056729078, NULL, NULL, NULL),
(34, 43, 'SPBU Pondok Ungu Permai', -6.176549630390152, 106.99949406087399, NULL, NULL, NULL),
(35, 43, 'Rumahku', -6.173563986265774, 106.9981449097395, NULL, NULL, NULL),
(36, 43, 'Tugu Tarian Harapan Indah', -6.19229823265106, 106.97568845003843, NULL, NULL, 't');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jam_kerja`
--

CREATE TABLE `tbl_jam_kerja` (
  `id_jam_absen_kerja` int(11) NOT NULL,
  `jam_absen_masuk` time NOT NULL,
  `jam_absen_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data untuk tabel `tbl_jam_kerja`
--

INSERT INTO `tbl_jam_kerja` (`id_jam_absen_kerja`, `jam_absen_masuk`, `jam_absen_pulang`) VALUES
(1, '08:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_total_kerja`
--

CREATE TABLE `tbl_total_kerja` (
  `id_total_kerja` int(11) NOT NULL,
  `tahun_kerja` year(4) NOT NULL,
  `bulan_kerja` int(2) NOT NULL,
  `total_hari_kerja` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data untuk tabel `tbl_total_kerja`
--

INSERT INTO `tbl_total_kerja` (`id_total_kerja`, `tahun_kerja`, `bulan_kerja`, `total_hari_kerja`) VALUES
(1, 2020, 1, 23),
(2, 2020, 2, 24),
(3, 2020, 3, 22),
(4, 2020, 4, 21),
(5, 2020, 5, 19),
(6, 2020, 6, 21),
(7, 2020, 7, 22),
(8, 2020, 8, 18),
(9, 2020, 9, 22),
(10, 2020, 10, 20),
(11, 2020, 11, 21),
(12, 2020, 12, 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `full_name` text NOT NULL,
  `status` int(1) NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `password`, `full_name`, `status`, `image`) VALUES
(19, 'superadmin', '$2y$10$xbg0gQHI/X0lyH6YROeNkOoIyjapHNyYwjMXcBV4ZNevd7OhofUMG', 'Zeffry Reynando', 1, '4d56537a87dd9e24ea640100c2c58bd0.jpg'),
(27, 'qwerty', '$2y$10$lhd1Y.OODmVRXkQoKK.mYuQnCiArp9MNDqSeF2jelvqyYL1fakTi6', 'qwerty', 1, 'aee4a3ce492ac8aa28d75989e9f53519.jpg'),
(34, 'reizakur', '$2y$10$kF4dv3lmIg1POTgf9rDGy.8iLqkdmPn.cSmHiH32.SkS2fc8sQc6W', 'rheiza', 1, ''),
(38, 'developer', '$2y$10$eBitRbDWVddsukSaXWXg4OE2vbweikrK1HcPh1swSCvvWAY1Gx/6e', 'Akun Developer', 1, ''),
(39, 'testing', '$2y$10$EcEbh39Zkt5RBKovAy1iHOTEp1kEpSobpaSSp0ybkKs9/kvHmN6na', 'testing', 1, ''),
(43, 'test', '$2y$10$f1f4IeQLSq7wqo7trs/W0eYUoA7LWqIhmBhySltgbXWRjnUL94MvG', 'Zeffry Reynando', 1, 'bff6a2d5fdf980d22de1daadeb0e5073.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indeks untuk tabel `tbl_destinasi`
--
ALTER TABLE `tbl_destinasi`
  ADD PRIMARY KEY (`id_destinasi`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `tbl_destinasi`
--
ALTER TABLE `tbl_destinasi`
  MODIFY `id_destinasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
