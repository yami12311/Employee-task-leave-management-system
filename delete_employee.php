<?php
include 'db_config.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access Denied! Please log in as Admin.'); window.location.href='login.php';</script>";
    exit();
}

// Check if employee ID is provided
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']);

    // Delete user profile first (if exists)
    $delete_profile_query = "DELETE FROM user_profile WHERE employee_id = ?";
    $stmt = $conn->prepare($delete_profile_query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();

    // Now delete employee
    $delete_employee_query = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($delete_employee_query);
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        echo "<script>alert('Employee deleted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting employee.'); window.location.href='dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='dashboarrd.php';</script>";
}
?>
