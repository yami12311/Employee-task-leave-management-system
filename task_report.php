<?php 
session_start();
include('db_config.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$employeeTasks = [];
if (isset($_GET['employee_id'])) {
    $employeeId = intval($_GET['employee_id']);

    // Added t.task_id so we can use it for "Complete" link
    $query = "SELECT e.employee_id AS employee_id, e.username AS employee_name, 
                     t.task_id, t.task_name, t.assigned_date, t.due_date, t.status, t.completion_date
              FROM employees e
              LEFT JOIN task t ON e.employee_id = t.employee_id
              WHERE e.employee_id = $employeeId
              ORDER BY t.due_date";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $employeeTasks['name'] = $row['employee_name'];
            $employeeTasks['id'] = $row['employee_id'];
            $employeeTasks['tasks'][] = $row;
        }
    }
} else {
    $employeeListQuery = "SELECT employee_id, username FROM employees";
    $employeeListResult = $conn->query($employeeListQuery);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Report & Efficiency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            margin-top: 50px;
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            border-radius: 25px;
            padding: 6px 18px;
        }
        .btn-dashboard {
            background-color: #0d6efd;
            color: white;
            border-radius: 25px;
            padding: 6px 18px;
            text-decoration: none;
            float: right;
            margin-top: -40px;
        }
        h2 {
            color: #0d6efd;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="btn-dashboard btn">← Dashboard</a>

    <?php if (isset($_GET['employee_id']) && !empty($employeeTasks)): ?>
        <h2 class="text-center mb-4">Task Report & Efficiency</h2>
        <div class="mb-3">
            <a href="task_report.php" class="btn btn-back">&larr; Back to Employee List</a>
        </div>

        <?php
            $tasks = $employeeTasks['tasks'];
            $totalTasks = 0;
            $onTime = 0;
            $delayed = 0;

            foreach ($tasks as $task) {
                if (strtolower($task['status']) === 'completed') {
                    $totalTasks++;
                    $dueDate = strtotime($task['due_date']);
                    $completionDate = isset($task['completion_date']) ? strtotime($task['completion_date']) : null;

                    if ($completionDate && $completionDate > $dueDate) {
                        $delayed++;
                    } else {
                        $onTime++;
                    }
                }
            }

            $efficiency = $totalTasks > 0 ? round(($onTime / $totalTasks) * 100, 2) : 0;
        ?>

        <h5 class="mt-3 mb-4">Employee: <strong><?= htmlspecialchars($employeeTasks['name']) ?></strong></h5>

        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Task Name</th>
                    <th>Assigned Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Completion Date</th>
                    <th>Delay Status</th>
                    <th>Action</th> <!-- Added Action column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <?php
                        $delayStatus = "<span class='badge bg-secondary'>N/A</span>";
                        if (strtolower($task['status']) === 'completed') {
                            $dueDate = strtotime($task['due_date']);
                            $completionDate = isset($task['completion_date']) ? strtotime($task['completion_date']) : null;
                            if ($completionDate && $completionDate > $dueDate) {
                                $delayStatus = "<span class='badge bg-danger'>Delayed</span>";
                            } else {
                                $delayStatus = "<span class='badge bg-success'>On Time</span>";
                            }
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($task['task_name']) ?></td>
                        <td><?= $task['assigned_date'] ?></td>
                        <td><?= $task['due_date'] ?></td>
                        <td><?= ucfirst($task['status']) ?></td>
                        <td><?= $task['completion_date'] ?? '-' ?></td>
                        <td><?= $delayStatus ?></td>
                        <td>
                            <?php if (strtolower($task['status']) !== 'completed' && !empty($task['task_id'])): ?>
                                <a href="complete_task.php?task_id=<?= $task['task_id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Mark this task as completed?')">Complete</a>
                            <?php else: ?>
                                <span class="text-muted">Done</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="table table-bordered text-center mt-4">
            <thead class="table-success">
                <tr>
                    <th>Total Completed Tasks</th>
                    <th>Completed On Time</th>
                    <th>Delayed</th>
                    <th>Efficiency (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $totalTasks ?></td>
                    <td><?= $onTime ?></td>
                    <td><?= $delayed ?></td>
                    <td><?= $efficiency ?>%</td>
                </tr>
            </tbody>
        </table>

    <?php elseif (isset($_GET['employee_id'])): ?>
        <div class="alert alert-warning text-center">
            No task data found for this employee.
        </div>
        <div class="text-center">
            <a href="task_report.php" class="btn btn-secondary">Back to Employee List</a>
        </div>

    <?php else: ?>
        <h2 class="text-center mb-4">Employee List</h2>
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Employee ID</th>
                    <th>Username</th>
                    <th>Report</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $employeeListResult->fetch_assoc()): ?>
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
    <?php endif; ?>
</div>

</body>
</html>
