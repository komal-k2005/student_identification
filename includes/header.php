<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/database.php';
}
if (!class_exists('AuthController')) {
    require_once __DIR__ . '/../src/Controller/AuthController.php';
}

// Determine the current panel (staff or student) to apply correct auth check
$currentPath = isset($_GET['route']) ? $_GET['route'] : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isStaffPanel = strpos($currentPath, '/staff/') !== false || strpos($currentPath, '/admin/') !== false;
$isStudentPanel = strpos($currentPath, '/student/') !== false;

if ($isStaffPanel) {
    AuthController::checkStaffAuth();
} elseif ($isStudentPanel) {
    AuthController::checkStudentAuth();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <?php 
                    if ($isStaffPanel) echo "Staff Panel";
                    elseif ($isStudentPanel) echo "Student Panel";
                    else echo "Login";
                ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'staff'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/staff/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/staff/view_staff.php">View Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/staff/list_students.php">Manage Students</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/staff/mark_attendance.php">Mark Attendance</a>
                        </li>
                         <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin Actions
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownAdmin">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/create_staff.php">Create Staff</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/list_staff.php">View Staff List</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/daily_report.php">Attendance Reports</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/public/index.php?action=logout">Logout</a>
                        </li>
                    <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student'): ?>
                         <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/student/profile.php">Profile</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/student/view_marks.php">View Marks</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/student/view_attendance.php">View Attendance</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/public/index.php?action=logout">Logout</a>
                        </li>
                    <?php else: // Not logged in ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/public/index.php">Staff Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/public/index.php?student_login=true">Student Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
