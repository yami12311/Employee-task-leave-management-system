<?php
session_start();
include('db_config.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die("Please fill all fields.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    //  Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    //  Check for duplicate username or email
    $check_query = "SELECT * FROM employees WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        die("Username or Email already exists. Choose a different one.");
    }

    //  Insert new employee
    $insert_query = "INSERT INTO employees (username, email, password) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($insert_stmt->execute()) {
        $employee_id = $insert_stmt->insert_id; // Get the last inserted employee_id

        // Auto-add to user_profile
        $role = "Employee"; // Default role
        $add_profile_query = "INSERT INTO user_profile (employee_id, username, password, role, last_login) 
                              VALUES (?, ?, ?, ?, NULL)";
        $profile_stmt = $conn->prepare($add_profile_query);
        $profile_stmt->bind_param("isss", $employee_id, $username, $hashed_password, $role);
        $profile_stmt->execute();

        echo "Registration successful. You can now <a href='login.html'>login</a>.";
    } else {
        die("Error: " . $insert_stmt->error);
    }
}
?>
