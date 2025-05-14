<?php
include 'db_connect.php'; // Include database connection
if (!isset($_GET['assessment_id'])) {
    die("Assessment ID not provided.");
}

$assessment_id = $_GET['assessment_id'];

// Fetch assessment title
$assessment_query = $conn->query("SELECT assessment_title FROM assessments WHERE assessment_id = $assessment_id");
$assessment = $assessment_query->fetch_assoc();
$assessment_title = $assessment['assessment_title'];

// Fetch all questions for this assessment
$questions_query = $conn->query("SELECT * FROM questions WHERE assessment_id = $assessment_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions - <?php echo $assessment_title; ?></title>
    <link rel="stylesheet" href="manage_questions.css"> <!-- Link your CSS file -->
</head>
<body>

<div class="container">
    <h2>Manage Questions - <?php echo $assessment_title; ?></h2>
    <a href="add_question.php?assessment_id=<?php echo $assessment_id; ?>" class="btn btn-primary">+ Add New Question</a>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Option A</th>
                <th>Option B</th>
                <th>Option C</th>
                <th>Option D</th>
                <th>Correct Answer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            while ($question = $questions_query->fetch_assoc()) {
                echo "<tr>
                    <td>{$count}</td>
                    <td>{$question['question_text']}</td>
                    <td>{$question['option_a']}</td>
                    <td>{$question['option_b']}</td>
                    <td>{$question['option_c']}</td>
                    <td>{$question['option_d']}</td>
                    <td><strong>{$question['correct_answer']}</strong></td>
                    <td>
                        <a href='edit_question.php?question_id={$question['question_id']}' class='btn btn-edit'>Edit</a>
                        <a href='delete_question.php?question_id={$question['question_id']}&assessment_id={$assessment_id}' class='btn btn-delete' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                    </td>
                </tr>";
                $count++;
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
