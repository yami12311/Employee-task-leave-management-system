<?php
session_start();
include('db_config.php');

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle task creation securely
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_task'])) {
    $taskName = $_POST['task_name'];
    $employeeId = $_POST['employee_id'];
    $description = $_POST['description'];
    $assignedDate = $_POST['assigned_date'];
    $dueDate = $_POST['due_date'];
    $status = 'Pending'; // Default status

    // Insert Task
    $stmt = $conn->prepare("INSERT INTO task (task_name, employee_id, description, assigned_date, due_date, status) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $taskName, $employeeId, $description, $assignedDate, $dueDate, $status);

    if ($stmt->execute()) {
        // ✅ Add Notification for the Employee
        $notification_message = "New task assigned: $taskName (Due: $dueDate)";
        $notify_stmt = $conn->prepare("INSERT INTO notifications (employee_id, message, status) VALUES (?, ?, 'unread')");
        $notify_stmt->bind_param("is", $employeeId, $notification_message);
        $notify_stmt->execute();
        $notify_stmt->close();

        echo "<script>alert('Task created and notification sent!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error creating task: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Task</title>
    <link rel="stylesheet" href="create_task.css">
</head>
<body>
    <h2>Create New Task</h2>
    <form method="POST" action="">
        <label for="task_name">Task Name:</label>
        <input type="text" name="task_name" required><br><br>

        <label for="employee_id">Assign to Employee (ID):</label>
        <select name="employee_id" required>
            <?php 
            // Fetch employees to assign task
            $employees = $conn->query("SELECT employee_id, username FROM employees");
            while ($employee = $employees->fetch_assoc()) { ?>
                <option value="<?php echo $employee['employee_id']; ?>">
                    <?php echo htmlspecialchars($employee['username']); ?> (ID: <?php echo $employee['employee_id']; ?>)
                </option>
            <?php } ?>
        </select><br><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br><br>

        <label for="assigned_date">Assigned Date:</label>
        <input type="date" name="assigned_date" required><br><br>

        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" required><br><br>

        <button type="submit" name="create_task">Create Task</button>
    </form>
</body>
</html>
