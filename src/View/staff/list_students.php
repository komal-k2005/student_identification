<?php
// src/View/staff/list_students.php
require_once __DIR__ . '/../../../includes/header.php';
// $students is passed from StaffController::listStudents()
?>

<h2 class="mb-4">Student List</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php
endif; ?>

<div class="d-flex justify-content-between flex-wrap mb-3">
    <a href="<?php echo BASE_URL; ?>/staff/add_student.php" class="btn btn-primary mb-2">Add New Student</a>
    
    <div class="btn-group mb-2" role="group">
        <a href="<?php echo BASE_URL; ?>/staff/export_students.php" class="btn btn-success">Export CSV</a>
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importStudentsModal">
            Import CSV
        </button>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importStudentsModal" tabindex="-1" aria-labelledby="importStudentsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo BASE_URL; ?>/staff/import_students.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="importStudentsModalLabel">Import Students from CSV</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Upload a CSV file with the following columns in exactly this order (no headers):</p>
            <ul>
                <li>Full Name</li>
                <li>Father's Name</li>
                <li>Mother's Name</li>
                <li>Aadhaar Number</li>
                <li>Roll Number</li>
                <li>Enrollment Number</li>
                <li>Branch</li>
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

<?php if (!empty($students)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Full Name</th>
                    <th>Roll Number</th>
                    <th>Enrollment Number</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td>
                            <?php if (!empty($student['photo_path'])): ?>
                                <?php $photoSrc = BASE_URL . '/public/img/' . basename($student['photo_path']); ?>
                                <img src="<?php echo htmlspecialchars($photoSrc); ?>" alt="Student Photo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            <?php
        else: ?>
                                <img src="<?php echo BASE_URL; ?>/public/img/default-avatar.png" alt="Default Photo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            <?php
        endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['roll_number']); ?></td>
                        <td><?php echo htmlspecialchars($student['enrollment_number']); ?></td>
                        <td><?php echo htmlspecialchars($student['branch']); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/staff/view_student_details.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" class="btn btn-sm btn-success me-1">View</a>
                            <a href="<?php echo BASE_URL; ?>/staff/edit_student.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" class="btn btn-sm btn-info me-1">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/staff/generate_qr.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" class="btn btn-sm btn-warning me-1">Generate QR</a>
                            <a href="<?php echo BASE_URL; ?>/staff/add_marks.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" class="btn btn-sm btn-secondary me-1">Add/Edit Marks</a>
                            <a href="<?php echo BASE_URL; ?>/staff/delete_student.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to completely delete this student? All attendance and marks records will also be wiped. This is irreversible!');">Delete</a>
                        </td>
                    </tr>
                <?php
    endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
else: ?>
    <div class="alert alert-info">No students added yet.</div>
<?php
endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
