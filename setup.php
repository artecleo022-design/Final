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
        (10, 'Campus Journalists Guild', 'The voice of the students.')
    ");
    
    // Announcements
    $pdo->exec("INSERT IGNORE INTO `announcements` (`id`, `title`, `content`, `badge`, `org_id`) VALUES 
        (1, 'Welcome to ADSSU Student Orgs!', 'Explore and join our amazing campus organizations today.', 'INFO', NULL)
    ");

    echo "<h3>Database setup completed successfully.</h3>";
    echo "<a href='index.php'>Go to Homepage</a>";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
