<?php
// Excel Import Functionality
session_start();
include('db.php');

if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

$error_msg = '';
$success_msg = '';
$import_count = 0;

if(isset($_POST['import_excel'])) {
    // Check if file was uploaded
    if(isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $file = $_FILES['excel_file'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validate file type
        if($file_ext == 'xls' || $file_ext == 'xlsx' || $file_ext == 'csv') {
            // For CSV files, parse directly
            if($file_ext == 'csv') {
                $handle = fopen($file['tmp_name'], 'r');
                $row = 1;
                $headers = [];
                
                while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if($row == 1) {
                        // Skip header row
                        $headers = $data;
                        $row++;
                        continue;
                    }
                    
                    // Map CSV columns to database fields
                    if(count($data) >= 14) {
                        $u_card = mysqli_real_escape_string($con, trim($data[1] ?? ''));
                        $u_f_name = mysqli_real_escape_string($con, trim($data[2] ?? ''));
                        $u_l_name = mysqli_real_escape_string($con, trim($data[3] ?? ''));
                        $u_father = mysqli_real_escape_string($con, trim($data[4] ?? ''));
                        $u_aadhar = mysqli_real_escape_string($con, trim($data[5] ?? ''));
                        $u_birthday = mysqli_real_escape_string($con, trim($data[6] ?? ''));
                        $u_gender = mysqli_real_escape_string($con, trim($data[7] ?? ''));
                        $u_email = mysqli_real_escape_string($con, trim($data[8] ?? ''));
                        $u_phone = mysqli_real_escape_string($con, trim($data[9] ?? ''));
                        $u_mother = mysqli_real_escape_string($con, trim($data[10] ?? ''));
                        $u_address = mysqli_real_escape_string($con, trim($data[11] ?? ''));
                        $u_department = mysqli_real_escape_string($con, trim($data[12] ?? ''));
                        $u_academic_year = mysqli_real_escape_string($con, trim($data[13] ?? ''));
                        $u_10th_percentage = mysqli_real_escape_string($con, trim($data[14] ?? ''));
                        $staff_id = mysqli_real_escape_string($con, trim($data[15] ?? ''));
                        $semester1 = mysqli_real_escape_string($con, trim($data[18] ?? ''));
                        $semester2 = mysqli_real_escape_string($con, trim($data[19] ?? ''));
                        $semester3 = mysqli_real_escape_string($con, trim($data[20] ?? ''));
                        $semester4 = mysqli_real_escape_string($con, trim($data[21] ?? ''));
                        $semester5 = mysqli_real_escape_string($con, trim($data[22] ?? ''));
                        $semester6 = mysqli_real_escape_string($con, trim($data[23] ?? ''));
                        
                        // Validate required fields
                        if(!empty($u_card) && !empty($u_f_name)) {
                            // Check if student already exists
                            $check_stmt = mysqli_prepare($con, "SELECT id FROM card_activation WHERE u_card = ?");
                            mysqli_stmt_bind_param($check_stmt, "s", $u_card);
                            mysqli_stmt_execute($check_stmt);
                            $check_result = mysqli_stmt_get_result($check_stmt);
                            
                            if(mysqli_num_rows($check_result) == 0) {
                                // Insert new student
                                $insert_stmt = mysqli_prepare($con, "INSERT INTO card_activation (
                                    u_card, u_f_name, u_l_name, u_father, u_aadhar, u_birthday, u_gender, 
                                    u_email, u_phone, u_mother, u_address, u_department, u_academic_year, 
                                    u_10th_percentage, staff_id, image, uploaded, semester1, semester2, 
                                    semester3, semester4, semester5, semester6
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', NOW(), ?, ?, ?, ?, ?, ?)");
                                
                                mysqli_stmt_bind_param($insert_stmt, "ssssssssssssssssssssss", 
                                    $u_card, $u_f_name, $u_l_name, $u_father, $u_aadhar, $u_birthday, 
                                    $u_gender, $u_email, $u_phone, $u_mother, $u_address, $u_department, 
                                    $u_academic_year, $u_10th_percentage, $staff_id, $semester1, 
                                    $semester2, $semester3, $semester4, $semester5, $semester6
                                );
                                
                                if(mysqli_stmt_execute($insert_stmt)) {
                                    $import_count++;
                                }
                                mysqli_stmt_close($insert_stmt);
                            } else {
                                // Update existing student
                                $update_stmt = mysqli_prepare($con, "UPDATE card_activation SET 
                                    u_f_name = ?, u_l_name = ?, u_father = ?, u_aadhar = ?, u_birthday = ?, 
                                    u_gender = ?, u_email = ?, u_phone = ?, u_mother = ?, u_address = ?, 
                                    u_department = ?, u_academic_year = ?, u_10th_percentage = ?, 
                                    staff_id = ?, semester1 = ?, semester2 = ?, semester3 = ?, 
                                    semester4 = ?, semester5 = ?, semester6 = ?
                                    WHERE u_card = ?");
                                
                                mysqli_stmt_bind_param($update_stmt, "sssssssssssssssssssss", 
                                    $u_f_name, $u_l_name, $u_father, $u_aadhar, $u_birthday, 
                                    $u_gender, $u_email, $u_phone, $u_mother, $u_address, $u_department, 
                                    $u_academic_year, $u_10th_percentage, $staff_id, $semester1, 
                                    $semester2, $semester3, $semester4, $semester5, $semester6, $u_card
                                );
                                
                                if(mysqli_stmt_execute($update_stmt)) {
                                    $import_count++;
                                }
                                mysqli_stmt_close($update_stmt);
                            }
                            mysqli_stmt_close($check_stmt);
                        }
                    }
                    $row++;
                }
                fclose($handle);
                
                if($import_count > 0) {
                    $success_msg = "Successfully imported {$import_count} student record(s)!";
                } else {
                    $error_msg = "No records were imported. Please check your file format.";
                }
            } else {
                $error_msg = "Currently only CSV files are supported. Please convert your Excel file to CSV format or use the CSV template.";
            }
        } else {
            $error_msg = "Invalid file format. Please upload CSV, XLS, or XLSX files only.";
        }
    } else {
        $error_msg = "Please select a file to import.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import Excel Data</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <h2><i class="fa fa-upload"></i> Import Student Data from Excel/CSV</h2>
        <a href="index1.php" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</a>
        <hr>
        
        <?php if($error_msg): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i> <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>
        
        <?php if($success_msg): ?>
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>
        
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4>Upload Excel/CSV File</h4>
            </div>
            <div class="panel-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Select File (CSV format recommended):</label>
                        <input type="file" name="excel_file" class="form-control" accept=".csv,.xls,.xlsx" required>
                        <small class="text-muted">
                            Supported formats: CSV, XLS, XLSX<br>
                            <strong>Note:</strong> CSV format is recommended. If you have an Excel file, save it as CSV first.
                        </small>
                    </div>
                    <button type="submit" name="import_excel" class="btn btn-success">
                        <i class="fa fa-upload"></i> Import Data
                    </button>
                    <a href="excel.php?export_template=1" class="btn btn-info">
                        <i class="fa fa-download"></i> Download Template
                    </a>
                </form>
            </div>
        </div>
        
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>CSV File Format Requirements</h4>
            </div>
            <div class="panel-body">
                <p><strong>Required Column Order:</strong></p>
                <ol>
                    <li>S.L (Serial Number - will be ignored)</li>
                    <li>Card Number (Student ID - Required)</li>
                    <li>First Name (Required)</li>
                    <li>Last Name</li>
                    <li>Father Name</li>
                    <li>Aadhar Number</li>
                    <li>Birthday (Format: YYYY-MM-DD)</li>
                    <li>Gender</li>
                    <li>Email</li>
                    <li>Phone</li>
                    <li>Mother Name</li>
                    <li>Address</li>
                    <li>Department</li>
                    <li>Academic Year</li>
                    <li>10th Percentage</li>
                    <li>Staff ID</li>
                    <li>Image (Leave empty - will be ignored)</li>
                    <li>Uploaded (Leave empty - will be ignored)</li>
                    <li>Semester 1</li>
                    <li>Semester 2</li>
                    <li>Semester 3</li>
                    <li>Semester 4</li>
                    <li>Semester 5</li>
                    <li>Semester 6</li>
                </ol>
                <p><strong>Tips:</strong></p>
                <ul>
                    <li>The first row should contain headers (it will be skipped)</li>
                    <li>If a student with the same Card Number exists, the record will be updated</li>
                    <li>Empty fields are allowed except for Card Number and First Name</li>
                    <li>Date format must be YYYY-MM-DD (e.g., 2024-01-15)</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>



