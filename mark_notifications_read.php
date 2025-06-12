<?php
session_start();
include('db_config.php');

if (isset($_SESSION['employee_id'])) {
    $employee_id = $_SESSION['employee_id'];
    $update_sql = "UPDATE notifications SET status = 'read' WHERE employee_id = ? AND status = 'unread'";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $employee_id);
    $update_stmt->execute();
}
?>
