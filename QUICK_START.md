# Quick Start Guide - How to Run This Project

## Option 1: Using XAMPP (Easiest for Windows)

### Step 1: Install XAMPP
1. Download XAMPP from https://www.apachefriends.org/
2. Install XAMPP to `C:\xampp` (default location)

### Step 2: Place Project Files
1. Copy the entire `student_identification` folder
2. Paste it into `C:\xampp\htdocs\` folder
3. Full path should be: `C:\xampp\htdocs\student_identification\`

### Step 3: Start XAMPP Services
1. Open XAMPP Control Panel
2. Click "Start" for **Apache**
3. Click "Start" for **MySQL**
4. Both should show green "Running" status

### Step 4: Create Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Name it: `user` (or your preferred name)
4. Click "Create"

### Step 5: Import Database Tables
1. In phpMyAdmin, select the `user` database
2. Click "Import" tab
3. Click "Choose File" and select `sql/users.sql`
4. Click "Go" at bottom
5. Repeat for:
   - `sql/card_activation .sql`
   - `sql/staff_attendance_schema.sql` (if using attendance)
   - `sql/setup_staff_passwords.sql` (if using attendance)

### Step 6: Configure Database Connection
1. Open `db.php` file
2. Update these lines:
   ```php
   $con = mysqli_connect('localhost', 'root', '', 'user');
   ```
   - If you set a MySQL password, add it: `$con = mysqli_connect('localhost', 'root', 'your_password', 'user');`
   - If you named database differently, change `'user'` to your database name

### Step 7: Set Folder Permissions (Windows)
1. Right-click `upload_images` folder → Properties → Security
2. Make sure "Users" have "Write" permission
3. Repeat for `upload_qrcode` folder

### Step 8: Access the Application
1. Open browser
2. Go to: `http://localhost/student_identification/`
3. You should see the login page!

### Default Login
- **Email**: `staff@gmail.com`
- **Password**: `staff123`

---

## Option 2: Using WAMP (Windows)

### Step 1: Install WAMP
1. Download WAMP from http://www.wampserver.com/
2. Install WAMP

### Step 2: Place Project Files
1. Copy `student_identification` folder
2. Paste into `C:\wamp64\www\` (or your WAMP installation directory)
3. Full path: `C:\wamp64\www\student_identification\`

### Step 3: Start WAMP
1. Launch WAMP
2. Wait for icon to turn green
3. If not green, left-click icon → Start All Services

### Step 4-7: Follow same steps as XAMPP
- Create database in phpMyAdmin
- Import SQL files
- Configure `db.php`
- Access at `http://localhost/student_identification/`

---

## Option 3: Using MAMP (Mac)

### Step 1: Install MAMP
1. Download MAMP from https://www.mamp.info/
2. Install MAMP

### Step 2: Place Project Files
1. Copy `student_identification` folder
2. Paste into `/Applications/MAMP/htdocs/`
3. Full path: `/Applications/MAMP/htdocs/student_identification/`

### Step 3: Start MAMP
1. Open MAMP
2. Click "Start Servers"
3. Apache and MySQL should start

### Step 4-7: Follow same steps as XAMPP
- Create database in phpMyAdmin: `http://localhost:8888/phpMyAdmin`
- Import SQL files
- Configure `db.php`
- Access at: `http://localhost:8888/student_identification/`

---

## Option 4: Using Built-in PHP Server (Development Only)

### Step 1: Install PHP
- PHP 7.0+ must be installed
- MySQL/MariaDB must be installed and running

### Step 2: Navigate to Project Folder
```bash
cd C:\Users\k\OneDrive\Documents\GitHub\student_identification
```

### Step 3: Start PHP Server
```bash
php -S localhost:8000
```

### Step 4: Access Application
- Open browser: `http://localhost:8000`
- Note: This is for testing only, not for production

---

## Option 5: Deploy on Live Server (Production)

### Requirements:
- Web hosting with PHP 7.0+ and MySQL
- cPanel or FTP access
- Database access

### Steps:

1. **Upload Files via FTP**
   - Connect using FileZilla or similar
   - Upload entire `student_identification` folder to `public_html` or `www` directory
   - Ensure file permissions: folders 755, files 644

2. **Create Database on Server**
   - Login to cPanel
   - Go to "MySQL Databases"
   - Create new database (e.g., `username_student`)
   - Create database user and assign to database

3. **Import SQL Files**
   - Go to phpMyAdmin in cPanel
   - Select your database
   - Import all SQL files from `sql/` folder

4. **Update db.php**
   ```php
   $con = mysqli_connect('localhost', 'database_user', 'database_password', 'database_name');
   ```
   - Get connection details from cPanel → MySQL Databases

5. **Set Folder Permissions**
   - In FileZilla: Right-click `upload_images` → File Permissions → Set to 755
   - Repeat for `upload_qrcode`

6. **Access Your Site**
   - Go to: `http://yourdomain.com/student_identification/`

---

## Troubleshooting

### "Database Connection Failed"
- Check `db.php` credentials
- Ensure MySQL service is running
- Verify database name exists

### "404 Not Found"
- Check file path in browser URL
- Verify Apache/MySQL services are running
- Check `.htaccess` file exists

### "Permission Denied" on Upload
- Set folder permissions: `chmod 755 upload_images/`
- On Windows: Right-click → Properties → Security → Edit permissions

### Images Not Showing
- Check `upload_images/` folder exists
- Verify folder permissions
- Check `.htaccess` file is present

### QR Codes Not Generating
- Ensure `upload_qrcode/` folder exists
- Check folder is writable (755 or 777)
- Verify PHP GD library is installed

---

## Recommended: Use XAMPP for Development

For local development on Windows, **XAMPP is the easiest option**:

1. ✅ One-click install
2. ✅ Apache + MySQL included
3. ✅ phpMyAdmin included
4. ✅ Easy to start/stop services
5. ✅ Perfect for development

**Quick XAMPP Setup:**
```
1. Install XAMPP → C:\xampp
2. Copy project → C:\xampp\htdocs\student_identification\
3. Start Apache & MySQL in XAMPP
4. Create database in phpMyAdmin
5. Import SQL files
6. Update db.php
7. Open: http://localhost/student_identification/
```

---

## Need Help?

If you encounter issues:
1. Check PHP version: `php -v` (need 7.0+)
2. Check MySQL is running
3. Verify database credentials in `db.php`
4. Check PHP error logs in your server
5. Ensure all SQL files are imported successfully

---

**Good luck with your project! 🚀**


