SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `users`;
INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `course`, `role`, `created_at`) VALUES
('1', 'System Admin', 'admin', '$2y$10$910LIijZl22tXV/CYVTbX.1de8JXaB3/aybVlQgOjYPZNvYjmmCI.', NULL, 'sub_admin', '2026-05-28 10:17:48'),
('2', 'Juan Dela Cruz', 'juan@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSIT', 'student', '2026-05-28 10:17:48'),
('3', 'Maria Clara', 'maria_clara@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSED', 'student', '2026-05-28 10:17:48'),
('4', 'Jose Rizal', 'jose@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSBA', 'student', '2026-05-28 10:17:48'),
('5', 'Andres Bonifacio', 'andres@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSCRIM', 'student', '2026-05-28 10:17:48'),
('6', 'Melchora Aquino', 'melchora@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSN', 'student', '2026-05-28 10:17:48'),
('7', 'Emilio Aguinaldo', 'emilio@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSCE', 'student', '2026-05-28 10:17:48'),
('8', 'Apolinario Mabini', 'apolinario@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSIT', 'student', '2026-05-28 10:17:48'),
('9', 'Antonio Luna', 'antonio@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSBA', 'student', '2026-05-28 10:17:48'),
('10', 'Gabriela Silang', 'gabriela@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSED', 'student', '2026-05-28 10:17:48'),
('11', 'Lapu-Lapu', 'lapulapu@adssu.edu.ph', '$2y$10$UiX7.utlQHwjfKwmKc2cR.tCCMItkOc4jkCruqquAIQcz3hh5sZMK', 'BSCRIM', 'student', '2026-05-28 10:17:48'),
('12', 'art', 'art@adssu.edu.ph', '$2y$10$7fKi7JVN5SVfLdAwqrdZle6oP67VgTbk7LEbyCts1EUytRaBez.2K', 'Comscie', 'student', '2026-05-29 16:23:52'),
('13', 'Super Admin', 'admin@admin.com', 'admin', NULL, 'sub_admin', '2026-05-31 22:24:29'),
('15', 'Alice Ramos', 'alice@example.com', 'pass123', 'BSCS', 'student', '2026-06-01 13:29:03'),
('16', 'Bob Tan', 'bob@example.com', 'pass123', 'BSME', 'student', '2026-06-01 13:29:03'),
('17', 'Carla Diaz', 'carla@example.com', 'pass123', 'BSBA', 'student', '2026-06-01 13:29:03'),
('18', 'David Cruz', 'david@example.com', 'pass123', 'BFA', 'student', '2026-06-01 13:29:03'),
('19', 'Elena Santos', 'elena@example.com', 'pass123', 'BSES', 'student', '2026-06-01 13:29:03'),
('20', 'art', 'art20@adssu.edu.ph', '$2y$10$5rmWy7rB1fbeAMRD67BEJe.znYsa0Z7hzTNG8knJzwNDiAtJadWui', 'bsit', 'student', '2026-06-01 13:30:52'),
('44', 'michael', 'badiang02@adssu.edu.ph', '$2y$10$ptBjPoFK4E6tTcwOSGDUXu6QOmIVvdVcmKgokxWk9m2qQHncZDhTC', 'bsit', 'student', '2026-06-03 12:08:55');

TRUNCATE TABLE `organizations`;
INSERT INTO `organizations` (`id`, `name`, `description`, `created_at`) VALUES
('1', 'College of Computing Studies', 'Focuses on IT and CS excellence.', '2026-05-28 10:17:48'),
('2', 'Arts and Sciences Club', 'Promoting culture and holistic science.', '2026-05-28 10:17:48'),
('3', 'Business Administration Society', 'Future business leaders and entrepreneurs.', '2026-05-28 10:17:48'),
('4', 'Agriculture Students Guild', 'Advancing modern farming techniques.', '2026-05-28 10:17:48'),
('5', 'Future Educators League', 'Preparing the next generation of teachers.', '2026-05-28 10:17:48'),
('6', 'Nursing Student Body', 'Dedicated to healthcare and compassion.', '2026-05-28 10:17:48'),
('7', 'Criminology Vanguard', 'Law enforcement and public safety.', '2026-05-28 10:17:48'),
('8', 'Engineering Innovators', 'Designing the future through tech.', '2026-05-28 10:17:48'),
('9', 'ADSSU Chorale', 'The official singing ambassadors.', '2026-05-28 10:17:48'),
('10', 'Campus Journalists Guild', 'The voice of the students.', '2026-05-28 10:17:48'),
('11', 'Computer Science Society', 'Coding and tech innovation hub', '2024-03-15 09:30:00'),
('12', 'Engineering Guild', 'Engineering projects and competitions', '2024-08-10 14:15:00'),
('13', 'Business Club', 'Entrepreneurship and finance', '2025-01-20 10:00:00'),
('14', 'Arts & Culture Circle', 'Creative arts and cultural events', '2025-05-05 16:45:00'),
('15', 'Environmental Warriors', 'Sustainability and green initiatives', '2025-09-12 08:00:00');

