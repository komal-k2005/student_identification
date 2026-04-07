<?php
// src/View/staff/view_staff.php
require_once __DIR__ . '/../../../includes/header.php';
// $staff and $qrCodePath are passed from StaffController::viewStaffDetails()
?>

<h2 class="mb-4">View Staff Details</h2>

<?php if (isset($_GET['error']) && $_GET['error']): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<?php if ($staff): ?>
    <div class="card mb-4">
        <div class="card-header">
            Staff Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Staff Name:</strong> <?php echo htmlspecialchars($staff['staff_name']); ?></p>
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($staff['subject'] ?? 'N/A'); ?></p>
                    <p><strong>Education:</strong> <?php echo htmlspecialchars($staff['education'] ?? 'N/A'); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($staff['department'] ?? 'N/A'); ?></p>
                    <p><strong>Staff ID:</strong> <?php echo htmlspecialchars($staff['staff_id']); ?></p>
                </div>
                <div class="col-md-4 text-center">
                    <p><strong>Staff QR Code:</strong></p>
                    <img src="<?php echo htmlspecialchars($qrCodePath); ?>" alt="Staff QR Code" class="img-fluid border p-2" style="max-width: 150px;">
                    <p class="mt-2 text-muted">Scan to view staff details</p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($staff['is_active']): ?>
        <div class="alert alert-success">Your account is currently active.</div>
    <?php else: ?>
        <div class="alert alert-warning">Your account is currently inactive. Please contact admin.</div>
    <?php endif; ?>

<?php else: ?>
    <div class="alert alert-danger">Staff details could not be loaded.</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
