<?php
session_start();
include("db_connect.php");

// Ensure the 'uploads/resumes/' folder exists
$upload_dir = "C:/xampp/htdocs/Project/uploads/resumes/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Get user ID (ensure it's set)
$user_id = $_SESSION['user'] ?? null;
$job_id = $_POST['job_id'] ?? null;  

if (!$user_id) {
    die("<script>alert('Error: User ID missing!'); history.back();</script>");
}

if ($_FILES['resume']['error'] == 0) {
    $file_name = basename($_FILES['resume']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Allowed file types
    $allowed_types = ['pdf', 'docx'];
    if (!in_array($file_ext, $allowed_types)) {
        die("<script>alert('Invalid file type! Only PDF and DOCX allowed.'); history.back();</script>");
    }

    // Rename the file (avoid duplicates)
    $new_file_name = "resume_" . time() . "_" . $user_id . "." . $file_ext;
    $file_path = $upload_dir . $new_file_name;

    if ($_FILES['resume']['error'] != 0) {
        die("<script>alert('File Upload Error: " . $_FILES['resume']['error'] . "'); history.back();</script>");
    }    

    // Move file to server
    if (move_uploaded_file($_FILES['resume']['tmp_name'], $file_path)) {
        // Save file path in database
        $sql = "INSERT INTO job_applications (user_id, job_id, resume_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $job_id, $file_path);

        if ($stmt->execute()) {
            echo "<script>alert('Resume uploaded successfully!'); window.location.href='user_profile.php';</script>";
        } else {
            echo "<script>alert('Database error: " . $stmt->error . "'); history.back();</script>";
        }
    } else {
        echo "<script>alert('File upload failed! Please try again.'); history.back();</script>";
    }
} else {
    echo "<script>alert('No file uploaded.'); history.back();</script>";
}

$conn->close();
?>
