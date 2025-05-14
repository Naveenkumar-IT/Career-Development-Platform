<?php
session_start();
include("db_connect.php");

// Fetch all job listings from the database
$sql = "SELECT * FROM jobs ORDER BY job_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="manage_jobs.css">
</head>
<body>
    <div class="admin-container">
        <h2>Manage Job Listings</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Salary Range</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['job_id']; ?></td>
                        <td><?php echo $row['job_title']; ?></td>
                        <td><?php echo $row['company_name']; ?></td>
                        <td><?php echo $row['job_location']; ?></td>
                        <td><?php echo $row['salary_range']; ?></td>
                        <td>
                            <a href="edit_job.php?id=<?php echo $row['job_id']; ?>">Edit</a> | 
                            <a href="delete_job.php?id=<?php echo $row['job_id']; ?>" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <a href="add_job.php" class="add-job-btn">➕ Add New Job</a>
        </table>
        <br>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
