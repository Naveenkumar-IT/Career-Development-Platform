<?php
session_start();
include("db_connect.php");

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

// Get user details
$user_id = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="settings.css">
</head>
<body>
    <div class="sidebar">
        <button class="toggle-btn"><i class="fas fa-bars"></i></button>
        <h2>Settings</h2>
        <ul class="nav-links">
            <li><a href="Index.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="Dash.php"><i class="fas fa-dashboard"></i> Dashboard</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Settings</h1>

        <!-- Profile Settings -->
        <section class="settings-section">
            <h2>Profile Settings</h2>
            <form id="profileSettingsForm" enctype="multipart/form-data">
            <label>Full Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
                
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>

                <label>Phone:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['user_phone'] ?? ''); ?>">

                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['user_address'] ?? ''); ?>">

                <label>Profile Picture:</label>
                <input type="file" name="profile_picture" accept="image/*">

                <button type="submit" name="update_profile">Save Changes</button>
            </form>
            <p id="profileMessage"></p> <!-- For success/error message -->
        </section>

        <!-- Account Settings -->
        <section class="settings-section">
            <h2>Account Settings</h2>
            <form id="accountSettingsForm">
                <label>New Password:</label>
                <input type="password" name="new_password">
                
                <label>Enable Two-Factor Authentication:</label>
                <input type="checkbox" name="2fa_enabled">

                <button type="submit" name="update_account">Update Account</button>
            </form>
            <p id="accountMessage"></p> <!-- Message display -->
        </section>

        <!-- Notification Preferences -->
        <section class="settings-section">
            <h2>Notification Preferences</h2>
            <form id="notificationSettingsForm">
            <label>Job Alerts:</label>
                <input type="checkbox" name="job_alerts">

                <label>Application Status Updates:</label>
                <input type="checkbox" name="app_updates">

                <label>Promotional Emails:</label>
                <input type="checkbox" name="promo_emails">

                <button type="submit" name="update_notifications">Update Notifications</button>
            </form>
            <p id="notificationMessage"></p> <!-- Message display -->
        </section>

        <!-- Privacy & Security -->
        <section class="settings-section">
            <h2>Privacy & Security</h2>
            <form id="privacySettingsForm">
            <label>Manage Data Visibility:</label>
                <select name="data_visibility">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>

                <label>Delete Account:</label>
                <button type="submit" name="delete_account" class="delete-btn">Delete My Account</button>
            </form>
            <p id="privacyMessage"></p> <!-- Message display -->
        </section>

        <!-- Connected Accounts -->
        <section class="settings-section">
            <h2>Connected Accounts</h2>
            <form id="connectedAccountsForm">
            <label>LinkedIn:</label>
                <input type="text" name="linkedin" placeholder="Enter LinkedIn Profile URL">

                <label>GitHub:</label>
                <input type="text" name="github" placeholder="Enter GitHub Profile URL">

                <button type="submit" name="update_connections">Update Connections</button>
            </form>
            <p id="connectedMessage"></p> <!-- Message display -->
        </section>
    </div>

    <script>
        document.querySelector(".toggle-btn").addEventListener("click", function () {
            document.querySelector(".sidebar").classList.toggle("collapsed");
            document.querySelector(".main-content").classList.toggle("expanded");
        });
    </script>

<script>
document.getElementById("profileSettingsForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Stop normal form submission

    let formData = new FormData(this); // Get form data
    formData.append("update_profile", "1"); // Add an identifier for PHP

    fetch("update_settings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("profileMessage").innerText = data.message;
        document.getElementById("profileMessage").style.color = data.status === "success" ? "green" : "red";
    })
    .catch(error => console.error("Error:", error));
});
</script>

<script>
// 🔹 Account Settings Update (Password & 2FA)
document.getElementById("accountSettingsForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);
    formData.append("update_account", "1");

    fetch("update_settings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("accountMessage").innerText = data.message;
        document.getElementById("accountMessage").style.color = data.status === "success" ? "green" : "red";
    });
});

// 🔹 Notification Preferences Update
document.getElementById("notificationSettingsForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);
    formData.append("update_notifications", "1");

    fetch("update_settings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("notificationMessage").innerText = data.message;
        document.getElementById("notificationMessage").style.color = data.status === "success" ? "green" : "red";
    });
});

// 🔹 Privacy & Security Update
document.getElementById("privacySettingsForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);
    formData.append("update_privacy", "1");

    fetch("update_settings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("privacyMessage").innerText = data.message;
        document.getElementById("privacyMessage").style.color = data.status === "success" ? "green" : "red";
    });
});

// 🔹 Connected Accounts Update
document.getElementById("connectedAccountsForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);
    formData.append("update_connections", "1");

    fetch("update_settings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("connectedMessage").innerText = data.message;
        document.getElementById("connectedMessage").style.color = data.status === "success" ? "green" : "red";
    });
});
</script>

</body>
</html>
