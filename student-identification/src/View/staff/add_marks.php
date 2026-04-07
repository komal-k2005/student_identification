<?php
// src/View/staff/add_marks.php
require_once __DIR__ . '/../../../includes/header.php';
// $student, $currentMarks, $currentMaxSemester, $errors, $success are passed from StaffController::addStudentMarks()
?>

<h2 class="mb-4">Add/Edit Student Marks</h2>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>
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

<?php if ($student): ?>
    <div class="card mb-4">
        <div class="card-header">
            Student: <?php echo htmlspecialchars($student['full_name']); ?> (Roll No: <?php echo htmlspecialchars($student['roll_number']); ?>)
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/staff/add_marks.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="marks_10th" class="form-label">10th Marks</label>
                            <input type="number" step="0.01" class="form-control" id="marks_10th" name="marks_10th" value="<?php echo htmlspecialchars($currentMarks['marks_10th'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="marks_12th" class="form-label">12th Marks</label>
                            <input type="number" step="0.01" class="form-control" id="marks_12th" name="marks_12th" value="<?php echo htmlspecialchars($currentMarks['marks_12th'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <div class="mb-3">
                                <label for="marks_semester_<?php echo $i; ?>" class="form-label">Semester <?php echo $i; ?> Marks</label>
                                <?php
                                    $canEditSemester = false;
                                    if ($i === 1) { // Semester 1 can be edited if 10th or 12th marks are present
                                        $canEditSemester = (!empty($currentMarks['marks_10th']) || !empty($currentMarks['marks_12th']));
                                    } else { // Subsequent semesters can be edited if the previous semester has marks
                                        $canEditSemester = !empty($currentMarks['marks_semester_' . ($i - 1)]);
                                    }
                                    // Also allow editing if the mark already exists for that semester
                                    if (!empty($currentMarks['marks_semester_' . $i])) {
                                        $canEditSemester = true;
                                    }
                                ?>
                                <input type="number" step="0.01" class="form-control" id="marks_semester_<?php echo $i; ?>" name="marks_semester_<?php echo $i; ?>" value="<?php echo htmlspecialchars($currentMarks['marks_semester_' . $i] ?? ''); ?>" <?php echo $canEditSemester ? '' : 'disabled'; ?>>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Marks</button>
                <a href="<?php echo BASE_URL; ?>/staff/list_students.php" class="btn btn-secondary">Back to Student List</a>
            </form>
        </div>
    </div>
<?php else: ?>
    <p class="alert alert-info">Please select a student to add/edit marks.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
