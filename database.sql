-- ============================================================
--  ADSSU Student Organizations System – Database Setup
--  Database Name: adssu_student_orgs
-- ============================================================

DROP DATABASE IF EXISTS `adssu_student_orgs`;
CREATE DATABASE IF NOT EXISTS `adssu_student_orgs`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `adssu_student_orgs`;

-- ── USERS TABLE ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `course` VARCHAR(100) DEFAULT NULL,
    `role` ENUM('student', 'sub_admin') DEFAULT 'student',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── ORGANIZATIONS TABLE ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS `organizations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── APPLICATIONS TABLE ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS `applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `org_id` INT NOT NULL,
    `academic_year` VARCHAR(20) DEFAULT '2025-2026',
    `status` ENUM('PENDING', 'APPROVED', 'REJECTED') DEFAULT 'PENDING',
    `applied_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`org_id`) REFERENCES `organizations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── ANNOUNCEMENTS TABLE ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS `announcements` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `badge` VARCHAR(50) DEFAULT 'GENERAL',
    `org_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`org_id`) REFERENCES `organizations`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ── LOGIN LOGS TABLE (For VB.NET Monitoring) ─────────────────
CREATE TABLE IF NOT EXISTS `login_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `login_time` DATETIME NOT NULL,
    `logout_time` DATETIME DEFAULT NULL,
    `status` ENUM('ONLINE', 'OFFLINE') DEFAULT 'ONLINE',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ============================================================
--  SEED DATA (Admin, 10 Orgs, 10 Students, Applications)
-- ============================================================

-- Insert Default Sub Admin (Password: admin123)
INSERT IGNORE INTO `users` (`id`, `fullname`, `email`, `password`, `role`) VALUES 
(1, 'System Admin', 'admin', '$2y$10$7p5nElR7OZ3cG7U/0QTrW.Lp82z8GfmyBppJeHPkZUzJ2BfSXSmSm', 'sub_admin');

-- Insert 10 Students (Password: student123)
-- Hash generated via password_hash('student123', PASSWORD_DEFAULT)
INSERT IGNORE INTO `users` (`id`, `fullname`, `email`, `password`, `course`, `role`) VALUES 
(2, 'Juan Dela Cruz', 'juan@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSIT', 'student'),
(3, 'Maria Clara', 'maria_clara@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSED', 'student'),
(4, 'Jose Rizal', 'jose@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSBA', 'student'),
(5, 'Andres Bonifacio', 'andres@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSCRIM', 'student'),
(6, 'Melchora Aquino', 'melchora@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSN', 'student'),
(7, 'Emilio Aguinaldo', 'emilio@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSCE', 'student'),
(8, 'Apolinario Mabini', 'apolinario@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSIT', 'student'),
(9, 'Antonio Luna', 'antonio@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSBA', 'student'),
(10, 'Gabriela Silang', 'gabriela@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSED', 'student'),
(11, 'Lapu-Lapu', 'lapulapu@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSCRIM', 'student');

-- Insert 10 Organizations
INSERT IGNORE INTO `organizations` (`id`, `name`, `description`) VALUES 
(1, 'College of Computing Studies', 'Focuses on IT and CS excellence.'),
(2, 'Arts and Sciences Club', 'Promoting culture and holistic science.'),
(3, 'Business Administration Society', 'Future business leaders and entrepreneurs.'),
(4, 'Agriculture Students Guild', 'Advancing modern farming techniques.'),
(5, 'Future Educators League', 'Preparing the next generation of teachers.'),
(6, 'Nursing Student Body', 'Dedicated to healthcare and compassion.'),
(7, 'Criminology Vanguard', 'Law enforcement and public safety.'),
(8, 'Engineering Innovators', 'Designing the future through tech.'),
(9, 'ADSSU Chorale', 'The official singing ambassadors.'),
(10, 'Campus Journalists Guild', 'The voice of the students.');

-- Insert Applications (1 Student per Org, status APPROVED, varying Academic Years)
INSERT IGNORE INTO `applications` (`id`, `student_id`, `org_id`, `academic_year`, `status`) VALUES 
(1, 2, 1, '2024-2025', 'APPROVED'),
(2, 3, 2, '2025-2026', 'APPROVED'),
(3, 4, 3, '2024-2025', 'APPROVED'),
(4, 5, 4, '2025-2026', 'APPROVED'),
(5, 6, 5, '2024-2025', 'APPROVED'),
(6, 7, 6, '2025-2026', 'APPROVED'),
(7, 8, 7, '2024-2025', 'APPROVED'),
(8, 9, 8, '2025-2026', 'APPROVED'),
(9, 10, 9, '2024-2025', 'APPROVED'),
(10, 11, 10, '2025-2026', 'APPROVED');

-- Insert Default Announcement
INSERT IGNORE INTO `announcements` (`id`, `title`, `content`, `badge`) VALUES 
(1, 'Welcome to ADSSU Student Orgs!', 'Explore and join our amazing campus organizations today.', 'INFO');
