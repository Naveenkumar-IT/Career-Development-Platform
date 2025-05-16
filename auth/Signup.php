<?php
include("db_connect.php"); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate password confirmation
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='Signup.html';</script>";
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Use prepared statement to check if email exists
    $check_query = "SELECT user_email FROM users WHERE user_email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Try logging in.'); window.location.href='Login.html';</script>";
    } else {
        // Insert new user into the database using prepared statement
        $sql = "INSERT INTO users (user_name, user_email, user_password, user_role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! You can now log in.'); window.location.href='Login.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='Signup.html';</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
