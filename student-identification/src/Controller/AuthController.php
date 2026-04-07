<?php
// src/Controller/AuthController.php

require_once __DIR__ . '/../Model/Staff.php';
require_once __DIR__ . '/../Model/Student.php';

class AuthController {
    private $pdo;
    private $staffModel;
    private $studentModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->staffModel = new Staff($pdo);
        $this->studentModel = new Student($pdo);
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = $_POST['password'] ?? ''; // Keep raw for password_verify

            // Validate input
            if (empty($username) || empty($password)) {
                $error = "Please enter both username and password.";
            } else {
                $staff = $this->staffModel->getStaffByUsername($username);

                $isValidPassword = false;
                if ($staff) {
                    $storedPassword = $staff['password_hash'];

                    // Normal secure flow: hashed password in DB
                    if ($this->looksLikePasswordHash($storedPassword) && password_verify($password, $storedPassword)) {
                        $isValidPassword = true;
                    }
                    // Legacy fallback: plain-text password in DB, then auto-upgrade to hash
                    elseif ($password === $storedPassword) {
                        $isValidPassword = true;
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $this->updateStaffPasswordHash((int)$staff['staff_id'], $newHash);
                    }
                }

                if ($staff && $isValidPassword) {
                    if ($staff['is_active']) {
                        $_SESSION['user_id'] = $staff['staff_id'];
                        $_SESSION['username'] = $staff['username'];
                        $_SESSION['user_type'] = 'staff';
                        // Regenerate session ID to prevent session fixation
                        session_regenerate_id(true);

                        // Store session in database
                        $this->storeSession('staff', $staff['staff_id']);

                        header('Location: ' . BASE_URL . '/staff/dashboard.php');
                        exit();
                    } else {
                        $error = "Your account is deactivated. Please contact the administrator.";
                    }
                } else {
                    $error = "Invalid username or password.";
                }
            }
        }
        require_once __DIR__ . '/../View/auth/login.php';
    }

    public function logout() {
        // Destroy session from database
        if (isset($_SESSION['session_id'])) {
            $this->deleteSession($_SESSION['session_id']);
        }

        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/public/index.php?action=login');
        exit();
    }

    private function storeSession(string $userType, int $userId) {
        $sessionId = session_id();
        // Update last_activity for existing session or insert new one
        $stmt = $this->pdo->prepare("INSERT INTO sessions (session_id, user_id, user_type) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE user_id = ?, user_type = ?, last_activity = CURRENT_TIMESTAMP()");
        $stmt->execute([$sessionId, $userId, $userType, $userId, $userType]);
        $_SESSION['session_id'] = $sessionId;
    }

    private function deleteSession(string $sessionId) {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE session_id = ?");
        $stmt->execute([$sessionId]);
    }

    private function looksLikePasswordHash(string $value): bool {
        // Common PHP password_hash prefixes: $2y$, $2a$, $argon2i$, $argon2id$
        return (strpos($value, '$2y$') === 0
            || strpos($value, '$2a$') === 0
            || strpos($value, '$argon2i$') === 0
            || strpos($value, '$argon2id$') === 0);
    }

    private function updateStaffPasswordHash(int $staffId, string $passwordHash): void {
        $stmt = $this->pdo->prepare("UPDATE staff SET password_hash = ? WHERE staff_id = ?");
        $stmt->execute([$passwordHash, $staffId]);
    }

    public static function checkStaffAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
            header('Location: ' . BASE_URL . '/public/index.php?action=login');
            exit();
        }
    }

    public static function checkStudentAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
            header('Location: ' . BASE_URL . '/public/index.php?action=student_qr_login'); // Redirect students to QR login
            exit();
        }
    }
}
