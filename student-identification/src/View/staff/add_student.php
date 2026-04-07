<?php
// src/View/staff/add_student.php
require_once __DIR__ . '/../../../includes/header.php';
// $errors, $success are passed from StaffController::addStudent()
?>

<h2 class="mb-4">Add New Student</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if (isset($success) && $success): ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>/staff/add_student.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="father_name" class="form-label">Father's Name</label>
        <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo htmlspecialchars($_POST['father_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="mother_name" class="form-label">Mother's Name</label>
        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($_POST['mother_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="aadhaar_number" class="form-label">Aadhaar Number</label>
        <input type="text" class="form-control" id="aadhaar_number" name="aadhaar_number" required value="<?php echo htmlspecialchars($_POST['aadhaar_number'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="roll_number" class="form-label">Roll Number</label>
        <input type="text" class="form-control" id="roll_number" name="roll_number" required value="<?php echo htmlspecialchars($_POST['roll_number'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="enrollment_number" class="form-label">Enrollment Number</label>
        <input type="text" class="form-control" id="enrollment_number" name="enrollment_number" required value="<?php echo htmlspecialchars($_POST['enrollment_number'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="branch" class="form-label">Branch</label>
        <input type="text" class="form-control" id="branch" name="branch" required value="<?php echo htmlspecialchars($_POST['branch'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="student_photo" class="form-label">Student Photo</label>
        <input class="form-control" type="file" id="student_photo" name="student_photo" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Add Student</button>
</form>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
