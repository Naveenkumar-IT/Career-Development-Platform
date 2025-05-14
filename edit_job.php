<?php
session_start();
include("db_connect.php");

// Check if job_id is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Job ID!'); window.location.href='manage_jobs.php';</script>";
    exit();
}

$job_id = $_GET['id'];

// Fetch job details
$sql = "SELECT * FROM jobs WHERE job_id = '$job_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $job = $result->fetch_assoc();
} else {
    echo "<script>alert('Job not found!'); window.location.href='manage_jobs.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = $_POST['job_title'];
    $company_name = $_POST['company_name'];
    $job_location = $_POST['job_location'];
    $job_description = $_POST['job_description'];
    $salary_range = $_POST['salary_range'];

    // Update job in the database
    $update_sql = "UPDATE jobs SET job_title='$job_title', company_name='$company_name', job_location='$job_location', job_description='$job_description', salary_range='$salary_range' WHERE job_id='$job_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Job updated successfully!'); window.location.href='manage_jobs.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link rel="stylesheet" href="edit_job.css">
</head>
<body>
    <div class="admin-container">
        <h2>Edit Job</h2>
        <form action="edit_job.php?id=<?php echo $job_id; ?>" method="POST">
            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" value="<?php echo $job['job_title']; ?>" required>

            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" value="<?php echo $job['company_name']; ?>" required>

            <label for="job_location">Location:</label>
            <input type="text" name="job_location" value="<?php echo $job['job_location']; ?>" required>

            <label for="salary_range">Salary Range:</label>
            <input type="text" name="salary_range" value="<?php echo $job['salary_range']; ?>" required>

            <label for="job_description">Job Description:</label>
            <textarea name="job_description" required><?php echo $job['job_description']; ?></textarea>

            <button type="submit">Update Job</button>
        </form>
        <br>
        <a href="manage_jobs.php">Back to Job Listings</a>
    </div>
</body>
</html>
