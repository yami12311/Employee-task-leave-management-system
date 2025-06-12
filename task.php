<?php
include 'db_config.php';
session_start();

// Ensure Employee is Logged In
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Handle Task Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'], $_POST['status'])) {
    $task_id = intval($_POST['task_id']);
    $new_status = strtolower(trim($_POST['status'])); 

    // Check Task Ownership & Status
    $stmt = $conn->prepare("SELECT status FROM task WHERE task_id = ? AND employee_id = ?");
    $stmt->bind_param("ii", $task_id, $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        $current_status = strtolower($task['status']); 

        // Ensure correct status transition
        if (($current_status === 'pending' && $new_status === 'in-progress') ||
            ($current_status === 'in-progress' && $new_status === 'completed')) {

            if ($new_status === 'completed') {
                $currentDate = date('Y-m-d');
                $updateStmt = $conn->prepare("UPDATE task SET status = ?, completion_date = ? WHERE task_id = ? AND employee_id = ?");
                $updateStmt->bind_param("ssii", $new_status, $currentDate, $task_id, $employee_id);
            } else {
                $updateStmt = $conn->prepare("UPDATE task SET status = ? WHERE task_id = ? AND employee_id = ?");
                $updateStmt->bind_param("sii", $new_status, $task_id, $employee_id);
            }

            if ($updateStmt->execute()) {
                $_SESSION['message'] = "Task status updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update task status!";
            }
        } else {
            $_SESSION['error'] = "Invalid status update!";
        }
    } else {
        $_SESSION['error'] = "Unauthorized task update!";
    }
    header("Location: task.php");
    exit();
}

// Fetch Tasks Assigned to Employee
$tasksStmt = $conn->prepare("SELECT * FROM task WHERE employee_id = ? ORDER BY task_id DESC");
$tasksStmt->bind_param("i", $employee_id);
$tasksStmt->execute();
$tasks = $tasksStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .navbar {
            background-color: rgb(112, 109, 109);
            padding: 10px 20px;
        }
        .navbar a {
            text-decoration: none;
            font-size: 18px;
        }
        .container {
            margin-top: 80px;
        }
    </style>
</head>
<body>

<!-- Fixed Header -->
<nav class="navbar fixed-top">
    <div class="container-fluid d-flex justify-content-between">
        <h2 class="text-white m-0">My Tasks</h2>
        <a href="employee_dashboard.php" class="btn btn-light">← Back to Dashboard</a>
    </div>
</nav>

<div class="container">
    <!-- Session messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Task List -->
    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Task ID</th>
                <th>Task Name</th>
                <th>Description</th>
                <th>Assigned Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch_assoc()): ?>
                <?php 
                    $status = strtolower($task['status']);
                    $status_labels = [
                        'pending' => ['label' => 'Pending', 'class' => 'bg-secondary'],
                        'in-progress' => ['label' => 'In Progress', 'class' => 'bg-warning text-dark'],
                        'completed' => ['label' => 'Completed', 'class' => 'bg-success']
                    ];
                    $status_data = $status_labels[$status] ?? ['label' => 'Unknown', 'class' => 'bg-dark'];
                ?>
                <tr>
                    <td><?= $task['task_id']; ?></td>
                    <td><?= htmlspecialchars($task['task_name']); ?></td>
                    <td><?= htmlspecialchars($task['description']); ?></td>
                    <td><?= $task['assigned_date']; ?></td>
                    <td><?= $task['due_date']; ?></td>
                    <td><span class="badge <?= $status_data['class'] ?>"><?= $status_data['label'] ?></span></td>
                    <td>
                        <?php if ($status === 'pending'): ?>
                            <form method="POST" action="">
                                <input type="hidden" name="task_id" value="<?= $task['task_id']; ?>">
                                <input type="hidden" name="status" value="in-progress">
                                <button type="submit" class="btn btn-sm btn-warning">Mark as In Progress</button>
                            </form>
                        <?php elseif ($status === 'in-progress'): ?>
                            <form method="POST" action="">
                                <input type="hidden" name="task_id" value="<?= $task['task_id']; ?>">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-sm btn-success">Mark as Completed</button>
                            </form>
                        <?php else: ?>
                            <span class="text-success">Completed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
