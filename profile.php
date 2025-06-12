<?php
include 'db_config.php';
session_start();

// ✅ Ensure Only Admin Can Access
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access Denied! Please log in as Admin.'); window.location.href='login.php';</script>";
    exit();
}

// ✅ Fetch only employees who exist in user_profile (No more 'N/A' entries)
$sql = "SELECT up.profile_id, up.employee_id, e.username, e.password, up.role, up.last_login
        FROM user_profile up
        JOIN employees e ON up.employee_id = e.employee_id
        ORDER BY up.profile_id DESC"; // Sort properly

$result = $conn->query($sql);
$profiles = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Profiles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ✅ Admin Navbar -->
<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid d-flex justify-content-between">
        <h2 class="text-white m-0">Admin Panel</h2>
        <a href="dashboard.php" class="btn btn-light">← Back to Dashboard</a>
    </div>
</nav>

<!-- ✅ User Profile Section -->
<div class="container mt-4">
    <h2>Registered Users</h2>
    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Profile ID</th>
                <th>Employee ID</th>
                <th>Username</th>
                <th>Password (Hashed)</th>
                <th>Role</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profiles as $profile) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($profile['profile_id']); ?></td>
                    <td><?php echo htmlspecialchars($profile['employee_id']); ?></td>
                    <td><?php echo htmlspecialchars($profile['username']); ?></td>
                    <td><?php echo htmlspecialchars($profile['password']); ?></td> <!-- Hashed Password -->
                    <td><?php echo htmlspecialchars($profile['role']); ?></td>
                    <td><?php echo htmlspecialchars($profile['last_login'] ?: 'Never Logged In'); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
