# Student Identification System

A comprehensive web-based student identification and management system built with PHP and MySQL. This system allows educational institutions to manage student records, generate QR codes for student identification, maintain student profiles with images and academic information, and manage attendance through QR code scanning.

## Features

### Core Features
- **User Authentication**: Secure login and registration system for staff members
- **Student Management**: 
  - Add, edit, view, and delete student records
  - Upload and manage student photographs
  - Store comprehensive student information including personal details, academic records, and semester-wise performance
- **QR Code Generation**: Automatic generation of QR codes for each student and staff member
- **Excel Import/Export**: 
  - Export student data to Excel (.xls) or CSV format
  - Import student data from CSV files
  - Download template file for bulk data entry
- **Attendance Management**:
  - QR code-based attendance system
  - Staff authentication via QR code scanning
  - Subject-wise and date-wise attendance tracking
  - Attendance viewing for students via QR code
- **Responsive Design**: Bootstrap-based responsive interface for desktop and mobile devices
- **Secure Image Storage**: Images are stored privately and protected from direct access

## Requirements

- PHP 7.0 or higher
- MySQL 5.7 or higher (or MariaDB)
- Apache web server with mod_rewrite enabled (or any PHP-compatible web server)
- GD Library (for image processing)
- PHP QR Code Library (included in `phpqrcode/` folder)

## Where to Run This

### For Local Development (Your Computer)

**Windows Users:**
- **Recommended**: Use **XAMPP**
  - Download from: https://www.apachefriends.org/
  - Place project in: `C:\xampp\htdocs\student_identification\`
  - Start Apache & MySQL in XAMPP Control Panel
  - Access at: `http://localhost/student_identification/`

- **Alternative**: Use **WAMP**
  - Place project in: `C:\wamp64\www\student_identification\`
  - Access at: `http://localhost/student_identification/`

**Mac Users:**
- Use **MAMP**
  - Place project in: `/Applications/MAMP/htdocs/student_identification/`
  - Access at: `http://localhost:8888/student_identification/`

**Linux Users:**
- Use built-in PHP server or Apache
- Place project in `/var/www/html/student_identification/` or use `php -S localhost:8000`

### For Production (Live Website)

- Upload to web hosting (cPanel, shared hosting, VPS)
- Requires PHP 7.0+ and MySQL database
- Set proper folder permissions (755 for folders, 644 for files)

📖 **See [QUICK_START.md](QUICK_START.md) for detailed step-by-step instructions**

## Installation

### Step 1: Clone or Download Repository
```bash
git clone https://github.com/yourusername/student_identification.git
cd student_identification
```

### Step 2: Database Setup
1. Create a MySQL database named `user` (or update `db.php` with your database name)
2. Import the SQL files from the `sql/` folder in this order:
   ```sql
   -- Import users table
   source sql/users.sql
   
   -- Import card_activation table
   source sql/card_activation .sql
   
   -- Import attendance system tables (if using attendance feature)
   source sql/staff_attendance_schema.sql
   source sql/setup_staff_passwords.sql
   ```
3. Or run the SQL files directly through phpMyAdmin or MySQL command line

### Step 3: Configure Database Connection
Open `db.php` and update the database credentials:
```php
$con = mysqli_connect('localhost', 'your_username', 'your_password', 'user');
```
**Important**: Update `db.php` with your actual database credentials before deploying

### Step 4: Set Folder Permissions
Make sure the following folders are writable:
- `upload_images/` (chmod 755 or 777)
- `upload_qrcode/` (chmod 755 or 777)

```bash
chmod 755 upload_images/
chmod 755 upload_qrcode/
```

### Step 5: Web Server Configuration
- Place the project in your web server's document root (e.g., `htdocs/`, `www/`, or `public_html/`)
- If using Apache, ensure `.htaccess` files are enabled
- For Nginx, configure the appropriate rewrite rules

### Step 6: Access the Application
- Navigate to `http://localhost/student_identification/` in your browser (or `http://localhost:8000` if using PHP built-in server)
- Default login credentials (after importing SQL):
  - Email: `staff@gmail.com`
  - Password: `staff123`

**💡 Quick Setup Summary:**
1. Install XAMPP/WAMP/MAMP
2. Copy project to `htdocs/www` folder
3. Start Apache & MySQL
4. Create database in phpMyAdmin
5. Import SQL files
6. Update `db.php` with database credentials
7. Open in browser: `http://localhost/student_identification/`

For detailed instructions, see **[QUICK_START.md](QUICK_START.md)**

## Project Structure

