<?php
include('db.php');

$get_data = "SELECT * FROM card_activation";
$run_data = mysqli_query($con, $get_data);

while ($row = mysqli_fetch_array($run_data)) {
    $id = $row['id'];
    $card = $row['u_card'];
    $name = $row['u_f_name'];
    $name2 = $row['u_l_name'];
    $father = $row['u_father'];
    $mother = $row['u_mother'];
    $gender = $row['u_gender'];
    $email = $row['u_email'];
    $aadhar = $row['u_aadhar'];
    $Bday = $row['u_birthday'];
    $family = $row['u_family'];
    $phone = $row['u_phone'];
    $address = $row['u_state'];
    $village = $row['u_village'];
    $police = $row['u_police'];
    $dist = $row['u_dist'];
    $pincode = $row['u_pincode'];
    $state = $row['u_state'];
    $time = $row['uploaded'];
    $image = $row['image'];

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
                                    <i class='fa fa-id-card' aria-hidden='true'></i> $card<br>
                                    <i class='fa fa-phone' aria-hidden='true'></i> $phone  <br>
                                    Issue Date : $time
                                </div>
                                <div class='col-sm-3 col-md-6'>
                                    <h3 class='text-primary'>$name $name2</h3>
                                    <p class='text-secondary'>
                                        <strong>S/O :</strong> $father <br>
                                        <strong>M/O :</strong> $mother <br>
                                        <strong>Aadhar :</strong> $aadhar <br>
                                        <i class='fa fa-venus-mars' aria-hidden='true'></i> $gender<br />
                                        <i class='fa fa-envelope-o' aria-hidden='true'></i> $email<br />
                                        <div class='card' style='width: 18rem;'>
                                            <i class='fa fa-users' aria-hidden='true'></i> Familiy :
                                            <div class='card-body'>
                                                <p> $family </p>
                                            </div>
                                        </div>
                                        <i class='fa fa-home' aria-hidden='true'> Address : </i> $village, $police, <br> $dist, $state - $pincode<br />
                                    </p>
                                </div>
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
