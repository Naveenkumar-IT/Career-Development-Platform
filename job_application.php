<?php
include("db_connect.php");
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Please log in to apply for jobs.'); window.location.href='Login.html';</script>";
    exit();
}

// Get job details
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
    <title>Apply for Job</title>
    <link rel="stylesheet" href="job_application.css">
</head>
<body>
    <div class="admin-container">
        <h2>Apply for <?php echo $job['job_title']; ?> at <?php echo $job['company_name']; ?></h2>
        
        <form action="upload_resume.php" method="POST" enctype="multipart/form-data">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" value="<?php echo $_SESSION['user_name']; ?>" required readonly>

            <label for="resume">Upload Resume (PDF/DOCX only):</label>
            <input type="file" name="resume" accept=".pdf,.docx" required>

            <input type="hidden" name="user" value="<?php echo $_SESSION['user']; ?>">
            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

            <button type="submit">Submit Application</button>
        </form>
        <br>
        <a href="jobs.php">Back to Job Listings</a>
    </div>
</body>
</html>
