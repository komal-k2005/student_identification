<?php
// Include necessary files and configurations
include('db.php');
require('fpdf.php'); // Include the FPDF library

// Check if student_id parameter is present in the URL
if(isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
    
    // Fetch data for the specific student using prepared statement
    $stmt = mysqli_prepare($con, "SELECT * FROM card_activation WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $run_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($run_data)) {
        // Data for the student is fetched, now generate the PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        // Add content to the PDF (you can customize this based on your requirements)
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'Student Profile',0,1,'C');
        $pdf->Cell(0,10,'----------------------------------',0,1,'C');
        $pdf->Cell(0,10,'Name: '.$row['u_f_name'].' '.$row['u_l_name'],0,1);
        // Add more information as needed
        // ...

        mysqli_stmt_close($stmt);
        // Output the PDF
        $pdf->Output('D', 'student_profile.pdf'); // 'D' forces download
        exit; // Stop further execution
    } else {
        // Student not found
        mysqli_stmt_close($stmt);
        echo "Student not found.";
    }
} else {
    // No student_id parameter provided
    echo "No student ID provided.";
}
?>
