-- =====================================================
-- SQL Migration untuk Non Project Activities (NPA)
-- Modul pencatatan aktivitas kerja di luar project
-- =====================================================

-- Tabel utama aktivitas non-project
CREATE TABLE IF NOT EXISTS `npa_activities` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `activity_date` DATE NOT NULL,
    `activity_description` TEXT NOT NULL,
    `manhour` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
    `remarks` TEXT NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_activity_date` (`activity_date`),
    INDEX `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel attachment untuk aktivitas
CREATE TABLE IF NOT EXISTS `npa_attachments` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `activity_id` INT(11) NOT NULL,
    `file_name_original` VARCHAR(255) NOT NULL,
    `file_name_hash` VARCHAR(255) NOT NULL,
    `catatan` TEXT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_activity_id` (`activity_id`),
    CONSTRAINT `fk_npa_attachments_activity` FOREIGN KEY (`activity_id`) REFERENCES `npa_activities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
