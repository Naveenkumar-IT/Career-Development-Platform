<?php
session_start();
include 'db_connect.php'; // Database connection


if (isset($_GET['id'])) {
    $score_id = $_GET['id'];

    $query = "DELETE FROM scores WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $score_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Score deleted successfully!'); window.location.href='manage_scores.php';</script>";
    } else {
        echo "<script>alert('Error deleting score!'); window.location.href='manage_scores.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
