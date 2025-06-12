<?php
include 'db_config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access Denied!'); window.location.href='login.php';</script>";
    exit();
}

//  Debug: Check if task_id exists in URL
if (!isset($_GET['task_id']) || !is_numeric($_GET['task_id'])) {
    die("Invalid request! Task ID missing or not a number.");
}

$task_id = intval($_GET['task_id']);

//  Debug: Check if task exists in the database
$result = $conn->prepare("SELECT * FROM task WHERE task_id = ?");
$result->bind_param("i", $task_id);
$result->execute();
$data = $result->get_result()->fetch_assoc();

if (!$data) {
    die("Task not found! ID: " . $task_id);
}

//  Delete the task
$stmt = $conn->prepare("DELETE FROM task WHERE task_id = ?");
$stmt->bind_param("i", $task_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo "<script>alert('Task deleted successfully!'); window.location.href='dashboard.php';</script>";
} else {
    echo "<script>alert('Error deleting task.'); window.location.href='dashboard.php';</script>";
}
?>
