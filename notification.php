<?php
include 'db_config.php';
session_start();

// Check if employee is logged in
if (!isset($_SESSION['employee_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch notifications for this employee
$stmt = $conn->prepare("SELECT message, created_at FROM notifications WHERE employee_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Your Notifications</h2>
    <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li class="list-group-item">
                <strong><?php echo $row['created_at']; ?>:</strong> <?php echo htmlspecialchars($row['message']); ?>
            </li>
        <?php } ?>
    </ul>
    <a href=" employee_dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
