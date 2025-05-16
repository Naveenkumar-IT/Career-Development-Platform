<?php
session_start();
include 'db_connect.php'; //Include DB connection 

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch total users
$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $conn->query($user_query);
$total_users = $user_result->fetch_assoc()['total_users'];

// Fetch total jobs
$job_query = "SELECT COUNT(*) AS total_jobs FROM jobs";
$job_result = $conn->query($job_query);
$total_jobs = $job_result->fetch_assoc()['total_jobs'];

// Fetch total applications
$app_query = "SELECT COUNT(*) AS total_apps FROM job_applications";
$app_result = $conn->query($app_query);
$total_apps = $app_result->fetch_assoc()['total_apps'];

$admin_email = $_SESSION['admin_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Admin_dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <button class="toggle-btn"><i class="fas fa-bars"></i></button>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="index.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a></li>
            <li><a href="manage_assessments.php"><i class="fas fa-pen"></i> Manage Assessments</a></li>
            <li><a href="manage_employers.php"><i class="fas fa-user-tie"></i> Manage Employers</a></li>
            <li><a href="manage_applications.php"><i class="fas fa-file-alt"></i> Manage Applications</a></li>
            <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <header>
            <h1>Welcome, Admin</h1>
            <p>Manage everything from here.</p>
        </header>
        
        <div class="cards">
            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="card">
                <h3>Total Jobs</h3>
                <p><?php echo $total_jobs; ?></p>
            </div>
            <div class="card">
                <h3>Applications Received</h3>
                <p><?php echo $total_apps; ?></p>
            </div>
        </div>
    </div>

    <script>
        gsap.from(".sidebar", { duration: 1, x: -200, opacity: 0 });
        gsap.from(".main-content", { duration: 1, opacity: 0, delay: 0.5 });
        gsap.from(".card", { duration: 1, y: 50, opacity: 0, stagger: 0.3 });
        document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.querySelector(".toggle-btn");
        const sidebar = document.querySelector(".sidebar");
        const mainContent = document.querySelector(".main-content");

        toggleBtn.addEventListener("click", function () {
            sidebar.classList.toggle("collapsed");
            mainContent.classList.toggle("expanded");
        });
    });
    </script>

<script>
    $(document).ready(function () {
        $(".nav-links a").click(function (e) {
            e.preventDefault(); // Prevent full page reload

            var pageUrl = $(this).attr("href"); // Get target page URL
            $("#main-content").load(pageUrl); // Load content dynamically

            // Remove active class from all links and add to the clicked one
            $(".nav-links a").removeClass("active");
            $(this).addClass("active");
        });
    });
</script>

</body>
</html>

