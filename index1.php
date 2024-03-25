<?php
include('db.php');
include('phpqrcode/qrlib.php');
session_start();
if(!isset($_SESSION['valid'])){
 header("Location: index.php");
}
$get_data = "SELECT * FROM card_activation ORDER BY id DESC";
$run_data = mysqli_query($con, $get_data);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Student_identification</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
</head>
</head>
<body>
	<div class="container">
	<a href="home.php" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</a>
	<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal">
  <i class="fa fa-plus"></i> Add New Student
  </button><div style="float: right;">
        <form method="post" action="excel.php">
            <input type="submit" name="export" class="btn btn-success" value="Excel Data" />
        </form>
    </div>
  <hr>
  <table class="table table-bordered table-striped table-hover" id="myTable">
    <thead>
        <tr>
            <th class="text-center" scope="col">S.L</th>
            <th class="text-center" scope="col">Name</th>
            <th class="text-center" scope="col">Student Id.</th>
            <th class="text-center" scope="col">Staff Id</th>
            <th class="text-center" scope="col">QR Code</th>
            <th class="text-center" scope="col">View</th>
            <th class="text-center" scope="col">Edit</th>
            <th class="text-center" scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;	
        while($row = mysqli_fetch_array($run_data)) {
            $sl = ++$i;
            $id = $row['id'];
            $u_card = $row['u_card'];
            $u_f_name = $row['u_f_name'];
            $u_l_name = $row['u_l_name'];
            $u_phone = $row['u_phone'];
            $staff_id= $row['staff_id'];
            $student_id = $row['u_card'];
            $encoded_id = urlencode($id);
            $local_ip = gethostbyname(trim(shell_exec("hostname"))); // Get local IP address using hostname
            $url = "http://{$local_ip}/student_identification/view.php?view$id&student_id=$id";
            $qr_code_filename = "upload_qrcode/{$student_id}.png"; // File path where the QR code will be saved
            QRcode::png($url, $qr_code_filename, 10, 5); // Generating QR code with dynamically generated $data
            echo "
				<tr>
				<td class='text-center'>$sl</td>
				<td class='text-left'>$u_f_name   $u_l_name</td>
				<td class='text-left'>$u_card</td>
				<td class='text-center'>$staff_id</td>
				<td class='text-center'>
                <span>
                <button class='btn btn-sm btn-info view-qr' data-qr-image='$qr_code_filename' data-qr-name='$u_card'>
                <i class='fa fa-qrcode fa-lg'></i> 
            </button>
                    <img src='$qr_code_filename' alt='QR Code' style='display: none;' class='full-qr-image' id='qr-image-$student_id' />
                </span>
            </td>
<img src='$qr_code_filename' alt='QR Code' style='display: none;' class='full-qr-image' />
</span></td>
<td class='text-center'>
<span>
					<a href='#' class='btn btn-success mr-3 profile' data-toggle='modal' data-target='#view$id' title='Prfile'><i class='fa fa-address-card-o' aria-hidden='true'></i></a>
					</span>
</td>

				<td class='text-center'>
					<span>
					<a href='#' class='btn btn-warning mr-3 edituser' data-toggle='modal' data-target='#edit$id' title='Edit'><i class='fa fa-pencil-square-o fa-lg'></i></a>   
					</span>
					
				</td>
				<td class='text-center'>
					<span>
					
						<a href='#' class='btn btn-danger deleteuser' title='Delete'>
						     <i class='fa fa-trash-o fa-lg' data-toggle='modal' data-target='#$id' style='' aria-hidden='true'></i>
						</a>
					</span>
					
				</td>
			</tr>

        		";
        	}

        	?>

			
		</table>
	</div>
	

<!-- View modal  -->
<?php 
$get_data = "SELECT * FROM card_activation";
$run_data = mysqli_query($con, $get_data);

