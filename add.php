<?php
//database connection
include('db.php');

//adding data to the database
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
	
	// Image upload with validation
	$msg = "";
	$image = "";
	$target = "";
	
	if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
		// Validate file type
		$allowed_types = array('jpg', 'jpeg', 'png', 'gif');
		$file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
		
		if(in_array($file_extension, $allowed_types)) {
			// Validate file size (max 5MB)
			if($_FILES['image']['size'] <= 5242880) {
				$image = mysqli_real_escape_string($con, basename($_FILES['image']['name']));
				$target = "upload_images/".basename($image);
				
				if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
					$msg = "Image uploaded successfully";
				} else {
					$msg = "Failed to upload image";
				}
			} else {
				$msg = "Image size too large. Maximum size is 5MB.";
			}
		} else {
			$msg = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
		}
	}

	// Use prepared statement to prevent SQL injection
	$stmt = mysqli_prepare($con, "INSERT INTO card_activation (
		u_card, u_f_name, u_l_name, u_father, u_aadhar, u_birthday, u_gender, 
		u_email, u_phone, u_mother, u_address, u_department, u_academic_year, 
		u_10th_percentage, staff_id, image, uploaded, semester1, semester2, 
		semester3, semester4, semester5, semester6
	) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
	
	if($stmt) {
		mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssss", 
			$u_card, $u_f_name, $u_l_name, $u_father, $u_aadhar, $u_birthday, 
			$u_gender, $u_email, $u_phone, $u_mother, $u_address, $u_department, 
			$u_academic_year, $u_10th_percentage, $staff_id, $image, $semester1, 
			$semester2, $semester3, $semester4, $semester5, $semester6
		);
		
		if(mysqli_stmt_execute($stmt)) {
			mysqli_stmt_close($stmt);
			header('location:index1.php');
			exit();
		} else {
			echo "Data not inserted: " . mysqli_error($con);
		}
		mysqli_stmt_close($stmt);
	} else {
		echo "Error preparing statement: " . mysqli_error($con);
	}
}


?>
