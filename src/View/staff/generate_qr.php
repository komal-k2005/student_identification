<?php
// src/View/staff/generate_qr.php
require_once __DIR__ . '/../../../includes/header.php';
// $student, $qrCodePath, $error are passed from StaffController::generateStudentQrCode()
?>

<h2 class="mb-4">Generate Student QR Code</h2>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if ($student && $qrCodePath): ?>
    <div class="card p-4 text-center">
        <h5 class="card-title">QR Code for: <?php echo htmlspecialchars($student['full_name']); ?> (Roll No: <?php echo htmlspecialchars($student['roll_number']); ?>)</h5>
        <img src="<?php echo htmlspecialchars($qrCodePath); ?>" alt="Student QR Code" class="img-fluid mx-auto mt-3 border p-2" style="max-width: 250px;">
        <p class="mt-3">The QR code encodes the encrypted student ID.</p>
        <a href="<?php echo BASE_URL; ?>/staff/list_students.php" class="btn btn-primary mt-3">Back to Student List</a>
    </div>
<?php else: ?>
    <div class="alert alert-info">Select a student from the <a href="<?php echo BASE_URL; ?>/staff/list_students.php">student list</a> to generate QR code.</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
