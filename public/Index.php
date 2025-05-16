<?php
session_start();
if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Career Development Platform</title>
  <link rel="stylesheet" href="Index.css">
</head>
<body>
  <!-- Navbar -->
  <header class="navbar">
    <div class="container">
      <h1 class="logo">CareerPro</h1>
      <nav>
        <ul class="nav-links">
          <li><a href="Admin_login.php">Admin</a></li>
          <li><a href="jobs.php">Jobs</a></li>
          <li><a href="courses.php">Courses</a></li>
          <li><a href="Contact.html">Contact</a></li>
          <li><a href="Login.html" class="btn-login">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h2>Find Your Dream Career</h2>
      <p>Explore job opportunities, build your resume, and get career advice, all in one place.</p>
      <a href="#" class="btn-cta">Get Started</a>
    </div>
  </section>

  <!-- Featured Services -->
  <section class="services">
    <div class="container">
      <h3>What We Offer</h3>
      <div class="service-cards">
        <div class="card">
          <h4>Job Listings</h4>
          <p>Access thousands of job opportunities tailored to your skills.</p>
        </div>
        <div class="card">
          <h4>Courses</h4>
          <p>Access to wide range of course platforms.</p>
        </div>
        <div class="card">
          <h4>Career Advice</h4>
          <p>Get expert guidance to excel in your career journey.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2025 CareerPro. All rights reserved.</p>
      <ul class="social-links">
        <li><a href="#">Facebook</a></li>
        <li><a href="#">Twitter</a></li>
        <li><a href="#">LinkedIn</a></li>
      </ul>
    </div>
  </footer>
</body>
</html>