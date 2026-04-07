<?php
// src/View/admin/list_staff.php
require_once __DIR__ . '/../../../includes/header.php';
// $staffList is passed from StaffController::listStaff()
?>

<h2 class="mb-4">Staff List</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php
endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php
endif; ?>

<div class="d-flex justify-content-between flex-wrap mb-3">
    <a href="<?php echo BASE_URL; ?>/admin/create_staff.php" class="btn btn-primary mb-2">Create New Staff</a>
    
    <div class="btn-group mb-2" role="group">
        <a href="<?php echo BASE_URL; ?>/admin/export_staff.php" class="btn btn-success">Export CSV</a>
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importStaffModal">
            Import CSV
        </button>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importStaffModal" tabindex="-1" aria-labelledby="importStaffModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo BASE_URL; ?>/admin/import_staff.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="importStaffModalLabel">Import Staff from CSV</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Upload a CSV file with the following columns in exactly this order (no headers):</p>
            <ul>
                <li>Username (e.g., johndoe)</li>
                <li>Password (Clear text, will be hashed securely)</li>
                <li>Staff Full Name</li>
                <li>Subject</li>
                <li>Education</li>
                <li>Department</li>
            </ul>
            <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File</label>
                <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Import</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle"></i> <strong>Note on Quick Attendance:</strong> When staff scan a student's QR code to mark attendance, they can use their <strong>Username</strong> or <strong>Password</strong> as the "Secret Code". The <strong>Subject</strong> they enter must accurately match their assigned Subject listed below.
</div>

<?php if (!empty($staffList)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staffList as $staff): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($staff['staff_id']); ?></td>
                        <td><?php echo htmlspecialchars($staff['username']); ?></td>
                        <td><?php echo htmlspecialchars($staff['staff_name']); ?></td>
                        <td><?php echo htmlspecialchars($staff['subject'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($staff['department'] ?? 'N/A'); ?></td>
                        <td>
                            <?php if ($staff['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php
        else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php
        endif; ?>
                        </td>
                        <td>
                            <?php if ($staff['is_active']): ?>
                                <a href="<?php echo BASE_URL; ?>/admin/deactivate_staff.php?id=<?php echo htmlspecialchars($staff['staff_id']); ?>" class="btn btn-sm btn-danger">Deactivate</a>
                            <?php
        else: ?>
                                <a href="<?php echo BASE_URL; ?>/admin/activate_staff.php?id=<?php echo htmlspecialchars($staff['staff_id']); ?>" class="btn btn-sm btn-success">Activate</a>
                            <?php
        endif; ?>
                        </td>
                    </tr>
                <?php
    endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
else: ?>
    <div class="alert alert-info">No staff accounts found.</div>
<?php
endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