while($row = mysqli_fetch_array($run_data)) {
    $id = $row['id'];
    $card_no = $row['u_card'];
    $user_first_name = $row['u_f_name'];
    $user_last_name = $row['u_l_name'];
    $user_father= $row['u_father'];
    $user_mother = $row['u_mother'];
    $gender = $row['u_gender'];
    $user_email = $row['u_email'];
    $user_aadhar = $row['u_aadhar'];
    $user_dob = $row['u_birthday'];
    $user_phone = $row['u_phone'];
    $address = $row['u_address'];
    $state = $row['u_department'];
    $academic_year = $row['u_academic_year'];
    $pincode = $row['u_10th_percentage'];
    $staff_id = $row['staff_id'];
    $time = $row['uploaded'];
    $image = $row['image'];
    $semester1= $row['semester1'];
    $semester2 = $row['semester2'];
    $semester3 = $row['semester3'];
    $semester4 = $row['semester4'];
    $semester5 = $row['semester5'];
    $semester6 = $row['semester6'];
    

    echo "
        <div class='modal fade' id='view$id' tabindex='-1' role='dialog' aria-labelledby='userViewModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Profile <i class='fa fa-user-circle-o' aria-hidden='true'></i></h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class='container' id='profile'> 
                            <div class='row'>
                                <div class='col-sm-4 col-md-2'>
                                    <img src='upload_images/$image' alt='' style='width: 150px; height: 150px;' ><br>
                                    <i class='fa fa-id-card' aria-hidden='true'></i> $card_no<br>
                                    <i class='fa fa-phone' aria-hidden='true'></i>  $user_phone <br>
                                    <i class='fa fa-envelope-o' aria-hidden='true'></i>   $user_email<br />
                                    Issue Date : $time
                                </div>
                                <div class='col-sm-3 col-md-6'>
                                    <h3 > $user_first_name $user_last_name </h3>
                                    <p class='text-secondary'>
                                        <strong>Father Name:</strong>$user_father $user_last_name<br>
                                        <strong>Mother Name:</strong>$user_mother $user_last_name<br>
                                        <strong>Aadhar :</strong>  $user_aadhar <br>
                                        <strong>Gender:</strong>   $gender<br>
                                        <strong>Date of Birth :</strong>   $user_dob <br>
                                        <strong>Address :</strong> $address <br>
                                        <strong>Department :</strong>   $state <br>
                                        <strong>Academic Year :</strong> $academic_year <br>
                                        <strong>10th Percentage :</strong> $pincode <br>
                                        <strong>Staff ID :</strong> $staff_id <br>
                                    <strong>Semester details:</strong><br>
                                    <table  BORDER=1 WIDTH=240>
                                    <thead>
                                        <tr>
                                            <th>Semester</th>
                                            <th width=120>Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Semester 1</td>
                                            <td>$semester1</td>
                                        </tr>
                                        <tr>
                                            <td>Semester 2</td>
                                            <td>$semester2</td>
                                        </tr>
                                        <tr>
                                            <td>Semester 3</td>
                                            <td>$semester3</td>
                                        </tr>
                                        <tr>
                                            <td>Semester 4</td>
                                            <td>$semester4</td>
                                        </tr>
                                        <tr>
                                            <td>Semester 5</td>
                                            <td>$semester5</td>
                                        </tr>
                                        <tr>
                                            <td>Semester 6</td>
                                            <td>$semester6</td>
                                        </tr>
                                    </tbody>
                                </table></div>
                                </p>
                            </div>

                        </div>   
                    </div>
                    
                    <div class='modal-footer'>
                        <!-- Add a button to trigger PDF download -->
                      <!--  <button class='btn btn-primary' onclick='downloadPDF($id)'>Download PDF</button>
                      -->  <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div> 
    ";
}

?>

