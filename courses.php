<?php
include("db_connect.php");
session_start();

if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}

$sql = "SELECT * FROM courses ORDER BY created_at DESC";
$result = $conn->query($sql);
$courses = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="courses.css">
</head>
<body>
    <h1>📚 Available Courses</h1>
    <div class="course-container">
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <img src="uploads/course_thumbnails/<?= htmlspecialchars($course['thumbnail']); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
                <h3><?= htmlspecialchars($course['title']); ?></h3>
                <p><?= htmlspecialchars($course['description']); ?></p>
                <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor']); ?></p>
                <p><strong>Price:</strong> $<?= number_format($course['price'], 2); ?></p>
                <a href="course_details.php?id=<?= $course['course_id']; ?>" class="btn">View Course</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
