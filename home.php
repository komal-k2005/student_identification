<?php  
include("db.php");
session_start();
if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

// Get user info
$id = intval($_SESSION['id']);
$stmt = mysqli_prepare($con, "SELECT * FROM users WHERE Id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

$res_Uname = '';
$res_Email = '';
$staff_id = '';
$res_id = 0;
$res_subject = '';

if($result = mysqli_fetch_assoc($query)){
    $res_Uname = htmlspecialchars($result['Username'], ENT_QUOTES, 'UTF-8');
    $res_Email = htmlspecialchars($result['Email'], ENT_QUOTES, 'UTF-8');
    $staff_id = htmlspecialchars(isset($result['staff_id']) ? $result['staff_id'] : '', ENT_QUOTES, 'UTF-8');
    $res_id = intval($result['Id']);
    $res_subject = htmlspecialchars(isset($result['subject']) ? $result['subject'] : '', ENT_QUOTES, 'UTF-8');
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Dashboard - Student Identification</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .dashboard-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
            transition: transform 0.3s;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }
        .stat-card i {
            font-size: 56px;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        .stat-card h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }
        .quick-action {
            display: block;
            padding: 18px;
            margin: 12px 0;
            background: #f8f9fa;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            border-left: 4px solid #667eea;
        }
        .quick-action:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(10px);
            border-left-color: white;
        }
        .welcome-section {
            text-align: center;
            padding: 20px 0;
        }
        .welcome-section img {
            max-width: 200px;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .profile-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 25px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="dashboard-card">
                    <div class="welcome-section">
                        <?php if(file_exists('logo.jpg')): ?>
                        <img src="logo.jpg" alt="College Logo" class="img-fluid">
                        <?php endif; ?>
                        <h2 class="mt-3" style="color: #667eea; font-weight: 700;">Welcome, <?php echo $res_Uname; ?>!</h2>
                        <p class="text-muted" style="font-size: 18px;">Student Identification System - College Management Portal</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <a href="index1.php" style="text-decoration: none;">
                    <div class="stat-card">
                        <i class="fas fa-user-graduate"></i>
                        <h4>Students</h4>
                        <p>Manage student records</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="staff.php" style="text-decoration: none;">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h4>Staff</h4>
                        <p>Staff management</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="view_attendance.php" style="text-decoration: none;">
                    <div class="stat-card">
                        <i class="fas fa-calendar-check"></i>
                        <h4>Attendance</h4>
                        <p>View attendance records</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="staff_qr_management.php" style="text-decoration: none;">
                    <div class="stat-card">
                        <i class="fas fa-qrcode"></i>
                        <h4>QR Codes</h4>
                        <p>QR code management</p>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <h4><i class="fas fa-user-circle"></i> Your Profile</h4>
                    <hr>
                    <div class="profile-section">
                        <p><strong><i class="fas fa-user"></i> Username:</strong> <?php echo $res_Uname; ?></p>
                        <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?php echo $res_Email; ?></p>
                        <p><strong><i class="fas fa-id-badge"></i> Staff ID:</strong> <?php echo $staff_id ?: 'N/A'; ?></p>
                        <?php if($res_subject): ?>
                        <p><strong><i class="fas fa-book"></i> Subject:</strong> <?php echo $res_subject; ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="editprofile.php?Id=<?php echo $res_id; ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card">
                    <h4><i class="fas fa-bolt"></i> Quick Actions</h4>
                    <hr>
                    <a href="index1.php" class="quick-action">
                        <i class="fas fa-plus-circle"></i> Add New Student
                    </a>
                    <a href="excel_import.php" class="quick-action">
                        <i class="fas fa-file-excel"></i> Import Excel Data
                    </a>
                    <a href="view_attendance.php" class="quick-action">
                        <i class="fas fa-chart-line"></i> View Attendance Reports
                    </a>
                    <a href="staff_qr_management.php" class="quick-action">
                        <i class="fas fa-qrcode"></i> Generate Staff QR Codes
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

