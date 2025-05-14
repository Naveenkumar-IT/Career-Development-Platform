<?php
session_start();
include 'db_connect.php'; // Database connection

$query = "SELECT scores.score_id, users.user_name AS user_name, assessments.assessment_title AS assessment_title, 
                 scores.score, scores.total_marks, scores.attempt_date 
          FROM scores 
          JOIN users ON scores.user_id = users.user_id 
          JOIN assessments ON scores.assessment_id = assessments.assessment_id 
          ORDER BY scores.attempt_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Scores</title>
    <link rel="stylesheet" href="manage_scores.css">
</head>
<body>
    <div class="container">
        <h2>Manage Scores</h2>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Assessment</th>
                    <th>Score</th>
                    <th>Total Marks</th>
                    <th>Percentage</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['assessment_title']; ?></td>
                    <td><?php echo $row['score']; ?></td>
                    <td><?php echo $row['total_marks']; ?></td>
                    <td><?php echo round(($row['score'] / $row['total_marks']) * 100, 2) . "%"; ?></td>
                    <td><?php echo $row['attempt_date']; ?></td>
                    <td>
                        <a href="delete_score.php?id=<?php echo $row['id']; ?>" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
