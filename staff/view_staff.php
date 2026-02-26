<?php
// staff/view_staff.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Model/Staff.php';

$staffModel = new Staff($pdo);
$staffId = $_SESSION['user_id'];
$staff = $staffModel->getStaffById($staffId);

if (!$staff) {
    echo "<div class=\"alert alert-danger\">Staff not found.</div>";
    require_once __DIR__ . '/../includes/footer.php';
    exit();
}

require_once __DIR__ . '/../includes/functions.php';

$qrCodePath = (defined('BASE_URL') ? BASE_URL : '') . '/public/img/placeholder_qr.png';

if (empty($staff['qr_code_data'])) {
    $encryptedStaffId = encryptData($staffId);
    if ($encryptedStaffId !== '') {
        $staffModel->updateStaffQRCode($staffId, $encryptedStaffId);
        $staff['qr_code_data'] = $encryptedStaffId;
    }
}

if (!empty($staff['qr_code_data'])) {
    $qrCodeFileName = 'staff_' . $staffId . '.png';
    $qrCodeFilePath = __DIR__ . '/../public/qr_codes/' . $qrCodeFileName;
    if (!file_exists($qrCodeFilePath) || !isValidPngFile($qrCodeFilePath)) {
        generateQRCode(getStaffQrScanUrl($staff['qr_code_data']), $qrCodeFilePath);
    }
    $qrCodePath = (defined('BASE_URL') ? BASE_URL : '') . '/public/qr_codes/' . $qrCodeFileName;
}

?>

<h2 class="mb-4">View Staff Details</h2>

<div class="card mb-4">
    <div class="card-header">
        Staff Information
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p><strong>Staff Name:</strong> <?php echo htmlspecialchars($staff['staff_name']); ?></p>
                <p><strong>Subject:</strong> <?php echo htmlspecialchars($staff['subject'] ?? 'N/A'); ?></p>
                <p><strong>Education:</strong> <?php echo htmlspecialchars($staff['education'] ?? 'N/A'); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($staff['department'] ?? 'N/A'); ?></p>
                <p><strong>Staff ID:</strong> <?php echo htmlspecialchars($staff['staff_id']); ?></p>
            </div>
            <div class="col-md-4 text-center">
                <p><strong>Staff QR Code:</strong></p>
                <img src="<?php echo $qrCodePath; ?>" alt="Staff QR Code" class="img-fluid border p-2" style="max-width: 150px;">
                <p class="mt-2 text-muted">Scan to view staff details</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
