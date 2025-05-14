<?php
include("db_connect.php");

// Fetch all assessments with course details
$sql = "SELECT assessments.assessment_id, assessments.assessment_title, courses.title AS course_name,
        (SELECT COUNT(*) FROM questions WHERE questions.assessment_id = assessment_id) AS total_questions
        FROM assessments 
        INNER JOIN courses ON assessments.course_id = courses.course_id
        ORDER BY assessments.created_at DESC";

$result = $conn->query($sql);
$assessments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assessments</title>
    <link rel="stylesheet" href="manage_assessments.css">
</head>
<body>
    <div class="container">
        <h1>📊 Manage Assessments</h1>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Assessment Title</th>
                    <th>Total Questions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assessments as $assessment): ?>
                <tr>
                    <td><?= htmlspecialchars($assessment['course_name']); ?></td>
                    <td><?= htmlspecialchars($assessment['assessment_title']); ?></td>
                    <td><?= htmlspecialchars($assessment['total_questions']); ?></td>
                    <td class="action-buttons">
                        <a href="edit_assessment.php?assessment_id=<?= $assessment['assessment_id']; ?>" class="btn-edit">Edit</a>
                        <a href="delete_assessment.php?assessment_id=<?= $assessment['assessment_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                        <a href="manage_questions.php?assessment_id=<?= $assessment['assessment_id']; ?>" class="btn-manage">Manage Questions</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <a href="add_assessment.php" class="add-assessment-btn">➕ Add New Assessment</a>
            <a href="manage_scores.php" class="manage-scores-btn">➕ Manage Scores</a>
        </table>
    </div>
</body>
</html>
