/* 
 * ======================================================
 *                    DATABASE YARATISH                  
 * ======================================================
 */

-- 1. Avvalgi mavjud database bo‘lsa, uni o‘chirish
DROP DATABASE IF EXISTS `foyda_zarar`;

-- 2. Yangi database yaratish
CREATE DATABASE `foyda_zarar`;

-- 3. Databasa tanlash
USE `foyda_zarar`;

-- 4. Foydalanuvchilar jadvali yaratish
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'seller', 'manager') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `purchase_price` DECIMAL(10,2) NOT NULL, -- Olingan narxi
    `sale_price` DECIMAL(10,2) NOT NULL,     -- Sotiladigan narxi
    `quantity` INT DEFAULT 0,                -- Ombordagi soni
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Test foydalanuvchi qo‘shish
INSERT INTO `users` (`name`, `email`, `password`) VALUES
(
    'Azatbek',
    'aermalaev07@gmail.com',
    '$2y$10$vzdlXgvQgb0Y0i2BBVOape64X3Ffo2ghMWnprXkuTyB77orLspfW.'
)
