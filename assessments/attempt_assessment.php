<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}

// Check if assessment ID is provided
if (!isset($_GET['assessment_id']) || empty($_GET['assessment_id'])) {
    die("Invalid assessment.");
}

$assessment_id = $_GET['assessment_id'];

// Fetch assessment details
$assessment_query = "SELECT * FROM assessments WHERE assessment_id = ?";
$stmt = $conn->prepare($assessment_query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$assessment_result = $stmt->get_result();
$assessment = $assessment_result->fetch_assoc();

// Fetch questions for this assessment
$question_query = "SELECT * FROM questions WHERE assessment_id = ?";
$stmt = $conn->prepare($question_query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$questions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Assessment</title>
    <link rel="stylesheet" href="assessments.css">
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($assessment['assessment_title']); ?></h2>

        <form action="submit_assessment.php" method="POST">
            <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">

            <?php while ($question = $questions->fetch_assoc()): ?>
                <div class="question-block">
                    <p><strong><?php echo htmlspecialchars($question['question_text']); ?></strong></p>
                    <?php
                    $options = ['option_a', 'option_b', 'option_c', 'option_d'];
                    foreach ($options as $opt): ?>
                        <label>
                            <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="<?php echo $opt; ?>" required>
                            <?php echo htmlspecialchars($question[$opt]); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            <?php endwhile; ?>

            <button type="submit">Submit Assessment</button>
        </form>
    </div>
</body>
</html>