<script>
$(document).ready(function () {
    // DataTable initialization
    $('#myTable').DataTable();

    // QR code click event to open modal
    $('.view-qr').on('click', function () {
        var qrImage = $(this).data('qr-image');
        var modalBody = $('#qrModal').find('.modal-body');
        var qrName = $(this).data('qr-name');
        modalBody.find('#qrImage').attr('src', qrImage);
        modalBody.find('#qrName').text(qrName);
        $('#downloadLink').attr('href', qrImage);
        $('#qrModal').modal('show');
    });

    // Check if URL contains modal=view parameter
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('modal') && urlParams.get('modal') === 'view') {
        // Extract student ID from URL
        var studentId = urlParams.get('student_id');
        // Show the corresponding view modal
        $('#view' + studentId).modal('show');
    }
});
</script>



	<!---Add in modal---->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Add New Student</h4>
      </div>
      <div class="modal-body">
        <form action="add.php?id=$id" method="POST" enctype="multipart/form-data">
<div class="form-row">
<div class="form-group col-md-6">
<label for="inputEmail4">Student Id.</label>
<input type="text" class="form-control" name="card_no" placeholder="Enter 10-digit Student Id." maxlength="10" pattern="\d{10}"  required>
</div>
<div class="form-group col-md-6">
<label for="inputPassword4">Mobile No.</label>
<input type="phone" class="form-control" name="user_phone" placeholder="Enter 10-digit Mobile no." maxlength="10" pattern="\d{10}"  required>
</div>
</div>


<div class="form-row">
<div class="form-group col-md-6">
<label for="firstname">First Name</label>
<input type="text" class="form-control" name="user_first_name" placeholder="Enter First Name">
</div>
<div class="form-group col-md-6">
<label for="lastname">Last Name</label>
<input type="text" class="form-control" name="user_last_name" placeholder="Enter Last Name">
</div>
</div>


<div class="form-row">
<div class="form-group col-md-6">
<label for="fathername">Father's Name</label>
<input type="text" class="form-control" name="user_father" placeholder="Enter First Name">
</div>
<div class="form-group col-md-6">
<label for="mothername">Mother's Name</label>
<input type="text" class="form-control" name="user_mother" placeholder="Enter Mother Name">
</div>
</div>


<div class="form-row">
<div class="form-group col-md-6">
<label for="email">Email Id</label>
<input type="email" class="form-control" name="user_email" placeholder="Enter Email id">
</div>
<div class="form-group col-md-6">
<label for="aadharno">Aadhar No.</label>
<input type="text" class="form-control" name="user_aadhar" maxlength="12" placeholder="Enter 12-digit Aadhar no. "  pattern="\d{12}" required>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-6">
<label for="inputState">Gender</label>
<select id="inputState" name="user_gender" class="form-control">
  <option selected>Choose...</option>
  <option>Male</option>
  <option>Female</option>
  <option>Other</option>
</select>
</div>
<div class="form-group col-md-6">
<label for="inputPassword4">Date of Birth</label>
<input type="date" class="form-control" name="user_dob" placeholder="Date of Birth">
</div>
</div>
<div class="form-group">
<label for="inputAddress">Address</label>
<input type="text" class="form-control" name="address" placeholder="enter address">
</div>
<div class="form-row">
<div class="form-group col-md-4">
<label for="inputState">Department</label>
<select name="state" class="form-control">
  <option selected>Choose...</option>
  <option value="Computer Technology">Computer Technology</option>
<option value="Eelectrical Engineering">Electrical Engineering</option>
<option value="Civil Engineering">Civil Engineering</option>
<option value="Electronic & Telecommunication">Electronic & Telecommunication</option>
<option value="Mechanical">Mechanical</option>	
								
</select>
</div>
<div class="form-group col-md-4">
    <label for="inputState">Academic Year</label>
    <select name="academic_year" class="form-control">
        <option selected>Choose...</option>
        <option value="1st Year">1st Year</option>
        <option value="2nd Year">2nd Year</option>
        <option value="3rd Year">3rd Year</option>
    </select>
</div>

<div class="form-group col-md-4">
<label for="inputZip">10th percentage</label>
<input type="text" class="form-control" name="pincode" placeholder="enter percentage">
</div>
</div>


