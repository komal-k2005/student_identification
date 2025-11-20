<?php
// Staff QR Code Management Page
include('db.php');
include('phpqrcode/qrlib.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

// Generate staff QR codes
$get_staff = "SELECT DISTINCT u.staff_id, u.Username, u.Email 
              FROM users u 
              WHERE u.staff_id IS NOT NULL AND u.staff_id != '' 
              ORDER BY u.staff_id";
$staff_result = mysqli_query($con, $get_staff);

// Generate QR code if staff_id is provided
if(isset($_GET['generate_qr']) && isset($_GET['staff_id'])) {
    $staff_id = mysqli_real_escape_string($con, trim($_GET['staff_id']));
    
    // Check if staff exists
    $check_stmt = mysqli_prepare($con, "SELECT * FROM users WHERE staff_id = ?");
    mysqli_stmt_bind_param($check_stmt, "s", $staff_id);
    mysqli_stmt_execute($check_stmt);
    $staff_check = mysqli_stmt_get_result($check_stmt);
    
    if(mysqli_num_rows($staff_check) > 0) {
        $base_url = isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : 'http://localhost';
        $url = $base_url . dirname($_SERVER['PHP_SELF']) . "/qr_scan.php?staff_id=" . urlencode($staff_id);
        $qr_code_filename = "upload_qrcode/staff_{$staff_id}.png";
        
        // Generate QR code
        QRcode::png($url, $qr_code_filename, 10, 5);
        
        // Save QR code path to database
        $insert_stmt = mysqli_prepare($con, "INSERT INTO staff_qr_codes (staff_id, qr_code_path) VALUES (?, ?) ON DUPLICATE KEY UPDATE qr_code_path = ?");
        mysqli_stmt_bind_param($insert_stmt, "sss", $staff_id, $qr_code_filename, $qr_code_filename);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
        
        echo "<script>alert('QR Code generated successfully!');</script>";
    }
    mysqli_stmt_close($check_stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff QR Code Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h2><i class="fa fa-users"></i> Staff QR Code Management</h2>
        <a href="home.php" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</a>
        <hr>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>QR Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($staff = mysqli_fetch_assoc($staff_result)): 
                    $staff_id = htmlspecialchars($staff['staff_id']);
                    $qr_path = "upload_qrcode/staff_{$staff_id}.png";
                    $qr_exists = file_exists($qr_path);
                ?>
                    <tr>
                        <td><?php echo $staff_id; ?></td>
                        <td><?php echo htmlspecialchars($staff['Username']); ?></td>
                        <td><?php echo htmlspecialchars($staff['Email']); ?></td>
                        <td>
                            <?php if($qr_exists): ?>
                                <img src="<?php echo $qr_path; ?>" alt="Staff QR Code" style="width: 100px; height: 100px;">
                            <?php else: ?>
                                <span class="text-muted">Not Generated</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="staff_qr_management.php?generate_qr=1&staff_id=<?php echo urlencode($staff_id); ?>" class="btn btn-sm btn-info">
                                <i class="fa fa-qrcode"></i> Generate QR
                            </a>
                            <?php if($qr_exists): ?>
                                <button class="btn btn-sm btn-success view-staff-qr" data-qr-image="<?php echo $qr_path; ?>" data-staff-id="<?php echo $staff_id; ?>">
                                    <i class="fa fa-eye"></i> View
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <!-- QR Code Modal -->
    <div class="modal fade" id="staffQrModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Staff QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img id="staffQrImage" src="" alt="Staff QR Code" style="max-width: 100%;">
                    <p id="staffQrId"></p>
                    <a id="staffQrDownload" href="#" class="btn btn-primary" download>Download QR Code</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        $('.view-staff-qr').click(function() {
            var qrImage = $(this).data('qr-image');
            var staffId = $(this).data('staff-id');
            $('#staffQrImage').attr('src', qrImage);
            $('#staffQrId').text('Staff ID: ' + staffId);
            $('#staffQrDownload').attr('href', qrImage);
            $('#staffQrModal').modal('show');
        });
    });
    </script>
</body>
</html>



