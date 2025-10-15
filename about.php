<?php
session_start();
include 'db_connect.php';

$isLoggedIn = isset($_SESSION['fullName']);
$fullName   = $isLoggedIn ? $_SESSION['fullName'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About | CareerScope</title>
  <link rel="icon" type="image/x-icon" href="img/cs.png" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      color: #333;
      line-height: 1.6;
    }

    /* HEADER */
    header {
      background: linear-gradient(135deg, #666, #888);
      color: white;
      padding: 25px 0;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    /* HAMBURGER */
    .hamburger {
      position: absolute;
      top: 20px;
      left: 20px;
      width: 30px;
      height: 22px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      cursor: pointer;
      z-index: 300;
    }

    .hamburger span {
      height: 4px;
      background: white;
      border-radius: 2px;
    }

    /* SIDEBAR */
    .sidebar {
      position: fixed;
      top: 0;
      left: -250px;
      width: 250px;
      height: 100%;
      background: #444;
      color: white;
      padding: 60px 20px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      transition: left 0.3s ease;
      z-index: 200;
    }

    .sidebar.active {
      left: 0;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      font-size: 1.1rem;
      padding: 8px 0;
      display: block;
      transition: 0.3s;
    }

    .sidebar a:hover {
      color: #ffcc00;
      transform: translateX(5px);
    }

    /* OVERLAY */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease;
      z-index: 100;
    }

    .overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* MAIN CONTENT */
    .container {
      max-width: 1000px;
      margin: 60px auto;
      padding: 40px 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .container h2 {
      color: #333;
      text-align: center;
      font-size: 2rem;
      margin-bottom: 20px;
      position: relative;
      padding-bottom: 15px;
    }

    .container h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 3px;
      background: linear-gradient(90deg, #666, #ffcc00);
      border-radius: 3px;
    }

    .container p {
      color: #555;
      font-size: 1.1rem;
      text-align: center;
      margin-bottom: 25px;
    }

    ul {
      margin: 20px auto;
      max-width: 700px;
      color: #555;
      font-size: 1.05rem;
    }

    ul li {
      margin-bottom: 10px;
    }

    /* FOOTER */
    footer {
      text-align: center;
      padding: 20px;
      background: #444;
      color: #ddd;
      margin-top: 40px;
      font-size: 0.95rem;
    }

    footer a {
      color: #ffcc00;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <header>
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <h1>CareerScope</h1>
    <p>Empowering students with data-driven career guidance</p>
  </header>

  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <a href="index.php">Home</a>
    <a href="career-guidance.php">Career Guidance</a>
    <a href="careerpath.php">Career Path</a>
    <a href="about.php" style="color:#ffcc00;">About</a>
    <hr style="border:1px solid rgba(255,255,255,0.2);">

    <?php if ($isLoggedIn): ?>
      <span style="color:#ffcc00;">Welcome, <?= htmlspecialchars($fullName) ?>!</span>
      <a href="settings.php">Settings</a>
      <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </div>

  <div class="overlay" id="overlay"></div>

  <!-- MAIN CONTENT -->
  <div class="container">
    <h2>About CareerScope</h2>
    <p>
      CareerScope is a web-based career guidance system designed to empower students through data-driven insights.
      It analyzes academic performance and personal interests to help students discover suitable career paths.
    </p>

    <p>
      This system was developed as a <strong>Capstone Project</strong> by Information Technology students from
      <strong>Bulacan State University - Bustos Campus</strong>. The project team includes
      <strong>Christian Amor, Cedrick Antonio, Lander Bryan, Carlo Moreno,</strong> and <strong>Thomas Tadeo</strong>.
    </p>

    <ul>
      <li>Provides AI-powered and data-backed career recommendations</li>
      <li>Analyzes subjects and skill performance for accurate guidance</li>
      <li>Integrates academic data and personal interests into insights</li>
      <li>Helps students align strengths with emerging job opportunities</li>
    </ul>

    <p>
      Built with reliability and accessibility in mind, CareerScope utilizes secure database integration and modern
      web technologies to ensure accurate, fast, and personalized guidance. Its goal is to help students make
      informed decisions about their future careers with confidence.
    </p>
  </div>

  <footer>
    <p>
      &copy; <?= date("Y") ?> CareerScope | A Capstone Project by IT Students of Bulacan State University - Bustos Campus |
      <a href="privacy.html">Privacy Policy</a> | <a href="terms.html">Terms</a>
    </p>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    hamburger.addEventListener('click', () => {
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
    });
  </script>

</body>
</html>

