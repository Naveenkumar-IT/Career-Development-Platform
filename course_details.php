<?php
include("db_connect.php");
session_start();

if (!isset($_GET['id'])) {
    die("Invalid course ID.");
}

if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}

$course_id = $_GET['id'];
$sql = "SELECT * FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();
$stmt->close();

if (!$course) {
    die("Course not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="course_details.css">
</head>
<body>
    <div class="course-details">
        <img src="uploads/course_thumbnails/<?= htmlspecialchars($course['thumbnail']); ?>" alt="<?= htmlspecialchars($course['title']); ?>">
        <h1><?= htmlspecialchars($course['title']); ?></h1>
        <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor']); ?></p>
        <p><?= htmlspecialchars($course['description']); ?></p>
        <p><strong>Price:</strong> $<?= number_format($course['price'], 2); ?></p>
        <a href="course_assessments.php?course_id=<?php echo $course['course_id']; ?>" class="btn">View Assessments</a>
        <button class="enroll-btn">Enroll Now</button>
    </div>

    <script>
document.querySelector(".enroll-btn").addEventListener("click", function() {
    let courseId = <?= $course['course_id']; ?>;
    fetch("enroll.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "course_id=" + courseId
    }).then(response => response.text()).then(data => {
        if (data === "success") {
            alert("Enrollment successful!");
        } else {
            alert("Error enrolling.");
        }
    });
});
</script>

</body>
</html>
