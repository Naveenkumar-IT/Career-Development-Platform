<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="jobs.css">
</head>
<body>
    <div class="container">
        <h2>Available Jobs</h2>
        <div class="search-filter">
            <input type="text" id="search" placeholder="Search jobs...">
            <select id="category">
                <option value="">All Categories</option>
                <option value="IT">IT</option>
                <option value="Marketing">Marketing</option>
                <option value="Finance">Finance</option>
            </select>
            <button onclick="filterJobs()">Search</button>
        </div>
        <div class="job-listings" id="job-list"> <!-- Jobs will be dynamically loaded here -->

        <?php
        $query = "SELECT * FROM jobs ORDER BY posted_at DESC";
        $result = $conn->query($query);

        if ($result->num_rows > 0):
            while ($job = $result->fetch_assoc()):
        ?>

            <div class="job-card">
                <h3><?= htmlspecialchars($job['job_title']); ?></h3>
                <p><strong>Company:</strong> <?= htmlspecialchars($job['company_name']); ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($job['job_location']); ?></p>
                <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary_range']); ?></p>
                <p><?= htmlspecialchars(substr($job['job_description'], 0, 100)); ?>...</p>
                <a href="job_details.php?job_id=<?= $job['job_id']; ?>" class="btn">View Details</a>
            </div>
        <?php endwhile; else: ?>
            <p>No job listings available.</p>
        <?php endif; ?>
        </div>
    </div>

    <script>
        function filterJobs() {
            let searchValue = document.getElementById('search').value.toLowerCase();
            let categoryValue = document.getElementById('category').value;
            let jobs = document.querySelectorAll('.job-card');

            jobs.forEach(job => {
                let title = job.querySelector('.job-title').innerText.toLowerCase();
                let category = job.getAttribute('data-category');

                if ((title.includes(searchValue) || searchValue === "") && (category === categoryValue || categoryValue === "")) {
                    job.style.display = "block";
                } else {
                    job.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>
