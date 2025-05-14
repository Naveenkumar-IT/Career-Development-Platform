<?php
session_start();
include("db_connect.php"); // Database connection

// Fetch all employers
$sql = "SELECT * FROM employers ORDER BY created_at DESC";
$result = $conn->query($sql);

// Handle Approve, Reject, and Suspend actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $employer_id = $_GET['id'];
    
    if ($action == 'approve') {
        $update_sql = "UPDATE employers SET status='Approved' WHERE employer_id='$employer_id'";
    } elseif ($action == 'reject') {
        $update_sql = "UPDATE employers SET status='Rejected' WHERE employer_id='$employer_id'";
    } elseif ($action == 'suspend') {
        $update_sql = "UPDATE employers SET status='Suspended' WHERE employer_id='$employer_id'";
    } elseif ($action == 'delete') {
        $update_sql = "DELETE FROM employers WHERE employer_id='$employer_id'";
    }
    
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Action performed successfully!'); window.location.href='manage_employers.php';</script>";
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
    <title>Manage Employers</title>
    <link rel="stylesheet" href="manage_employers.css"> <!-- Admin panel CSS -->
</head>
<body>
    <div class="admin-container">
        <h2>Manage Employers</h2>
        <table>
            <thead>
                <tr>
                    <th>Employer ID</th>
                    <th>Company Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['employer_id']; ?></td>
                        <td><?php echo $row['company_name']; ?></td>
                        <td><?php echo $row['contact_person']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                 <a href="manage_employers.php?action=approve&id=<?php echo $row['employer_id']; ?>" class="action-btn approve">Approve</a>
                                 <a href="manage_employers.php?action=reject&id=<?php echo $row['employer_id']; ?>" class="action-btn reject">Reject</a>
                            <?php } elseif ($row['status'] == 'Approved') { ?>
                                 <a href="manage_employers.php?action=suspend&id=<?php echo $row['employer_id']; ?>" class="action-btn reject">Suspend</a>
                            <?php } ?>
                            <a href="manage_employers.php?action=delete&id=<?php echo $row['employer_id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this employer?');">Delete</a>
                         </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
           <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>
