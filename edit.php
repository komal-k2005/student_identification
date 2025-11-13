<?php
include('db.php');

// Validate and sanitize ID
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id = intval($_GET['id']);
} else {
    header("location: index1.php");
    exit();
}

//Fetching previous image to update
$edit_query = "SELECT * FROM card_activation WHERE id = ?";
$stmt = mysqli_prepare($con, $edit_query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$edit_query_run = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($edit_query_run) > 0){
    $edit_row = mysqli_fetch_array($edit_query_run);
    $e_image = $edit_row['image'];
} else {
    header('location: index1.php');
    exit();
}
mysqli_stmt_close($stmt);

// Check if form is submitted
if(isset($_POST['submit'])){
    // Sanitize and validate input
    $u_card = mysqli_real_escape_string($con, trim($_POST['card_no']));
    $u_f_name = mysqli_real_escape_string($con, trim($_POST['user_first_name']));
    $u_l_name = mysqli_real_escape_string($con, trim($_POST['user_last_name']));
    $u_father = mysqli_real_escape_string($con, trim($_POST['user_father']));
    $u_aadhar = mysqli_real_escape_string($con, trim($_POST['user_aadhar']));
    $u_birthday = mysqli_real_escape_string($con, trim($_POST['user_dob']));
    $u_gender = mysqli_real_escape_string($con, trim($_POST['user_gender']));
    $u_email = mysqli_real_escape_string($con, trim($_POST['user_email']));
    $u_phone = mysqli_real_escape_string($con, trim($_POST['user_phone']));
    $u_mother = mysqli_real_escape_string($con, trim($_POST['user_mother']));
    $staff_id = mysqli_real_escape_string($con, trim($_POST['staff_id']));
    $u_10th_percentage = mysqli_real_escape_string($con, trim($_POST['pincode']));
    $u_department = mysqli_real_escape_string($con, trim($_POST['state']));
    $u_academic_year = mysqli_real_escape_string($con, trim($_POST['academic_year']));
    $u_address = mysqli_real_escape_string($con, trim($_POST['address']));
    $semester1 = mysqli_real_escape_string($con, trim($_POST['semester1']));
    $semester2 = mysqli_real_escape_string($con, trim($_POST['semester2']));
    $semester3 = mysqli_real_escape_string($con, trim($_POST['semester3']));
    $semester4 = mysqli_real_escape_string($con, trim($_POST['semester4']));
    $semester5 = mysqli_real_escape_string($con, trim($_POST['semester5']));
    $semester6 = mysqli_real_escape_string($con, trim($_POST['semester6']));

    // Handle image upload
    $image = $e_image; // Default to existing image
    $target = "";
    
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0 && !empty($_FILES['image']['name'])) {
        // Validate file type
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if(in_array($file_extension, $allowed_types)) {
            // Validate file size (max 5MB)
            if($_FILES['image']['size'] <= 5242880) {
                $image = mysqli_real_escape_string($con, basename($_FILES['image']['name']));
                $target = "upload_images/".basename($image);
            }
        }
    }

    // Use prepared statement to prevent SQL injection
    $update = "UPDATE card_activation SET 
            u_card = ?, u_f_name = ?, u_l_name = ?, u_father = ?, u_aadhar = ?, 
            u_birthday = ?, u_gender = ?, u_email = ?, u_phone = ?, u_mother = ?, 
            u_address = ?, u_department = ?, u_academic_year = ?, u_10th_percentage = ?, 
            staff_id = ?, image = ?, semester1 = ?, semester2 = ?, semester3 = ?, 
            semester4 = ?, semester5 = ?, semester6 = ?
            WHERE id = ?";
    
    $stmt = mysqli_prepare($con, $update);
    
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssi", 
            $u_card, $u_f_name, $u_l_name, $u_father, $u_aadhar, $u_birthday, 
            $u_gender, $u_email, $u_phone, $u_mother, $u_address, $u_department, 
            $u_academic_year, $u_10th_percentage, $staff_id, $image, $semester1, 
            $semester2, $semester3, $semester4, $semester5, $semester6, $id
        );
        
        if(mysqli_stmt_execute($stmt)){
            // Upload new image if provided
            if(!empty($target) && isset($_FILES['image']['tmp_name'])) {
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
            }
            mysqli_stmt_close($stmt);
            echo "<script>
             alert('Success! Data has been successfully updated!');
             window.location.href='index1.php';
             </script>";
        } else {
            echo "Data not updated: " . mysqli_error($con);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
}

?>