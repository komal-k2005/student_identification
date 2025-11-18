<?php
// Attendance Entry Page for Staff
session_start();
include('db.php');

// Check if staff is authenticated
if(!isset($_SESSION['staff_authenticated']) || $_SESSION['staff_authenticated'] !== true) {
    header("Location: index.php");
    exit();
}

$staff_id = $_SESSION['staff_id'];

// Get staff assignments (subjects and years)
$stmt = mysqli_prepare($con, "SELECT DISTINCT subject, academic_year, department FROM staff_assignments WHERE staff_id = ?");
mysqli_stmt_bind_param($stmt, "s", $staff_id);
mysqli_stmt_execute($stmt);
$assignments_result = mysqli_stmt_get_result($stmt);

$subjects = [];
$years = [];
while($assignment = mysqli_fetch_assoc($assignments_result)) {
    $subjects[] = $assignment['subject'];
    $years[] = $assignment['academic_year'];
}
mysqli_stmt_close($stmt);

// Get students for this staff's assigned years and department
// For 3rd year Computer Technology students
$current_year = "3rd Year";
$current_dept = "Computer Technology";

$students_query = "SELECT * FROM card_activation WHERE u_academic_year = ? AND u_department = ? ORDER BY u_f_name, u_l_name";
$stmt = mysqli_prepare($con, $students_query);
mysqli_stmt_bind_param($stmt, "ss", $current_year, $current_dept);
mysqli_stmt_execute($stmt);
$students_result = mysqli_stmt_get_result($stmt);

// Handle attendance submission
if(isset($_POST['submit_attendance'])) {
    $attendance_date = mysqli_real_escape_string($con, trim($_POST['attendance_date']));
    $subject = mysqli_real_escape_string($con, trim($_POST['subject']));
    $academic_year = mysqli_real_escape_string($con, trim($_POST['academic_year']));
    
    // Get all student IDs and their attendance status
    foreach($_POST['student_attendance'] as $student_id => $status) {
        $student_id = intval($student_id);
        $status = mysqli_real_escape_string($con, trim($status));
        
        // Check if attendance already exists for this date
        $check_stmt = mysqli_prepare($con, "SELECT id FROM attendance WHERE student_id = ? AND staff_id = ? AND subject = ? AND attendance_date = ?");
        mysqli_stmt_bind_param($check_stmt, "isss", $student_id, $staff_id, $subject, $attendance_date);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if(mysqli_num_rows($check_result) > 0) {
            // Update existing attendance
            $update_stmt = mysqli_prepare($con, "UPDATE attendance SET status = ? WHERE student_id = ? AND staff_id = ? AND subject = ? AND attendance_date = ?");
            mysqli_stmt_bind_param($update_stmt, "sisss", $status, $student_id, $staff_id, $subject, $attendance_date);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
        } else {
            // Insert new attendance
            $insert_stmt = mysqli_prepare($con, "INSERT INTO attendance (student_id, staff_id, subject, attendance_date, status, academic_year) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "isssss", $student_id, $staff_id, $subject, $attendance_date, $status, $academic_year);
            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
    
    echo "<script>alert('Attendance marked successfully!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance - Staff</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .attendance-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .student-row {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .student-row:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="attendance-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="fa fa-calendar-check-o"></i> Mark Attendance</h3>
                <div>
                    <span class="badge badge-info">Staff: <?php echo htmlspecialchars($_SESSION['staff_name']); ?></span>
                    <a href="php/logout.php?staff=1" class="btn btn-sm btn-danger ml-2">Logout</a>
                </div>
            </div>
            
            <form method="POST" action="">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="attendance_date">Date:</label>
                        <input type="date" class="form-control" id="attendance_date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="subject">Subject:</label>
                        <select class="form-control" id="subject" name="subject" required>
                            <option value="">Select Subject</option>
                            <?php 
                            $unique_subjects = array_unique($subjects);
                            foreach($unique_subjects as $subj): 
                            ?>
                                <option value="<?php echo htmlspecialchars($subj); ?>"><?php echo htmlspecialchars($subj); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="academic_year">Academic Year:</label>
                        <select class="form-control" id="academic_year" name="academic_year" required>
                            <option value="3rd Year" selected>3rd Year</option>
                            <?php 
                            $unique_years = array_unique($years);
                            foreach($unique_years as $year): 
                                if($year != "3rd Year"):
                            ?>
                                <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </select>
                    </div>
                </div>
                
                <hr>
                
                <h5>Students List (3rd Year - Computer Technology):</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while($student = mysqli_fetch_assoc($students_result)):
                                // Check existing attendance for today if date is today
                                $attendance_date_default = date('Y-m-d');
                                $check_attendance = mysqli_prepare($con, "SELECT status FROM attendance WHERE student_id = ? AND staff_id = ? AND attendance_date = ? LIMIT 1");
                                mysqli_stmt_bind_param($check_attendance, "iss", $student['id'], $staff_id, $attendance_date_default);
                                mysqli_stmt_execute($check_attendance);
                                $attendance_result = mysqli_stmt_get_result($check_attendance);
                                $existing_status = 'Present'; // Default
                                if($att = mysqli_fetch_assoc($attendance_result)) {
                                    $existing_status = $att['status'];
                                }
                                mysqli_stmt_close($check_attendance);
                            ?>
                                <tr class="student-row">
                                    <td><?php echo htmlspecialchars($student['u_card']); ?></td>
                                    <td><?php echo htmlspecialchars($student['u_f_name'] . ' ' . $student['u_l_name']); ?></td>
                                    <td>
                                        <select name="student_attendance[<?php echo $student['id']; ?>]" class="form-control" required>
                                            <option value="Present" <?php echo $existing_status == 'Present' ? 'selected' : ''; ?>>Present</option>
                                            <option value="Absent" <?php echo $existing_status == 'Absent' ? 'selected' : ''; ?>>Absent</option>
                                            <option value="Late" <?php echo $existing_status == 'Late' ? 'selected' : ''; ?>>Late</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <button type="submit" name="submit_attendance" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"></i> Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
if(isset($stmt)) {
    mysqli_stmt_close($stmt);
}
?>