<div class="form-group col-md-4">
    <label for="semester1">1st Semester</label>
    <select name="semester1" class="form-control">
        <option selected>Choose...</option>
        <option value="First Class">First Class</option>
        <option value="Second Class">Second Class</option>
        <option value="Third Class">Third Class</option>
        <option value="ATKT">ATKT</option>
        <option value="None">-</option>
    </select>
</div>

<div class="form-group col-md-4">
    <label for="semester2">2nd Semester</label>
    <select name="semester2" class="form-control">
        <option selected>Choose...</option>
        <option value="First Class">First Class</option>
        <option value="Second Class">Second Class</option>
        <option value="Third Class">Third Class</option>
        <option value="ATKT">ATKT</option>
        <option value="None">-</option>
    </select>
</div>

<div class="form-group col-md-4">
    <label for="semester3">3rd Semester</label>
    <select name="semester3" class="form-control">
        <option selected>Choose...</option>
        <option value="First Class">First Class</option>
        <option value="Second Class">Second Class</option>
        <option value="Third Class">Third Class</option>
        <option value="ATKT">ATKT</option>
        <option value="None">-</option>
    </select>
</div>

<div class="form-group col-md-4">
    <label for="semester4">4th Semester</label>
    <select name="semester4" class="form-control">
        <option selected>Choose...</option>
        <option value="First Class">First Class</option>
        <option value="Second Class">Second Class</option>
        <option value="Third Class">Third Class</option>
        <option value="ATKT">ATKT</option>
        <option value="None">-</option>
    </select>
</div>

<div class="form-group col-md-4">
    <label for="semester5">5th Semester</label>
    <select name="semester5" class="form-control">
        <option selected>Choose...</option>
        <option value="First Class">First Class</option>
        <option value="Second Class">Second Class</option>
        <option value="Third Class">Third Class</option>
        <option value="ATKT">ATKT</option>
        <option value="None">-</option>
    </select>
</div>

<div class="form-group col-md-4">
    <label for="semester6">6th Semester</label>
    <select name="semester6" class="form-control">
        <option selected>Choose...</option>
        <option value="First Class">First Class</option>
        <option value="Second Class">Second Class</option>
        <option value="Third Class">Third Class</option>
        <option value="ATKT">ATKT</option>
        <option value="None">-</option>
    </select>
</div>
<div class="form-group col-md-8">
<label for="inputAddress">Staff Id one who add the student.</label>
<input type="text" class="form-control" name="staff_id" maxlength="4" placeholder="Enter 4-digit Staff Id"  pattern="\d{4}" required>
</div><div class="form-group col-md-4">
        		<label>Image</label>
        		<input type="file" name="image" for='image'>
        	</div>
          <div class='modal-footer'>
                            <input type='submit' name='submit' class='btn btn-info btn-large' value='Submit'>
                            </form>
    
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div></div>
</div></div>


<!------DELETE modal---->




<!-- Modal -->
<?php

$get_data = "SELECT * FROM card_activation";
$run_data = mysqli_query($con,$get_data);

while($row = mysqli_fetch_array($run_data))
{
	$id = $row['id'];
	echo "

<div id='$id' class='modal fade' role='dialog'>
  <div class='modal-dialog'>

    <!-- Modal content-->
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title text-center'>Are you want to sure??</h4>
      </div>
      <div class='modal-body'>
        <a href='delete.php?id=$id' class='btn btn-danger' style='margin-left:250px'>Delete</a>
      </div>
      
    </div>

  </div>
</div>


	";
	
}


?>


<!----edit Data--->
<?php
$get_data = "SELECT * FROM card_activation";
$run_data = mysqli_query($con, $get_data);

