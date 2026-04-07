<?php
// src/View/admin/reports.php
require_once __DIR__ . '/../../../includes/header.php';

// These variables would be set by AttendanceController methods (dailyReport, monthlyReport, etc.)
$reportTitle = $reportTitle ?? "Attendance Report";
$attendanceRecords = $attendanceRecords ?? [];
$currentReportType = $_GET['reportType'] ?? 'daily'; // Default to daily
?>

<h2 class="mb-4"><?php echo htmlspecialchars($reportTitle); ?></h2>

<div class="card mb-4">
    <div class="card-header">
        Filter Reports
    </div>
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/admin/" method="GET" class="row g-3" id="reportFilterForm">
            <div class="col-md-4">
                <label for="reportType" class="form-label">Report Type</label>
                <select id="reportType" name="reportType" class="form-select">
                    <option value="daily" <?php echo($currentReportType == 'daily') ? 'selected' : ''; ?>>Daily</option>
                    <option value="monthly" <?php echo($currentReportType == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                    <option value="semester" <?php echo($currentReportType == 'semester') ? 'selected' : ''; ?>>Semester-wise</option>
                </select>
            </div>
            <div class="col-md-4" id="dateFilter" style="display: <?php echo($currentReportType == 'daily') ? 'block' : 'none'; ?>;">
                <label for="date" class="form-label">Date (for Daily)</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? date('Y-m-d')); ?>">
            </div>
            <div class="col-md-4" id="monthYearFilter" style="display: <?php echo($currentReportType == 'monthly') ? 'flex' : 'none'; ?>;">
                <div class="col-md-6">
                    <label for="month" class="form-label">Month (for Monthly)</label>
                    <select id="month" name="month" class="form-select">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo(isset($_GET['month']) && $_GET['month'] == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : ((!isset($_GET['month']) && $i == date('m')) ? 'selected' : ''); ?>><?php echo date('F', mktime(0, 0, 0, $i, 10)); ?></option>
                        <?php
endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="yearMonth" class="form-label">Year</label>
                    <select id="yearMonth" name="year" class="form-select">
                        <?php for ($i = date('Y'); $i >= (date('Y') - 5); $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo(isset($_GET['year']) && $_GET['year'] == $i) ? 'selected' : ((!isset($_GET['year']) && $i == date('Y')) ? 'selected' : ''); ?>><?php echo $i; ?></option>
                        <?php
endfor; ?>
                    </select>
                </div>
            </div>
             <div class="col-md-4" id="semesterFilter" style="display: <?php echo($currentReportType == 'semester') ? 'flex' : 'none'; ?>;">
                 <div class="col-md-6">
                    <label for="semester" class="form-label">Semester (for Semester-wise)</label>
                    <select id="semester" name="semester" class="form-select">
                        <option value="">All Semesters</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo(isset($_GET['semester']) && $_GET['semester'] == $i) ? 'selected' : ''; ?>>Semester <?php echo $i; ?></option>
                        <?php
endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="yearSemester" class="form-label">Year</label>
                    <select id="yearSemester" name="year" class="form-select">
                        <?php for ($i = date('Y'); $i >= (date('Y') - 5); $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo(isset($_GET['year']) && $_GET['year'] == $i) ? 'selected' : ((!isset($_GET['year']) && $i == date('Y')) ? 'selected' : ''); ?>><?php echo $i; ?></option>
                        <?php
endfor; ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($attendanceRecords)): ?>
    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Staff Name</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['staff_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['subject']); ?></td>
                        <td><?php echo htmlspecialchars($record['attendance_date']); ?></td>
                        <td><?php echo htmlspecialchars($record['attendance_time']); ?></td>
                    </tr>
                <?php
    endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
else: ?>
    <div class="alert alert-info mt-4">No attendance records found for the selected criteria.</div>
<?php
endif; ?>

<script>
    document.getElementById('reportType').addEventListener('change', function() {
        var reportType = this.value;
        var form = document.getElementById('reportFilterForm');
        
        document.getElementById('dateFilter').style.display = 'none';
        document.getElementById('monthYearFilter').style.display = 'none';
        document.getElementById('semesterFilter').style.display = 'none';

        if (reportType === 'daily') {
            document.getElementById('dateFilter').style.display = 'block';
            form.action = '<?php echo BASE_URL; ?>/admin/daily_report.php';
        } else if (reportType === 'monthly') {
            document.getElementById('monthYearFilter').style.display = 'flex';
            form.action = '<?php echo BASE_URL; ?>/admin/monthly_report.php';
        } else if (reportType === 'semester') {
            document.getElementById('semesterFilter').style.display = 'flex';
            form.action = '<?php echo BASE_URL; ?>/admin/semester_report.php';
        }
    });
    // Trigger change on load to set initial visibility
    document.getElementById('reportType').dispatchEvent(new Event('change'));
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
