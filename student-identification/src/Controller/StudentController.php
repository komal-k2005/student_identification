<?php
// src/Controller/StudentController.php

require_once __DIR__ . '/../Model/Student.php';
require_once __DIR__ . '/../Model/Attendance.php';
require_once __DIR__ . '/../Model/StudentMarks.php';
require_once __DIR__ . '/../../includes/functions.php';

class StudentController {
    private $pdo;
    private $studentModel;
    private $attendanceModel;
    private $studentMarksModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->studentModel = new Student($pdo);
        $this->attendanceModel = new Attendance($pdo);
        $this->studentMarksModel = new StudentMarks($pdo);
    }

    public function loginWithQrCode() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qrCodeData = filter_input(INPUT_POST, 'qr_code_data', FILTER_SANITIZE_STRING);

            if (empty($qrCodeData)) {
                $error = "Please scan your QR code.";
            } else {
                $qrCodeData = parseQrDataFromScan($qrCodeData) ?? $qrCodeData;
                $decryptedStudentId = decryptData($qrCodeData);
                if ($decryptedStudentId !== false) {
                    $student = $this->studentModel->getStudentById((int)$decryptedStudentId);

                    if ($student) {
                        $_SESSION['user_id'] = $student['student_id'];
                        $_SESSION['full_name'] = $student['full_name'];
                        $_SESSION['user_type'] = 'student';
                        session_regenerate_id(true);

                        // Store session in database
                        $this->storeSession('student', $student['student_id']);

                        header('Location: ' . BASE_URL . '/student/profile.php');
                        exit();
                    } else {
                        $error = "Invalid QR Code. Student not found.";
                    }
                } else {
                    $error = "Invalid QR Code data.";
                }
            }
        }
        require_once __DIR__ . '/../View/student/qr_login.php';
    }

    public function viewProfile() {
        AuthController::checkStudentAuth();
        $studentId = $_SESSION['user_id'];
        $student = $this->studentModel->getStudentById($studentId);

        if (!$student) {
            // Handle error: Student not found
            header('Location: ' . BASE_URL . '/public/index.php?action=logout'); // Redirect to logout if student not found
            exit();
        }

        require_once __DIR__ . '/../View/student/profile.php';
    }

    public function viewSemesterMarks() {
        AuthController::checkStudentAuth();
        $studentId = $_SESSION['user_id'];
        $studentMarks = $this->studentMarksModel->getMarksByStudentId($studentId);

        require_once __DIR__ . '/../View/student/view_marks.php';
    }

    public function viewAttendance() {
        AuthController::checkStudentAuth();
        $studentId = $_SESSION['user_id'];
        $attendanceRecords = $this->attendanceModel->getStudentAttendance($studentId);

        require_once __DIR__ . '/../View/student/view_attendance.php';
    }

    public function downloadQrIdCard() {
        AuthController::checkStudentAuth();
        $studentId = $_SESSION['user_id'];
        $student = $this->studentModel->getStudentById($studentId);

        if (!$student || empty($student['qr_code_data'])) {
            // Handle error: Student or QR data not found
            header('Location: ' . BASE_URL . '/student/profile.php?error=' . urlencode("QR Code not available."));
            exit();
        }

        $qrCodeFileName = 'student_' . $studentId . '.png';
        $qrCodeFilePath = __DIR__ . '/../../public/qr_codes/' . $qrCodeFileName;

        if (!file_exists($qrCodeFilePath) || !isValidPngFile($qrCodeFilePath)) {
            generateQRCode(getStudentQrScanUrl($student['qr_code_data']), $qrCodeFilePath);
        }

        // Serve the QR code file for download
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="Student_QR_ID_Card_' . $student['roll_number'] . '.png"');
        if (file_exists($qrCodeFilePath) && isValidPngFile($qrCodeFilePath)) {
            readfile($qrCodeFilePath);
        } else {
            // Fallback if QR code file is somehow missing
            error_log("QR Code file not found for download: " . $qrCodeFilePath);
            header('Location: ' . BASE_URL . '/student/profile.php?error=' . urlencode("QR Code file not found."));
        }
        exit();
    }

    private function storeSession(string $userType, int $userId) {
        $sessionId = session_id();
        $stmt = $this->pdo->prepare("INSERT INTO sessions (session_id, user_id, user_type) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE user_id = ?, user_type = ?, last_activity = CURRENT_TIMESTAMP()");
        $stmt->execute([$sessionId, $userId, $userType, $userId, $userType]);
        $_SESSION['session_id'] = $sessionId;
    }
}
