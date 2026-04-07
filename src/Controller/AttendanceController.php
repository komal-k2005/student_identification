<?php
// src/Controller/AttendanceController.php

require_once __DIR__ . '/../Model/Attendance.php';
require_once __DIR__ . '/../Model/Student.php';
require_once __DIR__ . '/../Model/Staff.php';

class AttendanceController {
    private $pdo;
    private $attendanceModel;
    private $studentModel;
    private $staffModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->attendanceModel = new Attendance($pdo);
        $this->studentModel = new Student($pdo);
        $this->staffModel = new Staff($pdo);
    }

    public function dailyReport() {
        $reportTitle = "Daily Attendance Report";
        $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING) ?? date('Y-m-d');
        $attendanceRecords = $this->attendanceModel->getDailyAttendance($date);
        require_once __DIR__ . '/../View/admin/reports.php'; // Admin view for reports
    }

    public function monthlyReport() {
        $reportTitle = "Monthly Attendance Report";
        $month = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING) ?? date('m');
        $year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_STRING) ?? date('Y');
        $attendanceRecords = $this->attendanceModel->getMonthlyAttendance($month, $year);
        require_once __DIR__ . '/../View/admin/reports.php';
    }

    public function semesterReport() {
        $reportTitle = "Semester-wise Attendance Report";
        $semester = filter_input(INPUT_GET, 'semester', FILTER_SANITIZE_STRING);
        $year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_STRING) ?? date('Y');
        $attendanceRecords = $this->attendanceModel->getSemesterAttendance($semester, $year);
        require_once __DIR__ . '/../View/admin/reports.php';
    }
}
