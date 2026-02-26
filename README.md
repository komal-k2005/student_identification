<div align="center">

# 🎓 Student Identification & Management System

<p align="center"> 
  <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white"/> 
  <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white"/> 
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white"/> 
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/> 
</p> 

**A Secure QR-Based Digital Identity & Attendance Management Platform**<br>
*Diploma Final Year Major Project*

---
</div>

## 📌 Project Overview

The **Student Identification & Management System** is a full-stack web application meticulously designed to digitize and streamline campus identity verification and attendance workflows using securely encrypted QR codes.

Built from the ground up, the system replaces traditional manual attendance and identity verification processes with a secure, highly scalable, and structurally efficient digital ecosystem.

**This project demonstrates strong proficiency in:**
- Backend Architecture Design & Custom MVC Implementation
- Database Normalization & Query Optimization
- Secure Authentication Systems & Role-Based Access Control (RBAC)
- Data Encryption (AES-256-CBC) & Cryptographic Validation
- Real-world System Integration & Deployment Workflows

---

## 🚀 Key Technical Achievements

- **Custom MVC Architecture:** Designed and implemented a robust Model-View-Controller pattern from scratch, ensuring clean code separation, modularity, and maintainability without relying on heavy frameworks.
- **Enterprise-Grade Security:** Built a secure Role-Based Access Control (RBAC) authorization matrix safeguarding Admin, Staff, and Student portals.
- **Advanced Cryptography:** Implemented **AES-256-CBC encryption** for QR identity matrices, preventing QR spoofing or unauthorized identity replication.
- **Data Protection:** Secured user credentials utilizing PHP's native robust password hashing techniques and eliminated SQL injection vulnerabilities via **PDO Prepared Statements**.
- **Data Portability:** Developed a robust CSV-based bulk import/export processing pipeline to handle legacy data integration effortlessly.
- **Dynamic Analytics:** Created a powerful computational engine for real-time attendance reporting (supporting Daily, Monthly, and Semester-wise data slicing).
- **High-Performance UI/UX:** Engineered an asynchronous, mobile-responsive QR scanning interface that processes attendance transactions in **under 2 seconds**.

---

## 🔐 Security Implementation Deep-Dive

Security was designed as a first-class citizen at every layer of the application:
* **QR Cryptography:** Implemented state-of-the-art `AES-256-CBC` encryption via OpenSSL to secure QR payloads.
* **Authentication:** Enforced solid credential storage utilizing `password_hash()`.
* **Sanitization Layer:** Built stringent input validation and sanitization filters to mitigate XSS (Cross-Site Scripting).
* **Database Threat Mitigation:** Adopted 100% `PDO Prepared Statements` across the data layer, neutralizing SQL Injection (SQLi) risks.
* **Validation Protocols:** Protected quick-scan attendance endpoints uniquely using verifiable **Staff Secret Key verification**.

---

## ✨ Core Features

### 👨‍💼 Admin & Staff Module
- **Comprehensive Data Management:** Complete CRUD operations governing Students, Staff profiles, and Academic Marks.
- **Automated Provisioning:** Automatic algorithmic generation of secure QR Codes serving as unforgeable digital identity cards.
- **Lightning-Fast Operations:** Next-gen mobile QR attendance processing yielding results in under two seconds.
- **Reporting Engine:** Holistic attendance tracking with structured hierarchical reporting.
- **Interoperability:** Seamless CSV bulk data import/export capabilities.
- **Administrative Dashboard:** Bird’s-eye view analytics and system-wide monitoring.

### 🎓 Student Portal
- **Secure Access:** QR-enabled secure login workflows.
- **Transparency:** Real-time visibility into personal attendance metrics and aggregated monitoring.
- **Academic Tracking:** Instantaneous display of semester-wise marks and grading evaluation.
- **Digital Convenience:** Providable, downloadable, and dynamically generated digital QR ID cards.

