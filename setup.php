<?php
require_once 'db.php';

try {
    // Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `fullname` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `course` VARCHAR(100) DEFAULT NULL,
        `role` ENUM('student', 'sub_admin') DEFAULT 'student',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // Organizations table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `organizations` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `description` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // Applications table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `applications` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `student_id` INT NOT NULL,
        `org_id` INT NOT NULL,
        `academic_year` VARCHAR(20) DEFAULT '2025-2026',
        `status` ENUM('PENDING', 'APPROVED', 'REJECTED') DEFAULT 'PENDING',
        `applied_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`org_id`) REFERENCES `organizations`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    // Announcements table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `announcements` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL,
        `badge` VARCHAR(50) DEFAULT 'GENERAL',
        `org_id` INT DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`org_id`) REFERENCES `organizations`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB;");

    // Migration: Add org_id to existing announcements table if it's missing
    try {
        $pdo->exec("ALTER TABLE `announcements` ADD COLUMN `org_id` INT DEFAULT NULL AFTER `badge` ");
    } catch (PDOException $e) {
        // Column likely already exists
    }

    try {
        $pdo->exec("ALTER TABLE `announcements` ADD CONSTRAINT `fk_ann_org` FOREIGN KEY (`org_id`) REFERENCES `organizations`(`id`) ON DELETE SET NULL");
    } catch (PDOException $e) {
        // Constraint likely already exists
    }

    // Login Logs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `login_logs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `login_time` DATETIME NOT NULL,
        `logout_time` DATETIME DEFAULT NULL,
        `status` ENUM('ONLINE', 'OFFLINE') DEFAULT 'ONLINE',
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    // Seed Data
    // Admin
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO `users` (`id`, `fullname`, `email`, `password`, `role`) VALUES (1, 'System Admin', 'admin', '$adminPassword', 'sub_admin')");

    // Students
    $studentPass = password_hash('student123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO `users` (`id`, `fullname`, `email`, `password`, `course`, `role`) VALUES 
        (2, 'Juan Dela Cruz', 'juan@adssu.edu.ph', '$studentPass', 'BSIT', 'student'),
        (3, 'Maria Clara', 'maria_clara@adssu.edu.ph', '$studentPass', 'BSED', 'student'),
        (4, 'Jose Rizal', 'jose@adssu.edu.ph', '$studentPass', 'BSBA', 'student'),
        (5, 'Andres Bonifacio', 'andres@adssu.edu.ph', '$studentPass', 'BSCRIM', 'student'),
        (6, 'Melchora Aquino', 'melchora@adssu.edu.ph', '$studentPass', 'BSN', 'student'),
        (7, 'Emilio Aguinaldo', 'emilio@adssu.edu.ph', '$studentPass', 'BSCE', 'student'),
        (8, 'Apolinario Mabini', 'apolinario@adssu.edu.ph', '$studentPass', 'BSIT', 'student'),
        (9, 'Antonio Luna', 'antonio@adssu.edu.ph', '$studentPass', 'BSBA', 'student'),
        (10, 'Gabriela Silang', 'gabriela@adssu.edu.ph', '$studentPass', 'BSED', 'student'),
        (11, 'Lapu-Lapu', 'lapulapu@adssu.edu.ph', '$studentPass', 'BSCRIM', 'student'),
        (12, 'art', 'art@adssu.edu.ph', '$studentPass', 'Comscie', 'student'),
        (15, 'Alice Ramos', 'alice@example.com', '$studentPass', 'BSCS', 'student'),
        (16, 'Bob Tan', 'bob@example.com', '$studentPass', 'BSME', 'student'),
        (17, 'Carla Diaz', 'carla@example.com', '$studentPass', 'BSBA', 'student'),
        (18, 'David Cruz', 'david@example.com', '$studentPass', 'BFA', 'student'),
        (19, 'Elena Santos', 'elena@example.com', '$studentPass', 'BSES', 'student'),
        (20, 'art', 'art20@adssu.edu.ph', '$studentPass', 'bsit', 'student'),
        (44, 'michael', 'badiang02@adssu.edu.ph', '$studentPass', 'bsit', 'student')
    ");

    // Organizations
    $pdo->exec("INSERT IGNORE INTO `organizations` (`id`, `name`, `description`) VALUES 
        (1, 'College of Computing Studies', 'Focuses on IT and CS excellence.'),
        (2, 'Arts and Sciences Club', 'Promoting culture and holistic science.'),
        (3, 'Business Administration Society', 'Future business leaders and entrepreneurs.'),
        (4, 'Agriculture Students Guild', 'Advancing modern farming techniques.'),
        (5, 'Future Educators League', 'Preparing the next generation of teachers.'),
        (6, 'Nursing Student Body', 'Dedicated to healthcare and compassion.'),
        (7, 'Criminology Vanguard', 'Law enforcement and public safety.'),
        (8, 'Engineering Innovators', 'Designing the future through tech.'),
        (9, 'ADSSU Chorale', 'The official singing ambassadors.'),
        (10, 'Campus Journalists Guild', 'The voice of the students.'),
        (11, 'Computer Science Society', 'Coding and tech innovation hub'),
        (12, 'Engineering Guild', 'Engineering projects and competitions'),
        (13, 'Business Club', 'Entrepreneurship and finance'),
        (14, 'Arts & Culture Circle', 'Creative arts and cultural events'),
        (15, 'Environmental Warriors', 'Sustainability and green initiatives')
    ");
    
    // Announcements
    $pdo->exec("INSERT IGNORE INTO `announcements` (`id`, `title`, `content`, `badge`, `org_id`) VALUES 
        (1, 'Welcome to ADSSU Student Orgs!', 'Explore and join our amazing campus organizations today.', 'INFO', NULL),
        (2, 'Event Announcement', 'Stay tuned for upcoming events!', 'EVENT', NULL)
    ");

    // Applications
    $pdo->exec("INSERT IGNORE INTO `applications` (`id`, `student_id`, `org_id`, `academic_year`, `status`, `applied_at`) VALUES
        (1, 2, 1, '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
        (2, 3, 2, '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
        (3, 4, 3, '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
        (4, 5, 4, '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
        (5, 6, 5, '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
        (6, 7, 6, '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
        (7, 8, 7, '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
        (8, 9, 8, '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
        (9, 10, 9, '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
        (10, 11, 10, '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
        (11, 12, 2, '2025-2026', 'PENDING', '2026-05-29 16:25:14'),
        (13, 15, 1, '2025-2026', 'APPROVED', '2026-06-01 13:29:03'),
        (14, 16, 2, '2025-2026', 'APPROVED', '2026-06-01 13:29:03'),
        (15, 17, 3, '2025-2026', 'PENDING', '2026-06-01 13:29:03'),
        (16, 18, 4, '2025-2026', 'APPROVED', '2026-06-01 13:29:03'),
        (17, 19, 5, '2025-2026', 'REJECTED', '2026-06-01 13:29:03'),
        (18, 20, 1, '2025-2026', 'REJECTED', '2026-06-01 13:31:15'),
        (19, 20, 1, '2025-2026', 'APPROVED', '2026-06-01 15:04:26'),
        (20, 20, 2, '2025-2026', 'REJECTED', '2026-06-01 20:58:31'),
        (21, 2, 1, '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
        (22, 3, 2, '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
        (23, 4, 3, '2023-2024', 'PENDING', '2026-06-03 09:04:45'),
        (24, 5, 4, '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
        (25, 6, 5, '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
        (26, 7, 6, '2023-2024', 'PENDING', '2026-06-03 09:04:45'),
        (27, 8, 7, '2023-2024', 'REJECTED', '2026-06-03 09:04:45'),
        (28, 9, 8, '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
        (29, 10, 9, '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
        (30, 11, 10, '2023-2024', 'PENDING', '2026-06-03 09:04:45'),
        (31, 3, 4, '2024-2025', 'APPROVED', '2026-06-03 09:04:45'),
        (32, 5, 6, '2024-2025', 'PENDING', '2026-06-03 09:04:45'),
        (33, 7, 8, '2024-2025', 'APPROVED', '2026-06-03 09:04:45'),
        (34, 9, 10, '2024-2025', 'REJECTED', '2026-06-03 09:04:45'),
        (35, 11, 1, '2024-2025', 'APPROVED', '2026-06-03 09:04:45'),
        (36, 2, 3, '2025-2026', 'APPROVED', '2026-06-03 09:04:45'),
        (37, 4, 5, '2025-2026', 'PENDING', '2026-06-03 09:04:45'),
        (38, 6, 7, '2025-2026', 'APPROVED', '2026-06-03 09:04:45'),
        (39, 8, 9, '2025-2026', 'REJECTED', '2026-06-03 09:04:45'),
        (40, 10, 1, '2025-2026', 'APPROVED', '2026-06-03 09:04:45'),
        (41, 15, 6, '2025-2026', 'PENDING', '2026-06-03 09:04:45'),
        (42, 2, 11, '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
        (43, 3, 12, '2026-2027', 'PENDING', '2026-06-03 09:04:45'),
        (44, 4, 13, '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
        (45, 5, 14, '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
        (46, 6, 15, '2026-2027', 'REJECTED', '2026-06-03 09:04:45'),
        (47, 7, 11, '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
        (48, 8, 12, '2026-2027', 'PENDING', '2026-06-03 09:04:45'),
        (49, 9, 13, '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
        (50, 10, 14, '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
        (51, 11, 15, '2026-2027', 'PENDING', '2026-06-03 09:04:45')
    ");

    echo "<h3>Database setup completed successfully.</h3>";
    echo "<a href='index.php'>Go to Homepage</a>";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
