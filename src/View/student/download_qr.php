<?php
// src/View/student/download_qr.php
require_once __DIR__ . '/../../../includes/header.php';
// $error is passed from StudentController::downloadQrIdCard()
?>

<h2 class="mb-4">Download QR ID Card</h2>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        Your QR ID Card download should start shortly.
        If not, you can try <a href="<?php echo BASE_URL; ?>/student/download_qr.php">downloading it again</a>.
    </div>
<?php endif; ?>

<div class="text-center mt-4">
    <a href="<?php echo BASE_URL; ?>/student/profile.php" class="btn btn-primary">Back to Profile</a>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
