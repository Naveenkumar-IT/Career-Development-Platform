<?php
include 'db_connect.php'; // Include your database connection file

// Ensure 'assessment_id' is passed correctly in the URL
if (!isset($_GET['assessment_id']) || empty($_GET['assessment_id'])) {
    die("Invalid request.");
}

$assessment_id = $_GET['assessment_id'];

// Fetch assessment details
$query = "SELECT * FROM assessments WHERE assessment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Assessment not found.");
}

$assessment = $result->fetch_assoc();

// Fetch all courses for the dropdown
$courses_query = "SELECT course_id, title FROM courses";
$courses_result = $conn->query($courses_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = $_POST['title'];
    $new_course_id = $_POST['course_id'];

    // Update assessment details
    $update_query = "UPDATE assessments SET assessment_title = ?, course_id = ? WHERE assessment_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sii", $new_title, $new_course_id, $assessment_id);

    if ($update_stmt->execute()) {
        header("Location: manage_assessments.php?success=Assessment updated successfully");
        exit();
    } else {
        echo "Error updating assessment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assessment</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Assessment</h2>
        <form method="POST">
            <label for="title">Assessment Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($assessment['assessment_title']); ?>" required>

            <label for="course">Course:</label>
            <select name="course_id" id="course">
                <?php while ($course = $courses_result->fetch_assoc()) { ?>
                    <option value="<?php echo $course['course_id']; ?>" 
                        <?php if ($course['course_id'] == $assessment['course_id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($course['title']); ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit" class="btn btn-primary">Update Assessment</button>
            <a href="manage_assessments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
