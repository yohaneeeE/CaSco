<?php
session_start();
include 'db_connect.php';

$isLoggedIn = isset($_SESSION['fullName']);
$fullName   = $isLoggedIn ? $_SESSION['fullName'] : null;

$careers = [];
mysqli_report(MYSQLI_REPORT_OFF);
$sql = "SELECT id, title, category, description, skills FROM careers ORDER BY category, title";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $careers[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CareerScope | Career Guidance</title>
  <link rel="icon" type="image/x-icon" href="img/cs.png" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
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
    .sidebar a.active {
      color: #ffcc00;
    }
    .sidebar hr {
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

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

    /* MAIN */
    main {
      max-width: 1100px;
      margin: 70px auto;
      background: #fff;
      padding: 40px 25px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    main h2 {
      text-align: center;
      font-size: 2rem;
      color: #333;
      margin-bottom: 25px;
    }

    .career-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
      gap: 25px;
    }

    .career-card {
      background: #fff;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .career-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    .career-card .category {
      display: inline-block;
      background: #ccc;
      color: #333;
      font-size: 0.9rem;
      font-weight: 600;
      border-radius: 5px;
      padding: 3px 9px;
      margin-bottom: 10px;
    }
    .career-card h3 {
      color: #333;
      font-size: 1.3rem;
      margin-bottom: 8px;
    }
    .career-card p {
      color: #555;
      font-size: 0.95rem;
      margin-bottom: 12px;
    }
    .career-card .skills {
      color: #666;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }
    .career-card a {
      display: inline-block;
      background: #ffcc00;
      color: #333;
      font-weight: 600;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 6px;
      transition: 0.3s;
    }
    .career-card a:hover {
      background: #e6b800;
    }

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

    @media (max-width: 768px) {
      header h1 {
        font-size: 2rem;
      }
      main {
        padding: 30px 20px;
      }
    }
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

<div class="sidebar" id="sidebar">
  <a href="index.php">Home</a>
  <a href="career-guidance.php" class="active">Career Guidance</a>
  <a href="careerpath.php">Career Path</a>
  <a href="about.php">About</a>
  <hr>
  <?php if($isLoggedIn): ?>
    <span style="color:#ffcc00;">👋 Welcome, <?= htmlspecialchars($fullName) ?>!</span>
    <a href="settings.php">Settings</a>
    <a href="logout.php" onclick="return confirm('Logout now?');">Logout</a>
  <?php else: ?>
    <a href="login.php">Login</a>
  <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<main>
  <h2>Available Career Paths</h2>
  <?php if(empty($careers)): ?>
    <p style="text-align:center;color:#777;">No career data available yet.</p>
  <?php else: ?>
  <div class="career-grid">
    <?php foreach($careers as $c): ?>
      <div class="career-card">
        <div class="category"><?= htmlspecialchars($c['category']) ?></div>
        <h3><?= htmlspecialchars($c['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars($c['description'])) ?></p>
        <div class="skills"><strong>Skills:</strong> <?= htmlspecialchars($c['skills']) ?></div>
        <a href="career-roadmap.php?career_id=<?= $c['id'] ?>">View Roadmap →</a>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</main>

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
