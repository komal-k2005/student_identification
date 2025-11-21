<?php
// QR Code Scan Landing Page - Shows two options when QR code is scanned
session_start();
include('db.php');

// Determine if this is a student QR or staff QR
$student_id = isset($_GET['student_id']) && is_numeric($_GET['student_id']) ? intval($_GET['student_id']) : null;
$staff_id = isset($_GET['staff_id']) ? mysqli_real_escape_string($con, trim($_GET['staff_id'])) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Options</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .option-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .option-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .option-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #667eea;
        }
        .option-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .option-description {
            color: #666;
            margin-bottom: 20px;
        }
        .container-custom {
            max-width: 800px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <?php if($student_id): ?>
            <!-- Student QR Code Scanned -->
            <div class="text-center mb-4">
                <h2 class="text-white mb-4">Student QR Code Scanned</h2>
                <p class="text-white">Please select an option:</p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="option-card" onclick="location.href='view.php?student_id=<?php echo $student_id; ?>'">
                        <div class="option-icon">
                            <i class="fa fa-user-circle-o"></i>
                        </div>
                        <div class="option-title">View Student Data</div>
                        <div class="option-description">
                            View complete student profile including personal information, academic records, and semester details.
                        </div>
                        <button class="btn btn-primary btn-lg">View Profile</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="option-card" onclick="location.href='view_attendance.php?student_id=<?php echo $student_id; ?>'">
                        <div class="option-icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <div class="option-title">View Attendance</div>
                        <div class="option-description">
                            Check attendance records for this student across all subjects and dates.
                        </div>
                        <button class="btn btn-success btn-lg">View Attendance</button>
                    </div>
                </div>
            </div>
        <?php elseif($staff_id): ?>
            <!-- Staff QR Code Scanned -->
            <div class="text-center mb-4">
                <h2 class="text-white mb-4">Staff QR Code Scanned</h2>
                <p class="text-white">Please enter your staff password to access attendance system:</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <div class="option-title">Staff Authentication</div>
                        <form method="POST" action="staff_auth.php">
                            <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">
                            <div class="form-group">
                                <label for="staff_password">Enter Staff Password:</label>
                                <input type="password" class="form-control" id="staff_password" name="staff_password" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fa fa-sign-in"></i> Login
                            </button>
                        </form>
                        <?php if(isset($_GET['error']) && $_GET['error'] == '1'): ?>
                            <div class="alert alert-danger mt-3">
                                <i class="fa fa-exclamation-circle"></i> Invalid password. Please try again.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Invalid QR Code -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="option-card">
                        <div class="option-icon" style="color: #dc3545;">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="option-title">Invalid QR Code</div>
                        <div class="option-description">
                            The scanned QR code is not recognized. Please scan a valid student or staff QR code.
                        </div>
                        <button class="btn btn-secondary btn-lg" onclick="history.back()">Go Back</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>



