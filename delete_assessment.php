<?php
include 'db_connect.php'; // Include your database connection file

// Check if 'id' is provided in the URL
if (!isset($_GET['assessment_id']) || empty($_GET['assessment_id'])) {
    die("Invalid request.");
}

$assessment_id = $_GET['assessment_id'];

// Check if the assessment exists before deleting
$check_query = "SELECT * FROM assessments WHERE assessment_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Assessment not found.");
}

// Delete the assessment
$delete_query = "DELETE FROM assessments WHERE assessment_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $assessment_id);

if ($delete_stmt->execute()) {
    header("Location: manage_assessments.php?success=Assessment deleted successfully");
    exit();
} else {
    echo "Error deleting assessment.";
}
?>
