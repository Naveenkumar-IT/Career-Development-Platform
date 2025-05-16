<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Dashboard.css">
</head>
<body>
<div class="dashboard-container">
    <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
    <p>You are now logged in.</p>

    <div class="spacer"></div>

    <a href="user_profile.php">Profile</a> |
    <a href="Index.php">Home</a>

    <div class="spacer"></div>

    <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>
</body>
</html>
