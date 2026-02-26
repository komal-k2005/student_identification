<?php
// src/View/student/qr_login.php
require_once __DIR__ . '/../../../config/database.php'; // Need BASE_URL
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student QR Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 500px;">
            <h2 class="card-title text-center mb-4">Student QR Login</h2>
            <?php if (isset($error) && $error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <div id="qr-reader" style="width: 100%; max-width: 500px;"></div>
            <div id="qr-reader-results" class="mt-3 text-center"></div>

            <form id="qrLoginForm" action="<?php echo BASE_URL; ?>/public/index.php?action=student_qr_login" method="POST" class="mt-4" style="display: none;">
                <input type="hidden" name="qr_code_data" id="qr_code_data-input">
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
             <div class="mt-3 text-center">
                <a href="<?php echo BASE_URL; ?>/public/index.php">Staff Login</a>
            </div>
        </div>
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
            document.getElementById('qrLoginForm').style.display = 'block';
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
