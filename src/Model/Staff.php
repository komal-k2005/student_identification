<?php
// src/Model/Staff.php

class Staff
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createStaff(string $username, string $passwordHash, string $staffName, ?string $subject = null, ?string $education = null, ?string $department = null): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO staff (username, password_hash, staff_name, subject, education, department) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$username, $passwordHash, $staffName, $subject, $education, $department]);
    }

    public function getStaffByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM staff WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result === false) ? null : $result;
    }

    public function getStaffById(int $staffId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM staff WHERE staff_id = ?");
        $stmt->execute([$staffId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result === false) ? null : $result;
    }

    public function getAllStaff(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM staff");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStaff(int $staffId, string $staffName, ?string $subject = null, ?string $education = null, ?string $department = null, ?bool $isActive = null): bool
    {
        $sql = "UPDATE staff SET staff_name = ?, subject = ?, education = ?, department = ?";
        $params = [$staffName, $subject, $education, $department];

        if ($isActive !== null) {
            $sql .= ", is_active = ?";
            $params[] = (int)$isActive;
        }
        $sql .= " WHERE staff_id = ?";
        $params[] = $staffId;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateStaffQRCode(int $staffId, string $qrCodeData): bool
    {
        $stmt = $this->pdo->prepare("UPDATE staff SET qr_code_data = ? WHERE staff_id = ?");
        return $stmt->execute([$qrCodeData, $staffId]);
    }

    public function deactivateStaff(int $staffId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE staff SET is_active = FALSE WHERE staff_id = ?");
        return $stmt->execute([$staffId]);
    }

    public function activateStaff(int $staffId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE staff SET is_active = TRUE WHERE staff_id = ?");
        return $stmt->execute([$staffId]);
    }

    public function verifyStaffSecretCode(string $secretCode, string $subject): ?int
    {
        // Find staff members who teach the specified subject (case-insensitive) and are active
        $stmt = $this->pdo->prepare("SELECT staff_id, password_hash, username FROM staff WHERE is_active = TRUE AND LOWER(subject) = LOWER(?)");
        $stmt->execute([trim($subject)]);
        $staffMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($staffMembers as $staff) {
            // Check if the secret code matches their password
            if (password_verify($secretCode, $staff['password_hash'])) {
                return (int)$staff['staff_id'];
            }
            // Alternative: Check if they used their exact username as a secret code (for easy testing)
            if ($secretCode === $staff['username']) {
                return (int)$staff['staff_id'];
            }
        }

        return null;
    }
}
