-- =====================================================
-- SQL Update untuk fitur Project Management sesuai konsep Excel
-- Tanggal: 23-07-2026 (Revisi v2)
-- =====================================================

-- =====================================================
-- 1. TABEL pm_projects - Update kolom
-- =====================================================
ALTER TABLE `pm_projects`
ADD COLUMN `total_modules` INT(5) NOT NULL DEFAULT 0 COMMENT 'Jumlah modul' AFTER `target_mh_programmer`,
ADD COLUMN `finished_modules` INT(5) NOT NULL DEFAULT 0 COMMENT 'Jumlah modul yang finish' AFTER `total_modules`;

-- Hapus kolom ba_id, programmer_id, qa_id lama (single user) jika ada
-- Akan diganti dengan tabel relasi many-to-many
-- ALTER TABLE `pm_projects` DROP COLUMN `ba_id`, DROP COLUMN `programmer_id`, DROP COLUMN `qa_id`;
-- NOTE: Jangan drop dulu, kita tambah tabel baru untuk multi-user

-- =====================================================
-- 2. TABEL pm_project_roles - Relasi multi user per role (BA & Programmer bisa > 1)
-- =====================================================
CREATE TABLE IF NOT EXISTS `pm_project_roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) NOT NULL,
  `role` ENUM('ba','programmer','qa','pm') NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_project_role` (`project_id`, `role`),
  KEY `idx_user_role` (`user_id`, `role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 3. TABEL pm_modules - Modul per project
-- =====================================================
CREATE TABLE IF NOT EXISTS `pm_modules` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) UNSIGNED NOT NULL,
  `module_name` VARCHAR(255) NOT NULL,
  `module_order` INT(5) NOT NULL DEFAULT 1,
  `status` ENUM('progress','finish') NOT NULL DEFAULT 'progress',
  `finished_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 4. TABEL pm_module_tahapan - 12 Tahapan FIX per modul
--    Ini adalah tahapan berurutan (sequential, tidak bisa bypass)
--    Setiap tahapan punya PIC yang bisa dipilih
-- =====================================================
CREATE TABLE IF NOT EXISTS `pm_module_tahapan` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` INT(11) UNSIGNED NOT NULL,
  `project_id` INT(11) UNSIGNED NOT NULL,
  `tahapan_order` INT(3) NOT NULL DEFAULT 1 COMMENT 'Urutan tahapan 1-12',
  `tahapan_name` VARCHAR(255) NOT NULL,
  `tahapan_role` VARCHAR(50) NOT NULL COMMENT 'Role default: ba, programmer, qa, others',
  `pic_user_id` INT(11) NULL DEFAULT NULL COMMENT 'PIC yang dipilih untuk tahapan ini',
  `plan_manhour` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `plan_due_date` DATE NULL DEFAULT NULL,
  `actual_manhour` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `actual_finish_date` DATE NULL DEFAULT NULL,
  `status` ENUM('locked','active','finish') NOT NULL DEFAULT 'locked' COMMENT 'locked=belum bisa diisi, active=sedang dikerjakan, finish=selesai',
  `is_plan_locked` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=plan tidak bisa diubah setelah save',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_module_id` (`module_id`),
  KEY `idx_project_id` (`project_id`),
  KEY `idx_order` (`module_id`, `tahapan_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 5. TABEL pm_tahapan_tasks - Task/pekerjaan yang diisi per tahapan
--    Ini adalah catatan pekerjaan harian (bisa banyak per tahapan)
-- =====================================================
CREATE TABLE IF NOT EXISTS `pm_tahapan_tasks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahapan_id` INT(11) UNSIGNED NOT NULL,
  `module_id` INT(11) UNSIGNED NOT NULL,
  `project_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) NOT NULL,
  `task_date` DATE NOT NULL,
  `task_description` TEXT NOT NULL,
  `manhour` DECIMAL(6,2) NOT NULL DEFAULT 0.00,
  `remarks` TEXT NULL DEFAULT NULL,
  `file_name_original` VARCHAR(255) NULL DEFAULT NULL,
  `file_name_hash` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tahapan_id` (`tahapan_id`),
  KEY `idx_module_id` (`module_id`),
  KEY `idx_project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =====================================================
-- 6. TABEL pm_master_tahapan - Master tahapan (tidak lagi hardcode)
--    Bisa di-manage via CRUD
-- =====================================================
CREATE TABLE IF NOT EXISTS `pm_master_tahapan` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahapan_order` INT(3) NOT NULL DEFAULT 1,
  `tahapan_name` VARCHAR(255) NOT NULL,
  `default_role` VARCHAR(50) NOT NULL COMMENT 'ba, programmer, qa, others',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`tahapan_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default data
INSERT INTO `pm_master_tahapan` (`tahapan_order`, `tahapan_name`, `default_role`, `is_active`, `created_at`) VALUES
(1, 'Konsep (harus plus test case)', 'ba', 1, NOW()),
(2, 'Approval konsep oleh client', 'ba', 1, NOW()),
(3, 'Coding, sampai trial dan FAT coding', 'programmer', 1, NOW()),
(4, 'FAT Coding', 'qa', 1, NOW()),
(5, 'FAT--> FAT ditutup setelah perbaikan inputan dari client saat sosialisasi', 'ba', 1, NOW()),
(6, 'Perbaikan setelah FAT', 'programmer', 1, NOW()),
(7, 'Sosialisasi + IK penggunaan', 'ba', 1, NOW()),
(8, 'UAT, ditutup setelah dapat approval UAT', 'ba', 1, NOW()),
(9, 'Perbaikan setelah UAT', 'programmer', 1, NOW()),
(10, 'Go live, sampai go live approval', 'ba', 1, NOW()),
(11, 'Perbaikan setelah Go live approval', 'programmer', 1, NOW()),
(12, 'Others, meeting', 'others', 1, NOW());


-- =====================================================
-- 7. UPDATE STRUKTUR: Versioning & Rollback & Meeting
-- =====================================================

-- Tambah kolom current_version di pm_module_tahapan
ALTER TABLE `pm_module_tahapan` 
ADD COLUMN `current_version` INT(3) NOT NULL DEFAULT 1 COMMENT 'Versi saat ini, increment tiap rollback' AFTER `is_plan_locked`;

-- Tambah kolom version di pm_tahapan_tasks
ALTER TABLE `pm_tahapan_tasks`
ADD COLUMN `version` INT(3) NOT NULL DEFAULT 1 COMMENT 'Versi tahapan saat task ini dibuat' AFTER `tahapan_id`;

-- Tabel log rollback
CREATE TABLE IF NOT EXISTS `pm_rollback_history` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` INT(11) UNSIGNED NOT NULL,
  `project_id` INT(11) UNSIGNED NOT NULL,
  `rolled_back_from_order` INT(3) NOT NULL COMMENT 'Dari step berapa (step yang sedang active saat rollback)',
  `rolled_back_to_order` INT(3) NOT NULL COMMENT 'Tahapan order yang di-rollback ke',
  `reason` TEXT NOT NULL,
  `rolled_back_by` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_module_id` (`module_id`),
  KEY `idx_project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel meeting/others per modul (terpisah dari tahapan sequential)
CREATE TABLE IF NOT EXISTS `pm_module_meetings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` INT(11) UNSIGNED NOT NULL,
  `project_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) NOT NULL,
  `task_date` DATE NOT NULL,
  `task_description` TEXT NOT NULL,
  `manhour` DECIMAL(6,2) NOT NULL DEFAULT 0.00,
  `remarks` TEXT NULL DEFAULT NULL,
  `file_name_original` VARCHAR(255) NULL DEFAULT NULL,
  `file_name_hash` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_module_id` (`module_id`),
  KEY `idx_project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Soft delete kolom untuk pm_modules
ALTER TABLE `pm_modules` ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;
