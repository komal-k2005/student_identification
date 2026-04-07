<?php
// src/View/student/profile.php
require_once __DIR__ . '/../../../includes/header.php';
// $student is passed from StudentController::viewProfile()
?>

<h2 class="mb-4">Student Profile</h2>

<?php if (!empty($student)): ?>
    <div class="card mb-4">
        <div class="card-header">
            Personal Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <?php if (!empty($student['photo_path'])): ?>
                        <?php $photoSrc = BASE_URL . '/public/img/' . basename($student['photo_path']); ?>
                        <img src="<?php echo htmlspecialchars($photoSrc); ?>" alt="Student Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php
    else: ?>
                        <img src="<?php echo BASE_URL; ?>/public/img/default-avatar.png" alt="Default Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php
    endif; ?>
                    <p class="mb-0"><strong><?php echo htmlspecialchars($student['full_name']); ?></strong></p>
                    <p class="text-muted"><?php echo htmlspecialchars($student['roll_number']); ?></p>
                </div>
                <div class="col-md-9">
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($student['full_name']); ?></p>
                    <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($student['father_name'] ?? 'N/A'); ?></p>
                    <p><strong>Mother's Name:</strong> <?php echo htmlspecialchars($student['mother_name'] ?? 'N/A'); ?></p>
                    <p><strong>Aadhaar Number:</strong> <?php echo htmlspecialchars($student['aadhaar_number']); ?></p>
                    <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll_number']); ?></p>
                    <p><strong>Enrollment Number:</strong> <?php echo htmlspecialchars($student['enrollment_number']); ?></p>
                    <p><strong>Branch:</strong> <?php echo htmlspecialchars($student['branch']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View Semester Marks</h5>
                    <p class="card-text">Access your academic performance records.</p>
                    <a href="<?php echo BASE_URL; ?>/student/view_marks.php" class="btn btn-info">View Marks</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View Attendance</h5>
                    <p class="card-text">Check your attendance history.</p>
                    <a href="<?php echo BASE_URL; ?>/student/view_attendance.php" class="btn btn-warning">View Attendance</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Download QR ID Card</h5>
                    <p class="card-text">Get your printable QR-based identification card.</p>
                    <a href="<?php echo BASE_URL; ?>/student/download_qr.php" class="btn btn-success">Download ID Card</a>
                </div>
            </div>
        </div>
    </div>

<?php
else: ?>
    <div class="alert alert-danger">Student profile could not be loaded.</div>
<?php
endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
