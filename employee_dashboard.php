<?php
session_start();
include('db_config.php');

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit;
}

$employee_id = $_SESSION['employee_id'];

// Fetch employee details
$sqlEmployee = "SELECT username FROM employees WHERE employee_id = ?";
$stmtEmployee = $conn->prepare($sqlEmployee);
$stmtEmployee->bind_param("i", $employee_id);
$stmtEmployee->execute();
$resultEmployee = $stmtEmployee->get_result();
$employee = $resultEmployee->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #fff !important;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background: #343a40;
            padding-top: 60px;
        }
        .sidebar .nav-link {
            color: #ffffff;
            padding: 15px;
            display: block;
            transition: 0.3s;
        }
        .sidebar .nav-link:hover {
            background: #007bff;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .logout {
            color: #ff4d4d;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Employee Portal</a>
    </div>
</nav>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <a href="employee_dashboard.php" class="nav-link"><i class="bi bi-house-door"></i> Dashboard</a>
    <a href="attendance.php" class="nav-link"><i class="bi bi-calendar-check"></i> Attendance</a>
    <a href="task.php" class="nav-link"><i class="bi bi-list-check"></i> Tasks</a>
    <a href="leave_request.php" class="nav-link"><i class="bi bi-calendar"></i> Leave Request</a>
    <a href="profile.php" class="nav-link"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="logout.php" class="nav-link logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>Welcome, <strong><?php echo htmlspecialchars($employee['username']); ?></strong>!</h2>

    <!-- Notifications Section -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5><i class="bi bi-bell"></i> Notifications</h5>
        </div>
        <div class="card-body">
            <ul class="list-group" id="notificationList">
                <li class="list-group-item text-muted">No New  notifications...</li>
            </ul>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-check"></i> Attendance</h5>
                    <p class="card-text">Check your attendance records.</p>
                    <a href="attendance.php" class="btn btn-light">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-list-check"></i> Tasks</h5>
                    <p class="card-text">View your assigned tasks.</p>
                    <a href="task.php" class="btn btn-light">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar"></i> Leave Requests</h5>
                    <p class="card-text">Manage your leave requests.</p>
                    <a href="leave_request.php" class="btn btn-light">View</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

 <script>
// Fetch notifications dynamically every 10 seconds
function fetchNotifications() {
    fetch("fetch_notifications.php")
    .then(response => response.text())
    .then(data => {
        document.getElementById("notificationList").innerHTML = data;
    });
}

setInterval(fetchNotifications, 10000);
window.onload = fetchNotifications;
</script>
 

</body>
</html> 
