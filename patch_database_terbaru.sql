items-- PATCH DATABASE INVENTARIS GKI
-- Jalankan ini untuk menambah fitur peminjaman publik tanpa menghapus data barang

USE `inventaris_gki`;

-- 1. Update Tabel Orders (Peminjaman)
ALTER TABLE `orders` 
ADD COLUMN `nama_peminjam` VARCHAR(255) NULL AFTER `id_barang`,
ADD COLUMN `kontak_peminjam` VARCHAR(255) NULL AFTER `nama_peminjam`,
ADD COLUMN `status` ENUM('Pending', 'Disetujui', 'Ditolak') DEFAULT 'Pending' AFTER `qty`,
ADD COLUMN `approved_by` BIGINT UNSIGNED NULL AFTER `status`,
ADD COLUMN `reject_reason` TEXT NULL AFTER `approved_by`,
MODIFY COLUMN `user_id` BIGINT UNSIGNED NULL;

-- 2. Update Tabel Borrowings (Rents)
ALTER TABLE `borrowings`
ADD COLUMN `kondisi_kembali` TEXT NULL,
ADD COLUMN `catatan_kembali` TEXT NULL,
ADD COLUMN `qty_kembali` INT NULL;

-- 3. Tambah Kategori Baru
INSERT INTO `categories` (`name`, `created_at`, `updated_at`) VALUES 
('Alat Multimedia', NOW(), NOW()),
('Peralatan Dapur', NOW(), NOW());

-- 4. Tabel Role & Permissions (Spatie)
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
);

-- 5. Setup Admin Role
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', NOW(), NOW()),
(2, 'Admin', 'web', NOW(), NOW());

-- Hubungkan user admin kamu (ID 1) ke Super Admin
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);
