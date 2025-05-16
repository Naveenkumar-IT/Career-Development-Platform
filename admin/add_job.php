<?php
session_start();
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = $_POST['job_title'];
    $company_name = $_POST['company_name'];
    $job_location = $_POST['job_location'];
    $job_description = $_POST['job_description'];
    $user_id = $_POST['user_id'];
    $salary_range = $_POST['salary_range'];

    // Insert job into the database
    $sql = "INSERT INTO jobs (job_title, company_name, job_location, job_description, user_id, salary_range) 
            VALUES ('$job_title', '$company_name', '$job_location', '$job_description', '$user_id', '$salary_range')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Job added successfully!'); window.location.href='manage_jobs.php';</script>";
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
    <title>Add Job</title>
    <link rel="stylesheet" href="add_job.css">
</head>
<body>
    <div class="admin-container">
        <h2>Add New Job</h2>
        <form action="add_job.php" method="POST">
            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" required>

            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" required>

            <label for="job_location">Location:</label>
            <input type="text" name="job_location" required>

            <label for="user_id">User Id:</label>
            <input type="text" name="user_id" required>

            <label for="salary_range">Salary Range:</label>
            <input type="text" name="salary_range" required>

            <label for="job_description">Job Description:</label>
            <textarea name="job_description" required></textarea>

            <button type="submit">Add Job</button>
        </form>
        <br>
        <a href="manage_jobs.php">Back to Job Listings</a>
    </div>
</body>
</html>
