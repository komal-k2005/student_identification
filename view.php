<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Add necessary CSS and JS libraries -->
    <!-- Bootstrap CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
       
        .modal-body {
            max-height: 700px; /* Adjust as needed */
            overflow-y: auto; /* Enable vertical scrolling */
        }
    </style>
</head>
<body>

<?php
include('db.php');

// Check if student_id parameter is present in the URL
if(isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    // Fetch data for the specific student
    $get_data = "SELECT * FROM card_activation WHERE id = $student_id";
    $run_data = mysqli_query($con, $get_data);
    
    if ($row = mysqli_fetch_array($run_data)) {
        $id = $row['id'];
        $card_no = $row['u_card'];
        $user_first_name = $row['u_f_name'];
        $user_last_name = $row['u_l_name'];
        $user_father = $row['u_father'];
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
        $semester1 = $row['semester1'];
        $semester2 = $row['semester2'];
        $semester3 = $row['semester3'];
        $semester4 = $row['semester4'];
        $semester5 = $row['semester5'];
        $semester6 = $row['semester6'];

        echo "
            <!-- Modal -->
            <div class='modal fade show' id='view$id' tabindex='-1' role='dialog' aria-labelledby='userViewModalLabel' aria-hidden='true' style='display: block;'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='exampleModalLabel'>Profile <i class='fa fa-user-circle-o' aria-hidden='true'></i></h5>
                        </div>
                        <div class='modal-body'>
                            <div class='container' id='profile'> 
                                <div class='row'>
                                    <div class='col-sm-4 col-md-2'>
                                        <img src='upload_images/$image' alt='' style='width: 150px; height: 150px;'><br>
                                        <i class='fa fa-id-card' aria-hidden='true'></i> $card_no<br>
                                        <i class='fa fa-phone' aria-hidden='true'></i> $user_phone  <br>
                                        <i class='fa fa-envelope-o' aria-hidden='true'></i> $user_email<br>
                                        Issue Date : $time
                                    </div>
                                    <div class='col-sm-6 col-md-6'>
                                        <h3>$user_first_name $user_last_name</h3>
                                        <p class='text-secondary'>
                                            <strong>Father Name:</strong> $user_father $user_last_name<br>
                                            <strong>Mother Name:</strong> $user_mother $user_last_name<br>
                                            <strong>Aadhar :</strong> $user_aadhar <br>
                                            <strong>Gender:</strong> $gender<br>
                                            <strong>Date of Birth :</strong> $user_dob <br>
                                            <strong>Address :</strong> $address <br>
                                            <strong>Department :</strong> $state <br>
                                            <strong>Academic Year :</strong> $academic_year <br>
                                            <strong>10th Percentage :</strong> $pincode <br>
                                            <strong>Staff ID :</strong> $staff_id <br>
                                            <strong>Semester details:</strong><br>
                                            <table BORDER=1 WIDTH=240>
                                                <thead>
                                                    <tr>
                                                        <th>Semester</th>
                                                        <th width='120'>Result</th>
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
                                            </table>
                                        </p>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    echo "<p>No student ID provided.</p>";
}
?>

</body>
</html>
