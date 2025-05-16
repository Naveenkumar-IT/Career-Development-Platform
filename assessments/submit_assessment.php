<?php
include 'db_connect.php'; // Database connection
session_start();

$user_id = $_SESSION['user'];
$assessment_id = $_POST['assessment_id'];
$total_marks = 0;
$score = 0;

// Fetch correct answers from the database
$query = "SELECT question_id, correct_answer FROM questions WHERE assessment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $question_id = $row['question_id'];
    $correct_answer = $row['correct_answer'];
    $total_marks++;

    // Check if user submitted an answer
    if (isset($_POST["question_$question_id"])) {
        $user_answer = $_POST["question_$question_id"];
        if ($user_answer == $correct_answer) {
            $score++; // Increment score for correct answer
        }
    }
}

// Insert the score into the scores table
$insert_query = "INSERT INTO scores (user_id, assessment_id, score, total_marks, attempt_date) 
                 VALUES (?, ?, ?, ?, NOW())";
$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param("iiii", $user_id, $assessment_id, $score, $total_marks);

if ($insert_stmt->execute()) {
    header("Location: assessment_results.php?assessment_id=$assessment_id");
} else {
    echo "Error recording score!";
}

$stmt->close();
$insert_stmt->close();
$conn->close();
?>
