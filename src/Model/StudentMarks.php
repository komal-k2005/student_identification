<?php
// src/Model/StudentMarks.php

class StudentMarks {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addOrUpdateMarks(int $studentId, array $marksData): bool {
        // Check if marks for this student already exist (we assume one row per student for all marks)
        $existingMarks = $this->getMarksByStudentId($studentId);

        $fields = [
            'marks_10th',
            'marks_12th',
            'marks_semester_1',
            'marks_semester_2',
            'marks_semester_3',
            'marks_semester_4',
            'marks_semester_5',
            'marks_semester_6',
            'marks_semester_7',
            'marks_semester_8'
        ];

        if (!empty($existingMarks)) {
            // Update existing marks
            $updateFields = [];
            $params = [];
            foreach ($fields as $field) {
                if (isset($marksData[$field])) {
                    $updateFields[] = "{$field} = ?";
                    $params[] = $marksData[$field];
                }
            }

            if (empty($updateFields)) {
                return true; // Nothing to update
            }

            $sql = "UPDATE student_marks SET " . implode(', ', $updateFields) . " WHERE student_id = ?";
            $params[] = $studentId;
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } else {
            // Insert new marks
            $insertFields = ['student_id'];
            $insertPlaceholders = ['?'];
            $params = [$studentId];

            foreach ($fields as $field) {
                if (isset($marksData[$field])) {
                    $insertFields[] = $field;
                    $insertPlaceholders[] = '?';
                    $params[] = $marksData[$field];
                }
            }
            // If no marks data is provided other than student_id, still create a row
            if (count($insertFields) === 1) {
                 // Insert a row with just student_id and nulls for marks
                $sql = "INSERT INTO student_marks (student_id) VALUES (?)";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$studentId]);
            }

            $sql = "INSERT INTO student_marks (" . implode(', ', $insertFields) . ") VALUES (" . implode(', ', $insertPlaceholders) . ")";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        }
    }

    public function getMarksByStudentId(int $studentId): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM student_marks WHERE student_id = ?");
        $stmt->execute([$studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result === false) ? null : $result;
    }

    // Removed getMarksByStudentIdAndSemester as marks are stored in a single row per student
}
