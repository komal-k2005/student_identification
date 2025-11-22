<?php
// Staff Assignments Management - Assign subjects and years to staff
include('db.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

// Handle form submission
if(isset($_POST['add_assignment'])) {
    $staff_id = mysqli_real_escape_string($con, trim($_POST['staff_id']));
    $subject = mysqli_real_escape_string($con, trim($_POST['subject']));
    $academic_year = mysqli_real_escape_string($con, trim($_POST['academic_year']));
    $department = mysqli_real_escape_string($con, trim($_POST['department']));
    
    // Check if assignment already exists
    $check_stmt = mysqli_prepare($con, "SELECT id FROM staff_assignments WHERE staff_id = ? AND subject = ? AND academic_year = ? AND department = ?");
    mysqli_stmt_bind_param($check_stmt, "ssss", $staff_id, $subject, $academic_year, $department);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if(mysqli_num_rows($check_result) == 0) {
        $insert_stmt = mysqli_prepare($con, "INSERT INTO staff_assignments (staff_id, subject, academic_year, department) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "ssss", $staff_id, $subject, $academic_year, $department);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
        echo "<script>alert('Assignment added successfully!');</script>";
    } else {
        echo "<script>alert('Assignment already exists!');</script>";
    }
    mysqli_stmt_close($check_stmt);
}

// Handle deletion
if(isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete_stmt = mysqli_prepare($con, "DELETE FROM staff_assignments WHERE id = ?");
    mysqli_stmt_bind_param($delete_stmt, "i", $id);
    mysqli_stmt_execute($delete_stmt);
    mysqli_stmt_close($delete_stmt);
    header("Location: staff_assignments.php");
    exit();
}

// Get all staff
$staff_query = "SELECT DISTINCT staff_id, Username FROM users WHERE staff_id IS NOT NULL AND staff_id != ''";
$staff_result = mysqli_query($con, $staff_query);

// Get all assignments
$assignments_query = "SELECT sa.*, u.Username FROM staff_assignments sa LEFT JOIN users u ON sa.staff_id = u.staff_id ORDER BY sa.staff_id, sa.academic_year, sa.subject";
$assignments_result = mysqli_query($con, $assignments_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Assignments</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h2><i class="fa fa-user-plus"></i> Staff Assignments Management</h2>
        <a href="home.php" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</a>
        <hr>
        
        <!-- Add Assignment Form -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4>Add Staff Assignment</h4>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Staff ID:</label>
                                <select name="staff_id" class="form-control" required>
                                    <option value="">Select Staff</option>
                                    <?php while($staff = mysqli_fetch_assoc($staff_result)): ?>
                                        <option value="<?php echo htmlspecialchars($staff['staff_id']); ?>">
                                            <?php echo htmlspecialchars($staff['staff_id'] . ' - ' . $staff['Username']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                    <?php mysqli_data_seek($staff_result, 0); // Reset pointer ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Subject:</label>
                                <input type="text" name="subject" class="form-control" placeholder="e.g., Mathematics, Physics" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Academic Year:</label>
                                <select name="academic_year" class="form-control" required>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year" selected>3rd Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Department:</label>
                                <select name="department" class="form-control" required>
                                    <option value="Computer Technology" selected>Computer Technology</option>
                                    <option value="Electrical Engineering">Electrical Engineering</option>
                                    <option value="Civil Engineering">Civil Engineering</option>
                                    <option value="Electronic & Telecommunication">Electronic & Telecommunication</option>
                                    <option value="Mechanical">Mechanical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_assignment" class="btn btn-success">
                        <i class="fa fa-plus"></i> Add Assignment
                    </button>
                </form>
            </div>
        </div>
        
        <hr>
        
        <!-- Existing Assignments -->
        <h4>Existing Assignments</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Subject</th>
                    <th>Academic Year</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($assignment = mysqli_fetch_assoc($assignments_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($assignment['staff_id']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['Username']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['subject']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['academic_year']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['department']); ?></td>
                        <td>
                            <a href="staff_assignments.php?delete=1&id=<?php echo $assignment['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this assignment?');">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>



