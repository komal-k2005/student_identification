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

function getRelativePath() {
    $basePath = parse_url(BASE_URL, PHP_URL_PATH);
    if (!empty($_GET['route'])) {
        $route = '/' . ltrim($_GET['route'], '/');
        return strtok($route, '?');
    }

    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $relativePath = substr($requestUri, strlen($basePath));
    return $relativePath === false ? '' : $relativePath;
}

function resolveAction($relativePath) {
    if ($relativePath === '/' || $relativePath === '/index.php') {
        return (isset($_GET['student_login']) && $_GET['student_login'] === 'true')
            ? 'student_qr_login'
            : 'login';
    }
    return $_GET['action'] ?? null;
}

function dispatchPath($relativePath, $routes) {
    if (!isset($routes[$relativePath])) {
        return false;
    }

    $route = $routes[$relativePath];
    $controller = $route['controller'];
    $method = $route['method'];
    $controller->$method();
    return true;
}

$relativePath = getRelativePath();
$action = resolveAction($relativePath);

$staffRoutes = [
    '/staff/dashboard.php' => ['controller' => $staffController, 'method' => 'dashboard'],
    '/staff/view_staff.php' => ['controller' => $staffController, 'method' => 'viewStaffDetails'],
    '/staff/add_student.php' => ['controller' => $staffController, 'method' => 'addStudent'],
    '/staff/edit_student.php' => ['controller' => $staffController, 'method' => 'editStudent'],
    '/staff/list_students.php' => ['controller' => $staffController, 'method' => 'listStudents'],
    '/staff/generate_qr.php' => ['controller' => $staffController, 'method' => 'generateStudentQrCode'],
    '/staff/mark_attendance.php' => ['controller' => $staffController, 'method' => 'markAttendance'],
    '/staff/add_marks.php' => ['controller' => $staffController, 'method' => 'addStudentMarks'],
    '/staff/export_students.php' => ['controller' => $staffController, 'method' => 'exportStudentsCsv'],
    '/staff/import_students.php' => ['controller' => $staffController, 'method' => 'importStudentsCsv'],
    '/staff/view_student_details.php' => ['controller' => $staffController, 'method' => 'viewStudentDetails'],
    '/staff/delete_student.php' => ['controller' => $staffController, 'method' => 'deleteStudent'],
];

$adminRoutes = [
    '/admin/create_staff.php' => ['controller' => $staffController, 'method' => 'createStaff'],
    '/admin/list_staff.php' => ['controller' => $staffController, 'method' => 'listStaff'],
    '/admin/activate_staff.php' => ['controller' => $staffController, 'method' => 'activateStaff'],
    '/admin/deactivate_staff.php' => ['controller' => $staffController, 'method' => 'deactivateStaff'],
    '/admin/daily_report.php' => ['controller' => $attendanceController, 'method' => 'dailyReport'],
    '/admin/monthly_report.php' => ['controller' => $attendanceController, 'method' => 'monthlyReport'],
    '/admin/semester_report.php' => ['controller' => $attendanceController, 'method' => 'semesterReport'],
    '/admin/export_staff.php' => ['controller' => $staffController, 'method' => 'exportStaffCsv'],
    '/admin/import_staff.php' => ['controller' => $staffController, 'method' => 'importStaffCsv'],
];

$studentRoutes = [
    '/student/profile.php' => ['controller' => $studentController, 'method' => 'viewProfile'],
    '/student/view_marks.php' => ['controller' => $studentController, 'method' => 'viewSemesterMarks'],
    '/student/view_attendance.php' => ['controller' => $studentController, 'method' => 'viewAttendance'],
    '/student/download_qr.php' => ['controller' => $studentController, 'method' => 'downloadQrIdCard'],
];

if (dispatchPath($relativePath, $staffRoutes) ||
    dispatchPath($relativePath, $adminRoutes) ||
    dispatchPath($relativePath, $studentRoutes)) {
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
