<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'student_identification_system');
define('DB_USER', 'root'); // Replace with your MySQL username
define('DB_PASS', '');     // Replace with your MySQL password

// Determine base URL dynamically or set explicitly
// This helps with correct redirection and asset loading when deployed in a subdirectory.
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

// Adjust if your project is in a subdirectory, e.g., /student-identification
// If your project root is accessible directly at http://localhost/, then BASE_PATH = '';
// If your project is at http://localhost/student-identification/, then BASE_PATH = '/student-identification';
$basePath = '/student-identification'; // <<< ADJUST THIS IF NECESSARY

define('BASE_PATH', $basePath);
define('BASE_URL', $protocol . "://" . $host . $basePath);

// QR codes URL - empty = auto-detect PC IP from connected WiFi/network (recommended)
// Set manually e.g. 'http://192.168.1.100' . $basePath if auto-detect fails
define('QR_SCAN_BASE_URL', '');

try {
    // Explicitly specify port 3306 for XAMPP MySQL
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = $e->getMessage();
    if (strpos($errorMsg, 'actively refused') !== false || strpos($errorMsg, '2002') !== false) {
        die("Database connection failed: MySQL service is not running. Please start MySQL in XAMPP Control Panel.");
    }
    die("Database connection failed: " . $errorMsg);
}
