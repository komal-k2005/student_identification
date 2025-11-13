<?php
include('db.php');

// Validate and sanitize ID
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id = intval($_GET['id']);
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "DELETE FROM card_activation WHERE id = ?");
    
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_close($stmt);
            header('location:index1.php');
            exit();
        } else {
            echo "Cannot delete: " . mysqli_error($con);
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
} else {
    header('location:index1.php');
    exit();
}
?>