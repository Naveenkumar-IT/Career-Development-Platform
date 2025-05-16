<?php
session_start();
include("db_connect.php");

// Check if user ID is provided
if (!isset($_GET['id'])) {
    echo "Invalid user ID.";
    exit();
}

$user_id = $_GET['id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update_sql = "UPDATE users SET user_name = ?, user_email = ?, user_role = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $fullname, $email, $role, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit_users.css">
</head>
<body>
    <div class="admin-container">
        <h2>Edit User</h2>
        <form method="POST">
            <label>Full Name:</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
            
            <label>Role:</label>
            <select name="role">
                <option value="graduate" <?php echo ($user['user_role'] == 'graduate') ? 'selected' : ''; ?>>Graduate</option>
                <option value="employer" <?php echo ($user['user_role'] == 'employer') ? 'selected' : ''; ?>>Employer</option>
                <option value="student" <?php echo ($user['user_role'] == 'student') ? 'selected' : ''; ?>>Student</option>
            </select>

            <button type="submit">Update User</button>
        </form>
        <br>
        <a href="manage_users.php">Back to User Management</a>
    </div>
</body>
</html>
