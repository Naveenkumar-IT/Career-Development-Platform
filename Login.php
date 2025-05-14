<?php
session_start();
include("db_connect.php"); // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT user_id, user_name, user_password, user_role FROM users WHERE user_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row['user_password']; // Get hashed password from DB

        // Debugging: Print entered and stored passwords
        echo "Entered Email: " . htmlspecialchars($email) . "<br>";
        echo "Entered Password: " . htmlspecialchars($password) . "<br>";
        echo "Stored Hashed Password: " . htmlspecialchars($stored_hashed_password) . "<br>";

        // Verify hashed password
        if (password_verify($password, $stored_hashed_password)) {
            // Store user_id instead of user_name
            $_SESSION['user'] = $row['user_id']; 
            $_SESSION['user_name'] = $row['user_name']; // Optional: Store username separately
            $_SESSION['role'] = $row['user_role']; // Store user role

            header("Location: Dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            echo "<script>alert('Invalid email or password!'); window.location.href='Login.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='Login.html';</script>";
    }
}
?>
