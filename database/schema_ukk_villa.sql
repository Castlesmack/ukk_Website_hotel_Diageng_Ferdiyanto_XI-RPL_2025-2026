-- Schema for UKK_Villa booking system (MySQL compatible)
-- Created: 2026-01-03
-- Engine/Charset
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `room_type_availabilities`;
DROP TABLE IF EXISTS `villa_images`;
DROP TABLE IF EXISTS `villa_room_types`;
DROP TABLE IF EXISTS `villas`;
DROP TABLE IF EXISTS `feedbacks`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(191) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NULL,
    `role` ENUM('guest', 'receptionist', 'admin') NOT NULL DEFAULT 'guest',
    `email_verified_at` DATETIME NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `villas` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(191) NOT NULL,
    `slug` VARCHAR(191) NOT NULL UNIQUE,
    `capacity` INT UNSIGNED NOT NULL DEFAULT 1,
    `base_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `rooms_total` INT UNSIGNED NOT NULL DEFAULT 1,
    `description` TEXT NULL,
    `status` ENUM('active', 'inactive', 'maintenance') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `villa_room_types` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `villa_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `capacity` INT UNSIGNED NOT NULL DEFAULT 1,
    `price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `rooms_count` INT UNSIGNED NOT NULL DEFAULT 1,
    `amenities` TEXT NULL,
    `description` TEXT NULL,
    `status` ENUM('available', 'unavailable') NOT NULL DEFAULT 'available',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_vrt_villa` (`villa_id`),
    CONSTRAINT `fk_vrt_villa` FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `villa_images` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `villa_id` INT UNSIGNED NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `alt` VARCHAR(255) NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_vi_villa` (`villa_id`),
    CONSTRAINT `fk_vi_villa` FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `room_type_availabilities` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `villa_room_type_id` INT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `available_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `price_override` DECIMAL(12, 2) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_roomtype_date` (`villa_room_type_id`, `date`),
    KEY `idx_rav_vrt` (`villa_room_type_id`),
    CONSTRAINT `fk_rav_vrt` FOREIGN KEY (`villa_room_type_id`) REFERENCES `villa_room_types`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `bookings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `villa_id` INT UNSIGNED NOT NULL,
    `villa_room_type_id` INT UNSIGNED NOT NULL,
    `booking_code` VARCHAR(100) NOT NULL UNIQUE,
    `check_in_date` DATE NOT NULL,
    `check_out_date` DATE NOT NULL,
    `nights` INT UNSIGNED NOT NULL,
    `rooms_booked` INT UNSIGNED NOT NULL DEFAULT 1,
    `guests` INT UNSIGNED NOT NULL DEFAULT 1,
    `total_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM(
        'pending',
        'confirmed',
        'cancelled',
        'checked_in',
        'completed'
    ) NOT NULL DEFAULT 'pending',
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `verified_by` INT UNSIGNED NULL,
    `verified_at` DATETIME NULL,
    `payment_status` ENUM('unpaid', 'pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'unpaid',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_bk_user` (`user_id`),
    KEY `idx_bk_villa` (`villa_id`),
    KEY `idx_bk_vrt` (`villa_room_type_id`),
    CONSTRAINT `fk_bk_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bk_villa` FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bk_vrt` FOREIGN KEY (`villa_room_type_id`) REFERENCES `villa_room_types`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bk_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users`(`id`) ON DELETE
    SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `payments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_id` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(12, 2) NOT NULL,
    `payment_method` ENUM('bank_transfer', 'card', 'cash', 'other') NOT NULL DEFAULT 'bank_transfer',
    `transaction_id` VARCHAR(191) NULL,
    `proof_url` VARCHAR(255) NULL,
    `paid_at` DATETIME NULL,
    `status` ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_pay_booking` (`booking_id`),
    CONSTRAINT `fk_pay_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `invoices` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_id` INT UNSIGNED NOT NULL,
    `invoice_code` VARCHAR(100) NOT NULL UNIQUE,
    `amount` DECIMAL(12, 2) NOT NULL,
    `pdf_url` VARCHAR(255) NULL,
    `sent_via` ENUM('email', 'none') NOT NULL DEFAULT 'none',
    `sent_at` DATETIME NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_inv_booking` (`booking_id`),
    CONSTRAINT `fk_inv_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE `feedbacks` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `booking_id` INT UNSIGNED NULL,
    `responder_id` INT UNSIGNED NULL,
    `channel` ENUM('livechat', 'email', 'web') NOT NULL DEFAULT 'web',
    `message` TEXT NOT NULL,
    `response` TEXT NULL,
    `status` ENUM('open', 'answered', 'closed') NOT NULL DEFAULT 'open',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_fdb_user` (`user_id`),
    KEY `idx_fdb_booking` (`booking_id`),
    CONSTRAINT `fk_fdb_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_fdb_responder` FOREIGN KEY (`responder_id`) REFERENCES `users`(`id`) ON DELETE
    SET NULL,
        CONSTRAINT `fk_fdb_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE
    SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- Useful views / helper queries (optional)
-- total income per villa per period
CREATE OR REPLACE VIEW `vw_income_per_villa` AS
SELECT v.id AS villa_id,
    v.name AS villa_name,
    SUM(p.amount) AS income
FROM villas v
    JOIN villa_room_types vrt ON vrt.villa_id = v.id
    JOIN bookings b ON b.villa_room_type_id = vrt.id
    JOIN payments p ON p.booking_id = b.id
    AND p.status = 'completed'
GROUP BY v.id,
    v.name;
-- End of file