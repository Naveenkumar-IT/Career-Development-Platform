<?php
session_start();
include("db_connect.php"); // Database connection

// Redirect to login page if session user is not set
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

// Check if user_id is set in the session
$session_user_id = $_SESSION['user'] ?? null;

// Debugging - Check what is stored in session
var_dump($session_user_id);

if (!$session_user_id) {
    die("Error: User not logged in."); // Debugging message (Remove later)
}

// Fetch user details using a prepared statement
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$profile_picture_path = "";
$default_profile_picture = "default.jpg";

$profile_picture = (!empty($user['profile_picture']) && file_exists(__DIR__ . "/$profile_picture_path" . $user['profile_picture']))
    ? $profile_picture_path . $user['profile_picture']
    : $profile_picture_path . $default_profile_picture;

// Handle case when user is not found
if (!$user) {
    die("Error: User not found in database. Session User ID: " . $session_user_id);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="user_profile.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="sidebar">
        <button class="toggle-btn"><i class="fas fa-bars"></i></button>
        <h2>Profile</h2>
        <ul class="nav-links">
            <li><a href="Index.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="Dash.php"><i class="fas fa-dashboard"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-file-alt"></i> My Applications </a></li>
            <li><a href="#"><i class="fas fa-briefcase"></i> Saved Jobs</a></li>
            <li><a href="Settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
    <div class="hero-section">
    <header>
            <h1>Welcome, <?php echo htmlspecialchars($user['user_name']); ?>!</h1>
            <p>Your professional career journey starts here.</p>
            </header>

       <div class="profile-container">
            <div class="profile-card">
            <img src="<?php echo 'http://localhost/Project/' . htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic">
            <h2><?php echo htmlspecialchars($user['user_name']); ?></h2>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['user_email']); ?></p>
            <p><i class="fas fa-user-tag"></i> Role: <?php echo htmlspecialchars($user['user_role']); ?></p>
        </div>

        <div class="profile-details">
            <h3>Profile Details</h3>
            <table>
                <tr><th>Full Name:</th><td><?php echo htmlspecialchars($user['user_name']); ?></td></tr>
                <tr><th>Email:</th><td><?php echo htmlspecialchars($user['user_email']); ?></td></tr>
                <tr><th>Phone:</th><td><?php echo htmlspecialchars($user['user_phone'] ?? 'N/A'); ?></td></tr>
                <tr><th>Address:</th><td><?php echo htmlspecialchars($user['user_address'] ?? 'N/A'); ?></td></tr>
                <tr><th>Joined:</th><td><?php echo htmlspecialchars($user['created_at'] ?? 'N/A'); ?></td></tr>
            </table>
        </div>
    </div>

    <div class="additional-info">
            <h3>Additional Information</h3>
            <div class="info-section">
                <div class="info-card">
                    <h4>Skills</h4>
                    <p>Web Development, Python, Database Management</p>
                </div>
                <div class="info-card">
                    <h4>Experience</h4>
                    <p>2+ Years of Freelance Web Development</p>
                </div>
                <div class="info-card">
                    <h4>Resume</h4>
                    <?php if (!empty($resume['resume_path'])): ?>
                        <p><a href="<?php echo htmlspecialchars($resume['resume_path']); ?>" target="_blank">View Resume</a></p>
                    <?php endif; ?>
                    <form action="upload_resume.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?php echo $session_user_id; ?>">
                        <input type="hidden" name="job_id" value="">  <!-- Default value -->
                        <input type="file" name="resume" accept=".pdf,.docx" required>
                        <button type="submit">Upload Resume</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    document.querySelector(".toggle-btn").addEventListener("click", function () {
        document.querySelector(".sidebar").classList.toggle("collapsed");
        document.querySelector(".main-content").classList.toggle("expanded");
    });
</script>
</body>
</html>
