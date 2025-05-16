<?php
session_start();
include 'db_connect.php'; // Database connection

if (!isset($_SESSION['user'])) {
    die("Access Denied!");
}

$user_id = $_SESSION['user'];
$assessment_id = $_GET['assessment_id'] ?? 0;

// Fetch user score for the specific assessment
$query = "SELECT score, total_marks, attempt_date FROM scores WHERE user_id = ? AND assessment_id = ? ORDER BY attempt_date DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $assessment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $score = $row['score'];
    $total_marks = $row['total_marks'];
    $attempt_date = $row['attempt_date'];

    // Calculate percentage
    $percentage = ($score / $total_marks) * 100;
} else {
    die("No results found for this assessment!");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Results</title>
    <link rel="stylesheet" href="assessments.css">
</head>
<body>
    <div class="container">
        <h2>📊 Assessment Results</h2>
        <p><strong>Score:</strong> <?= $score; ?> / <?= $total_marks; ?></p>
        <p><strong>Percentage:</strong> <?= number_format($percentage, 2); ?>%</p>
        <p><strong>Date Attempted:</strong> <?= $attempt_date; ?></p>

        <a href="dash.php" class="btn">Go to Dashboard</a>
        <a href="course_assessments.php" class="btn btn-alt">Try Another Assessment</a>
    </div>
</body>
</html>
