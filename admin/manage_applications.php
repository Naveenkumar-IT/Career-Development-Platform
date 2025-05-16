<?php
session_start();
include("db_connect.php"); // Database connection

// Fetch all job applications
$sql = "SELECT ja.application_id, ja.status, ja.applied_at, 
               u.user_name, j.job_title 
        FROM job_applications ja
        JOIN users u ON ja.user_id = u.user_id
        JOIN jobs j ON ja.job_id = j.job_id
        ORDER BY ja.applied_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Job Applications</title>
    <link rel="stylesheet" href="manage_applications.css"> <!-- Admin panel CSS -->
</head>
<body>
    <div class="admin-container">
        <h2>Manage Job Applications</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Job Title</th>
                    <th>Applicant Name</th>
                    <th>Status</th>
                    <th>Resume</th>
                    <th>Applied On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['application_id']; ?></td>
                        <td><?php echo $row['job_title']; ?></td>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                        <?php if (!empty($row['resume_path'])) { ?>
                            <a href="<?php echo $row['resume_path']; ?>" target="_blank">View Resume</a>
                        <?php } else { ?>
                            No Resume Uploaded
                        <?php } ?>
                        </td>
                        <td><?php echo $row['applied_at']; ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                <a href="update_applications.php?id=<?php echo $row['application_id']; ?>&status=Approved">Approve</a> | 
                                <a href="update_applications.php?id=<?php echo $row['application_id']; ?>&status=Rejected">Reject</a>
                            <?php } else { ?>
                                <?php echo $row['status']; ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
