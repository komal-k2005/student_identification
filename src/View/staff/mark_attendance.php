<?php
// src/View/staff/mark_attendance.php
require_once __DIR__ . '/../../../includes/header.php';
// $errors, $success, $scannedStudent are passed from StaffController::markAttendance()
?>

<h2 class="mb-4">Mark Student Attendance</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if (isset($success) && $success): ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($success); ?>
    </div>
    <?php if ($scannedStudent): ?>
        <div class="card card-body mt-3">
            <p><strong>Student:</strong> <?php echo htmlspecialchars($scannedStudent['full_name']); ?></p>
            <p><strong>Roll No:</strong> <?php echo htmlspecialchars($scannedStudent['roll_number']); ?></p>
            <p><strong>Branch:</strong> <?php echo htmlspecialchars($scannedStudent['branch']); ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="card p-4">
    <form id="attendanceForm" action="<?php echo BASE_URL; ?>/staff/mark_attendance.php" method="POST">
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>

        <div id="qr-reader" style="width: 100%; max-width: 500px;"></div>
        <div id="qr-reader-results" class="mt-3 text-center"></div>

        <input type="hidden" name="qr_code_data" id="qr_code_data-input">
        <button type="submit" class="btn btn-primary w-100 mt-3" id="confirmAttendanceButton" style="display: none;">Confirm Attendance</button>
    </form>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code matched = ${decodedText}`, decodedResult);
        var qrData = decodedText;
        if (decodedText.indexOf('scan.php') !== -1 && decodedText.indexOf('type=student') !== -1) {
            try {
                var url = new URL(decodedText);
                qrData = url.searchParams.get('id') || decodedText;
            } catch (e) {}
        }
        document.getElementById('qr_code_data-input').value = qrData;
        document.getElementById('qr-reader-results').innerHTML = `<div class="alert alert-success">QR Code Scanned!</div>`;
        document.getElementById('confirmAttendanceButton').style.display = 'block';
        html5QrCode.stop().then(ignore => {
            // QR code scanning stopped.
        }).catch(err => {
            console.error("Failed to stop scanning: ", err);
        });
    }

    function onScanFailure(error) {
        // console.warn(`Code scan error = ${error}`);
    }

    let html5QrCode = new Html5Qrcode("qr-reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess,
        onScanFailure
    ).catch(err => {
        console.error("Error starting QR scanner: ", err);
        document.getElementById('qr-reader-results').innerHTML = '<div class="alert alert-danger">Error starting QR scanner. Please ensure your device has a camera and grant permissions.</div>';
    });
</script>
<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
