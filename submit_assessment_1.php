<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $assessment_id = $_POST['assessment_id'];
    $answers = $_POST['answers'];
    $user_id = $_SESSION['user'];

    $score = 0;
    $total_questions = count($answers);

     // Debug: Check if answers are received
     echo "<pre>";
     print_r($_POST);
     echo "</pre>";
     // Debug: Check if user_id exists

    echo "User ID: " . $user_id . "<br>";

    // Validate user existence
    $check_user = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $check_user->bind_param("i", $user_id);
    $check_user->execute();
    $user_result = $check_user->get_result();

    if ($user_result->num_rows == 0) {
        die("Error: User ID does not exist in users table.");
    }
    // Fetch correct answers
    $query = "SELECT assessment_id, correct_answer FROM questions WHERE assessment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $assessment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_questions = $result->num_rows; // ✅ Get the number of questions

    while ($question = $result->fetch_assoc()) {
        if (isset($answers[$question['question_id']]) && $answers[$question['question_id']] == $question['correct_answer']) {
            $score++;
        }        
    }

    // Debug: Print calculated score
    echo "Total Questions: $total_questions <br>";
    echo "Correct Answers: $score <br>";

    if ($total_questions > 0) {
        $final_score = ($score / $total_questions) * 100;
    } else {
        $final_score = 0; // Prevent division by zero
    }

    // Store result in database
    $insert_query = "INSERT INTO scores (user_id, assessment_id, score) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iid", $user_id, $assessment_id, $final_score);
    
    if ($stmt->execute()) {
        header("Location: assessment_results.php?score=" . round($final_score, 2));
        exit;
    } else {
        echo "Error saving score: " . $stmt->error;
    }
} else {
    die("Invalid request.");
}
?>
