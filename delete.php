<?php

include "db.php";

// Get and validate ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    $_SESSION['error'] = 'Invalid user ID.';
    header("Location: index.php");
    exit;
}

// Use prepared statement to prevent SQL injection
$query = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'User deleted successfully!';
} else {
    $_SESSION['error'] = 'Error deleting user: ' . $conn->error;
}
$stmt->close();

header("Location: index.php");
exit;

?>
