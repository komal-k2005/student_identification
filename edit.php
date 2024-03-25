<?php
include('db.php');

$id = $_GET['id'];

//Fetching privious image to update
if(isset($_GET['id'])){
    $edit_id = $_GET['id'];
    $edit_query = "SELECT * FROM card_activation WHERE id = $edit_id";
    $edit_query_run = mysqli_query($con, $edit_query);
    if(mysqli_num_rows($edit_query_run) > 0){
        $edit_row = mysqli_fetch_array($edit_query_run);
      
        $e_image = $edit_row['image'];
     
    }
    else{
        // header('location: index.php');
        echo "Error1";
    }
}
else{
    // header("location: index.php");
    echo "Error2";
}

// Check if form is submitted
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

  	
	$msg = "";
	$image = $_FILES['image']['name'];
	if(empty($image)){
	    $image = $e_image;
	}
	$target = "upload_images/".basename($image);

    // Construct the SQL query for data update
    $update = "UPDATE card_activation SET 
            u_card = '$u_card',
            u_f_name = '$u_f_name',
            u_l_name = '$u_l_name',
            u_father = '$u_father',
            u_aadhar = '$u_aadhar',
            u_birthday = '$u_birthday',
            u_gender = '$u_gender',
            u_email = '$u_email',
            u_phone = '$u_phone',
            u_mother = '$u_mother',
            u_address = '$u_address',
            u_department = '$u_department',
            u_academic_year = '$u_academic_year',
            u_10th_percentage = '$u_10th_percentage',
            staff_id = '$staff_id',
            image = '$image',
            semester1='$semester1',
            semester2='$semester2',
            semester3='$semester3',
            semester4='$semester4',
            semester5='$semester5',
            semester6='$semester6'
             WHERE id=$id ";
    
    // Execute the update query
    $run_update = mysqli_query($con, $update);

	if($run_update){
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        echo "<script>
         alert('Success! Data has been successfully updated!');
         window.location.href='index1.php';
         </script>";
 }else{
     echo "Data not update";
 }
}

?>