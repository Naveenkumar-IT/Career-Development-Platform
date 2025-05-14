<?php
include("db_connect.php"); // Database connection

if (isset($_GET['id'])) {
    $application_id = $_GET['id'];
    
    // Fetch the application details
    $sql = "SELECT * FROM job_applications WHERE application_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    
    // Update application status
    $update_sql = "UPDATE job_applications SET status = ? WHERE application_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $application_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Application status updated successfully!'); window.location.href='manage_applications.php';</script>";
    } else {
        echo "<script>alert('Error updating status!');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Application</title>
    <link rel="stylesheet" href="update_applications.css">
</head>
<body>
    <div class="container">
        <h2>Update Job Application Status</h2>
        <form action="update_applications.php" method="POST">
            <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
            <label for="status">Change Status:</label>
            <select name="status" required>
                <option value="Pending" <?php if ($application['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Accepted" <?php if ($application['status'] == 'Accepted') echo 'selected'; ?>>Accepted</option>
                <option value="Rejected" <?php if ($application['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
            </select>
            <button type="submit">Update</button>
        </form>
        <a href="manage_applications.php">Back to Applications</a>
    </div>
</body>
</html>
