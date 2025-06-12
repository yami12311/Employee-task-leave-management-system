<?php

include('db_config.php'); 

// Admin details (replace these with actual data or inputs from a form)
$username = "admin"; 
$password = "admin123"; 
$contact_number = "1234567890"; 
$email = "admin@example.com"; 

// Check if the username is already taken
$sql_check = "SELECT id FROM admins WHERE username = ? OR email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $username, $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "Username or email already taken, please choose a different one.";
    exit;
}

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL query to insert the new admin
$sql = "INSERT INTO admins (username, password, contact_number, email) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("ssss", $username, $hashed_password, $contact_number, $email); 
$stmt->execute(); 

// Check if the admin was added successfully
if ($stmt->affected_rows > 0) {
    echo "Admin created successfully.";
} else {
    echo "Error creating admin.";
}
?>
