<?php 
session_start();
include 'db_config.php';
// Ensure the employee is logged in


//  Handle Search Query
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "";
$searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : "";

if (!empty($searchQuery)) {
    $query = "SELECT * FROM employees WHERE employee_id LIKE ? OR username LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $employees = $stmt->get_result();
} else {
    $employees = $conn->query("SELECT * FROM employees ORDER BY employee_id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn {
            margin: 2px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center">Manage Employees</h2>

    <!-- 🔍 Search Form -->
    <form method="GET" action="manage.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Employee ID or Name..." value="<?php echo $searchQuery; ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if (!empty($searchQuery)) { ?>
                <a href="manage.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Back
                </a>
            <?php } ?>
        </div>
    </form>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>Employee ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Department</th>
                <th>Position</th>
                <th>Salary</th>
                <th>Join Date</th>
                <th>Contact Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $employees->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['employee_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo $row['salary']; ?></td>
                    <td><?php echo $row['join_date']; ?></td>
                    <td><?php echo $row['contact_number']; ?></td>
                    <td>
                        <a href='edit.php?id=<?php echo $row['employee_id']; ?>' class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href='delete_employee.php?id=<?php echo $row['employee_id']; ?>' onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- 🔙 Back Button (Returns to Dashboard) -->
    <div class="text-center mt-3">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