while ($row = mysqli_fetch_array($run_data)) {
    $id = $row['id'];
    $card_no = $row['u_card'];
    $user_first_name = $row['u_f_name'];
    $user_last_name = $row['u_l_name'];
    $user_father= $row['u_father'];
    $user_mother = $row['u_mother'];
    $gender = $row['u_gender'];
    $user_email = $row['u_email'];
    $user_aadhar = $row['u_aadhar'];
    $user_dob = $row['u_birthday'];
    $user_phone = $row['u_phone'];
    $address = $row['u_address'];
    $state = $row['u_department'];
    $academic_year = $row['u_academic_year'];
    $pincode = $row['u_10th_percentage'];
    $staff_id = $row['staff_id'];
    $time = $row['uploaded'];
    $image = $row['image'];
    $semester1= $row['semester1'];
    $semester2 = $row['semester2'];
    $semester3 = $row['semester3'];
    $semester4 = $row['semester4'];
    $semester5 = $row['semester5'];
    $semester6 = $row['semester6'];
    
    echo "
    <div id='edit$id' class='modal fade' role='dialog'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <h4 class='modal-title text-center'>Edit Student Data</h4>
                </div>
                <div class='modal-body'>
                <form action='edit.php?id=$id' method='post' enctype='multipart/form-data'>

                        <div class='form-row'>
                            <div class='form-group col-md-6'>
                                <label for='inputEmail4'>Student ID</label>
                                <input type='text' class='form-control' name='card_no' placeholder='Enter 10-digit Student ID' maxlength='10' value='$card_no' required>
                            </div>
                            <div class='form-group col-md-6'>
                                <label for='inputPassword4'>Mobile No.</label>
                                <input type='text' class='form-control' name='user_phone' placeholder='Enter 10-digit Mobile no.' maxlength='10' value='$user_phone' required>
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='form-group col-md-6'>
                                <label for='firstname'>First Name</label>
                                <input type='text' class='form-control' name='user_first_name' placeholder='Enter First Name' value='$user_first_name'>
                            </div>
                            <div class='form-group col-md-6'>
                                <label for='lastname'>Last Name</label>
                                <input type='text' class='form-control' name='user_last_name' placeholder='Enter Last Name' value='$user_last_name'>
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='form-group col-md-6'>
                                <label for='fathername'>Father's Name</label>
                                <input type='text' class='form-control' name='user_father' placeholder='Enter Father's Name' value='$user_father'>
                            </div>
                            <div class='form-group col-md-6'>
                                <label for='mothername'>Mother's Name</label>
                                <input type='text' class='form-control' name='user_mother' placeholder='Enter Mother's Name' value='$user_mother'>
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='form-group col-md-6'>
                                <label for='email'>Email Id</label>
                                <input type='email' class='form-control' name='user_email' placeholder='Enter Email id' value='$user_email'>
                            </div>
                            <div class='form-group col-md-6'>
                                <label for='aadharno'>Aadhar No.</label>
                                <input type='text' class='form-control' name='user_aadhar' maxlength='12' placeholder='Enter 12-digit Aadhar no.' value='$user_aadhar'>
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='form-group col-md-6'>
                                <label for='inputState'>Gender</label>
                                <select id='inputState' name='user_gender' class='form-control'>
                                    <option selected>$gender</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class='form-group col-md-6'>
                                <label for='inputPassword4'>Date of Birth</label>
                                <input type='date' class='form-control' name='user_dob' value='$user_dob'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='inputAddress'>Address</label>
                            <input type='text' class='form-control' name='address' placeholder='Enter Address' value='$address'>
                        </div>
                        <div class='form-row'>
                            <div class='form-group col-md-4'>
                                <label for='inputState'>Branch</label>
                                <select name='state' class='form-control'>
                                    <option>$state</option>
                                    <option value='Computer Technology'>Computer Technology</option>
                                    <option value='Electrical Engineering'>Electrical Engineering</option>
                                    <option value='Civil Engineering'>Civil Engineering</option>
                                    <option value='Electronic & Telecommunication'>Electronic & Telecommunication</option>
                                    <option value='Mechanical'>Mechanical</option>
                                </select>
                            </div>
                            <div class='form-group col-md-4'>
                                <label for='inputState'>Academic Year</label>
                                <select name='academic_year' class='form-control'>
                                    <option>$academic_year</option>
                                    <option value='1st Year'>1st Year</option>
                                    <option value='2nd Year'>2nd Year</option>
                                    <option value='3rd Year'>3rd Year</option>
                                </select>
                            </div>
                            <div class='form-group col-md-4'>
                                <label for='inputZip'>10th Percentage</label>
                                <input type='text' class='form-control' name='pincode' value='$pincode' placeholder='Enter Perecentage'>
                            </div>
                            
<div class='form-group col-md-4'>
<label for='semester1'>1st Semester</label>
<select name='semester1' class='form-control'>
    <option selected>$semester1</option>
    <option value='First Class'>First Class</option>
    <option value='Second Class'>Second Class</option>
    <option value='Third Class'>Third Class</option>
    <option value='ATKT'>ATKT</option>
</select>
</div>     <div class='form-group col-md-4'>
<label for='semester2'>2nd Semester</label>
<select name='semester2' class='form-control'>
    <option selected>$semester2</option>
    <option value='First Class'>First Class</option>
    <option value='Second Class'>Second Class</option>
    <option value='Third Class'>Third Class</option>
    <option value='ATKT'>ATKT</option>
</select>
</div>        
<div class='form-group col-md-4'>
<label for='semester3'>3rd Semester</label>
<select name='semester3' class='form-control'>
    <option selected>$semester3</option>
    <option value='First Class'>First Class</option>
    <option value='Second Class'>Second Class</option>
    <option value='Third Class'>Third Class</option>
    <option value='ATKT'>ATKT</option>
</select>
</div>        
<div class='form-group col-md-4'>
<label for='semester4'>4th Semester</label>
<select name='semester4' class='form-control'>
    <option selected>$semester4</option>
    <option value='First Class'>First Class</option>
    <option value='Second Class'>Second Class</option>
    <option value='Third Class'>Third Class</option>
    <option value='ATKT'>ATKT</option>
</select>
</div>        
<div class='form-group col-md-4'>
<label for='semester5'>5th Semester</label>
<select name='semester5' class='form-control'>
    <option selected>$semester5</option>
    <option value='First Class'>First Class</option>
    <option value='Second Class'>Second Class</option>
    <option value='Third Class'>Third Class</option>
    <option value='ATKT'>ATKT</option>
</select>
</div>        
<div class='form-group col-md-4'>
<label for='semester6'>6th Semester</label>
<select name='semester6' class='form-control'>
    <option selected>$semester6</option>
    <option value='First Class'>First Class</option>
    <option value='Second Class'>Second Class</option>
    <option value='Third Class'>Third Class</option>
    <option value='ATKT'>ATKT</option>
</select>
</div>             </div>
<div class='form-group col-md-8'>
                            <label for='image'>Image</label>
                            <input type='file' name='image' class='form-control col-md-4'>
                            <img src='upload_images/$image' style='width:50px; height:50px'>
                        </div>
                        <div class='form-group col-md-4'>
                        <label for='inputAddress'>Staff ID</label>
                        <input type='text' class='form-control' name='staff_id' maxlength='4' placeholder='Enter 4-digit Staff ID' value='$staff_id' required>
                    </div >
                    
                        <div class='modal-footer'>
                        <input type='submit' name='submit' class='btn btn-info btn-large' value='Submit'>

                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>";
}
?>


<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();

    });
  </script>

<!-- Modal for QR Code -->
<div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="qrImage" src="" alt="QR Code">
                        <p id="qrName"></p>
                        <a id="downloadLink" href="#" class="btn btn-primary" download>Download</a>
                    </div>
                </d mjuiv>
            </div>
        </div>
  </div>

  <script>
    
    $(document).ready(function () {
        // DataTable initialization
        $('#myTable').DataTable();

        // QR code click event to open modal
        $('.view-qr').on('click', function () {
            var qrImage = $(this).data('qr-image');
            var modalBody = $('#qrModal').find('.modal-body');
            var qrName = $(this).data('qr-name');
            modalBody.find('#qrImage').attr('src', qrImage);
            modalBody.find('#qrName').text(qrName);
            $('#downloadLink').attr('href', qrImage);
            $('#qrModal').modal('show');
        });
    });</script>
    

</body>
</html>