TRUNCATE TABLE `applications`;
INSERT INTO `applications` (`id`, `student_id`, `org_id`, `academic_year`, `status`, `applied_at`) VALUES
('1', '2', '1', '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
('2', '3', '2', '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
('3', '4', '3', '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
('4', '5', '4', '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
('5', '6', '5', '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
('6', '7', '6', '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
('7', '8', '7', '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
('8', '9', '8', '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
('9', '10', '9', '2024-2025', 'APPROVED', '2026-05-28 10:17:48'),
('10', '11', '10', '2025-2026', 'APPROVED', '2026-05-28 10:17:48'),
('11', '12', '2', '2025-2026', 'PENDING', '2026-05-29 16:25:14'),
('13', '15', '1', '2025-2026', 'APPROVED', '2026-06-01 13:29:03'),
('14', '16', '2', '2025-2026', 'APPROVED', '2026-06-01 13:29:03'),
('15', '17', '3', '2025-2026', 'PENDING', '2026-06-01 13:29:03'),
('16', '18', '4', '2025-2026', 'APPROVED', '2026-06-01 13:29:03'),
('17', '19', '5', '2025-2026', 'REJECTED', '2026-06-01 13:29:03'),
('18', '20', '1', '2025-2026', 'REJECTED', '2026-06-01 13:31:15'),
('19', '20', '1', '2025-2026', 'APPROVED', '2026-06-01 15:04:26'),
('20', '20', '2', '2025-2026', 'REJECTED', '2026-06-01 20:58:31'),
('21', '2', '1', '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
('22', '3', '2', '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
('23', '4', '3', '2023-2024', 'PENDING', '2026-06-03 09:04:45'),
('24', '5', '4', '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
('25', '6', '5', '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
('26', '7', '6', '2023-2024', 'PENDING', '2026-06-03 09:04:45'),
('27', '8', '7', '2023-2024', 'REJECTED', '2026-06-03 09:04:45'),
('28', '9', '8', '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
('29', '10', '9', '2023-2024', 'APPROVED', '2026-06-03 09:04:45'),
('30', '11', '10', '2023-2024', 'PENDING', '2026-06-03 09:04:45'),
('31', '3', '4', '2024-2025', 'APPROVED', '2026-06-03 09:04:45'),
('32', '5', '6', '2024-2025', 'PENDING', '2026-06-03 09:04:45'),
('33', '7', '8', '2024-2025', 'APPROVED', '2026-06-03 09:04:45'),
('34', '9', '10', '2024-2025', 'REJECTED', '2026-06-03 09:04:45'),
('35', '11', '1', '2024-2025', 'APPROVED', '2026-06-03 09:04:45'),
('36', '2', '3', '2025-2026', 'APPROVED', '2026-06-03 09:04:45'),
('37', '4', '5', '2025-2026', 'PENDING', '2026-06-03 09:04:45'),
('38', '6', '7', '2025-2026', 'APPROVED', '2026-06-03 09:04:45'),
('39', '8', '9', '2025-2026', 'REJECTED', '2026-06-03 09:04:45'),
('40', '10', '1', '2025-2026', 'APPROVED', '2026-06-03 09:04:45'),
('41', '15', '6', '2025-2026', 'PENDING', '2026-06-03 09:04:45'),
('42', '2', '11', '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
('43', '3', '12', '2026-2027', 'PENDING', '2026-06-03 09:04:45'),
('44', '4', '13', '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
('45', '5', '14', '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
('46', '6', '15', '2026-2027', 'REJECTED', '2026-06-03 09:04:45'),
('47', '7', '11', '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
('48', '8', '12', '2026-2027', 'PENDING', '2026-06-03 09:04:45'),
('49', '9', '13', '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
('50', '10', '14', '2026-2027', 'APPROVED', '2026-06-03 09:04:45'),
('51', '11', '15', '2026-2027', 'PENDING', '2026-06-03 09:04:45');

TRUNCATE TABLE `announcements`;
INSERT INTO `announcements` (`id`, `title`, `content`, `badge`, `org_id`, `created_at`) VALUES
('1', 'Welcome to ADSSU Student Orgs!', 'Explore and join our amazing campus organizations today.', 'INFO', NULL, '2026-05-28 10:17:48'),
('2', 'gwapo', 'ayu.....', 'EVENT', NULL, '2026-06-03 12:10:23');

SET FOREIGN_KEY_CHECKS = 1;

-- Fix plain text password for user 13 (if exists)
UPDATE users SET password = '$2y$10$vVQ3.lMDRYjgF9mrRRhrD.esFZYmjR9L6vO/001ZBY6iyHSJxINUy' WHERE email = 'admin@admin.com' AND LENGTH(password) < 20;
