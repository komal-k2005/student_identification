<?php  
// Excel Export and Template Generation
include 'db.php';
session_start();

// Check if template download is requested
if(isset($_GET["export_template"])) {
    $output = "S.L,Card Number,First Name,Last Name,Father Name,Aadhar Number,Birthday,Gender,Email,Phone,Mother Name,Address,Department,Academic Year,10th Percentage,Staff ID,Image,Uploaded,Semester 1,Semester 2,Semester 3,Semester 4,Semester 5,Semester 6\n";
    $output .= "1,2111580001,John,Doe,Robert Doe,123456789012,2000-01-15,Male,john@example.com,9876543210,Jane Doe,123 Main St,Computer Technology,3rd Year,85,1158,,,75,80,82,78,85,88\n";
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=Student_Data_Template.csv');
    echo $output;
    exit();
}

// Regular export
$output = '';
if(isset($_POST["export"]))
{
    // Check if CSV format is requested
    $format = isset($_POST['export_format']) ? $_POST['export_format'] : 'excel';
    
    if($format == 'csv') {
        // CSV Export
        $query = "SELECT * FROM card_activation ORDER BY id DESC";
        $result = mysqli_query($con, $query);
        
        // CSV Headers
        $output = "S.L,Card Number,First Name,Last Name,Father Name,Aadhar Number,Birthday,Gender,Email,Phone,Mother Name,Address,Department,Academic Year,10th Percentage,Staff ID,Image,Uploaded,Semester 1,Semester 2,Semester 3,Semester 4,Semester 5,Semester 6\n";
        
        $i = 0;
        while($row = mysqli_fetch_array($result)) {
            $sl = ++$i;
            // Escape commas and quotes in CSV
            $escape_csv = function($value) {
                $value = str_replace('"', '""', $value);
                if(strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
                    return '"' . $value . '"';
                }
                return $value;
            };
            
            $output .= $sl . ',';
            $output .= $escape_csv($row["u_card"]) . ',';
            $output .= $escape_csv($row["u_f_name"]) . ',';
            $output .= $escape_csv($row["u_l_name"]) . ',';
            $output .= $escape_csv($row["u_father"]) . ',';
            $output .= $escape_csv($row["u_aadhar"]) . ',';
            $output .= $escape_csv($row["u_birthday"]) . ',';
            $output .= $escape_csv($row["u_gender"]) . ',';
            $output .= $escape_csv($row["u_email"]) . ',';
            $output .= $escape_csv($row["u_phone"]) . ',';
            $output .= $escape_csv($row["u_mother"]) . ',';
            $output .= $escape_csv($row["u_address"]) . ',';
            $output .= $escape_csv($row["u_department"]) . ',';
            $output .= $escape_csv($row["u_academic_year"]) . ',';
            $output .= $escape_csv($row["u_10th_percentage"]) . ',';
            $output .= $escape_csv($row["staff_id"]) . ',';
            $output .= $escape_csv($row["image"]) . ',';
            $output .= $escape_csv($row["uploaded"]) . ',';
            $output .= $escape_csv($row["semester1"] ?? '') . ',';
            $output .= $escape_csv($row["semester2"] ?? '') . ',';
            $output .= $escape_csv($row["semester3"] ?? '') . ',';
            $output .= $escape_csv($row["semester4"] ?? '') . ',';
            $output .= $escape_csv($row["semester5"] ?? '') . ',';
            $output .= $escape_csv($row["semester6"] ?? '') . "\n";
        }
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Student_Data_' . date('Y-m-d') . '.csv');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel compatibility
        echo $output;
        exit();
    } else {
        // Excel Export (HTML table format)
        $query = "SELECT * FROM card_activation ORDER BY id DESC";
        $result = mysqli_query($con, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $output .= '
            <table class="table" border="1">  
                <tr>  
                    <th>S.L</th>  
                    <th>Card Number</th>
                    <th>First Name</th>
                    <th>Last Name</th>
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
            while($row = mysqli_fetch_array($result)) {
                $sl = ++$i;
                $output .= '
                <tr>  
                    <td>'.$sl.'</td>
                    <td>'.htmlspecialchars($row["u_card"]).'</td>
                    <td>'.htmlspecialchars($row["u_f_name"]).'</td>
                    <td>'.htmlspecialchars($row["u_l_name"]).'</td>
                    <td>'.htmlspecialchars($row["u_father"]).'</td>
                    <td>'.htmlspecialchars($row["u_aadhar"]).'</td>
                    <td>'.htmlspecialchars($row["u_birthday"]).'</td>
                    <td>'.htmlspecialchars($row["u_gender"]).'</td>
                    <td>'.htmlspecialchars($row["u_email"]).'</td>
                    <td>'.htmlspecialchars($row["u_phone"]).'</td>
                    <td>'.htmlspecialchars($row["u_mother"]).'</td>
                    <td>'.htmlspecialchars($row["u_address"]).'</td>
                    <td>'.htmlspecialchars($row["u_department"]).'</td>
                    <td>'.htmlspecialchars($row["u_academic_year"]).'</td>
                    <td>'.htmlspecialchars($row["u_10th_percentage"]).'</td>
                    <td>'.htmlspecialchars($row["staff_id"]).'</td>
                    <td>'.htmlspecialchars($row["image"]).'</td>
                    <td>'.htmlspecialchars($row["uploaded"]).'</td>
                    <td>'.htmlspecialchars($row["semester1"] ?? '').'</td>
                    <td>'.htmlspecialchars($row["semester2"] ?? '').'</td>
                    <td>'.htmlspecialchars($row["semester3"] ?? '').'</td>
                    <td>'.htmlspecialchars($row["semester4"] ?? '').'</td>
                    <td>'.htmlspecialchars($row["semester5"] ?? '').'</td>
                    <td>'.htmlspecialchars($row["semester6"] ?? '').'</td>
                </tr>
                ';
            }
            $output .= '</table>';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=Student_Data_' . date('Y-m-d') . '.xls');
            echo $output;
            exit();
        }
    }
}
?>
