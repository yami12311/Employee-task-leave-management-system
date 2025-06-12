<?php
session_start();
include('db_config.php');

// Check if user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo "<li class='list-group-item text-danger'>You must be logged in to view notifications.</li>";
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch unread notifications
$sql = "SELECT * FROM notifications WHERE employee_id = ? AND status = 'unread' ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item'>
                <i class='bi bi-info-circle'></i> " . htmlspecialchars($row['message']) . 
                " <small class='text-muted'>" . $row['created_at'] . "</small>
              </li>";
    }

    // ✅ Mark notifications as read after fetching
    $update_sql = "UPDATE notifications SET status = 'read' WHERE employee_id = ? AND status = 'unread'";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $employee_id);
    $update_stmt->execute();
} else {
    echo "<li class='list-group-item text-muted'>No new notifications.</li>";
}
?>
