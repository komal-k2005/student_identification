# 🎓 Student Identification & Management System

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</p>

An end-to-end, full-stack **Student Identification & Management System** engineered to digitize and streamline campus operations. This project replaces manual attendance and identity verification with a robust, **QR Code-based ecosystem**, demonstrating a strong command of backend architecture, database design, and real-world system integration.

---

## 🚀 Technical Highlights & Portfolio Impact

This project was built from the ground up to solve a real-world administrative problem, showcasing several key software engineering proficiencies:

*   **Custom MVC Architecture:** Implemented a lightweight, custom MVC (Model-View-Controller) framework in PHP, strictly separating business logic, database transaction layers, and presentation.
*   **Dynamic QR Code Ecosystem:** Integrated algorithmic QR generation that encodes encrypted student/staff identifiers. Built a mobile-responsive JavaScript scanner that parses QR data instantly for frictionless identity verification and rapid attendance marking.
*   **Secure Authentication & Encryption:** Engineered a role-based access control (RBAC) system for Admins, Staff, and Students. Protected sensitive ID data within QR codes using `openssl` AES-256-CBC encryption and fortified user credentials using native PHP password hashing.
*   **Database Design & Optimization:** Designed a normalized MySQL relational database using PDO prepared statements to entirely eliminate SQL injection vulnerabilities.
*   **Data Portability Layer:** Hand-coded endpoints for bulk exporting and importing database records to CSV format, bridging the gap between legacy spreadsheet operations and modern database structures.

## ✨ Core Features

### 👨‍🏫 Admin / Staff Capabilities
*   **Complete CRUD Operations:** Fully manage student profiles, academic marks, and staff records.
*   **Instant QR Generation:** Automatically generate and assign unique QR identity codes for students and faculty.
*   **"Quick-Scan" Attendance:** Staff can scan student IDs via mobile devices to mark attendance in under 2 seconds without requiring full session logins (protected via staff Secret Key verification).
*   **Bulk Data Management:** Seamlessly import new cohorts or export existing databases to CSV.
*   **Comprehensive Reporting:** Generate dynamic Daily, Monthly, and Semester-wise attendance reports.

### 🎓 Student Portal
*   **QR-Powered Login:** Students can log in simply by scanning their digital ID card.
*   **Academic Dashboard:** View updated semester marks, personal details, and attendance history in real-time.
*   **Digital ID Distribution:** Students can download their standardized QR ID card directly to their devices.

---

## 🏗️ Project Architecture

```text
.
├── config/              // Environment and database configuration setups
├── includes/            // Reusable header/footer templates and encryption functions
├── public/
│   ├── index.php        // Core application Front Controller routing
│   ├── scan.php         // Mobile-first QR scanning interface
│   ├── css/ & js/       // Frontend styling and dynamic scripts
│   └── qr_codes/        // Directory for locally generated code images
├── src/
│   ├── Controller/      // Business logic (Staff, Student, Auth, Attendance)
│   ├── Model/           // Database interactions (PDO Prepared Statements)
│   └── View/            // Segmented HTML rendering by user role
├── vendor/              // External dependencies (PHP QR Code)
├── .htaccess            // URL rewriting rules for clean, secure routing
└── database.sql         // Relational schema mappings
```

---

## ⚙️ Installation & Setup Engine

**For a full step-by-step developer deployment guide, see [SETUP.md](SETUP.md).**

1.  **Environment:** Requires PHP 8.0+, a MySQL server, and Apache (e.g., XAMPP).
2.  **Deployment:** Clone the repository directly into your web server's document root (e.g., `htdocs/student-identification`).
3.  **Database:** Create a database named `student_identification_system` and execute `database.sql` to build the schemas.
4.  **Networking:** Update `config/database.php` with your database credentials. *Note: Ensure dynamic base URL detection is enabled for local network testing on mobile devices.*
5.  **Execution:** Navigate to `http://localhost/student-identification/public/index.php` to launch the application.
