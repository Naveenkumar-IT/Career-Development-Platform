<?php
session_start();
include("db_connect.php"); // Include database connection

header("Content-Type: application/json"); // Ensure response is JSON

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    die(json_encode(["status" => "error", "message" => "User not logged in."]));
}

// Get user ID
$user_id = $_SESSION['user'];

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Handle Profile Picture Upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/profile_pictures/";
    
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
    
        $file_name = basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $file_name;
    
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE users SET profile_picture=? WHERE user_id=?");
            $stmt->bind_param("si", $file_name, $user_id);
            $stmt->execute();
        }
    }       

        $sql = "UPDATE users SET user_name = ?, user_email = ?, 
        user_phone = IFNULL(?, user_phone), 
        user_address = IFNULL(?, user_address),
        profile_picture = IFNULL(?, profile_picture) 
        WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $phone, $address, $target_file, $user_id);
    } else {
        $sql = "UPDATE users SET user_name = ?, user_email = ?, user_phone = ?, user_address = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Profile updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update profile."]);
    }

    $stmt->close();

// Handle Account Settings Update (Password & 2FA)
if (isset($_POST['update_account'])) {
    $new_password = $_POST['new_password'];
    $two_factor = isset($_POST['2fa_enabled']) ? 1 : 0;

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET user_password = ?, two_factor_auth = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $hashed_password, $two_factor, $user_id);
    } else {
        $sql = "UPDATE users SET two_factor_auth = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $two_factor, $user_id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Account updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update account."]);
    }

    $stmt->close();
}

// Handle Notification Preferences
if (isset($_POST['update_notifications'])) {
    $job_alerts = isset($_POST['job_alerts']) ? 1 : 0;
    $app_updates = isset($_POST['app_updates']) ? 1 : 0;
    $promo_emails = isset($_POST['promo_emails']) ? 1 : 0;

    $sql = "UPDATE users SET job_alerts = ?, app_updates = ?, promo_emails = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $job_alerts, $app_updates, $promo_emails, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Notification preferences updated!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update notifications."]);
    }

    $stmt->close();
}

// Handle Privacy & Security Settings
if (isset($_POST['data_visibility'])) {
    $data_visibility = $_POST['data_visibility'];
    $sql = "UPDATE users SET data_visibility = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $data_visibility, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Privacy settings updated!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update privacy settings."]);
    }

    $stmt->close();
}

// Handle Account Deletion
if (isset($_POST['delete_account'])) {
    $user_id = $_SESSION['user'];

    // Step 1: Delete all jobs posted by the user
    $sql = "DELETE FROM jobs WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Step 2: Delete the user account
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Destroy session and redirect
    session_destroy();
    echo json_encode(["status" => "success", "message" => "Account deleted successfully!"]);
    exit();
}

// Handle Connected Accounts
if (isset($_POST['update_connections'])) {
    $linkedin = $_POST['linkedin'];
    $github = $_POST['github'];

    $sql = "UPDATE users SET linkedin = ?, github = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $linkedin, $github, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Connected accounts updated!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update connected accounts."]);
    }

    $stmt->close();
}

// Close database connection
$conn->close();
?>
