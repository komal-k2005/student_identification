<?php
// public/index.php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Controller/AuthController.php';
require_once __DIR__ . '/../src/Controller/StaffController.php';
require_once __DIR__ . '/../src/Controller/StudentController.php';
require_once __DIR__ . '/../src/Controller/AttendanceController.php';

$authController = new AuthController($pdo);
$staffController = new StaffController($pdo);
$studentController = new StudentController($pdo);
$attendanceController = new AttendanceController($pdo);

// Determine the current requested path (from .htaccess route param or REQUEST_URI)
$basePath = parse_url(BASE_URL, PHP_URL_PATH);
if (!empty($_GET['route'])) {
    $relativePath = '/' . ltrim($_GET['route'], '/');
    // Use only the path part (no query string)
    $relativePath = strpos($relativePath, '?') !== false ? strstr($relativePath, '?', true) : $relativePath;
}
else {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $relativePath = substr($requestUri, strlen($basePath));
    if ($relativePath === false) {
        $relativePath = '';
    }
}

// Special handling for the root of the public directory or index.php
if ($relativePath === '/' || $relativePath === '/index.php') {
    if (isset($_GET['student_login']) && $_GET['student_login'] === 'true') {
        $action = 'student_qr_login';
    }
    else {
        $action = 'login';
    }
}
else {
    // Extract action from query parameter or use a default if no clean URL rewriting
    $action = $_GET['action'] ?? null;
}

// --- Routing Logic ---
// This section uses basic `strpos` and direct file includes.
// For a production system, consider a more robust routing library.

if (strpos($relativePath, '/staff/') === 0) {
    // Staff Panel Routes
    // Auth check is handled in header.php for all panel pages.
    if ($relativePath === '/staff/dashboard.php') {
        $staffController->dashboard();
    }
    elseif ($relativePath === '/staff/view_staff.php') {
        $staffController->viewStaffDetails();
    }
    elseif ($relativePath === '/staff/add_student.php') {
        $staffController->addStudent();
    }
    elseif ($relativePath === '/staff/edit_student.php') {
        $staffController->editStudent();
    }
    elseif ($relativePath === '/staff/list_students.php') {
        $staffController->listStudents();
    }
    elseif ($relativePath === '/staff/generate_qr.php') {
        $staffController->generateStudentQrCode();
    }
    elseif ($relativePath === '/staff/mark_attendance.php') {
        $staffController->markAttendance();
    }
    elseif ($relativePath === '/staff/add_marks.php') {
        $staffController->addStudentMarks();
    }
    elseif ($relativePath === '/staff/export_students.php') {
        $staffController->exportStudentsCsv();
    }
    elseif ($relativePath === '/staff/import_students.php') {
        $staffController->importStudentsCsv();
    }
    elseif ($relativePath === '/staff/view_student_details.php') {
        $staffController->viewStudentDetails();
    }
    elseif ($relativePath === '/staff/delete_student.php') {
        $staffController->deleteStudent();
    }
    exit();
}
elseif (strpos($relativePath, '/admin/') === 0) {
    // Admin Panel Routes (assuming staff user type can access these for now)
    // Auth check is handled in header.php.
    if ($relativePath === '/admin/create_staff.php') {
        $staffController->createStaff();
    }
    elseif ($relativePath === '/admin/list_staff.php') {
        $staffController->listStaff();
    }
    elseif ($relativePath === '/admin/activate_staff.php') {
        $staffController->activateStaff();
    }
    elseif ($relativePath === '/admin/deactivate_staff.php') {
        $staffController->deactivateStaff();
    }
    elseif ($relativePath === '/admin/daily_report.php') {
        $attendanceController->dailyReport();
    }
    elseif ($relativePath === '/admin/monthly_report.php') {
        $attendanceController->monthlyReport();
    }
    elseif ($relativePath === '/admin/semester_report.php') {
        $attendanceController->semesterReport();
    }
    elseif ($relativePath === '/admin/export_staff.php') {
        $staffController->exportStaffCsv();
    }
    elseif ($relativePath === '/admin/import_staff.php') {
        $staffController->importStaffCsv();
    }
    exit();
}
elseif (strpos($relativePath, '/student/') === 0) {
    // Student Panel Routes
    // Auth check is handled in header.php for all panel pages.
    if ($relativePath === '/student/profile.php') {
        $studentController->viewProfile();
    }
    elseif ($relativePath === '/student/view_marks.php') {
        $studentController->viewSemesterMarks();
    }
    elseif ($relativePath === '/student/view_attendance.php') {
        $studentController->viewAttendance();
    }
    elseif ($relativePath === '/student/download_qr.php') {
        $studentController->downloadQrIdCard();
    }
    exit();
}

// General Actions (login/logout)
switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'student_qr_login':
        $studentController->loginWithQrCode();
        break;
    default:
        // If no specific action or route matched, redirect to default login
        header('Location: ' . BASE_URL . '/public/index.php?action=login');
        exit();
}
