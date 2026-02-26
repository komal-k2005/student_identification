<?php
// src/Model/Student.php

class Student
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createStudent(string $fullName, ?string $fatherName, ?string $motherName, string $aadhaarNumber, string $rollNumber, string $enrollmentNumber, string $branch, ?string $photoPath = null): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO students (full_name, father_name, mother_name, aadhaar_number, roll_number, enrollment_number, branch, photo_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$fullName, $fatherName, $motherName, $aadhaarNumber, $rollNumber, $enrollmentNumber, $branch, $photoPath]);
    }

    public function getStudentById(int $studentId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result === false) ? null : $result;
    }

    public function getAllStudents(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStudent(int $studentId, string $fullName, ?string $fatherName, ?string $motherName, string $aadhaarNumber, string $rollNumber, string $enrollmentNumber, string $branch, ?string $photoPath = null): bool
    {
        $sql = "UPDATE students SET full_name = ?, father_name = ?, mother_name = ?, aadhaar_number = ?, roll_number = ?, enrollment_number = ?, branch = ?";
        $params = [$fullName, $fatherName, $motherName, $aadhaarNumber, $rollNumber, $enrollmentNumber, $branch];
        if ($photoPath !== null) {
            $sql .= ", photo_path = ?";
            $params[] = $photoPath;
        }
        else {
            $sql .= ", photo_path = NULL"; // Allow setting photo_path to NULL if not provided
        }
        $sql .= " WHERE student_id = ?";
        $params[] = $studentId;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateStudentQRCode(int $studentId, string $qrCodeData): bool
    {
        $stmt = $this->pdo->prepare("UPDATE students SET qr_code_data = ? WHERE student_id = ?");
        return $stmt->execute([$qrCodeData, $studentId]);
    }

    public function getStudentByQRCodeData(string $qrCodeData): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE qr_code_data = ?");
        $stmt->execute([$qrCodeData]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result === false) ? null : $result;
    }

    public function deleteStudent(int $studentId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE student_id = ?");
        return $stmt->execute([$studentId]);
    }
}
