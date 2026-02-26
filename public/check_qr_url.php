<?php
/**
 * Diagnostic page: Shows the URL used in QR codes and helps fix "site can't be reached"
 * Open: http://localhost/student-identification/public/check_qr_url.php
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$detectedIp = getLocalNetworkIp();
$qrBaseUrl = getQrBaseUrl();
$sampleScanUrl = getQrBaseUrl() . '/public/scan.php?type=staff&id=test';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR URL Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px">
        <h2 class="mb-4">QR Code URL Check</h2>
        
        <div class="card mb-3">
            <div class="card-body">
                <p class="mb-1"><strong>Detected PC IP:</strong> <code><?php echo $detectedIp ?: 'Not detected'; ?></code></p>
                <p class="mb-1"><strong>QR Scan URL (base):</strong> <code><?php echo htmlspecialchars($qrBaseUrl); ?></code></p>
                <p class="mb-0"><strong>Sample scan URL:</strong> <code class="small"><?php echo htmlspecialchars($sampleScanUrl); ?></code></p>
            </div>
        </div>

        <?php if (!$detectedIp || strpos($qrBaseUrl, 'localhost') !== false): ?>
        <div class="alert alert-warning">
            <strong>IP not detected or using localhost.</strong> Add your PC IP manually in <code>config/database.php</code>:
            <pre class="mt-2 mb-0">define('QR_SCAN_BASE_URL', 'http://YOUR_PC_IP/student-identification');</pre>
            Find your IP: CMD → <code>ipconfig</code> → IPv4 Address (e.g. 192.168.1.100)
        </div>
        <?php endif; ?>

        <div class="card border-info">
            <div class="card-header bg-info text-white">If phone shows "site can't be reached"</div>
            <div class="card-body">
                <ol class="mb-0">
                    <li><strong>Check IP above</strong> – Open this URL on your phone: <a href="<?php echo $qrBaseUrl; ?>/public/scan.php" target="_blank"><?php echo htmlspecialchars($qrBaseUrl); ?>/public/scan.php</a></li>
                    <li><strong>Same WiFi</strong> – Phone and PC must be on the same network</li>
                    <li><strong>Windows Firewall</strong> – Allow Apache/HTTP: Windows Security → Firewall → Allow an app → Apache HTTP Server (Private)</li>
                    <li><strong>Regenerate QR codes</strong> – Delete files in <code>public/qr_codes/</code> and run <a href="<?php echo BASE_URL; ?>/sample_data.php">sample_data.php</a> or generate QR again</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>
