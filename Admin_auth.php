<?php
session_start();
include("db_connect.php"); // Ensure database connection is established

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['admin_email'];
    $password = $_POST['admin_password'];

    // Fetch admin details from the database
    $sql = "SELECT * FROM admins WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row['admin_password'];

        if (password_verify($password, $stored_hashed_password)) {
            $_SESSION['admin_email'] = $email; // Store email in session
            header("Location: admin_dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            echo "<script>alert('Invalid email or password!'); window.location.href='admin_login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='admin_login.php';</script>";
    }
}
?>
