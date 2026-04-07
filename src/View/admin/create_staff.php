<?php
// src/View/admin/create_staff.php
require_once __DIR__ . '/../../../includes/header.php';
// $errors and $success are passed from StaffController::createStaff()
?>

<h2 class="mb-4">Create New Staff Account</h2>

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

<form action="<?php echo BASE_URL; ?>/admin/create_staff.php" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="staff_name" class="form-label">Staff Name</label>
        <input type="text" class="form-control" id="staff_name" name="staff_name" required value="<?php echo htmlspecialchars($_POST['staff_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="education" class="form-label">Education</label>
        <input type="text" class="form-control" id="education" name="education" value="<?php echo htmlspecialchars($_POST['education'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($_POST['department'] ?? ''); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Create Staff</button>
</form>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
