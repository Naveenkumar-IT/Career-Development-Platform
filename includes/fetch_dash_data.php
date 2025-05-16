<?php
session_start();
include "db_connect.php"; // Your database connection file

$user_id = $_SESSION['user']; // Assuming user is logged in

$response = [];

// Fetch Enrolled Courses
$course_query = "SELECT COUNT(*) AS course_count FROM enrollments WHERE user_id = ?";
$stmt = $conn->prepare($course_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$response['enrolled_courses'] = $row['course_count'];

// Fetch Progress
$progress_query = "SELECT AVG(progress) AS avg_progress FROM enrollments WHERE user_id = ?";
$stmt = $conn->prepare($progress_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$response['avg_progress'] = round($row['avg_progress'], 1);

// Fetch Achievements (certificates earned)
$cert_query = "SELECT COUNT(*) AS cert_count FROM certificates WHERE user_id = ?";
$stmt = $conn->prepare($cert_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$response['certificates_earned'] = $row['cert_count'];

// Return JSON response
echo json_encode($response);
?>
