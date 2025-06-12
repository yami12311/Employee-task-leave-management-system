<?php
session_start();
include('db_config.php'); // Ensure correct DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }

    // ✅ Check if the user exists in `user_profile`
    $sql = "SELECT employee_id, username, password, role FROM user_profile WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // 🔹 Check if passwords are **hashed** or **plain text**
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // ✅ Update last login time
            $updateLoginTime = $conn->prepare("UPDATE user_profile SET last_login = NOW() WHERE employee_id = ?");
            $updateLoginTime->bind_param("i", $user['employee_id']);
            $updateLoginTime->execute();

            // ✅ Redirect based on role
            if ($user['role'] == 'Admin') {
                header('Location: dashboard.php');
            } else {
                header('Location: employee_dashboard.php');
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        // ✅ If not found in `user_profile`, check `employees`
        $sql = "SELECT employee_id, username, password FROM employees WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $employee = $result->fetch_assoc();

            // 🔹 Check if passwords are stored **hashed** or **plain text**
            if (password_verify($password, $employee['password']) || $password === $employee['password']) {
                $_SESSION['employee_id'] = $employee['employee_id'];
                $_SESSION['username'] = $employee['username'];
                $_SESSION['role'] = 'Employee'; // Default role

                // ✅ Insert into `user_profile` if not exists
                $insertProfile = $conn->prepare("INSERT INTO user_profile (employee_id, username, password, role, last_login) 
                                                 VALUES (?, ?, ?, 'Employee', NOW())");
                $insertProfile->bind_param("iss", $employee['employee_id'], $employee['username'], $employee['password']);
                $insertProfile->execute();

                header('Location: employee_dashboard.php');
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            // ✅ If username not found in employees, check `admins`
            $sql = "SELECT id, username, password FROM admins WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $admin = $result->fetch_assoc();

                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['role'] = 'Admin';

                    header('Location: dashboard.php');
                    exit();
                } else {
                    echo "Invalid password.";
                }
            } else {
                echo "No user found with that username.";
            }
        }
    }
}
?>
