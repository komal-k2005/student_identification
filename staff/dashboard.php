<?php
// staff/dashboard.php
require_once __DIR__ . '/../includes/header.php';
?>

<h2 class="mb-4">Staff Dashboard</h2>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View Staff Details</h5>
                <p class="card-text">View your personal and professional information.</p>
                <a href="<?php echo BASE_URL; ?>/staff/view_staff.php" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Student Management</h5>
                <p class="card-text">Add, edit, view students, and generate QR codes.</p>
                <a href="<?php echo BASE_URL; ?>/staff/list_students.php" class="btn btn-success">Manage Students</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Attendance Management</h5>
                <p class="card-text">Mark student attendance using QR scan and view reports.</p>
                <a href="<?php echo BASE_URL; ?>/staff/mark_attendance.php" class="btn btn-warning">Mark Attendance</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
