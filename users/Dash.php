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

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Fetch user scores
$scores_query = "
    SELECT scores.score, scores.attempt_date, assessments.assessment_title, courses.title 
    FROM scores
    JOIN assessments ON scores.assessment_id = assessments.assessment_id
    JOIN courses ON assessments.course_id = courses.course_id
    WHERE scores.user_id = ?
    ORDER BY scores.attempt_date DESC
";

$scores_stmt = $conn->prepare($scores_query);
$scores_stmt->bind_param("i", $user_id);
$scores_stmt->execute();
$scores = $scores_stmt->get_result();
$scores_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Career Platform</title>
    <link rel="stylesheet" href="Dash.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- For charts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h2>Career Platform</h2>
        <ul>
            <li><a href="Index.html"><i class="fas fa-home"></i> <span>Home</span></a></li>
            <li><a href="#"><i class="fas fa-book"></i> <span>My Courses</span></a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> <span>Progress</span></a></li>
            <li><a href="user_profile.php"><i class="fas fa-user"></i> <span>Profile</span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($user['user_name']); ?> 👋</h1>
            <p>Your learning journey starts here!</p>
            <div class="notification-container">
                <button id="notification-btn">🔔</button>
                <div id="notificationBox" class="notification-box"></div>
                <div class="notification-dropdown" id="notification-dropdown">
                    <h3>Notifications</h3>
                    <ul id="notification-list">
                        <li>Loading...</li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dashboard Overview -->
        <section class="dashboard-overview">
           <div class="card">
               <h3>📚 Enrolled Courses</h3>
               <p id="course-count">Loading...</p>
           </div>
           <div class="card">
               <h3>📈 Progress</h3>
               <p id="progress-percent">Loading...</p>
           </div>
           <div class="card">
               <h3>🎯 Achievements</h3>
               <p id="certificates-earned">Loading...</p>
           </div>
        </section>

        <div class="container">
            <h2>My Past Scores</h2>
            <?php if ($scores->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Assessment</th>
                            <th>Score (%)</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $scores->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['assessment_title']); ?></td>
                                <td><?php echo round($row['score'], 2); ?>%</td>
                                <td><?php echo date("d M Y, H:i", strtotime($row['attempt_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No assessments attempted yet.</p>
            <?php endif; ?>
        </div>

        <!-- Course Progress Chart -->
        <section class="chart-section">
            <h2>Your Progress</h2>
            <canvas id="progressChart"></canvas>
        </section>
    </div>

    <script src="Dash.js"></script>

</body>
</html>
