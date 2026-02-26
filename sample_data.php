<?php
// sample_data.php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Model/Staff.php';
require_once __DIR__ . '/src/Model/Student.php';
require_once __DIR__ . '/src/Model/StudentMarks.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/vendor/phpqrcode/phpqrcode.php';

echo "<h1>Generating Sample Data...</h1>";

// Clear existing data (optional, for fresh runs)
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE staff");
$pdo->exec("TRUNCATE TABLE students");
$pdo->exec("TRUNCATE TABLE student_marks");
$pdo->exec("TRUNCATE TABLE attendance");
$pdo->exec("TRUNCATE TABLE sessions");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
echo "<p>Existing data cleared.</p>";

$staffModel = new Staff($pdo);
$studentModel = new Student($pdo);
$studentMarksModel = new StudentMarks($pdo);

// 1. Create Staff Accounts
echo "<h2>Creating Staff Accounts...</h2>";
$staff1_password = password_hash("staff123", PASSWORD_DEFAULT);
$staff2_password = password_hash("staff456", PASSWORD_DEFAULT);

$staffModel->createStaff("john.doe", $staff1_password, "John Doe", "Computer Science", "M.Tech", "CSE");
$staffModel->createStaff("jane.smith", $staff2_password, "Jane Smith", "Mathematics", "M.Sc", "Math");

$allStaff = $staffModel->getAllStaff();
foreach ($allStaff as $staff) {
    $encryptedStaffId = encryptData($staff['staff_id']);
    $staffModel->updateStaffQRCode($staff['staff_id'], $encryptedStaffId);
    $qrCodeFileName = 'staff_' . $staff['staff_id'] . '.png';
    $qrCodeFilePath = __DIR__ . '/public/qr_codes/' . $qrCodeFileName;
    generateQRCode(getStaffQrScanUrl($encryptedStaffId), $qrCodeFilePath);
    echo "<p>Staff: " . htmlspecialchars($staff['staff_name']) . " (ID: " . htmlspecialchars($staff['staff_id']) . ") created with QR.</p>";
}

// 2. Create Student Records
echo "<h2>Creating Student Records...</h2>";
$studentModel->createStudent("Alice Johnson", "Robert Johnson", "Maria Johnson", "123456789012", "S101", "EN2023001", "CSE", "/public/img/placeholder_student_alice.png");
$studentModel->createStudent("Bob Williams", "David Williams", "Sarah Williams", "987654321098", "S102", "EN2023002", "ECE", "/public/img/placeholder_student_bob.png");

$allStudents = $studentModel->getAllStudents();
foreach ($allStudents as $student) {
    $encryptedStudentId = encryptData($student['student_id']);
    $studentModel->updateStudentQRCode($student['student_id'], $encryptedStudentId);
    $qrCodeFileName = 'student_' . $student['student_id'] . '.png';
    $qrCodeFilePath = __DIR__ . '/public/qr_codes/' . $qrCodeFileName;
    generateQRCode(getStudentQrScanUrl($encryptedStudentId), $qrCodeFilePath);
    echo "<p>Student: " . htmlspecialchars($student['full_name']) . " (Roll No: " . htmlspecialchars($student['roll_number']) . ") created with QR.</p>";

    // Add some sample marks for students
    $studentMarksModel->addOrUpdateMarks($student['student_id'], [
        'marks_10th' => 85.5,
        'marks_12th' => 78.0,
        'marks_semester_1' => 75.2
    ]);
    echo "<p>Marks added for " . htmlspecialchars($student['full_name']) . ".</p>";
}

// 3. Create placeholder images
if (!file_exists(__DIR__ . '/public/img/placeholder_student_alice.png')) {
    file_put_contents(__DIR__ . '/public/img/placeholder_student_alice.png', "Placeholder for Alice's photo");
}
if (!file_exists(__DIR__ . '/public/img/placeholder_student_bob.png')) {
    file_put_contents(__DIR__ . '/public/img/placeholder_student_bob.png', "Placeholder for Bob's photo");
}
if (!file_exists(__DIR__ . '/public/img/default-avatar.png')) {
    file_put_contents(__DIR__ . '/public/img/default-avatar.png', 'Default avatar');
}

echo "<h2>Sample Data Generation Complete!</h2>";
echo "<p>You can now try logging in:</p>";
echo "<ul>";
echo "<li>Staff Login: Username `john.doe`, Password `staff123`</li>";
echo "<li>Student Login: Use the QR code generated for Alice Johnson or Bob Williams via QR scanner.</li>";
echo "</ul>";
