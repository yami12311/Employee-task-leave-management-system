<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$query = "SELECT employee_id, username FROM employees";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fff3e0);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .table thead {
            background-color: #4dabf7;
            color: white;
        }

        .table-hover tbody tr:hover {
            background-color: #f1faff;
        }

        h2 {
            font-weight: bold;
            color: #0077b6;
        }
    </style>
</head>
<body>
    <div class="container mt-5">

        <!-- Back Button -->
        <div class="mb-4">
            <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
        </div>

        <h2 class="text-center mb-4">Employee List</h2>

        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Username</th>
                    <th>Report</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['employee_id'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td>
                            <a href="task_report.php?employee_id=<?= $row['employee_id'] ?>" class="btn btn-info btn-sm">
                                View Report
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
