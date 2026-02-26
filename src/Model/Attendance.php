<?php
// src/Model/Attendance.php

class Attendance
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function markAttendance(int $studentId, int $staffId, string $subject): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO attendance (student_id, staff_id, subject, attendance_date, attendance_time) VALUES (?, ?, ?, CURDATE(), CURTIME())");
        return $stmt->execute([$studentId, $staffId, $subject]);
    }

    public function getDailyAttendance(string $date): array
    {
        $stmt = $this->pdo->prepare("SELECT a.*, s.full_name as student_name, st.staff_name FROM attendance a JOIN students s ON a.student_id = s.student_id JOIN staff st ON a.staff_id = st.staff_id WHERE attendance_date = ? ORDER BY attendance_time DESC");
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyAttendance(string $month, string $year): array
    {
        $stmt = $this->pdo->prepare("SELECT a.*, s.full_name as student_name, st.staff_name FROM attendance a JOIN students s ON a.student_id = s.student_id JOIN staff st ON a.staff_id = st.staff_id WHERE MONTH(attendance_date) = ? AND YEAR(attendance_date) = ? ORDER BY attendance_date DESC, attendance_time DESC");
        $stmt->execute([$month, $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSemesterAttendance(?string $semester, string $year): array
    {
        // This logic needs to be refined based on how semesters are defined (e.g., month ranges).
        // For now, it filters by year and can optionally filter by a specific semester number if defined.
        $sql = "SELECT a.*, s.full_name as student_name, st.staff_name FROM attendance a JOIN students s ON a.student_id = s.student_id JOIN staff st ON a.staff_id = st.staff_id WHERE YEAR(attendance_date) = ?";
        $params = [$year];

        if ($semester !== null && $semester !== '') {
        // Add semester-specific filtering if applicable (e.g., if semesters map to specific month ranges)
        // For example, Semester 1 might be Aug-Dec, Semester 2 Jan-May.
        // This would require more complex DATE_FORMAT or BETWEEN clauses.
        // For a basic implementation, if 'semester' represents a grouping, you might need a lookup table
        // or more complex logic here. For now, we'll just show all for the year if semester isn't granularly defined.
        // As per database schema, semester is part of student_marks, not attendance. So filtering attendance by a semester number directly is not straightforward without more data.
        // Reverting to just year-based filter for simplicity if direct semester-to-attendance linkage isn't provided.
        }

        $sql .= " ORDER BY attendance_date DESC, attendance_time DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentAttendance(int $studentId): array
    {
        $stmt = $this->pdo->prepare("SELECT a.*, st.staff_name FROM attendance a JOIN staff st ON a.staff_id = st.staff_id WHERE a.student_id = ? ORDER BY attendance_date DESC, attendance_time DESC");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
