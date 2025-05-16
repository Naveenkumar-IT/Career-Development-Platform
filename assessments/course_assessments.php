<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}

// Check if a course ID is provided
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    die("Invalid course selection.");
}

$course_id = $_GET['course_id'];

// Fetch course details
$course_query = "SELECT * FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($course_query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course_result = $stmt->get_result();
$course = $course_result->fetch_assoc();

// Fetch assessments for this course
$assessment_query = "SELECT * FROM assessments WHERE course_id = ?";
$stmt = $conn->prepare($assessment_query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$assessments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessments for <?php echo $course['course_name']; ?></title>
    <link rel="stylesheet" href="assessments.css">
</head>
<body>
    <div class="container">
        <h2>Assessments for <?php echo htmlspecialchars($course['title']); ?></h2>
        
        <?php if ($assessments->num_rows > 0): ?>
            <ul>
                <?php while ($row = $assessments->fetch_assoc()): ?>
                    <li>
                        <?php echo htmlspecialchars($row['assessment_title']); ?> - 
                        <a href="attempt_assessment.php?assessment_id=<?php echo $row['assessment_id']; ?>">Start Assessment</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No assessments available for this course.</p>
        <?php endif; ?>
    </div>
</body>
</html>
