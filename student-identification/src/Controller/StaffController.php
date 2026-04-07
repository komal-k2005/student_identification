<?php
// src/Controller/StaffController.php

require_once __DIR__ . '/../Model/Staff.php';
require_once __DIR__ . '/../Model/Student.php';
require_once __DIR__ . '/../Model/Attendance.php';
require_once __DIR__ . '/../Model/StudentMarks.php';
require_once __DIR__ . '/../../includes/functions.php';

class StaffController
{
    private $pdo;
    private $staffModel;
    private $studentModel;
    private $attendanceModel;
    private $studentMarksModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->staffModel = new Staff($pdo);
        $this->studentModel = new Student($pdo);
        $this->attendanceModel = new Attendance($pdo);
        $this->studentMarksModel = new StudentMarks($pdo);
    }

    public function dashboard()
    {
        AuthController::checkStaffAuth();
        require_once __DIR__ . '/../View/staff/dashboard.php';
    }

    public function viewStaffDetails()
    {
        AuthController::checkStaffAuth();
        $staffId = $_SESSION['user_id'];
        $staff = $this->staffModel->getStaffById($staffId);

        if (!$staff) {
            // Handle error: Staff not found
            header('Location: ' . BASE_URL . '/staff/dashboard.php?error=Staff not found.');
            exit();
        }

        // Generate QR code logic (moved from view to controller)
        $qrCodePath = '';
        if (empty($staff['qr_code_data'])) {
            $encryptedStaffId = encryptData($staffId);
            if ($encryptedStaffId !== '') {
                $this->staffModel->updateStaffQRCode($staffId, $encryptedStaffId);
                $staff['qr_code_data'] = $encryptedStaffId;
            }
        }

        if (!empty($staff['qr_code_data'])) {
            $qrCodeFileName = 'staff_' . $staffId . '.png';
            $qrCodeFilePath = __DIR__ . '/../../public/qr_codes/' . $qrCodeFileName;
            // Always regenerate to ensure the current local IP is used for mobile scanning
            generateQRCode(getStaffQrScanUrl($staff['qr_code_data']), $qrCodeFilePath);
            $qrCodePath = BASE_URL . '/public/qr_codes/' . $qrCodeFileName;
        }
        else {
            $qrCodePath = BASE_URL . '/public/img/placeholder_qr.png'; // Fallback
        }

        require_once __DIR__ . '/../View/staff/view_staff.php';
    }

    public function addStudent()
    {
        AuthController::checkStaffAuth();
        $errors = [];
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
            $fatherName = filter_input(INPUT_POST, 'father_name', FILTER_SANITIZE_STRING);
            $motherName = filter_input(INPUT_POST, 'mother_name', FILTER_SANITIZE_STRING);
            $aadhaarNumber = filter_input(INPUT_POST, 'aadhaar_number', FILTER_SANITIZE_STRING);
            $rollNumber = filter_input(INPUT_POST, 'roll_number', FILTER_SANITIZE_STRING);
            $enrollmentNumber = filter_input(INPUT_POST, 'enrollment_number', FILTER_SANITIZE_STRING);
            $branch = filter_input(INPUT_POST, 'branch', FILTER_SANITIZE_STRING);

            // Basic validation
            if (empty($fullName) || empty($aadhaarNumber) || empty($rollNumber) || empty($enrollmentNumber) || empty($branch)) {
                $errors[] = "All required fields must be filled.";
            }

            // Handle photo upload
            $photoPath = null;
            if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] === UPLOAD_ERR_OK) {
                $targetDir = __DIR__ . "/../../public/img/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fileName = uniqid('student_photo_') . '_' . basename($_FILES['student_photo']['name']);
                $targetFilePath = $targetDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                // Allow certain file formats
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES['student_photo']['tmp_name'], $targetFilePath)) {
                        $photoPath = '/public/img/' . $fileName;
                    }
                    else {
                        $errors[] = "Sorry, there was an error uploading your file.";
                    }
                }
                else {
                    $errors[] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
                }
            }

            if (empty($errors)) {
                if ($this->studentModel->createStudent($fullName, $fatherName, $motherName, $aadhaarNumber, $rollNumber, $enrollmentNumber, $branch, $photoPath)) {
                    $success = "Student added successfully!";
                    header('Location: ' . BASE_URL . '/staff/list_students.php?success=' . urlencode($success));
                    exit();
                }
                else {
                    $errors[] = "Error adding student to database.";
                }
            }
        }
        require_once __DIR__ . '/../View/staff/add_student.php';
    }

    public function editStudent()
    {
        AuthController::checkStaffAuth();
        $errors = [];
        $success = '';
        $studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $student = null;

        if ($studentId) {
            $student = $this->studentModel->getStudentById($studentId);
            if (!$student) {
                $errors[] = "Student not found.";
            }
        }
        else {
            $errors[] = "Invalid student ID.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $student) {
            $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
            $fatherName = filter_input(INPUT_POST, 'father_name', FILTER_SANITIZE_STRING);
            $motherName = filter_input(INPUT_POST, 'mother_name', FILTER_SANITIZE_STRING);
            $aadhaarNumber = filter_input(INPUT_POST, 'aadhaar_number', FILTER_SANITIZE_STRING);
            $rollNumber = filter_input(INPUT_POST, 'roll_number', FILTER_SANITIZE_STRING);
            $enrollmentNumber = filter_input(INPUT_POST, 'enrollment_number', FILTER_SANITIZE_STRING);
            $branch = filter_input(INPUT_POST, 'branch', FILTER_SANITIZE_STRING);
            $existingPhotoPath = $student['photo_path'];

            if (empty($fullName) || empty($aadhaarNumber) || empty($rollNumber) || empty($enrollmentNumber) || empty($branch)) {
                $errors[] = "All required fields must be filled.";
            }

            // Handle photo upload
            $photoPath = $existingPhotoPath; // Default to existing photo
            if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] === UPLOAD_ERR_OK) {
                $targetDir = __DIR__ . "/../../public/img/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fileName = uniqid('student_photo_') . '_' . basename($_FILES['student_photo']['name']);
                $targetFilePath = $targetDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES['student_photo']['tmp_name'], $targetFilePath)) {
                        $photoPath = '/public/img/' . $fileName;
                    }
                    else {
                        $errors[] = "Sorry, there was an error uploading your new file.";
                    }
                }
                else {
                    $errors[] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
                }
            }

            if (empty($errors)) {
                if ($this->studentModel->updateStudent($studentId, $fullName, $fatherName, $motherName, $aadhaarNumber, $rollNumber, $enrollmentNumber, $branch, $photoPath)) {
                    $success = "Student updated successfully!";
                    header('Location: ' . BASE_URL . '/staff/list_students.php?success=' . urlencode($success));
                    exit();
                }
                else {
                    $errors[] = "Error updating student in database.";
                }
            }
        }

        require_once __DIR__ . '/../View/staff/edit_student.php';
    }

    public function listStudents()
    {
        AuthController::checkStaffAuth();
        $students = $this->studentModel->getAllStudents();
        require_once __DIR__ . '/../View/staff/list_students.php';
    }

    public function viewStudentDetails()
    {
        AuthController::checkStaffAuth();
        $studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $student = null;
        $marks = [];
        $attendance = [];
        $error = '';

        if ($studentId) {
            $student = $this->studentModel->getStudentById($studentId);
            if ($student) {
                $marks = $this->studentMarksModel->getMarksByStudentId($studentId);
                $attendance = $this->attendanceModel->getStudentAttendance($studentId);
            }
            else {
                $error = "Student not found.";
            }
        }
        else {
            $error = "Invalid student ID.";
        }

        require_once __DIR__ . '/../View/staff/view_student_details.php';
    }

    public function generateStudentQrCode()
    {
        AuthController::checkStaffAuth();
        $studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $student = null;
        $qrCodePath = '';
        $error = '';

        if ($studentId) {
            $student = $this->studentModel->getStudentById($studentId);
            if (!$student) {
                $error = "Student not found.";
            }
            else {
                if (empty($student['qr_code_data'])) {
                    $encryptedStudentId = encryptData($studentId);
                    if ($encryptedStudentId !== '') {
                        $this->studentModel->updateStudentQRCode($studentId, $encryptedStudentId);
                        $student['qr_code_data'] = $encryptedStudentId;
                    }
                }
                if (!empty($student['qr_code_data'])) {
                    $qrCodeFileName = 'student_' . $studentId . '.png';
                    $qrCodeFilePath = __DIR__ . '/../../public/qr_codes/' . $qrCodeFileName;

                    generateQRCode(getStudentQrScanUrl($student['qr_code_data']), $qrCodeFilePath);
                    $qrCodePath = BASE_URL . '/public/qr_codes/' . $qrCodeFileName;
                }
                else {
                    $error = "Failed to generate QR code data.";
                }
            }
        }
        else {
            $error = "Invalid student ID.";
        }

        require_once __DIR__ . '/../View/staff/generate_qr.php';
    }

    public function markAttendance()
    {
        AuthController::checkStaffAuth();
        $errors = [];
        $success = '';
        $scannedStudent = null;
        $staffId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qrCodeData = filter_input(INPUT_POST, 'qr_code_data', FILTER_SANITIZE_STRING);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);

            if (empty($qrCodeData) || empty($subject)) {
                $errors[] = "QR Code data and Subject are required.";
            }
            else {
                $qrCodeData = parseQrDataFromScan($qrCodeData) ?? $qrCodeData;
                $decryptedStudentId = decryptData($qrCodeData);
                if ($decryptedStudentId !== false) {
                    $student = $this->studentModel->getStudentById((int)$decryptedStudentId);
                    if ($student) {
                        if ($this->attendanceModel->markAttendance($student['student_id'], $staffId, $subject)) {
                            $success = "Attendance marked successfully for " . htmlspecialchars($student['full_name']) . ".";
                            $scannedStudent = $student;
                        }
                        else {
                            $errors[] = "Failed to mark attendance.";
                        }
                    }
                    else {
                        $errors[] = "No student found for the scanned QR code.";
                    }
                }
                else {
                    $errors[] = "Invalid or unreadable QR Code data.";
                }
            }
        }
        require_once __DIR__ . '/../View/staff/mark_attendance.php';
    }

    public function addStudentMarks()
    {
        AuthController::checkStaffAuth();
        $errors = [];
        $success = '';
        $studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $student = null;
        $currentMarks = [];
        $currentMaxSemester = 0; // The highest semester for which marks have been entered

        if ($studentId) {
            $student = $this->studentModel->getStudentById($studentId);
            if (!$student) {
                $errors[] = "Student not found.";
            }
            else {
                $currentMarks = $this->studentMarksModel->getMarksByStudentId($studentId);
                if ($currentMarks) {
                    // Determine the highest semester for which marks are entered
                    for ($i = 8; $i >= 1; $i--) {
                        if (!empty($currentMarks['marks_semester_' . $i])) {
                            $currentMaxSemester = $i;
                            break;
                        }
                    }
                    // If 12th marks are present, but no semester marks, assume ready for sem 1
                    if ($currentMaxSemester === 0 && !empty($currentMarks['marks_12th'])) {
                        $currentMaxSemester = 0; // Can enter 10th, 12th, or Sem 1, so 0 for flexibility initially.
                    }
                    // If 10th marks are present, but no 12th or semester marks
                    if ($currentMaxSemester === 0 && !empty($currentMarks['marks_10th'])) {
                        $currentMaxSemester = 0;
                    }
                }
            }
        }
        else {
            $errors[] = "Invalid student ID.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $student) {
            $marksData = [];

            $marksData['marks_10th'] = filter_input(INPUT_POST, 'marks_10th', FILTER_VALIDATE_FLOAT);
            $marksData['marks_12th'] = filter_input(INPUT_POST, 'marks_12th', FILTER_VALIDATE_FLOAT);

            // Collect semester marks with validation based on previous semester completion
            for ($i = 1; $i <= 8; $i++) {
                $inputMark = filter_input(INPUT_POST, 'marks_semester_' . $i, FILTER_VALIDATE_FLOAT);

                // Only allow setting a mark if previous semester (or 12th) has a value, or it's the first semester
                $canEnter = false;
                if ($i === 1) { // First semester can always be entered after 12th or 10th
                    $canEnter = true;
                }
                elseif ($i > 1 && isset($currentMarks['marks_semester_' . ($i - 1)]) && $currentMarks['marks_semester_' . ($i - 1)] !== null) {
                    $canEnter = true;
                }

                if ($canEnter) {
                    $marksData['marks_semester_' . $i] = $inputMark;
                }
                else if ($inputMark !== null) {
                    $errors[] = "Cannot enter marks for Semester " . $i . " before completing previous semester.";
                }
            }

            if (empty($errors)) {
                if ($this->studentMarksModel->addOrUpdateMarks($studentId, $marksData)) {
                    $success = "Student marks updated successfully!";
                    // Re-fetch marks to reflect changes in UI after update
                    $currentMarks = $this->studentMarksModel->getMarksByStudentId($studentId);
                    // Re-calculate currentMaxSemester after update
                    for ($i = 8; $i >= 1; $i--) {
                        if (!empty($currentMarks['marks_semester_' . $i])) {
                            $currentMaxSemester = $i;
                            break;
                        }
                    }
                }
                else {
                    $errors[] = "Error updating student marks.";
                }
            }
        }
        require_once __DIR__ . '/../View/staff/add_marks.php';
    }

    public function createStaff()
    {
        AuthController::checkStaffAuth();
        $errors = [];
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $staffName = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $education = filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING);
            $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING);

            if (empty($username) || empty($password) || empty($staffName)) {
                $errors[] = "Username, Password, and Staff Name are required.";
            }

            if (empty($errors)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                if ($this->staffModel->createStaff($username, $passwordHash, $staffName, $subject, $education, $department)) {
                    $success = "Staff account created successfully!";
                    // Generate QR for staff immediately
                    $newStaff = $this->staffModel->getStaffByUsername($username);
                    if ($newStaff) {
                        $encryptedStaffId = encryptData($newStaff['staff_id']);
                        if ($encryptedStaffId !== '') {
                            $this->staffModel->updateStaffQRCode($newStaff['staff_id'], $encryptedStaffId);
                            $qrCodeFileName = 'staff_' . $newStaff['staff_id'] . '.png';
                            $qrCodeFilePath = __DIR__ . '/../../public/qr_codes/' . $qrCodeFileName;
                            generateQRCode(getStaffQrScanUrl($encryptedStaffId), $qrCodeFilePath);
                        }
                    }
                }
                else {
                    $errors[] = "Error creating staff account. Username might already exist.";
                }
            }
        }
        require_once __DIR__ . '/../View/admin/create_staff.php';
    }

    public function listStaff()
    {
        AuthController::checkStaffAuth();
        $staffList = $this->staffModel->getAllStaff();
        require_once __DIR__ . '/../View/admin/list_staff.php';
    }

    public function activateStaff()
    {
        AuthController::checkStaffAuth();
        $staffId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($staffId) {
            $this->staffModel->activateStaff($staffId);
            header('Location: ' . BASE_URL . '/admin/list_staff.php?success=' . urlencode("Staff activated successfully."));
            exit();
        }
        header('Location: ' . BASE_URL . '/admin/list_staff.php?error=' . urlencode("Invalid staff ID."));
        exit();
    }

    public function deleteStudent()
    {
        AuthController::checkStaffAuth();
        $studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($studentId) {
            if ($this->studentModel->deleteStudent($studentId)) {
                $success = "Student deleted successfully.";
                // Delete photo logic could be added here if we track file usage
                header('Location: ' . BASE_URL . '/staff/list_students.php?success=' . urlencode($success));
                exit();
            }
            else {
                header('Location: ' . BASE_URL . '/staff/list_students.php?error=' . urlencode('Failed to delete student.'));
                exit();
            }
        }
        header('Location: ' . BASE_URL . '/staff/list_students.php?error=' . urlencode('Invalid student ID.'));
        exit();
    }

    public function deactivateStaff()
    {
        AuthController::checkStaffAuth();
        $staffId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($staffId) {
            $this->staffModel->deactivateStaff($staffId);
            header('Location: ' . BASE_URL . '/admin/list_staff.php?success=' . urlencode("Staff deactivated successfully."));
            exit();
        }
        header('Location: ' . BASE_URL . '/admin/list_staff.php?error=' . urlencode("Invalid staff ID."));
        exit();
    }

    public function exportStudentsCsv()
    {
        AuthController::checkStaffAuth();
        $students = $this->studentModel->getAllStudents();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="students_list_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Student ID', 'Full Name', 'Father Name', 'Mother Name', 'Aadhaar Number', 'Roll Number', 'Enrollment Number', 'Branch']);

        foreach ($students as $student) {
            fputcsv($output, [
                $student['student_id'],
                $student['full_name'],
                $student['father_name'],
                $student['mother_name'],
                $student['aadhaar_number'],
                $student['roll_number'],
                $student['enrollment_number'],
                $student['branch']
            ]);
        }
        fclose($output);
        exit();
    }

    public function exportStaffCsv()
    {
        AuthController::checkStaffAuth();
        $staffList = $this->staffModel->getAllStaff();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="staff_list_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Staff ID', 'Username', 'Name', 'Subject', 'Education', 'Department', 'Is Active']);

        foreach ($staffList as $staff) {
            fputcsv($output, [
                $staff['staff_id'],
                $staff['username'],
                $staff['staff_name'],
                $staff['subject'],
                $staff['education'],
                $staff['department'],
                $staff['is_active'] ? 'Yes' : 'No'
            ]);
        }
        fclose($output);
        exit();
    }

    public function importStudentsCsv()
    {
        AuthController::checkStaffAuth();
        $successCount = 0;
        $errorCount = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];

            if (($handle = fopen($fileTmpPath, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    // Expecting: Full Name, Father's Name, Mother's Name, Aadhaar Number, Roll Number, Enrollment Number, Branch
                    if (count($data) >= 7) {
                        $fullName = trim($data[0]);
                        $fatherName = trim($data[1]);
                        $motherName = trim($data[2]);
                        $aadhaarNumber = trim($data[3]);
                        $rollNumber = trim($data[4]);
                        $enrollmentNumber = trim($data[5]);
                        $branch = trim($data[6]);

                        // Skip empty rows
                        if (empty($fullName) || empty($rollNumber)) {
                            continue;
                        }

                        if ($this->studentModel->createStudent($fullName, $fatherName, $motherName, $aadhaarNumber, $rollNumber, $enrollmentNumber, $branch, null)) {
                            $successCount++;
                        }
                        else {
                            $errorCount++;
                        }
                    }
                }
                fclose($handle);
            }
            $msg = urlencode("Import Complete. Successfully added: $successCount. Failed: $errorCount.");
            header('Location: ' . BASE_URL . '/staff/list_students.php?success=' . $msg);
            exit();
        }

        header('Location: ' . BASE_URL . '/staff/list_students.php?error=' . urlencode('Invalid file upload.'));
        exit();
    }

    public function importStaffCsv()
    {
        AuthController::checkStaffAuth();
        $successCount = 0;
        $errorCount = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];

            if (($handle = fopen($fileTmpPath, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    // Expecting: Username, Password, Name, Subject, Education, Department
                    if (count($data) >= 6) {
                        $username = trim($data[0]);
                        $password = trim($data[1]);
                        $staffName = trim($data[2]);
                        $subject = trim($data[3]);
                        $education = trim($data[4]);
                        $department = trim($data[5]);

                        // Skip empty rows
                        if (empty($username) || empty($password) || empty($staffName)) {
                            continue;
                        }

                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        if ($this->staffModel->createStaff($username, $passwordHash, $staffName, $subject, $education, $department)) {
                            // Automatically generate the QR Code for new staff
                            $newStaff = $this->staffModel->getStaffByUsername($username);
                            if ($newStaff) {
                                $encryptedStaffId = encryptData($newStaff['staff_id']);
                                if ($encryptedStaffId !== '') {
                                    $this->staffModel->updateStaffQRCode($newStaff['staff_id'], $encryptedStaffId);
                                    $qrCodeFileName = 'staff_' . $newStaff['staff_id'] . '.png';
                                    $qrCodeFilePath = __DIR__ . '/../../public/qr_codes/' . $qrCodeFileName;
                                    generateQRCode(getStaffQrScanUrl($encryptedStaffId), $qrCodeFilePath);
                                }
                            }
                            $successCount++;
                        }
                        else {
                            $errorCount++;
                        }
                    }
                }
                fclose($handle);
            }
            $msg = urlencode("Import Complete. Successfully added: $successCount. Failed or duplicates skipped: $errorCount.");
            header('Location: ' . BASE_URL . '/admin/list_staff.php?success=' . $msg);
            exit();
        }

        header('Location: ' . BASE_URL . '/admin/list_staff.php?error=' . urlencode('Invalid file upload.'));
        exit();
    }
}
