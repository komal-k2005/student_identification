<?php
// src/View/staff/edit_student.php
require_once __DIR__ . '/../../../includes/header.php';
// $student, $errors, $success are passed from StaffController::editStudent()
?>

<h2 class="mb-4">Edit Student</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php
    endforeach; ?>
        </ul>
    </div>
<?php
endif; ?>
<?php if (isset($success) && $success): ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php
endif; ?>

<?php if ($student): ?>
<form action="<?php echo BASE_URL; ?>/staff/edit_student.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="father_name" class="form-label">Father's Name</label>
        <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo htmlspecialchars($student['father_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="mother_name" class="form-label">Mother's Name</label>
        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($student['mother_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="aadhaar_number" class="form-label">Aadhaar Number</label>
        <input type="text" class="form-control" id="aadhaar_number" name="aadhaar_number" value="<?php echo htmlspecialchars($student['aadhaar_number']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="roll_number" class="form-label">Roll Number</label>
        <input type="text" class="form-control" id="roll_number" name="roll_number" value="<?php echo htmlspecialchars($student['roll_number']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="enrollment_number" class="form-label">Enrollment Number</label>
        <input type="text" class="form-control" id="enrollment_number" name="enrollment_number" value="<?php echo htmlspecialchars($student['enrollment_number']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="branch" class="form-label">Branch</label>
        <input type="text" class="form-control" id="branch" name="branch" value="<?php echo htmlspecialchars($student['branch']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="student_photo" class="form-label">Student Photo</label>
        <?php if (!empty($student['photo_path'])): ?>
            <div class="mb-2">
                <?php $photoSrc = BASE_URL . '/public/img/' . basename($student['photo_path']); ?>
                <img src="<?php echo htmlspecialchars($photoSrc); ?>" alt="Student Photo" style="max-width: 150px;" class="img-thumbnail">
                <small class="text-muted">Current Photo</small>
            </div>
        <?php
    endif; ?>
        <input class="form-control" type="file" id="student_photo" name="student_photo" accept="image/*">
        <small class="form-text text-muted">Upload a new photo to replace the existing one.</small>
    </div>
    <button type="submit" class="btn btn-primary">Update Student</button>
    <a href="<?php echo BASE_URL; ?>/staff/list_students.php" class="btn btn-secondary">Cancel</a>
</form>
<?php
else: ?>
    <p class="alert alert-danger">Student data could not be loaded.</p>
<?php
endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