```
student_identification/
├── add.php                      # Student registration form handler
├── db.php                       # Database connection configuration
├── delete.php                   # Student deletion handler
├── edit.php                     # Student edit form handler
├── excel.php                    # Excel/CSV export functionality
├── excel_import.php             # Excel/CSV import functionality
├── home.php                     # Main dashboard/home page
├── index.php                    # Login page
├── index1.php                   # Student management page
├── register.php                 # User registration page
├── view.php                     # Student profile view page
├── view_attendance.php          # Student attendance viewing
├── qr_scan.php                  # QR code landing page
├── staff_auth.php               # Staff authentication via QR
├── mark_attendance.php          # Staff attendance marking interface
├── staff_qr_management.php      # Staff QR code management
├── staff_assignments.php        # Staff assignment management
├── download_pdf.php             # PDF download functionality
├── navbar.php                   # Navigation bar component
├── editprofile.php              # User profile editing
├── upload_images/               # Student image storage (PRIVATE - not in git)
├── upload_qrcode/               # Generated QR codes (PRIVATE - not in git)
├── phpqrcode/                   # QR code generation library
├── style/                       # CSS stylesheets
├── sql/                         # Database schema files
│   ├── users.sql
│   ├── card_activation .sql
│   ├── staff_attendance_schema.sql
│   └── setup_staff_passwords.sql
├── .gitignore                   # Git ignore file
├── README.md                    # This file
├── ATTENDANCE_SETUP.md          # Attendance system setup guide
└── .htaccess                     # Apache configuration (in upload folders)
```

## Security Features

- **SQL Injection Prevention**: All database queries use prepared statements
- **Input Sanitization**: All user inputs are sanitized using `mysqli_real_escape_string()` and validated
- **File Upload Security**: 
  - File type validation (only images allowed)
  - File size limits (max 5MB)
  - Secure file naming
- **Image Folder Protection**: `.htaccess` files prevent direct access to uploaded images and QR codes
- **Session Management**: Secure session handling for user authentication
- **XSS Protection**: Output is escaped using `htmlspecialchars()` where appropriate
- **CSV Injection Protection**: CSV exports handle special characters properly

## Usage Guide

### Adding a Student

1. Login to the system
2. Navigate to Student Management (index1.php)
3. Click "Add New Student" button
4. Fill in all required information:
   - Student ID (10 digits)
   - Personal information (name, DOB, gender, etc.)
   - Contact details (email, phone)
   - Academic information (department, academic year, semester marks)
   - Upload student photograph
   - Enter Staff ID (4 digits)
5. Click "Submit"

### Viewing Student Profile

- Click the profile icon (📋) next to any student in the list
- Or scan the student's QR code and select "View Student Data"

### Editing Student Information

1. Click the edit icon (✏️) next to the student
2. Update the required fields
3. Optionally upload a new image
4. Click "Submit"

### Deleting a Student

1. Click the delete icon (🗑️) next to the student
2. Confirm deletion in the modal dialog

### Exporting Data

#### Excel Export (.xls)
1. Click "Export Excel" button on the Student Management page
2. The file will download in Excel format with all student records

#### CSV Export
1. Click "Export CSV" button on the Student Management page
2. The file will download in CSV format (better for import/export workflows)

#### Download Template
1. Go to Excel Import page (`excel_import.php`)
2. Click "Download Template" to get a sample CSV file with correct format

### Importing Data

1. Navigate to "Import Excel" from Student Management page
2. Prepare your CSV file using the template format:
   - First row should contain headers
   - Required columns: Card Number, First Name
   - Follow the exact column order (see template)
