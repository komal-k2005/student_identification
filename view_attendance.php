<?php
// View Attendance for Students
include('db.php');

// Check if student_id is provided
if(!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
    die("Invalid student ID");
}

$student_id = intval($_GET['student_id']);

// Get student information
$stmt = mysqli_prepare($con, "SELECT * FROM card_activation WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$student_result = mysqli_stmt_get_result($stmt);

if(!$student = mysqli_fetch_assoc($student_result)) {
    die("Student not found");
}
mysqli_stmt_close($stmt);

// Get attendance records for this student
$attendance_query = "SELECT a.*, u.Username as staff_name 
                     FROM attendance a 
                     LEFT JOIN users u ON a.staff_id = u.staff_id 
                     WHERE a.student_id = ? 
                     ORDER BY a.attendance_date DESC, a.subject ASC";
$stmt = mysqli_prepare($con, $attendance_query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$attendance_result = mysqli_stmt_get_result($stmt);

// Calculate statistics
$total_days = 0;
$present_days = 0;
$absent_days = 0;
$late_days = 0;

$attendance_records = [];
while($record = mysqli_fetch_assoc($attendance_result)) {
    $attendance_records[] = $record;
    $total_days++;
    if($record['status'] == 'Present') $present_days++;
    elseif($record['status'] == 'Absent') $absent_days++;
    elseif($record['status'] == 'Late') $late_days++;
}
mysqli_stmt_close($stmt);

$attendance_percentage = $total_days > 0 ? round(($present_days / $total_days) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Record - <?php echo htmlspecialchars($student['u_f_name'] . ' ' . $student['u_l_name']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .attendance-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-present {
            color: #28a745;
            font-weight: bold;
        }
        .status-absent {
            color: #dc3545;
            font-weight: bold;
        }
        .status-late {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="attendance-card">
            <h3><i class="fa fa-calendar-check-o"></i> Attendance Record</h3>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['u_card']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($student['u_f_name'] . ' ' . $student['u_l_name']); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($student['u_department']); ?></p>
                    <p><strong>Academic Year:</strong> <?php echo htmlspecialchars($student['u_academic_year']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="row text-center">
                <div class="col-md-3">
                    <h2><?php echo $total_days; ?></h2>
                    <p>Total Days</p>
                </div>
                <div class="col-md-3">
                    <h2><?php echo $present_days; ?></h2>
                    <p>Present</p>
                </div>
                <div class="col-md-3">
                    <h2><?php echo $absent_days; ?></h2>
                    <p>Absent</p>
                </div>
                <div class="col-md-3">
                    <h2><?php echo $attendance_percentage; ?>%</h2>
                    <p>Attendance %</p>
                </div>
            </div>
        </div>
        
        <div class="attendance-card">
            <h5>Detailed Attendance Record</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Marked By</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($attendance_records) > 0): ?>
                            <?php foreach($attendance_records as $record): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($record['attendance_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($record['subject']); ?></td>
                                    <td>
                                        <span class="status-<?php echo strtolower($record['status']); ?>">
                                            <?php echo htmlspecialchars($record['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($record['staff_name'] ? $record['staff_name'] : 'N/A'); ?></td>
                                    <td><?php echo date('H:i', strtotime($record['marked_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No attendance records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center">
            <a href="qr_scan.php?student_id=<?php echo $student_id; ?>" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</body>
</html>



