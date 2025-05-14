<?php
session_start();
include("db_connect.php"); // Include database connection

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage_users.css"> <!-- Admin panel styling -->
</head>
<body>
    <div class="admin-container">
        <h2>Manage Users</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['user_name']}</td>
                        <td>{$row['user_email']}</td>
                        <td>{$row['user_role']}</td>
                        <td>
                            <a href='edit_users.php?id={$row['user_id']}'>Edit</a> | 
                            <a href='delete_user.php?id={$row['user_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found.</td></tr>";
            }
            ?>
        </table>
        <br>
        <a href="Admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
