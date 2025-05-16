<?php
session_start();
include("db_connect.php");

// Check if user ID is provided
if (!isset($_GET['id'])) {
    echo "Invalid user ID.";
    exit();
}

$user_id = $_GET['id'];

// Delete user from database
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "<script>alert('User deleted successfully!'); window.location.href='manage_users.php';</script>";
} else {
    echo "Error deleting user: " . $conn->error;
}

$conn->close();
?>
