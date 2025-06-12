<?php
include 'db_config.php';
session_start();

// Handle Approve/Reject Actions
if (isset($_GET['id']) && isset($_GET['status'])) {
    $leave_id = $_GET['id'];
    $new_status = $_GET['status']; // "Approved" or "Rejected"

    // Update the leave request status
    $stmt = $conn->prepare("UPDATE leave_request SET status = ? WHERE leave_request_id = ?");
    $stmt->bind_param("si", $new_status, $leave_id);

    if ($stmt->execute()) {
        // Fetch Employee ID
        $stmt = $conn->prepare("SELECT employee_id FROM leave_request WHERE leave_request_id = ?");
        $stmt->bind_param("i", $leave_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $employee_id = $row['employee_id'];

        // Insert Notification into Database
        $message = "Your leave request #$leave_id has been $new_status.";
        $stmt = $conn->prepare("INSERT INTO notifications (employee_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $employee_id, $message);
        $stmt->execute();

        // Redirect with success message
        echo "<script>alert('Leave request $new_status successfully!'); window.location.href='manage_leave.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating leave request. Try again.');</script>";
    }
}

// Fetch leave requests with employee names and proof_path
$leaveRequests = $conn->query("
    SELECT lr.*, e.username 
    FROM leave_request lr 
    JOIN employees e ON lr.employee_id = e.employee_id 
    ORDER BY lr.leave_request_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Leave Requests</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            color: #fff !important;
        }
        .container {
            margin-top: 80px;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
            transition: 0.3s;
        }
        .btn-back {
            background-color: #343a40;
            color: white;
        }
        .btn-back:hover {
            background-color: #495057;
        }
        img.proof-img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
            object-fit: contain;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        img.proof-img:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Leave Management</a>
        <a href="dashboard.php" class="btn btn-back"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4 text-center">Manage Leave Requests</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Request ID</th>
                    <th>Employee Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Leave Type</th>
                    <th>Reason</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $leaveRequests->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['leave_request_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['end_date']; ?></td>
                        <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td>
                            <?php 
                            if (!empty($row['proof_path'])) {
                                $proof_url = $row['proof_path']; 
                                $ext = strtolower(pathinfo($proof_url, PATHINFO_EXTENSION));
                                
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    // Clickable image opening full image in new tab
                                    echo '<a href="' . htmlspecialchars($proof_url) . '" target="_blank" title="View Full Image">';
                                    echo '<img src="' . htmlspecialchars($proof_url) . '" alt="Proof Image" class="proof-img" />';
                                    echo '</a>';
                                } else {
                                    // Link for other file types
                                    echo '<a href="' . htmlspecialchars($proof_url) . '" target="_blank">View Document</a>';
                                }
                            } else {
                                echo 'No proof attached';
                            }
                            ?>
                        </td>
                        <td><strong><?php echo $row['status']; ?></strong></td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href='manage_leave.php?id=<?php echo $row['leave_request_id']; ?>&status=Approved' class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Approve
                                    </a>
                                    <a href='manage_leave.php?id=<?php echo $row['leave_request_id']; ?>&status=Rejected' class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </a>
                                </div>
                            <?php } else { 
                                echo $row['status']; 
                            } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
