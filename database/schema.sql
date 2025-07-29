CREATE DATABASE IF NOT EXISTS `nama_database_anda`; -- Ganti dengan nama database Anda

USE `nama_database_anda`; -- Ganti dengan nama database Anda

CREATE TABLE IF NOT EXISTS `blogs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `subdomain_name` VARCHAR(255) UNIQUE NOT NULL, -- Nama subdomain unik (misal: 'nama-blog')
    `blog_title` VARCHAR(255) NOT NULL,
    `blog_content` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contoh Data
INSERT INTO `blogs` (`subdomain_name`, `blog_title`, `blog_content`) VALUES
('contohblog', 'Ini Adalah Blog Contoh Saya', 'Selamat datang di blog contoh! Ini adalah konten awal blog Anda.'),
('myjourney', 'My Daily Journey', 'Berbagi cerita perjalanan dan pengalaman hidup setiap hari.'),
('resepmasakan', 'Dapur Kreasi: Resep Masakan Nusantara', 'Temukan berbagai resep masakan tradisional dan modern di sini!');