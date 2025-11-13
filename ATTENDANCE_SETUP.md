# Attendance System Setup Guide

## Overview
This guide explains how to set up and use the new QR Code-based attendance system with staff authentication.

## Features
- **Student QR Codes**: When scanned, show two options:
  1. View Student Data - Shows complete student profile
  2. View Attendance - Shows attendance records for the student

- **Staff QR Codes**: When scanned, require password authentication, then allow staff to mark attendance

- **Staff Management**: 
  - Assign subjects and academic years to staff members
  - Generate QR codes for staff
  - Mark attendance for students based on subject assignments

## Database Setup

### Step 1: Run the SQL Schema
Execute the following SQL file to create necessary tables:

```bash
mysql -u your_username -p your_database < sql/staff_attendance_schema.sql
```

Or import through phpMyAdmin:
- Navigate to phpMyAdmin
- Select your database
- Click "Import"
- Choose `sql/staff_attendance_schema.sql`

### Step 2: Set Up Staff Passwords
Run the setup script to add password support:

```bash
mysql -u your_username -p your_database < sql/setup_staff_passwords.sql
```

**Important**: Update staff passwords individually in the database:
```sql
UPDATE users SET staff_password = 'your_secure_password' WHERE staff_id = '1158';
```

## Usage Instructions

### For Administrators

#### 1. Generate Staff QR Codes
1. Login to the system
2. Navigate to "Staff QR Codes" from the home page
3. Click "Generate QR" next to each staff member
4. Download or print the QR codes for staff members

#### 2. Assign Subjects to Staff
1. Navigate to "Staff Assignments" from the home page
2. Fill in the form:
   - Select Staff ID
   - Enter Subject name (e.g., "Mathematics", "Physics")
   - Select Academic Year (1st Year, 2nd Year, 3rd Year)
   - Select Department (default: Computer Technology)
3. Click "Add Assignment"
4. Staff can now mark attendance for students in their assigned subjects and years

### For Staff Members

#### 1. Mark Attendance
1. Scan your staff QR code with a mobile device
2. Enter your staff password
3. After authentication, you'll see the attendance marking page
4. Select:
   - Date (default: today)
   - Subject (from your assigned subjects)
   - Academic Year
5. Mark each student as Present, Absent, or Late
6. Click "Save Attendance"

**Note**: Staff can only mark attendance for:
- Students in their assigned academic year
- Students in their assigned department
- Subjects they are assigned to

### For Students/Public

#### 1. View Student Data via QR Code
1. Scan a student's QR code
2. Select "View Student Data"
3. View complete student profile with all information

#### 2. View Attendance via QR Code
1. Scan a student's QR code
2. Select "View Attendance"
3. View:
   - Total attendance statistics
   - Detailed attendance records by date and subject
   - Attendance percentage

## System Flow

### Student QR Code Flow
```
Scan Student QR → qr_scan.php?student_id=X
                ↓
        [Two Options Displayed]
                ↓
    ┌───────────┴───────────┐
    ↓                       ↓
View Data              View Attendance
    ↓                       ↓
view.php          view_attendance.php
```

### Staff QR Code Flow
```
Scan Staff QR → qr_scan.php?staff_id=X
                ↓
        [Password Prompt]
                ↓
    Enter Password → staff_auth.php
                ↓
        [Authentication Check]
                ↓
        mark_attendance.php
                ↓
    [Mark Attendance Interface]
```

## Database Tables

### staff_qr_codes
Stores staff QR code file paths.

### staff_assignments
Links staff to subjects, academic years, and departments.

**Columns**:
- `staff_id`: Staff's 4-digit ID
- `subject`: Subject name
- `academic_year`: 1st Year, 2nd Year, or 3rd Year
- `department`: Department name

### attendance
Stores all attendance records.

**Columns**:
- `student_id`: Reference to student
- `staff_id`: Reference to staff member
- `subject`: Subject name
- `attendance_date`: Date of attendance
- `status`: Present, Absent, or Late
- `academic_year`: Academic year

## Security Notes

1. **Staff Passwords**: 
   - Currently stored in plain text (for development)
   - In production, implement password hashing
   - Use `password_hash()` and `password_verify()` in PHP

2. **Session Management**:
   - Staff sessions are separate from admin sessions
   - Staff must authenticate via QR code each time

3. **Access Control**:
   - Staff can only mark attendance for their assigned subjects/years
   - Attendance records are linked to staff ID for auditing

## Current Configuration

This system is configured for **3rd Year Computer Technology** students by default. To change:

1. Update `mark_attendance.php` line with `$current_year` and `$current_dept`
2. Ensure staff assignments match the year/department you want

## Troubleshooting

### QR codes not working
- Check that QR code files are being generated in `upload_qrcode/` folder
- Verify folder permissions (chmod 755)
- Check that URLs in QR codes match your domain

### Staff can't see students
- Verify staff assignments are created correctly
- Check that students belong to the assigned academic year and department
- Ensure subject matches assignment

### Attendance not saving
- Check database connection
- Verify `attendance` table exists
- Check for duplicate entries (same student, staff, subject, date)

## Future Enhancements

Consider implementing:
1. Password hashing for staff passwords
2. Email notifications for attendance alerts
3. Attendance reports and analytics
4. Bulk attendance marking
5. SMS notifications
6. Mobile app for easier scanning



