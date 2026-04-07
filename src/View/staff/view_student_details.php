<?php
// src/View/staff/view_student_details.php
require_once __DIR__ . '/../../../includes/header.php';
// $student, $marks, $attendance, $error are passed from StaffController::viewStudentDetails()
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Student Details</h2>
    <div>
        <a href="<?php echo BASE_URL; ?>/staff/edit_student.php?id=<?php echo htmlspecialchars($studentId); ?>" class="btn btn-info me-2">Edit</a>
        <a href="<?php echo BASE_URL; ?>/staff/delete_student.php?id=<?php echo htmlspecialchars($studentId); ?>" class="btn btn-danger me-2" onclick="return confirm('WARNING: Are you sure you want to completely delete this student? All attendance and marks records will be permanently wiped.');">Delete</a>
        <a href="<?php echo BASE_URL; ?>/staff/list_students.php" class="btn btn-secondary">Back to List</a>
    </div>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php
elseif ($student): ?>
    <div class="row">
        <!-- Student Info Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Profile Info</h5>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($student['photo_path'])): ?>
                        <?php
        $photoSrc = BASE_URL . '/public/img/' . basename($student['photo_path']);
?>
                        <img src="<?php echo htmlspecialchars($photoSrc); ?>" alt="Photo" class="img-fluid rounded-circle mb-3 border shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php
    else: ?>
                        <img src="<?php echo BASE_URL; ?>/public/img/default-avatar.png" alt="Default Photo" class="img-fluid rounded-circle mb-3 border shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php
    endif; ?>
                    
                    <h4><?php echo htmlspecialchars($student['full_name']); ?></h4>
                    <p class="text-muted mb-1"><?php echo htmlspecialchars($student['branch'] ?? 'No Branch'); ?></p>
                    <p class="mb-0"><strong>Roll No:</strong> <?php echo htmlspecialchars($student['roll_number']); ?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Father's Name:</strong> <?php echo htmlspecialchars($student['father_name'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Mother's Name:</strong> <?php echo htmlspecialchars($student['mother_name'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Aadhaar No:</strong> <?php echo htmlspecialchars($student['aadhaar_number'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Enrollment No:</strong> <?php echo htmlspecialchars($student['enrollment_number'] ?? 'N/A'); ?></li>
                </ul>
            </div>
        </div>

        <!-- Academic & Attendance Tabs -->
        <div class="col-md-8">
            <ul class="nav nav-tabs" id="studentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="marks-tab" data-bs-toggle="tab" data-bs-target="#marks" type="button" role="tab" aria-controls="marks" aria-selected="true">Academic Marks</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab" aria-controls="attendance" aria-selected="false">Attendance History</button>
                </li>
            </ul>
            <div class="tab-content border border-top-0 p-3 bg-white mb-4" id="studentTabsContent">
                
                <!-- Marks Tab -->
                <div class="tab-pane fade show active" id="marks" role="tabpanel" aria-labelledby="marks-tab">
                    <?php if ($marks): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Exam / Semester</th>
                                        <th>Marks / CGPA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>10th Standard</td>
                                        <td><?php echo $marks['marks_10th'] !== null ? htmlspecialchars($marks['marks_10th']) . '%' : 'N/A'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>12th / Diploma</td>
                                        <td><?php echo $marks['marks_12th'] !== null ? htmlspecialchars($marks['marks_12th']) . '%' : 'N/A'; ?></td>
                                    </tr>
                                    <?php for ($i = 1; $i <= 8; $i++): ?>
                                        <tr>
                                            <td>Semester <?php echo $i; ?></td>
                                            <td><?php echo $marks['marks_semester_' . $i] !== null ? htmlspecialchars($marks['marks_semester_' . $i]) : 'N/A'; ?></td>
                                        </tr>
                                    <?php
        endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
    else: ?>
                        <div class="alert alert-warning">No marks have been recorded for this student.</div>
                    <?php
    endif; ?>
                </div>
                
                <!-- Attendance Tab -->
                <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                    <?php if ($attendance && count($attendance) > 0): ?>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped table-sm">
                                <thead class="table-light" style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Marked By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendance as $record): ?>
                                        <tr>
                                            <td><?php echo date('d-M-Y h:i A', strtotime($record['attendance_date'] . ' ' . $record['attendance_time'])); ?></td>
                                            <td><?php echo htmlspecialchars($record['subject']); ?></td>
                                            <td><span class="badge bg-success">Present</span></td>
                                            <td><?php echo htmlspecialchars($record['staff_name'] ?? 'Staff ID ' . $record['staff_id']); ?></td>
                                        </tr>
                                    <?php
        endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
    else: ?>
                        <div class="alert alert-info">No attendance records found for this student.</div>
                    <?php
    endif; ?>
                </div>

            </div>
        </div>
    </div>
<?php
endif; ?>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
