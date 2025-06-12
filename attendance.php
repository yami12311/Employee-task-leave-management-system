<?php
// Start session
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.html');
    exit;
}

// Include database connection
include('db_config.php');

// Variable to store the success message
$successMessage = "";

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

// Handle form submission for attendance recording
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check CSRF token
    if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    $employee_id = $_SESSION['employee_id'];
    $attendance_date = date('Y-m-d'); // Automatically set current date
    $check_in_time = $_POST['check_in_time'];
    $check_out_time = $_POST['check_out_time'];
    $status = $_POST['status'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : ''; // Ensure remarks is set

    // Calculate work hours (assuming the check-in and check-out times are in valid format)
    $check_in = new DateTime($check_in_time);
    $check_out = new DateTime($check_out_time);
    $interval = $check_in->diff($check_out);
    $work_hours = $interval->h + ($interval->i / 60); // hours + minutes as a fraction

    // Insert attendance record into the database
    $sql = "INSERT INTO attendance (employee_id, attendance_date, check_in_time, check_out_time, status, work_hours, remarks) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssds", $employee_id, $attendance_date, $check_in_time, $check_out_time, $status, $work_hours, $remarks);
    
    if ($stmt->execute()) {
        // Redirect to avoid resubmission on page reload
        header("Location: attendance.php?success=true");
        exit;
    } else {
        $errorMessage = "Error recording attendance: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <link rel="stylesheet" href="attendance.css">
</head>
<body>
    <h1>Record Your Attendance</h1>

    <!-- Success Message -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        <script>
            window.onload = function() {
                alert("Attendance recorded successfully!");
                // Redirect to the employee dashboard after the alert
                setTimeout(function() {
                    window.location.href = "employee_dashboard.php"; // Replace with your dashboard URL
                }, 100); // Delay of 1 second before redirect
            };
        </script>
    <?php endif; ?>

    <form method="POST" action="attendance.php">
        <!-- CSRF Token Field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label for="attendance_date">Attendance Date (Automatically filled):</label>
        <input type="text" id="attendance_date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" readonly><br><br>

        <label for="check_in_time">Check-in Time:</label>
        <input type="time" id="check_in_time" name="check_in_time" required><br>

        <label for="check_out_time">Check-out Time:</label>
        <input type="time" id="check_out_time" name="check_out_time" required><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="On Leave">On Leave</option>
        </select><br>

        <label for="remarks">Remarks:</label><br>
        <textarea id="remarks" name="remarks" rows="4" cols="50"></textarea><br><br>

        <button type="submit" id="submitButton">Submit Attendance</button>
    </form>

    <script>
        // Disable submit button after form submission to prevent resubmission
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('submitButton').disabled = true;
            document.getElementById('submitButton').innerText = 'Submitting...';
        });
    </script>
</body>
</html>
