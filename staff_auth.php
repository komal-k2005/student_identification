<?php
// Staff Authentication via QR Code
session_start();
include('db.php');

if(isset($_POST['staff_id']) && isset($_POST['staff_password'])) {
    $staff_id = mysqli_real_escape_string($con, trim($_POST['staff_id']));
    $entered_password = mysqli_real_escape_string($con, trim($_POST['staff_password']));
    
    // Check if staff exists and password matches
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE staff_id = ? AND user_type = 'staff'");
    mysqli_stmt_bind_param($stmt, "s", $staff_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        // Check password (compare with stored password or staff_password field)
        $stored_password = $row['Password'];
        $staff_password_field = isset($row['staff_password']) ? $row['staff_password'] : null;
        
        // Use staff_password field if exists, otherwise use regular Password field
        $correct_password = $staff_password_field ? $staff_password_field : $stored_password;
        
        if($entered_password === $correct_password) {
            // Set staff session
            $_SESSION['staff_id'] = $row['staff_id'];
            $_SESSION['staff_name'] = $row['Username'];
            $_SESSION['staff_email'] = $row['Email'];
            $_SESSION['staff_authenticated'] = true;
            
            mysqli_stmt_close($stmt);
            // Redirect to attendance entry page
            header("Location: mark_attendance.php");
            exit();
        } else {
            mysqli_stmt_close($stmt);
            header("Location: qr_scan.php?staff_id=" . urlencode($staff_id) . "&error=1");
            exit();
        }
    } else {
        mysqli_stmt_close($stmt);
        header("Location: qr_scan.php?staff_id=" . urlencode($staff_id) . "&error=1");
        exit();
    }
} else {
    header("Location: qr_scan.php?error=1");
    exit();
}
?>



