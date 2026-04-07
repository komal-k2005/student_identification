<?php
// src/View/student/view_marks.php
require_once __DIR__ . '/../../../includes/header.php';
// $studentMarks is passed from StudentController::viewSemesterMarks()
?>

<h2 class="mb-4">View Semester Marks</h2>

<?php if (!empty($studentMarks)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>10th Marks</th>
                    <th>12th Marks</th>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <th>Sem <?php echo $i; ?> Marks</th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($studentMarks['marks_10th'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($studentMarks['marks_12th'] ?? 'N/A'); ?></td>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <td><?php echo htmlspecialchars($studentMarks['marks_semester_' . $i] ?? 'N/A'); ?></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No semester marks available yet.</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