---

## 🏗️ Project Architecture

```text
.
├── config/              # Environment & database configuration setup
├── includes/            # Encryption utilities, helpers & shared UI templates
├── public/
│   ├── index.php        # Application Front Controller (Entry Point)
│   ├── scan.php         # Secure QR Scanning Interface
│   ├── css/ & js/       # Frontend static assets & dynamic scripts
│   └── qr_codes/        # Ephemeral/Stored Generated QR image directory
├── src/
│   ├── Controller/      # Business logic & routing controllers
│   ├── Model/           # Database abstraction layer (PDO Entities)
│   └── View/            # Role-based UI rendering engines
├── vendor/              # Third-party QR generation library dependencies
├── .htaccess            # Secure routing rules and access denial configurations
└── database.sql         # Base database schema & structural dumps
```

---

## 🗄 Database Design

- **State-of-the-Art Normalization:** Fully normalized relational schema eliminating data redundancy.
- **Structured Entity Relationships:** Highly cohesive modeling mapping between `Users`, `Students`, `Staff`, `Attendance`, and `Marks`.
- **Query Optimization:** Indexed and optimized SQL queries engineered specifically for high-load attendance reporting operations.
- **Legacy Integration:** Specifically designed CSV portability layer mapping straight to relational entities.

---

## 👥 Project Team & Responsibilities

This project was orchestrated as a Diploma Final Year Major Project, simulating a real-world software development lifecycle with explicit, specialized role distributions.

* **👩‍💻 Komal Kathawde – Database & Backend Developer**
  * Architected and normalized the comprehensive MySQL database schema.
  * Developed the core backend business logic, session management, and routing via PHP.
  * Engineered the intricate AES-256 encrypted QR identity validation system and the calculation-heavy attendance engine logic.
  * Ensured impenetrable database interactions using PDO prepared statements.
* **🎨 Bobade Mayuri – Frontend Developer**
  * Conceptualized and developed responsive, modern UI/UX utilizing Bootstrap 5.
  * Mapped and integrated dynamic frontend forms seamlessly with backend API controllers.
* **🧪 Vaishnavi Mirge – Testing & Quality Assurance**
  * Executed rigorous module-wise functional integration tests.
  * Validated end-to-end QR scanning workflows and edge-case bug identification.
* **📄 Argade Pallavi – Documentation Engineer**
  * Authored the comprehensive technical documentation, ER diagrams, system flow charts, and the final academic synopsis.

---

## 🎥 Project Demonstration

Experience the system in action through our comprehensive demonstration modules:

* 🔗 **[Full System Demonstration](https://drive.google.com/file/d/1qqQJsuIbN9HttcmQ_9AGwK3udXcOWCSR/view)**
* 🔗 **[Staff QR Scanning Workflow](https://drive.google.com/file/d/1FIai8_bvT7y-CHENhZZLxuaxq6QrFu7H/view)**
* 🔗 **[Student QR Login Process](https://drive.google.com/file/d/1yAYUcc5BY46Wbhv65on0u2NAqwqcBwUN/view)**

---

## ⚙️ Installation & Setup

### Requirements
- **PHP** 8.0 or higher
- **MySQL** Database Server
- **Apache** Web Server (Via XAMPP / WAMP / LAMP stack)

### Quick Setup Steps

1. **Clone the repository** into your local server environment:
   ```bash
   cd htdocs/
   git clone <repository-url> student_identification
   ```
2. **Database Initialization:**
   * Create a new database named `student_identification_system`.
   * Import the provided `database.sql` structural dump.
3. **Environment Configuration:**
   * Navigate to `config/database.php`.
   * Update the credentials to match your local MySQL server setup.
4. **Launch the Application:**
   * Access the application via your browser:
     ```text
     http://localhost/student_identification/public/index.php
     ```

---
<div align="center">
<i>Built with passion to modernize academic infrastructure.</i>
</div>