3. Click "Choose File" and select your CSV file
4. Click "Import Data"
5. System will:
   - Import new students (if Card Number doesn't exist)
   - Update existing students (if Card Number matches)
   - Show success/error messages

**CSV Format Requirements:**
- Format: CSV (Comma Separated Values)
- Encoding: UTF-8
- First row: Headers (will be skipped)
- Date format: YYYY-MM-DD (e.g., 2024-01-15)
- Required fields: Card Number, First Name
- All other fields are optional

### QR Code Features

#### Student QR Codes
When a student's QR code is scanned:
1. **View Student Data**: Shows complete student profile
2. **View Attendance**: Shows attendance records with statistics

#### Staff QR Codes
When a staff member's QR code is scanned:
1. Password authentication prompt appears
2. After authentication, staff can mark attendance for their assigned students

### Attendance Management

#### For Staff
1. Scan your staff QR code
2. Enter your staff password
3. Select date, subject, and academic year
4. Mark each student as Present, Absent, or Late
5. Click "Save Attendance"

#### For Students
1. Scan your student QR code
2. Select "View Attendance"
3. View attendance statistics and detailed records

#### Staff Assignment Setup
1. Navigate to "Staff Assignments" from home page
2. Assign subjects, academic years, and departments to staff members
3. Staff can only mark attendance for their assigned subjects/years

For detailed attendance setup, see [ATTENDANCE_SETUP.md](ATTENDANCE_SETUP.md)

## Database Schema

### users Table
- `Id` (Primary Key, Auto Increment)
- `Username`
- `Email`
- `Password`
- `staff_id` (4 digits)
- `staff_password` (for QR authentication)
- `user_type` (admin/staff)

### card_activation Table
- `id` (Primary Key, Auto Increment)
- `u_card` (Student ID, Unique, 10 characters)
- `u_f_name`, `u_l_name` (Student name)
- `u_father`, `u_mother` (Parent names)
- `u_aadhar` (12 digits)
- `u_birthday` (Date)
- `u_gender`
- `u_email`
- `u_phone` (10 digits)
- `u_address`
- `u_department`
- `u_academic_year`
- `u_10th_percentage`
- `staff_id` (4 digits)
- `image` (Image filename)
- `uploaded` (Timestamp)
- `semester1` through `semester6` (Semester marks)

### attendance Table (if attendance system is enabled)
- `id` (Primary Key, Auto Increment)
- `student_id` (Foreign Key)
- `staff_id` (Foreign Key)
- `subject`
- `attendance_date` (Date)
- `status` (Present/Absent/Late)
- `marked_at` (Timestamp)
- `academic_year`

### staff_assignments Table (if attendance system is enabled)
- `id` (Primary Key, Auto Increment)
- `staff_id` (Foreign Key)
- `subject`
- `academic_year`
- `department`

See `sql/` folder for complete schema definitions.

## Important Notes

### Image Privacy

- The `upload_images/` folder is excluded from Git via `.gitignore`
- The `upload_qrcode/` folder is excluded from Git via `.gitignore`
- Images are protected by `.htaccess` to prevent direct URL access
- Always ensure proper file permissions are set on the server
- **Do not commit actual student images or QR codes to the repository**

### Security Recommendations

1. **Change Default Passwords**: Immediately change default login credentials
2. **Database Security**: Use strong database passwords and restrict database access
3. **HTTPS**: Deploy the application over HTTPS in production
4. **Regular Backups**: Maintain regular backups of the database
5. **Update Credentials**: Never commit `db.php` with actual credentials to version control
6. **Session Security**: Configure secure session settings in `php.ini`
7. **File Upload Limits**: Configure appropriate `upload_max_filesize` in `php.ini`
8. **Staff Passwords**: For production, implement password hashing for staff QR authentication

### CSV Import Tips

- Use UTF-8 encoding to avoid character issues
- Remove special characters that might break CSV parsing
- Test with a small file first
- Keep backups before bulk imports
- Verify data after import

## Troubleshooting

### Images not uploading
- Check folder permissions (`chmod 755 upload_images/`)
- Verify `upload_max_filesize` and `post_max_size` in `php.ini`
- Check PHP error logs
- Ensure GD library is installed

### QR codes not generating
- Ensure `upload_qrcode/` folder exists and is writable
- Check PHP GD library is installed
- Verify PHP QR Code library files are present
- Check folder permissions

### Database connection errors
- Verify database credentials in `db.php`
- Ensure MySQL service is running
- Check database name matches
- Verify user has proper permissions

### Excel/CSV export issues
- Check PHP memory limit
- For large datasets, use CSV format instead of Excel
- Ensure write permissions for downloads

### CSV import not working
- Verify file is in CSV format (not Excel)
- Check column order matches template
- Ensure first row is headers
- Check for special characters in data
- Verify date format is YYYY-MM-DD
- Check PHP error logs for specific errors

### 404 Errors
- Verify Apache `mod_rewrite` is enabled
- Check `.htaccess` file exists and is readable
- Confirm web server document root is correct

### Attendance System Issues
- Verify attendance tables are created (`staff_attendance_schema.sql`)
- Check staff assignments are configured
- Ensure staff passwords are set
- Verify QR codes are generated for staff

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is open source and available under the MIT License.

## Support

For issues, questions, or contributions, please open an issue on the GitHub repository.

## Changelog

### Version 2.0.0 (Current)
- Added QR code-based attendance system
- Added staff QR code authentication
- Added Excel/CSV import functionality
- Improved Excel/CSV export (with format options)
- Added attendance viewing for students
- Added staff assignment management
- Enhanced security with prepared statements
- Added comprehensive documentation

### Version 1.1.0
- Fixed SQL injection vulnerabilities
- Added input validation and sanitization
- Implemented prepared statements for all database queries
- Added image upload security (type and size validation)
- Protected image folder with `.htaccess`
- Created `.gitignore` to exclude sensitive folders
- Improved error handling
- Fixed code quality issues

### Version 1.0.0
- Initial release
- Basic CRUD operations
- QR code generation
- Excel export functionality

## Related Documentation

- [ATTENDANCE_SETUP.md](ATTENDANCE_SETUP.md) - Complete guide for setting up the attendance system

---

**Note**: This application is designed for educational purposes. Always follow your institution's privacy and data protection policies when handling student information.
