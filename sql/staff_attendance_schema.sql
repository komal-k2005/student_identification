-- Staff QR Codes Table
CREATE TABLE IF NOT EXISTS `staff_qr_codes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `staff_id` VARCHAR(4) NOT NULL UNIQUE,
  `qr_code_path` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`staff_id`) REFERENCES `users`(`staff_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Staff Assignments Table (subject and year assignments)
CREATE TABLE IF NOT EXISTS `staff_assignments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `staff_id` VARCHAR(4) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `academic_year` VARCHAR(10) NOT NULL,
  `department` VARCHAR(50) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`staff_id`) REFERENCES `users`(`staff_id`) ON DELETE CASCADE,
  INDEX `idx_staff_year` (`staff_id`, `academic_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Attendance Records Table
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `student_id` int(10) NOT NULL,
  `staff_id` VARCHAR(4) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `attendance_date` DATE NOT NULL,
  `status` ENUM('Present', 'Absent', 'Late') NOT NULL DEFAULT 'Present',
  `marked_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `card_activation`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`staff_id`) REFERENCES `users`(`staff_id`) ON DELETE CASCADE,
  INDEX `idx_student_date` (`student_id`, `attendance_date`),
  INDEX `idx_staff_date` (`staff_id`, `attendance_date`),
  UNIQUE KEY `unique_attendance` (`student_id`, `staff_id`, `subject`, `attendance_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update users table to add staff password field if needed
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `staff_password` VARCHAR(255) DEFAULT NULL AFTER `Password`,
ADD COLUMN IF NOT EXISTS `user_type` ENUM('admin', 'staff') DEFAULT 'staff' AFTER `staff_id`;



