<?php
// public/mark_attendance_guest.php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../src/Model/Attendance.php';
require_once __DIR__ . '/../src/Model/Student.php';
require_once __DIR__ . '/../src/Model/Staff.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qrCodeData = filter_input(INPUT_POST, 'qr_code_data', FILTER_SANITIZE_STRING);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $secretCode = filter_input(INPUT_POST, 'secret_code', FILTER_SANITIZE_STRING);

    if (empty($qrCodeData) || empty($subject) || empty($secretCode)) {
        $error = "QR Code data, Subject, and Secret Code are all required.";
    }
    else {
        // Find staff by secret code (we'll implement this check)
        $staffModel = new Staff($pdo);
        // Using getStaffBySecretCode - we will add this in the Staff Model
        $staffId = $staffModel->verifyStaffSecretCode($secretCode, $subject);

        if (!$staffId) {
            $error = "Invalid Secret Code or Subject. Attendance not marked.";
        }
        else {
            $qrCodeData = parseQrDataFromScan($qrCodeData) ?? $qrCodeData;
            $decryptedStudentId = decryptData($qrCodeData);

            if ($decryptedStudentId !== false) {
                $studentModel = new Student($pdo);
                $student = $studentModel->getStudentById((int)$decryptedStudentId);

                if ($student) {
                    $attendanceModel = new Attendance($pdo);
                    if ($attendanceModel->markAttendance($student['student_id'], $staffId, $subject)) {
                        $success = "Attendance marked successfully for " . htmlspecialchars($student['full_name']) . " (" . htmlspecialchars($subject) . ").";
                    }
                    else {
                        $error = "Failed to mark attendance. It may already be marked for today.";
                    }
                }
                else {
                    $error = "No student found for the scanned QR code.";
                }
            }
            else {
                $error = "Invalid or unreadable QR Code data.";
            }
        }
    }
}
else {
    // Redirect GET requests back to scan or home
    header("Location: " . BASE_URL . "/public/index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <style>
        .result-icon {
            font-size: 4rem;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-pilled">
                    <div class="card-body text-center p-5">
                        <?php if ($success): ?>
                            <div class="text-success mb-3 result-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                            </div>
                            <h4 class="mb-3 text-success">Success!</h4>
                            <p class="text-muted"><?php echo htmlspecialchars($success); ?></p>
                            <a href="<?php echo BASE_URL; ?>/public/scan.php?type=student&id=<?php echo urlencode($_POST['qr_code_data'] ?? ''); ?>" class="btn btn-outline-primary mt-4 w-100">Scan Next Student</a>
                        <?php
elseif ($error): ?>
                            <div class="text-danger mb-3 result-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                </svg>
                            </div>
                            <h4 class="mb-3 text-danger">Error</h4>
                            <p class="text-muted"><?php echo htmlspecialchars($error); ?></p>
                            <button onclick="window.history.back()" class="btn btn-outline-danger mt-4 w-100">Go Back & Try Again</button>
                        <?php
endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
