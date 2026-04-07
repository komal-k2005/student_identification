# Student Identification System - Simple Guide

## What this project does
- Staff can log in
- Staff can manage students
- Staff can mark attendance
- Students can view profile, marks, and attendance

## Tech used
- PHP
- MySQL
- Apache (XAMPP)

## Project folder
- Put project in: `C:\xampp\htdocs\student-identification`

## How to run (step by step)
1. Open XAMPP Control Panel
2. Start `Apache` and `MySQL`
3. Open `http://localhost/phpmyadmin`
4. Create database: `student_identification_system`
5. Import file: `database.sql`
6. Check DB settings in `config/database.php`
7. Open app:
   - `http://localhost/student-identification/public/index.php`

## Login
- Staff login page:
  - `http://localhost/student-identification/public/index.php?action=login`
- Example user:
  - Username: `admin`
  - Password: `admin123`

## Important note
- Password is stored in `password_hash` column.
- For secure login, use hashed password values (recommended).

## If error comes
- Check Apache and MySQL are running
- Check database name in `config/database.php`
- Check project path is inside `htdocs`
