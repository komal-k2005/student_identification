<?php
//database connection
include('db.php');

//adding data to the database
if(isset($_POST['submit'])){
	$u_card = $_POST['card_no'];
	$u_f_name = $_POST['user_first_name'];
	$u_l_name = $_POST['user_last_name'];
	$u_father = $_POST['user_father'];
	$u_aadhar = $_POST['user_aadhar'];
	$u_birthday = $_POST['user_dob'];
	$u_gender = $_POST['user_gender'];
	$u_email = $_POST['user_email'];
	$u_phone = $_POST['user_phone'];;
	$u_mother = $_POST['user_mother'];
	$staff_id = $_POST['staff_id'];
	$u_10th_percentage=$_POST['pincode'];
	$u_department=$_POST['state'];
	$u_academic_year=$_POST['academic_year'];
	$u_address=$_POST['address'];
	$semester1 = $_POST['semester1'];
	$semester2 = $_POST['semester2'];
	$semester3=$_POST['semester3'];
	$semester4=$_POST['semester4'];
	$semester5=$_POST['semester5'];
	$semester6=$_POST['semester6'];
	
	
	//image upload
	$msg = "";
	$image = $_FILES['image']['name'];
	$target = "upload_images/".basename($image);

	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
  		$msg = "Image uploaded successfully";
  	}else{
  		$msg = "Failed to upload image";
  	}

  	$insert_data = "INSERT INTO card_activation (
		u_card, 
		u_f_name, 
		u_l_name, 
		u_father, 
		u_aadhar, 
		u_birthday, 
		u_gender, 
		u_email, 
		u_phone,  
		u_mother, 
	u_address,
	u_department,	
	u_academic_year,
	u_10th_percentage,
		staff_id, 
		image,
		uploaded,
	semester1,
	semester2,
	semester3,
	semester4,
	semester5,
	semester6
	) VALUES (
		'$u_card',
		'$u_f_name',
		'$u_l_name',
		'$u_father',
		'$u_aadhar',
		'$u_birthday',
		'$u_gender',
		'$u_email',
		'$u_phone',
		'$u_mother',
		'$u_address',
	'$u_department',
	'$u_academic_year',
		'$u_10th_percentage',
		'$staff_id',
		'$image',
		NOW(),
	'$semester1',
	'$semester2',
	'$semester3',
	'$semester4',
	'$semester5',
	'$semester6'
	)";
  	$run_data = mysqli_query($con,$insert_data);

  	if($run_data){
  		header('location:index1.php');
  	}else{
  		echo "Data not insert";
  	}

}

?>
