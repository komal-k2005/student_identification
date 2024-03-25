<?php  
//export.php  
include 'db.php';
$output = '';
if(isset($_POST["export"]))
{
 $query = "SELECT * FROM card_activation order by 1 desc";
 $result = mysqli_query($con, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" border="1">  
                    <tr>  
                         <th>S.L</th>  
                         <th>Card Number</th>
                         <th>First Name</th>
                         <th>Father Name</th>
                         <th>Aadhar Number</th>
                         <th>Birthday</th>
                         <th>Gender</th>
                         <th>Email</th>
                         <th>Phone</th>
                         <th>Mother Name</th>
                         <th>Address</th>
                         <th>Department</th>
                         <th>Academic Year</th>
                         <th>10th Percentage</th>
                         <th>Staff ID</th>
                         <th>Image</th>
                         <th>Uploaded</th>
                         <th>Semester 1</th>
                         <th>Semester 2</th>
                         <th>Semester 3</th>
                         <th>Semester 4</th>
                         <th>Semester 5</th>
                         <th>Semester 6</th>

                    </tr>
  ';
  $i = 0;
  while($row = mysqli_fetch_array($result))
  {
    $sl = ++$i;
   $output .= '
    <tr>  
                         <td > '.$sl.' </td>
                         <td>'.$row["u_card"].'</td>
                         <td>'.$row["u_f_name"].$row["u_l_name"].'</td> 
                         <td>'. $row["u_father"]. $row["u_l_name"].'</td>
                         <td>'. $row["u_aadhar"].'</td>
                         <td>'. $row["u_birthday"].'</td>
                         <td>'. $row["u_gender"].'</td>
                         <td>'. $row["u_email"].'</td>
                         <td>'. $row["u_phone"].'</td>
                         <td>'. $row["u_mother"]. $row["u_l_name"].'</td>
                         <td>'. $row["u_address"].'</td>
                         <td>'. $row["u_department"].'</td>
                         <td>'. $row["u_academic_year"].'</td>
                         <td>'. $row["u_10th_percentage"].'</td>
                         <td>'. $row["staff_id"].'</td>
                         <td>'. $row["image"].'</td>
                         <td>'. $row["uploaded"].'</td>
                         <td>'. $row["semester1"].'</td>
                         <td>'. $row["semester2"].'</td>
                         <td>'. $row["semester3"].'</td>
                         <td>'. $row["semester4"].'</td>
                         <td>'. $row["semester5"].'</td>
                         <td>'.$row["semester6"].'</td>
                         
                         
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=Student_Data.xls'); // Corrected filename attribute
  echo $output;
 }
}
?>
