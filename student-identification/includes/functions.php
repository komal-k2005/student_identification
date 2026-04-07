<?php
// includes/functions.php

// Encryption key - IMPORTANT: In a real application, store this securely (e.g., environment variable)
// This key should be 32 bytes (256 bits) for aes-256-cbc
define('ENCRYPTION_KEY', 'your_32_byte_encryption_key_here'); // <<< CHANGE THIS KEY!!!
define('ENCRYPTION_IV', 'your_16_byte_iv!'); // <<< CHANGE THIS IV!!! (16 bytes for AES-256-CBC - must be exactly 16 bytes)

function encryptData(string $data): string {
    $cipher = "aes-256-cbc";
    // Using a fixed IV for simplicity in this example, but it's generally better to generate a unique IV per encryption
    // and prepend it to the ciphertext, then extract it during decryption.
    // For this example, we'll use a defined IV. Make sure ENCRYPTION_IV is 16 bytes.
    $iv = ENCRYPTION_IV;
    $ciphertext = openssl_encrypt($data, $cipher, ENCRYPTION_KEY, 0, $iv);
    if ($ciphertext === false) {
        // Handle encryption error, log it, etc.
        return ''; // Return empty or throw exception
    }
    return base64_encode($ciphertext . '::' . $iv); // Store IV with ciphertext
}

/**
 * Auto-detect local network IP (so mobile can reach the PC when scanning QR).
 */
function getLocalNetworkIp(): ?string {
    // 1. Try gethostbyname - often returns primary adapter IP
    $hostname = gethostname();
    if ($hostname) {
        $ip = gethostbyname($hostname);
        if ($ip && $ip !== $hostname && $ip !== '127.0.0.1' && filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }
    // 2. On Windows: parse ipconfig for first non-loopback IPv4
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $output = @shell_exec('ipconfig');
        if ($output && preg_match_all('/IPv4[^:]*:\s*(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/', $output, $m)) {
            foreach ($m[1] as $ip) {
                $ip = trim($ip);
                if ($ip !== '127.0.0.1' && filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }
    // 3. Try SERVER_ADDR (may be 127.0.0.1 when using localhost)
    if (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] !== '127.0.0.1') {
        return $_SERVER['SERVER_ADDR'];
    }
    return null;
}

/**
 * Get base URL for QR codes (auto-detect IP when QR_SCAN_BASE_URL is empty).
 */
function getQrBaseUrl(): string {
    $basePath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/') : '/student-identification';
    if (defined('QR_SCAN_BASE_URL') && QR_SCAN_BASE_URL !== '') {
        return rtrim(QR_SCAN_BASE_URL, '/');
    }
    $localIp = getLocalNetworkIp();
    if ($localIp) {
        return 'http://' . $localIp . $basePath;
    }
    return defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
}

/**
 * Build the scan URL for a student QR code (used when generating QR image).
 */
function getStudentQrScanUrl(string $encryptedStudentId): string {
    return getQrBaseUrl() . '/public/scan.php?type=student&id=' . rawurlencode($encryptedStudentId);
}

/**
 * Build the scan URL for a staff QR code (used when generating QR image).
 */
function getStaffQrScanUrl(string $encryptedStaffId): string {
    return getQrBaseUrl() . '/public/scan.php?type=staff&id=' . rawurlencode($encryptedStaffId);
}

/**
 * Extract encrypted ID from scanned QR content (handles both raw encrypted string and scan URL).
 */
function parseQrDataFromScan(string $scannedText): ?string {
    $scannedText = trim($scannedText);
    if (empty($scannedText)) {
        return null;
    }
    // If it's a scan URL, extract the id parameter
    if (strpos($scannedText, 'scan.php') !== false && (strpos($scannedText, 'type=student') !== false || strpos($scannedText, 'type=staff') !== false)) {
        parse_str(parse_url($scannedText, PHP_URL_QUERY) ?: '', $params);
        return $params['id'] ?? null;
    }
    // Otherwise assume it's raw encrypted data
    return $scannedText;
}

function decryptData(string $data): string|false {
    $cipher = "aes-256-cbc";
    $decoded = base64_decode($data);
    if ($decoded === false) {
        return false;
    }
    $parts = explode('::', $decoded, 2);
    if (count($parts) === 2) {
        list($ciphertext, $iv) = $parts;
        return openssl_decrypt($ciphertext, $cipher, ENCRYPTION_KEY, 0, $iv);
    }
    return false;
}

/**
 * Check if a file exists and is a valid PNG (not an old text placeholder).
 */
function isValidPngFile(string $filePath): bool {
    if (!file_exists($filePath)) {
        return false;
    }
    $size = filesize($filePath);
    if ($size < 8) {
        return false;
    }
    $fh = @fopen($filePath, 'rb');
    if (!$fh) {
        return false;
    }
    $header = fread($fh, 8);
    fclose($fh);
    return ($header === "\x89PNG\r\n\x1a\n");
}

// Function to generate QR code
function generateQRCode(string $data, string $filePath, int $size = 4, int $margin = 2): bool {
    if (!is_dir(dirname($filePath))) {
        mkdir(dirname($filePath), 0777, true);
    }
    
    // If file exists but is not a valid PNG (e.g. old text placeholder), remove it so we regenerate
    if (file_exists($filePath) && !isValidPngFile($filePath)) {
        @unlink($filePath);
    }
    
    // Load QR library if not already loaded (needed when generating via web requests)
    if (!class_exists('QRcode')) {
        $qrLib = defined('PROJECT_ROOT') ? (PROJECT_ROOT . '/vendor/phpqrcode/phpqrcode.php') : (__DIR__ . '/../vendor/phpqrcode/phpqrcode.php');
        if (is_file($qrLib)) {
            require_once $qrLib;
        }
    }
    
    // Check if GD library is available (required for image generation)
    if (!function_exists('imagecreate')) {
        error_log("GD library is not available. Please enable GD extension in PHP.");
        return false;
    }
    
    // Use standard QRcode class (phpqrcode library)
    if (class_exists('QRcode') && method_exists('QRcode', 'png')) {
        QRcode::png($data, $filePath, QR_ECLEVEL_H, $size, $margin);
        return file_exists($filePath) && isValidPngFile($filePath);
    }
    error_log("QR Code library not found. Please ensure phpqrcode library is installed.");
    return false;
}
