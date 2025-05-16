<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['user_id'])) {
    die("Error: Please log in first.");
}

$user_id = $_SESSION['user_id'];
$course_id = $_POST['course_id'];

$sql = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $course_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
?>
