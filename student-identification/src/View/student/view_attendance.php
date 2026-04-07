<?php
// src/View/student/view_attendance.php
require_once __DIR__ . '/../../../includes/header.php';
// $attendanceRecords is passed from StudentController::viewAttendance()
?>

<h2 class="mb-4">View Attendance</h2>

<?php if (!empty($attendanceRecords)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Staff Name</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['subject']); ?></td>
                        <td><?php echo htmlspecialchars($record['staff_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['attendance_date']); ?></td>
                        <td><?php echo htmlspecialchars($record['attendance_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No attendance records found.</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
