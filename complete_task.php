<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['task_id'])) {
    $taskId = intval($_GET['task_id']);

    // First, get employee_id for redirect after update
    $query = "SELECT employee_id FROM task WHERE task_id = $taskId LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $employeeId = $row['employee_id'];

        // Update the task status to completed and set completion date
        $today = date('Y-m-d');
        $updateQuery = "UPDATE task SET status = 'completed', completion_date = '$today' WHERE task_id = $taskId";
        if ($conn->query($updateQuery) === TRUE) {
            // Redirect back to task report for that employee
            header("Location: task_report.php?employee_id=$employeeId");
            exit;
        } else {
            echo "Error updating task: " . $conn->error;
        }
    } else {
        echo "Task not found.";
    }
} else {
    echo "Invalid request.";
}
