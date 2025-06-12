<?php
// Include database connection
include 'db_config.php';

$searchPerformed = false; // Flag to check if search was performed

// Check if search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_value'])) {
    $searchPerformed = true;
    $searchValue = trim($_POST['search_value']);
    
    // Prepare query with search filter
    $stmt = $conn->prepare("
        SELECT a.attendance_id, a.employee_id, e.username, a.attendance_date, a.check_in_time, a.check_out_time, 
               a.status, a.work_hours, a.remarks 
        FROM attendance a
        JOIN employees e ON a.employee_id = e.employee_id
        WHERE e.username LIKE ? OR a.employee_id = ?
        ORDER BY a.attendance_id DESC
    ");
    
    $searchPattern = "%$searchValue%";
    $stmt->bind_param("si", $searchPattern, $searchValue);
    $stmt->execute();
    $attendanceRecords = $stmt->get_result();
} else {
    // Fetch all records if no search is performed
    $attendanceRecords = $conn->query("
        SELECT a.attendance_id, a.employee_id, e.username, a.attendance_date, a.check_in_time, a.check_out_time, 
               a.status, a.work_hours, a.remarks 
        FROM attendance a
        JOIN employees e ON a.employee_id = e.employee_id
        ORDER BY a.attendance_id DESC
        LIMIT 100
    ");
}

// Handle CSV download before any output
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=attendance_records.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Attendance ID', 'Employee ID', 'Employee Name', 'Date', 'Check-in Time', 'Check-out Time', 'Status', 'Work Hours', 'Remarks']);

    if ($attendanceRecords && $attendanceRecords->num_rows > 0) {
        while ($row = $attendanceRecords->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }

    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 30px;
        }

        h2 {
            font-weight: bold;
            color: #343a40;
            text-align: center;
        }

        /*  Table Container with Scroll */
        .table-container {
            max-height: 500px; /* Controls scroll height */
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 1px solid #ddd;
        }

        /* Fixed Table Header */
        .table thead th {
            position: sticky;
            top: 0;
            background-color: #343a40 !important;
            color: white !important;
            z-index: 1000;
            text-align: center;
            border: 1px solid #ddd;
        }

        /*  Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        thead th, tbody td {
            text-align: center;
            padding: 12px;
            border: 1px solid #ddd;
        }

        /*  Hover Effect */
        tbody tr:hover {
            background-color: #e9ecef;
        }

        /*  Buttons */
        .btn {
            font-weight: bold;
        }

        .btn-dark {
            margin-top: 10px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Manage Attendance</h2>

    <!-- Search Form -->
    <form method="POST" action="" class="mb-3">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search_value" class="form-control" placeholder="Search by Employee ID or Name" required value="<?php echo isset($_POST['search_value']) ? htmlspecialchars($_POST['search_value']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" name="search" class="btn btn-primary">Search</button>
                <button type="submit" name="download" class="btn btn-success">Download CSV</button>
            </div>
            <?php if ($searchPerformed) { ?>
                <div class="col-md-3 text-end">
                    <a href="attendance_manage.php" class="btn btn-secondary">Back to Main Table</a>
                </div>
            <?php } ?>
        </div>
    </form>

    <!-- Attendance Table -->
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Attendance ID</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Check-in Time</th>
                    <th>Check-out Time</th>
                    <th>Status</th>
                    <th>Work Hours</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendanceRecords && $attendanceRecords->num_rows > 0) { 
                    while ($row = $attendanceRecords->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['attendance_id']; ?></td>
                            <td><?php echo $row['employee_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['attendance_date']; ?></td>
                            <td><?php echo $row['check_in_time']; ?></td>
                            <td><?php echo $row['check_out_time']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['work_hours']; ?></td>
                            <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                        </tr>
                    <?php } 
                } else { ?>
                    <tr>
                        <td colspan="9" class="text-center">No records found</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-3">
        <a href="dashboard.php" class="btn btn-dark">Back to Dashboard</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
