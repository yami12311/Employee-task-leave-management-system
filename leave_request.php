<?php
include 'db_config.php';
session_start(); // Start the session

// Ensure the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch employee details
$stmt = $conn->prepare("SELECT employee_id, username FROM employees WHERE employee_id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $leave_type = $_POST['leave_type'];
    $reason = trim($_POST['reason']); // Trim whitespace from reason

    // Validate dates
    if ($start_date > $end_date) {
        echo "<script>alert('End date must be after start date.');</script>";
    } else {
        // Handle file upload (proof)
        $proof_path = null; // default null if no file uploaded

        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['proof']['tmp_name'];
            $fileName = $_FILES['proof']['name'];
            $fileSize = $_FILES['proof']['size'];
            $fileType = $_FILES['proof']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed file extensions
            $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Sanitize file name
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                // Directory where files will be saved
                $uploadFileDir = './uploads/';

                // Create directory if not exists
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $proof_path = $dest_path;
                } else {
                    echo "<script>alert('Error moving the uploaded file.');</script>";
                }
            } else {
                echo "<script>alert('Upload failed. Allowed file types: jpg, jpeg, png, pdf, doc, docx');</script>";
            }
        }

        // Insert leave request into the database with proof path (if any)
        $stmt = $conn->prepare("INSERT INTO leave_request (employee_id, start_date, end_date, leave_type, reason, status, proof_path) VALUES (?, ?, ?, ?, ?, 'Pending', ?)");
        $stmt->bind_param("isssss", $employee_id, $start_date, $end_date, $leave_type, $reason, $proof_path);

        if ($stmt->execute()) {
            // Redirect with success message
            echo "<script>alert('Leave request submitted successfully!'); window.location.href='leave_request.php';</script>";
        } else {
            echo "<script>alert('Error submitting request. Try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... your existing head content ... -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Request Leave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <style>
        /* your existing styles */
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            color: #fff !important;
        }
        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding-top: 60px; /* Adds space below the fixed navbar */
        }
        .card {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background: white;
        }
        .btn-back {
            background-color: #343a40;
            color: white;
        }
        .btn-back:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Leave Management</a>
        <a href="employee_dashboard.php" class="btn btn-back"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</nav>

<!-- Leave Request Form -->
<div class="container">
    <div class="card">
        <h2 class="text-center mb-4">Request Leave</h2>
        <!-- NOTE: Added enctype="multipart/form-data" for file uploads -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Employee ID:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['employee_id']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Employee Name:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['username']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Start Date:</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">End Date:</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Leave Type:</label>
                <select name="leave_type" class="form-control" required>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Annual Leave">Annual Leave</option>
                    <option value="Casual Leave">Casual Leave</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Reason:</label>
                <textarea name="reason" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Attach Proof (optional):</label>
                <input type="file" name="proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />
                <small class="form-text text-muted">Allowed file types: jpg, jpeg, png, pdf, doc, docx</small>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit Request</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
