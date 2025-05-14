<?php
include 'db_connect.php'; // Include database connection

if (!isset($_GET['question_id'])) {
    die("Question ID not provided.");
}

$question_id = $_GET['question_id'];

// Get assessment_id before deleting the question
$assessment_query = "SELECT assessment_id FROM questions WHERE question_id = '$question_id'";
$assessment_result = $conn->query($assessment_query);
if ($assessment_result->num_rows == 0) {
    die("Question not found.");
}
$assessment_row = $assessment_result->fetch_assoc();
$assessment_id = $assessment_row['assessment_id'];

// Delete the question
$sql = "DELETE FROM questions WHERE question_id = '$question_id'";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Question deleted successfully!'); window.location.href='manage_questions.php?assessment_id=$assessment_id';</script>";
} else {
    echo "Error deleting question: " . $conn->error;
}

$conn->close();
?>
