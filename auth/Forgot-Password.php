<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['user_email'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "career_platform");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate token
        $token = bin2hex(random_bytes(50));

        // Store token in database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE user_email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send reset email
        $resetLink = "http://yourwebsite.com/reset-password.html?token=" . $token;
        mail($email, "Password Reset", "Click here to reset your password: $resetLink", "From: no-reply@careerplatform.com");

        echo "Password reset link has been sent to your email.";
    } else {
        echo "Email not found!";
    }

    $conn->close();
}
?>
