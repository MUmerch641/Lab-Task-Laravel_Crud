<?php

include "db.php";

// Validate input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'All fields are required.';
        header("Location: index.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header("Location: index.php");
        exit;
    }

    // Use prepared statement to prevent SQL injection
    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'User added successfully!';
    } else {
        $_SESSION['error'] = 'Error adding user: ' . $conn->error;
    }
    $stmt->close();
}

header("Location: index.php");
exit;

?>
