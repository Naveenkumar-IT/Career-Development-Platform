<?php
include("db_connect.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $assessment_title = $_POST['assessment_title'];
    $description = $_POST['description'];
    $total_marks = $_POST['total_marks'];
    $total_questions = $_POST['total_questions'];

    $sql = "INSERT INTO assessments (course_id, assessment_title, description, total_marks, total_questions) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issii", $course_id, $assessment_title, $description, $total_marks, $total_questions);

    if ($stmt->execute()) {
        $message = "✅ Assessment added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }

    $stmt->close();
}

// Fetch courses for the dropdown
$course_query = "SELECT course_id, title FROM courses";
$course_result = $conn->query($course_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assessment</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h2>Add New Assessment</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?= $message; ?></p>
        <?php endif; ?>

        <form action="add_assessment.php" method="POST">
            <label for="course_id">Select Course:</label>
            <select name="course_id" required>
                <option value="">-- Select Course --</option>
                <?php while ($course = $course_result->fetch_assoc()): ?>
                    <option value="<?= $course['course_id']; ?>"><?= htmlspecialchars($course['title']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="assessment_title">Assessment Title:</label>
            <input type="text" name="assessment_title" required>

            <label for="description">Description:</label>
            <textarea name="description" required></textarea>

            <label for="total_marks">Total Marks:</label>
            <input type="number" name="total_marks" required>

            <label for="total_questions">Total Questions:</label>
            <input type="number" name="total_questions" required>

            <button type="submit">Add Assessment</button>
        </form>
    </div>
</body>
</html>
