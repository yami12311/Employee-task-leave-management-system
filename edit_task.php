<?php
include 'db_config.php';
session_start();

//  Ensure Admin is Logged In
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access Denied!'); window.location.href='login.php';</script>";
    exit();
}

//  Validate Task ID in URL
if (!isset($_GET['task_id']) || !is_numeric($_GET['task_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='admin_dashboard.php';</script>";
    exit();
}

$task_id = intval($_GET['task_id']);

//  Fetch Task Data
$stmt = $conn->prepare("SELECT * FROM task WHERE task_id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    echo "<script>alert('Task not found.'); window.location.href='dashboard.php';</script>";
    exit();
}

//  Handle Update Request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $assigned_date = $_POST['assigned_date'];
    $due_date = $_POST['due_date'];

    $update_stmt = $conn->prepare("UPDATE task SET task_name=?, description=?, status=?, assigned_date=?, due_date=? WHERE task_id=?");
    $update_stmt->bind_param("sssssi", $task_name, $description, $status, $assigned_date, $due_date, $task_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Task updated successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating task.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Task</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Task Name</label>
            <input type="text" class="form-control" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>
        <div class="mb-3">
    <label class="form-label">Assigned Date</label>
    <input type="date" class="form-control" name="assigned_date" value="<?php echo htmlspecialchars($task['assigned_date']); ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Due Date</label>
    <input type="date" class="form-control" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
</div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-control" name="status">
                <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Task</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
