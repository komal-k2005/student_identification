-- Setup script to add staff passwords
-- Run this after creating the staff_attendance_schema.sql tables

-- Add staff_password column to users table if it doesn't exist
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `staff_password` VARCHAR(255) DEFAULT NULL AFTER `Password`,
ADD COLUMN IF NOT EXISTS `user_type` ENUM('admin', 'staff') DEFAULT 'staff' AFTER `staff_id`;

-- Set default staff password for existing staff (change this password!)
-- Password should be set individually for each staff member for security
UPDATE `users` 
SET `staff_password` = 'staff123', `user_type` = 'staff' 
WHERE `staff_id` IS NOT NULL AND `staff_id` != '';

-- Example: Set individual staff passwords (update with actual passwords)
-- UPDATE users SET staff_password = 'password1' WHERE staff_id = '1158';
-- UPDATE users SET staff_password = 'password2' WHERE staff_id = '1159';

-- Note: In production, use password hashing (password_hash() in PHP)
-- For now, storing plain text passwords is for development only



