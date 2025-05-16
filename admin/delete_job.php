<?php
session_start();
include("db_connect.php");

// Check if job_id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Job ID!'); window.location.href='manage_jobs.php';</script>";
    exit();
}

$job_id = $_GET['id'];

// Delete job from the database
$sql = "DELETE FROM jobs WHERE job_id = '$job_id'";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Job deleted successfully!'); window.location.href='manage_jobs.php';</script>";
} else {
    echo "<script>alert('Error: " . $conn->error . "'); window.location.href='manage_jobs.php';</script>";
}
?>
