<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("Employee ID not provided.");
}

$employee_id = $_GET['id'];

// Fetch employee details
$query = "SELECT * FROM employees WHERE employee_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    die("Employee not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $join_date = $_POST['join_date'];
    $contact_number = $_POST['contact_number'];

    // Validate salary is not negative
    if ($salary < 0) {
        echo "<script>alert('Salary cannot be negative. Please enter a valid amount.');</script>";
    } else {
        $updateQuery = "UPDATE employees SET username=?, email=?, department=?, position=?, salary=?, join_date=?, contact_number=? WHERE employee_id=?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssssssi", $username, $email, $department, $position, $salary, $join_date, $contact_number, $employee_id);

        if ($updateStmt->execute()) {
            echo "<script>alert('Employee updated successfully!'); window.location.href='manage.php';</script>";
        } else {
            echo "<script>alert('Error updating employee.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Employee</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .back-button {
            display: block;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Edit Employee</h2>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-user icon"></i> Username:</label>
            <input
              type="text"
              name="username"
              class="form-control"
              value="<?php echo htmlspecialchars($employee['username']); ?>"
              required
            />
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-envelope icon"></i> Email:</label>
            <input
              type="email"
              name="email"
              class="form-control"
              value="<?php echo htmlspecialchars($employee['email']); ?>"
              required
            />
        </div>

        <!-- Department dropdown -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-building icon"></i> Department:</label>
            <select name="department" class="form-select" required>
                <?php
                $departments = ['HR', 'Finance', 'Sales', 'Marketing', 'IT', 'Operations'];

                foreach ($departments as $dept) {
                    $selected = ($employee['department'] === $dept) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($dept) . "\" $selected>" . htmlspecialchars($dept) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-briefcase icon"></i> Position:</label>
            <input
              type="text"
              name="position"
              class="form-control"
              value="<?php echo htmlspecialchars($employee['position']); ?>"
              required
            />
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-dollar-sign icon"></i> Salary:</label>
            <input
              type="number"
              name="salary"
              class="form-control"
              min="0"
              step="0.01"
              value="<?php echo htmlspecialchars($employee['salary']); ?>"
              required
            />
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-calendar icon"></i> Join Date:</label>
            <input
              type="date"
              name="join_date"
              class="form-control"
              value="<?php echo htmlspecialchars($employee['join_date']); ?>"
              required
            />
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-phone icon"></i> Contact Number:</label>
            <input
              type="text"
              name="contact_number"
              class="form-control"
              value="<?php echo htmlspecialchars($employee['contact_number']); ?>"
              required
            />
        </div>

        <button type="submit" class="btn btn-success w-100">
          <i class="fas fa-save"></i> Update Employee
        </button>
    </form>

    <!-- Back Button to Manage Employees -->
    <a href="manage.php" class="btn btn-secondary w-100 mt-3">
      <i class="fas fa-arrow-left"></i> Back to Manage Employees
    </a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
