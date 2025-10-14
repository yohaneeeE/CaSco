<?php
session_start();

include 'db_connect.php';

// Check login state
$isLoggedIn = isset($_SESSION['fullName']);
$fullName = $isLoggedIn ? $_SESSION['fullName'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About | Digital Career Guidance</title>
  <link rel="icon" type="image/x-icon" href="img/em.png">

  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #e6e6e6;
      color: grey;
      line-height:1.6;
    }

    header {
      background: linear-gradient(135deg, #444, #666);
      color:white;
      padding:25px 0;
      text-align:center;
      box-shadow:0 4px 12px rgba(0,0,0,0.2);
      position:relative;
    }
    header h1 { font-size:2.5rem; margin-bottom:10px; }
    header p { font-size:1.1rem; opacity:0.9; }

    /* Hamburger button */
    .hamburger {
        position: absolute;
        top: 20px; left: 20px;
        width: 30px; height: 22px;
        display: flex; flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
        z-index: 300;
        transition: transform 0.3s ease;
    }
    .hamburger span {
        height:4px;
        background:white;
        border-radius:2px;
        transition: all 0.3s ease;
    }
    .hamburger:hover { transform: scale(1.1); }
    .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
    .hamburger.active span:nth-child(2) { opacity:0; }
    .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px,-6px); }

    /* Sidebar */
    .sidebar {
        position: fixed; top:0; left:-250px;
        width:250px; height:100%;
        background:#333; color:white;
        padding:60px 20px 20px; /* added bottom padding */
        display:flex; flex-direction:column;
        gap:20px;
        transition:left 0.3s ease;
        z-index:200;
    }
    .sidebar.active { left:0; }
    .sidebar a {
        color:white; text-decoration:none;
        font-size:1.1rem; padding:8px 0;
        display:block; transition: color 0.3s ease, transform 0.2s ease;
    }
    .sidebar a:hover { color:#ffcc00; transform:translateX(5px); }

    /* User section in sidebar */
    .sidebar .user-info {
        margin-top:auto; /* push to bottom */
        padding-top:15px;
        border-top:1px solid rgba(255,255,255,0.2);
        font-size:0.95rem;
        color:#ffcc00;
        text-align:center;
    }

    /* Overlay */
    .overlay {
        position:fixed; top:0; left:0;
        width:100%; height:100%;
        background: rgba(0,0,0,0.4);
        opacity:0; visibility:hidden;
        transition: opacity 0.3s ease;
        z-index:100;
    }
    .overlay.active { opacity:1; visibility:visible; }

    .container {
      max-width:1200px;
      margin:40px auto;
      padding:30px;
      background:#fff;
      border-radius:15px;
      box-shadow:0 4px 20px rgba(0,0,0,0.08);
    }

    h2 {
      color: grey;
      margin-bottom:25px;
      text-align:center;
      font-size:2rem;
      position:relative;
      padding-bottom:15px;
    }
    h2::after {
      content:'';
      position:absolute;
      bottom:0;
      left:50%;
      transform: translateX(-50%);
      width:100px;
      height:3px;
      background: linear-gradient(90deg, #666, #ffcc00);
      border-radius:3px;
    }

    .intro-text {
      font-size:1.1em;
      margin-bottom:30px;
      text-align:center;
      color:#555;
      max-width:800px;
      margin-left:auto;
      margin-right:auto;
    }

    ul { margin-top:10px; padding-left:20px; margin-bottom:20px; }
    ul li { margin-bottom:8px; color:#555; }

    footer {
      text-align:center;
      padding:30px 0;
      background: linear-gradient(135deg,#444,#666);
      color:white;
      font-size:0.95em;
      margin-top:60px;
    }
    .footer-links { display:flex; justify-content:center; gap:20px; margin-bottom:15px; }
    .footer-links a { color:#ffcc00; text-decoration:none; transition:color 0.3s ease; }
    .footer-links a:hover { color:white; }
  </style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>eMentor</h1>
  <p>Empowering students with data-driven career guidance</p>
</header>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="index.php">Home</a>
    <a href="career-guidance.php">Career Guidance</a>
    <a href="careerpath.php">Career Path</a>
    <a href="about.php">About</a>
    <hr style="border: 1px solid rgba(255,255,255,0.2);">
    <?php if ($isLoggedIn): ?>
        <a href="settings.php">Settings</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        <div class="user-info">
            Logged in as <br><strong><?php echo htmlspecialchars($fullName); ?></strong>
        </div>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>About This Website</h2>
  <p class="intro-text">
    eMentor is a web-based career guidance system specifically developed for Information Technology students. It integrates data-driven analysis of career trends with students’ personal interests and academic performance to provide tailored recommendations.
  </p>
  <p class="intro-text">
    By leveraging industry insights and emerging technology trends, eMentor offers guidance on the most suitable career paths, helping students navigate today’s dynamic and competitive job market.
  </p>
  <ul>
    <li>Latest trends in IT careers with visual growth statistics</li>
    <li>Interactive tools to assess personal performance and interests</li>
    <li>Suggestions based on grades, preferences, and hobbies</li>
    <li>Guidance to align individual strengths with growing job sectors</li>
  </ul>
  <p class="intro-text">
    Whether you're a high school student, college graduate, or someone exploring a new career path, this platform gives you a head start in planning your future with confidence and clarity.
  </p>
</div>

<footer>
  <div class="footer-links">
    <a href="privacy.html">Privacy Policy</a>
    <a href="terms.html">Terms of Service</a>
    <a href="contact.html">Contact Us</a>
  </div>
  <p>&copy; 2025 eMentor. All rights reserved.</p>
  <p>Bulacan State University - Bustos Campus</p>
</footer>

<script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        hamburger.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        hamburger.classList.remove('active');
    });
</script>

</body>
</html>

