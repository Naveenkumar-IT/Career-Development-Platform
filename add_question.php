<?php
include 'db_connect.php'; // Include database connection

if (!isset($_GET['assessment_id'])) {
    die("Assessment ID not provided.");
}

$assessment_id = $_GET['assessment_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];

    // Insert question into database
    $sql = "INSERT INTO questions (assessment_id, question_text, option_a, option_b, option_c, option_d, correct_answer) 
            VALUES ('$assessment_id', '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_answer')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Question added successfully!'); window.location.href='manage_questions.php?assessment_id=$assessment_id';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link rel="stylesheet" href="add_question.css"> <!-- Link your CSS file -->
</head>
<body>

<div class="container">
    <h2>Add New Question</h2>
    <form action="" method="post">
        <label>Question:</label>
        <input type="text" name="question_text" required>

        <label>Option A:</label>
        <input type="text" name="option_a" required>

        <label>Option B:</label>
        <input type="text" name="option_b" required>

        <label>Option C:</label>
        <input type="text" name="option_c" required>

        <label>Option D:</label>
        <input type="text" name="option_d" required>

        <label>Correct Answer:</label>
        <select name="correct_answer" required>
            <option value="A">Option A</option>
            <option value="B">Option B</option>
            <option value="C">Option C</option>
            <option value="D">Option D</option>
        </select>

        <button type="submit" class="btn btn-primary">Add Question</button>
    </form>

    <a href="manage_questions.php?assessment_id=<?php echo $assessment_id; ?>" class="btn btn-secondary">Back</a>
</div>

</body>
</html>
