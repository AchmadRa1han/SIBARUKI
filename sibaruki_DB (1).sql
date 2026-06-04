-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 03, 2026 at 10:14 AM
-- Server version: 8.0.46
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sibaruki_DB`
--

-- --------------------------------------------------------

--
-- Table structure for table `arsinum`
--

CREATE TABLE `arsinum` (
  `id` int UNSIGNED NOT NULL,
  `jenis_pekerjaan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `volume` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pelaksana` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anggaran` decimal(20,2) DEFAULT NULL,
  `sumber_dana` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `koordinat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `foto_after` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aset_tanah`
--

CREATE TABLE `aset_tanah` (
  `id` int UNSIGNED NOT NULL,
  `no_sertifikat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_pemilik` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_m2` decimal(15,2) DEFAULT NULL,
  `lokasi` text COLLATE utf8mb4_general_ci,
  `desa_kelurahan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tgl_terbit` date DEFAULT NULL,
  `nomor_hak` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `peruntukan` text COLLATE utf8mb4_general_ci,
  `koordinat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nilai_aset` decimal(20,2) DEFAULT NULL,
  `status_tanah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backlog_data`
--

CREATE TABLE `backlog_data` (
  `id` int UNSIGNED NOT NULL,
  `desa_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_backlog` int NOT NULL DEFAULT '0',
  `tahun` varchar(4) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '2026',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kode_desa`
--

CREATE TABLE `kode_desa` (
  `desa_id` bigint NOT NULL,
  `kecamatan_id` int NOT NULL,
  `desa_nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `wkt` longtext COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kode_kecamatan`
--

CREATE TABLE `kode_kecamatan` (
  `kecamatan_id` int NOT NULL,
  `kecamatan_nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `wkt` longtext COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int UNSIGNED NOT NULL,
  `permission_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perumahan_formal`
--

CREATE TABLE `perumahan_formal` (
  `id` int UNSIGNED NOT NULL,
  `nama_perumahan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_kawasan_ha` decimal(10,2) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `pengembang` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_pembangunan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wkt` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pisew`
--

CREATE TABLE `pisew` (
  `id` int UNSIGNED NOT NULL,
  `jenis_pekerjaan` text COLLATE utf8mb4_general_ci,
  `lokasi_desa` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pelaksana` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anggaran` decimal(20,2) DEFAULT NULL,
  `sumber_dana` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `koordinat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `foto_before` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_after` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `psu_jalan`
--

CREATE TABLE `psu_jalan` (
  `id` int UNSIGNED NOT NULL,
  `wkt` text COLLATE utf8mb4_general_ci,
  `tahun` int DEFAULT NULL,
  `panjang_luas` double DEFAULT NULL,
  `nama_jalan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jalan` decimal(10,2) DEFAULT NULL,
  `foto_before` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_after` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ref_master`
--

CREATE TABLE `ref_master` (
  `id` int NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_pilihan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `role_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `scope` enum('global','local') COLLATE utf8mb4_general_ci DEFAULT 'global',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `permission_id` int UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rtlh_bansos`
--

CREATE TABLE `rtlh_bansos` (
  `id` int UNSIGNED NOT NULL,
  `id_survei` int UNSIGNED DEFAULT NULL,
  `nik` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_penerima` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `desa` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_anggaran` year NOT NULL,
  `sumber_dana` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `lokasi_realisasi` geometry DEFAULT NULL,
  `foto_before` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_after` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_setelah_depan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_setelah_samping` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_setelah_dalam` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rtlh_history_perubahan`
--

CREATE TABLE `rtlh_history_perubahan` (
  `id` int UNSIGNED NOT NULL,
  `id_survei` int UNSIGNED NOT NULL,
  `nik` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_penerima` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `sumber_bantuan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_anggaran` year NOT NULL,
  `data_sebelum` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `data_sesudah` longtext COLLATE utf8mb4_general_ci,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rtlh_kondisi_rumah`
--

CREATE TABLE `rtlh_kondisi_rumah` (
  `No` int DEFAULT NULL,
  `id_survei` int NOT NULL,
  `st_pondasi` int DEFAULT NULL,
  `st_kolom` int DEFAULT NULL,
  `st_balok` int DEFAULT NULL,
  `st_sloof` int DEFAULT NULL,
  `st_rangka_atap` int DEFAULT NULL,
  `st_plafon` int DEFAULT NULL,
  `st_jendela` int DEFAULT NULL,
  `st_ventilasi` int DEFAULT NULL,
  `mat_lantai` int DEFAULT NULL,
  `st_lantai` int DEFAULT NULL,
  `mat_dinding` int DEFAULT NULL,
  `st_dinding` int DEFAULT NULL,
  `mat_atap` int DEFAULT NULL,
  `st_atap` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rtlh_penerima`
--

CREATE TABLE `rtlh_penerima` (
  `No` int DEFAULT NULL,
  `nik` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `no_kk` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_kepala_keluarga` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pendidikan_id` int DEFAULT NULL,
  `pekerjaan_id` int DEFAULT NULL,
  `penghasilan_per_bulan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_anggota_keluarga` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rtlh_rumah`
--

CREATE TABLE `rtlh_rumah` (
  `No` int DEFAULT NULL,
  `id_survei` int NOT NULL,
  `nik_pemilik` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desa_id` bigint DEFAULT NULL,
  `alamat_detail` text COLLATE utf8mb4_general_ci,
  `kepemilikan_rumah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `aset_rumah_di_lokasi_lain` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kepemilikan_tanah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sumber_penerangan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sumber_penerangan_detail` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bantuan_perumahan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_bantuan` enum('Belum Menerima','Sudah Menerima') COLLATE utf8mb4_general_ci DEFAULT 'Belum Menerima',
  `status_backlog` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desil_nasional` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_bansos` year DEFAULT NULL,
  `jenis_kawasan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fungsi_ruang` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_rumah_m2` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_lahan_m2` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_penghuni_jiwa` int DEFAULT NULL,
  `sumber_air_minum` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jarak_sam_ke_tpa_tinja` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kamar_mandi_dan_jamban` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_jamban_kloset` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_tpa_tinja` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_depan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_samping` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_belakang` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_dalam` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lokasi_koordinat` point DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys_logs`
--

CREATE TABLE `sys_logs` (
  `id` int UNSIGNED NOT NULL,
  `user` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Admin',
  `action` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `severity` enum('info','warning','critical') COLLATE utf8mb4_general_ci DEFAULT 'info',
  `table_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `details` text COLLATE utf8mb4_general_ci,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trash_data`
--

CREATE TABLE `trash_data` (
  `id` int UNSIGNED NOT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `entity_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `data_json` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_by` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_desa`
--

CREATE TABLE `user_desa` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `desa_id` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` enum('rtlh','kumuh') COLLATE utf8mb4_general_ci DEFAULT 'rtlh',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wilayah_kumuh`
--

CREATE TABLE `wilayah_kumuh` (
  `FID` int NOT NULL,
  `Provinsi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kode_Prov` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kab_Kota` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kode_Kab` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kode_Kec` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kelurahan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desa_id` bigint DEFAULT NULL,
  `Kode_Kel` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kode_RT_RW` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Luas_kumuh` double DEFAULT NULL,
  `skor_kumuh` double DEFAULT NULL,
  `Sumber_data` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Sk_Kumuh` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Kawasan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `WKT` longtext COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arsinum`
--
ALTER TABLE `arsinum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aset_tanah`
--
ALTER TABLE `aset_tanah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backlog_data`
--
ALTER TABLE `backlog_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `desa_id` (`desa_id`);

--
-- Indexes for table `kode_desa`
--
ALTER TABLE `kode_desa`
  ADD PRIMARY KEY (`desa_id`);

--
-- Indexes for table `kode_kecamatan`
--
ALTER TABLE `kode_kecamatan`
  ADD PRIMARY KEY (`kecamatan_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_name` (`permission_name`);

--
-- Indexes for table `perumahan_formal`
--
ALTER TABLE `perumahan_formal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pisew`
--
ALTER TABLE `pisew`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `psu_jalan`
--
ALTER TABLE `psu_jalan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_master`
--
ALTER TABLE `ref_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `rtlh_bansos`
--
ALTER TABLE `rtlh_bansos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rtlh_history_perubahan`
--
ALTER TABLE `rtlh_history_perubahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_survei` (`id_survei`);

--
-- Indexes for table `rtlh_kondisi_rumah`
--
ALTER TABLE `rtlh_kondisi_rumah`
  ADD PRIMARY KEY (`id_survei`);

--
-- Indexes for table `rtlh_penerima`
--
ALTER TABLE `rtlh_penerima`
  ADD PRIMARY KEY (`nik`),
  ADD KEY `fk_pendidikan` (`pendidikan_id`),
  ADD KEY `fk_pekerjaan` (`pekerjaan_id`);

--
-- Indexes for table `rtlh_rumah`
--
ALTER TABLE `rtlh_rumah`
  ADD PRIMARY KEY (`id_survei`),
  ADD KEY `fk_pemilik` (`nik_pemilik`),
  ADD KEY `fk_desa_peta` (`desa_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `sys_logs`
--
ALTER TABLE `sys_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trash_data`
--
ALTER TABLE `trash_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_desa`
--
ALTER TABLE `user_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_desa_user_id_foreign` (`user_id`);

--
-- Indexes for table `wilayah_kumuh`
--
ALTER TABLE `wilayah_kumuh`
  ADD PRIMARY KEY (`FID`),
  ADD KEY `fk_kumuh_desa` (`desa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arsinum`
--
ALTER TABLE `arsinum`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aset_tanah`
--
ALTER TABLE `aset_tanah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backlog_data`
--
ALTER TABLE `backlog_data`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perumahan_formal`
--
ALTER TABLE `perumahan_formal`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pisew`
--
ALTER TABLE `pisew`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `psu_jalan`
--
ALTER TABLE `psu_jalan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ref_master`
--
ALTER TABLE `ref_master`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rtlh_bansos`
--
ALTER TABLE `rtlh_bansos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rtlh_history_perubahan`
--
ALTER TABLE `rtlh_history_perubahan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rtlh_rumah`
--
ALTER TABLE `rtlh_rumah`
  MODIFY `id_survei` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_logs`
--
ALTER TABLE `sys_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trash_data`
--
ALTER TABLE `trash_data`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_desa`
--
ALTER TABLE `user_desa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wilayah_kumuh`
--
ALTER TABLE `wilayah_kumuh`
  MODIFY `FID` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rtlh_kondisi_rumah`
--
ALTER TABLE `rtlh_kondisi_rumah`
  ADD CONSTRAINT `fk_survei_fisik` FOREIGN KEY (`id_survei`) REFERENCES `rtlh_rumah` (`id_survei`);

--
-- Constraints for table `rtlh_penerima`
--
ALTER TABLE `rtlh_penerima`
  ADD CONSTRAINT `fk_pekerjaan` FOREIGN KEY (`pekerjaan_id`) REFERENCES `ref_master` (`id`),
  ADD CONSTRAINT `fk_pendidikan` FOREIGN KEY (`pendidikan_id`) REFERENCES `ref_master` (`id`);

--
-- Constraints for table `rtlh_rumah`
--
ALTER TABLE `rtlh_rumah`
  ADD CONSTRAINT `fk_desa_peta` FOREIGN KEY (`desa_id`) REFERENCES `kode_desa` (`desa_id`),
  ADD CONSTRAINT `fk_pemilik` FOREIGN KEY (`nik_pemilik`) REFERENCES `rtlh_penerima` (`nik`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_desa`
--
ALTER TABLE `user_desa`
  ADD CONSTRAINT `user_desa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wilayah_kumuh`
--
ALTER TABLE `wilayah_kumuh`
  ADD CONSTRAINT `fk_kumuh_desa` FOREIGN KEY (`desa_id`) REFERENCES `kode_desa` (`desa_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
