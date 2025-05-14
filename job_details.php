<?php
include("db_connect.php");
session_start();

// Ensure job_id is provided
if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    echo "<script>alert('Invalid Job ID!'); window.location.href='jobs.php';</script>";
    exit();
}

$job_id = $_GET['job_id'];
$sql = "SELECT * FROM jobs WHERE job_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "<script>alert('Job not found!'); window.location.href='jobs.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $job['job_title']; ?> - Job Details</title>
    <link rel="stylesheet" href="job_details.css">
</head>
<body>
    <div class="job-container">
        <h2><?php echo $job['job_title']; ?></h2>
        <p><strong>Company:</strong> <?php echo $job['company_name']; ?></p>
        <p><strong>Location:</strong> <?php echo $job['job_location']; ?></p>
        <p><strong>Salary:</strong> <?php echo $job['salary_range']; ?></p>
        <p><strong>Description:</strong> <?php echo nl2br($job['job_description']); ?></p>

        <a href="job_application.php?job_id=<?php echo $job_id; ?>" class="apply-btn">Apply Now</a>
        <a href="jobs.php" class="back-btn">Back to Job Listings</a>
    </div>
</body>
</html>
