<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch counts
$tables = ['admins', 'employees', 'attendance', 'leave_request', 'task', 'user_profile'];
$counts = [];
foreach ($tables as $table) {
    $query = "SELECT COUNT(*) AS count FROM $table";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $counts[$table] = $row['count'];
}

// Fetch latest data
$latestTasks = $conn->query("SELECT task_id, employee_id, task_name, status FROM task ORDER BY task_id DESC LIMIT 5");
$employees = $conn->query("SELECT * FROM employees");

// Secure fetch of logged-in admin's profile using prepared statement
$stmt = $conn->prepare("SELECT profile_id, employee_id, username, password, role, last_login FROM user_profile WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $_SESSION['admin_username']);
$stmt->execute();
$profileResult = $stmt->get_result();
$profile = $profileResult->fetch_assoc();

// Handle task creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_task'])) {
    $taskName = $_POST['task_name'];
    $employeeId = $_POST['employee_id'];
    $description = $_POST['description'];
    $assignedDate = $_POST['assigned_date'];
    $dueDate = $_POST['due_date'];
    $status = $_POST['status'];

    // Insert new task into the database
    $insertQuery = "INSERT INTO task (task_name, employee_id, description, assigned_date, due_date, status) 
                    VALUES ('$taskName', '$employeeId', '$description', '$assignedDate', '$dueDate', '$status')";
    
    if ($conn->query($insertQuery)) {
        echo "<script>alert('Task created successfully!');</script>";
    } else {
        echo "<script>alert('Error creating task: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- FontAwesome and Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
            document.getElementById(tabId).style.display = 'block';
        }
    </script>
</head>
<body>
<div class="container-fluid">
    <header class="row bg-primary text-white p-4">
        <div class="col">
            <h1>Welcome, <?php echo $_SESSION['admin_username']; ?></h1>
            <div class="h5">Employee Management</div>
        </div>
        <div class="col text-right">
            <a href="login.html" class="btn btn-outline-light">Logout</a>
        </div>
    </header>

    <div class="row">
        <nav class="col-md-2 bg-light sidebar p-2">
            <h4>Admin Dashboard</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <button class="btn btn-link" onclick="showTab('dashboard-overview')">
                        <i class="fas fa-tachometer-alt"></i> Overview
                    </button>
                </li>
                <li class="nav-item">
                    <a href="manage.php" class="btn btn-link">
                        <i class="fas fa-users"></i> Manage Employees
                    </a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-link" onclick="showTab('manage-tasks')">
                        <i class="fas fa-tasks"></i> Tasks
                    </button>
                </li>
                <li class="nav-item">
                    <a href="manage_leave.php" class="btn btn-link">
                        <i class="fas fa-calendar-check"></i> Leave Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a href="attendance_manage.php" class="btn btn-link">
                        <i class="fas fa-clock"></i> Attendance
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="btn btn-link">
                        <i class="fas fa-user"></i> User Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a href="task_report.php" class="btn btn-link">
                        <i class="fas fa-chart-line"></i> Task Report
                    </a>
                </li>
            </ul>
        </nav>

        <main class="col-md-10 p-3">
            <!-- Dashboard Overview -->
            <div id="dashboard-overview" class="tab-content">
                <h2>Dashboard Overview</h2>
                <div class="row">
                    <?php foreach ($counts as $key => $count) { ?>
                        <div class="col-md-2">
                            <div class="card p-3">
                                <h5 class="card-title"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></h5>
                                <p class="card-text"><?php echo $count; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Manage Tasks -->
            <div id="manage-tasks" class="tab-content" style="display: none;">
                <h2>Manage Tasks</h2>
                <a href="create_task.php" class="btn btn-primary mb-3">Create New Task</a>
                <h3>Existing Tasks</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Name</th>
                            <th>Assigned To (ID)</th>
                            <th>Description</th>
                            <th>Assigned Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tasksResult = $conn->query("SELECT * FROM task ORDER BY task_id DESC");
                        while ($task = $tasksResult->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$task['task_id']}</td>
                                    <td>{$task['task_name']}</td>
                                    <td>{$task['employee_id']}</td>
                                    <td>{$task['description']}</td>
                                    <td>{$task['assigned_date']}</td>
                                    <td>{$task['due_date']}</td>
                                    <td>{$task['status']}</td>
                                    <td>
                                        <a href='edit_task.php?task_id={$task['task_id']}' class='btn btn-sm btn-warning'>Edit</a> |
                                        <a href='delete_task.php?task_id={$task['task_id']}' class='btn btn-sm btn-danger'>Delete</a>
                                    </td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Manage Profile -->
            <div id="manage-profile" class="tab-content" style="display: none;">
                <h2>User Profile</h2>
                <?php if ($profile): ?>
                    <table class="table">
                        <tr>
                            <th>Profile ID</th>
                            <td><?php echo htmlspecialchars($profile['profile_id']); ?></td>
                        </tr>
                        <tr>
                            <th>Employee ID</th>
                            <td><?php echo htmlspecialchars($profile['employee_id']); ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?php echo htmlspecialchars($profile['username']); ?></td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td>******</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><?php echo htmlspecialchars($profile['role']); ?></td>
                        </tr>
                        <tr>
                            <th>Last Login</th>
                            <td><?php echo htmlspecialchars($profile['last_login']); ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <p>No profile found.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
