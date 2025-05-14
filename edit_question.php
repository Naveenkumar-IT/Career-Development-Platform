<?php
include 'db_connect.php'; // Include database connection

if (!isset($_GET['question_id'])) {
    die("Question ID not provided.");
}

$question_id = $_GET['question_id'];

// Fetch question details
$sql = "SELECT * FROM questions WHERE question_id = '$question_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Question not found.");
}

$row = $result->fetch_assoc();
$assessment_id = $row['assessment_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];

    // Update question in the database
    $sql = "UPDATE questions SET 
            question_text = '$question_text', 
            option_a = '$option_a', 
            option_b = '$option_b', 
            option_c = '$option_c', 
            option_d = '$option_d', 
            correct_answer = '$correct_answer' 
            WHERE question_id = '$question_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Question updated successfully!'); window.location.href='manage_questions.php?assessment_id=$assessment_id';</script>";
    } else {
        echo "Error updating question: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Edit Question</h2>
    <form action="" method="post">
        <label>Question:</label>
        <input type="text" name="question_text" value="<?php echo $row['question_text']; ?>" required>

        <label>Option A:</label>
        <input type="text" name="option_a" value="<?php echo $row['option_a']; ?>" required>

        <label>Option B:</label>
        <input type="text" name="option_b" value="<?php echo $row['option_b']; ?>" required>

        <label>Option C:</label>
        <input type="text" name="option_c" value="<?php echo $row['option_c']; ?>" required>

        <label>Option D:</label>
        <input type="text" name="option_d" value="<?php echo $row['option_d']; ?>" required>

        <label>Correct Answer:</label>
        <select name="correct_answer" required>
            <option value="A" <?php if ($row['correct_answer'] == 'A') echo 'selected'; ?>>Option A</option>
            <option value="B" <?php if ($row['correct_answer'] == 'B') echo 'selected'; ?>>Option B</option>
            <option value="C" <?php if ($row['correct_answer'] == 'C') echo 'selected'; ?>>Option C</option>
            <option value="D" <?php if ($row['correct_answer'] == 'D') echo 'selected'; ?>>Option D</option>
        </select>

        <button type="submit" class="btn btn-primary">Update Question</button>
    </form>

    <a href="manage_questions.php?assessment_id=<?php echo $assessment_id; ?>" class="btn btn-secondary">Back</a>
</div>

</body>
</html>
