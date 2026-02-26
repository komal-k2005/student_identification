-- =============================================
-- Student Identification & Management System
-- MySQL Database Schema
-- =============================================
-- Import: Create database first, then run this file.
-- Example: mysql -u root -p student_identification_system < database.sql
-- Or use phpMyAdmin: create DB, then Import this file.
-- =============================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS student_marks;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS staff;

SET FOREIGN_KEY_CHECKS = 1;

-- Staff accounts (admin creates these)
CREATE TABLE staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    staff_name VARCHAR(100) NOT NULL,
    subject VARCHAR(100) DEFAULT NULL,
    education VARCHAR(100) DEFAULT NULL,
    department VARCHAR(100) DEFAULT NULL,
    qr_code_data VARCHAR(500) DEFAULT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Students (staff creates these)
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    photo_path VARCHAR(255) DEFAULT NULL,
    full_name VARCHAR(100) NOT NULL,
    father_name VARCHAR(100) DEFAULT NULL,
    mother_name VARCHAR(100) DEFAULT NULL,
    aadhaar_number VARCHAR(12) DEFAULT NULL UNIQUE,
    roll_number VARCHAR(20) NOT NULL UNIQUE,
    enrollment_number VARCHAR(20) NOT NULL UNIQUE,
    branch VARCHAR(50) DEFAULT NULL,
    qr_code_data VARCHAR(500) DEFAULT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Student marks (10th, 12th, sem 1–8) – one row per student
CREATE TABLE student_marks (
    mark_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    marks_10th DECIMAL(5,2) DEFAULT NULL,
    marks_12th DECIMAL(5,2) DEFAULT NULL,
    marks_semester_1 DECIMAL(5,2) DEFAULT NULL,
    marks_semester_2 DECIMAL(5,2) DEFAULT NULL,
    marks_semester_3 DECIMAL(5,2) DEFAULT NULL,
    marks_semester_4 DECIMAL(5,2) DEFAULT NULL,
    marks_semester_5 DECIMAL(5,2) DEFAULT NULL,
    marks_semester_6 DECIMAL(5,2) DEFAULT NULL,
    marks_semester_7 DECIMAL(5,2) DEFAULT NULL,
    marks_semester_8 DECIMAL(5,2) DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY uk_student_marks (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Attendance records
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    staff_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    attendance_date DATE NOT NULL,
    attendance_time TIME NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sessions (optional, for auth tracking)
CREATE TABLE sessions (
    session_id VARCHAR(255) PRIMARY KEY,
    user_id INT NOT NULL,
    user_type ENUM('staff','student') NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
