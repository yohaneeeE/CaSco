<?php
session_start();
$isLoggedIn = isset($_SESSION['fullName']);
$fullName   = $isLoggedIn ? $_SESSION['fullName'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CareerScope | Home</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

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
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  position: relative;
}
header h1 { font-size: 2.5rem; margin-bottom: 10px; }
header p { font-size: 1.1rem; opacity: 0.9; }

/* SIDEBAR */
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
.sidebar.active { left: 0; }
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

.overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  opacity: 0; visibility: hidden;
  transition: opacity 0.3s ease;
  z-index: 100;
}
.overlay.active { opacity: 1; visibility: visible; }

/* MAIN SECTION */
.hero {
  text-align: center;
  padding: 70px 20px;
  background: #fff;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  margin: 60px auto;
  border-radius: 10px;
  max-width: 900px;
}
.hero h2 {
  font-size: 2rem;
  color: #333;
  margin-bottom: 15px;
}
.hero p {
  font-size: 1.1rem;
  color: #555;
  margin-bottom: 30px;
}
.hero a {
  display: inline-block;
  padding: 12px 25px;
  background: #ffcc00;
  color: #333;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
}
.hero a:hover {
  background: #e6b800;
}

/* FEATURE CARDS */
.features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  margin: 60px auto;
  max-width: 1000px;
  padding: 0 20px;
}
.card {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.15);
}
.card h3 {
  margin-bottom: 10px;
  color: #333;
}
.card p {
  color: #666;
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
footer a:hover { text-decoration: underline; }
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger">
    <span></span><span></span><span></span>
  </div>
  <h1>CareerScope</h1>
  <p>Empowering students with data-driven career guidance</p>
</header>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <a href="index.php" style="color:#ffcc00;">Home</a>
  <a href="career-guidance.php">Career Guidance</a>
  <a href="careerpath.php">Career Path</a>
  <a href="about.php">About</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <?php if ($isLoggedIn): ?>
      <span style="color:#ffcc00;">👋 Welcome, <?= htmlspecialchars($fullName) ?>!</span>
      <a href="settings.php">Settings</a>
      <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
  <?php else: ?>
      <a href="login.php">Login</a>
  <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<!-- Main Content -->
<section class="hero">
  <h2>Welcome to CareerScope</h2>
  <p>Your trusted partner in exploring career paths that align with your skills and passion.</p>
  <a href="career-guidance.php">Get Started</a>
</section>

<section class="features">
  <div class="card">
    <h3>📊 Personalized Insights</h3>
    <p>Get data-driven career recommendations tailored to your skills and academic performance.</p>
  </div>
  <div class="card">
    <h3>🎯 Career Path Planning</h3>
    <p>Discover which subjects and competencies lead to your ideal profession.</p>
  </div>
  <div class="card">
    <h3>🧭 Student Empowerment</h3>
    <p>CareerScope helps you make confident, informed decisions about your future career.</p>
  </div>
</section>

<footer>
  <p>&copy; <?= date("Y") ?> CareerScope | Empowering students with data-driven guidance |
     <a href="about.php">Learn More</a>
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
