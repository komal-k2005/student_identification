<?php
// public/scan.php - QR scan landing page (student or staff)
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../src/Model/Staff.php';
require_once __DIR__ . '/../src/Model/Student.php';

$type = $_GET['type'] ?? '';
$encryptedId = $_GET['id'] ?? '';
$error = '';
$student = null;
$staff = null;

if (empty($type) || empty($encryptedId)) {
    $error = "Invalid QR code. Please scan a valid student or staff QR code.";
}
else {
    $decryptedId = decryptData($encryptedId);
    if ($decryptedId === false) {
        $error = "Invalid or expired QR code.";
    }
    elseif ($type === 'student') {
        $studentModel = new Student($pdo);
        $student = $studentModel->getStudentById((int)$decryptedId);
        if (!$student) {
            $error = "Student not found.";
        }
    }
    elseif ($type === 'staff') {
        $staffModel = new Staff($pdo);
        $staff = $staffModel->getStaffById((int)$decryptedId);
        if (!$staff) {
            $error = "Staff not found.";
        }
    }
    else {
        $error = "Invalid QR code type.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scan - <?php echo $student ? 'Student' : ($staff ? 'Staff' : 'Result'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <?php if ($error): ?>
                    <div class="card shadow">
                        <div class="card-body text-center py-5">
                            <div class="text-danger mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .065.016.146.146 0 0 1 .052.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.947 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                                </svg>
                            </div>
                            <h5><?php echo htmlspecialchars($error); ?></h5>
                            <a href="<?php echo BASE_URL; ?>/public/index.php" class="btn btn-primary mt-3">Go to Login</a>
                        </div>
                    </div>
                <?php
elseif ($student): ?>
                    <!-- Student QR scanned - show 2 options -->
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><?php echo htmlspecialchars($student['full_name']); ?> (<?php echo htmlspecialchars($student['roll_number']); ?>)</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Choose an action:</p>
                            <div class="d-grid gap-3">
                                <!-- Option 1: View Student Details -->
                                <a href="<?php echo BASE_URL; ?>/public/scan.php?type=student&id=<?php echo urlencode($encryptedId); ?>&view=1" class="btn btn-outline-primary btn-lg">1. View Student Details</a>

                                <!-- Option 2: Mark Attendance (No Login Required) -->
                                <div class="card border border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">2. Mark Attendance (Quick)</h6>
                                        <p class="card-text small text-muted">Enter your assigned subject and your Username (or Password) as the secret code.</p>
                                        <form action="<?php echo BASE_URL; ?>/public/mark_attendance_guest.php" method="POST" class="mt-2">
                                            <input type="hidden" name="qr_code_data" value="<?php echo htmlspecialchars($encryptedId); ?>">
                                            <div class="mb-2">
                                                <input type="text" name="subject" class="form-control" placeholder="Enter subject name" required>
                                            </div>
                                            <div class="mb-2">
                                                <input type="password" name="secret_code" class="form-control" placeholder="Enter secret code" required>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100">Mark Attendance</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
elseif ($staff): ?>
                    <!-- Staff QR scanned - show 2 options -->
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><?php echo htmlspecialchars($staff['staff_name']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Choose an action:</p>
                            <div class="d-grid gap-3">
                                <!-- Option 1: View Staff Info -->
                                <a href="<?php echo BASE_URL; ?>/public/scan.php?type=staff&id=<?php echo urlencode($encryptedId); ?>&view=1" class="btn btn-outline-primary btn-lg">1. View Staff Info</a>
                                <!-- Option 2: Login -->
                                <a href="<?php echo BASE_URL; ?>/public/index.php" class="btn btn-outline-success btn-lg">2. Staff Login</a>
                            </div>
                        </div>
                    </div>
                <?php
endif; ?>

                <?php
// View mode - show full details
$view = isset($_GET['view']) && $_GET['view'] == '1';
if ($view && ($student || $staff)):
?>
                    <div class="card shadow mt-4">
                        <div class="card-header">
                            <h5 class="mb-0"><?php echo $student ? 'Student Details' : 'Staff Details'; ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if ($student): ?>
                                <div class="row">
                                    <?php if (!empty($student['photo_path'])): ?>
                                        <div class="col-md-4 text-center mb-3">
                                            <?php
            // Extract filename to ensure it works across different host IPs instead of relying on saved 'localhost' paths
            $photoSrc = BASE_URL . '/public/img/' . basename($student['photo_path']);
?>
                                            <img src="<?php echo htmlspecialchars($photoSrc); ?>" alt="Photo" class="img-fluid rounded" style="max-height: 150px;">
                                        </div>
                                    <?php
        endif; ?>
                                    <div class="col">
                                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($student['full_name']); ?></p>
                                        <p><strong>Father:</strong> <?php echo htmlspecialchars($student['father_name'] ?? 'N/A'); ?></p>
                                        <p><strong>Mother:</strong> <?php echo htmlspecialchars($student['mother_name'] ?? 'N/A'); ?></p>
                                        <p><strong>Roll No:</strong> <?php echo htmlspecialchars($student['roll_number']); ?></p>
                                        <p><strong>Enrollment:</strong> <?php echo htmlspecialchars($student['enrollment_number'] ?? 'N/A'); ?></p>
                                        <p><strong>Branch:</strong> <?php echo htmlspecialchars($student['branch'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            <?php
    elseif ($staff): ?>
                                <p><strong>Staff Name:</strong> <?php echo htmlspecialchars($staff['staff_name']); ?></p>
                                <p><strong>Subject:</strong> <?php echo htmlspecialchars($staff['subject'] ?? 'N/A'); ?></p>
                                <p><strong>Education:</strong> <?php echo htmlspecialchars($staff['education'] ?? 'N/A'); ?></p>
                                <p><strong>Department:</strong> <?php echo htmlspecialchars($staff['department'] ?? 'N/A'); ?></p>
                                <p><strong>Staff ID:</strong> <?php echo htmlspecialchars($staff['staff_id']); ?></p>
                            <?php
    endif; ?>
                            <a href="<?php echo BASE_URL; ?>/public/scan.php?type=<?php echo $student ? 'student' : 'staff'; ?>&id=<?php echo urlencode($encryptedId); ?>" class="btn btn-secondary mt-3">← Back to Options</a>
                        </div>
                    </div>
                <?php
endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
