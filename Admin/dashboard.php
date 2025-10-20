<?php
session_start();
include 'db_admin.php';

// Initialize variables
$totalUsers = 0;
$totalCareers = 0;

// Fetch counts depending on connection type
if (isset($conn) && $conn instanceof mysqli) {
    $res = $conn->query("SELECT COUNT(*) AS cnt FROM users");
    if ($res) $totalUsers = (int)($res->fetch_assoc()['cnt'] ?? 0);

    $res = $conn->query("SELECT COUNT(*) AS cnt FROM careers");
    if ($res) $totalCareers = (int)($res->fetch_assoc()['cnt'] ?? 0);
} elseif (isset($pdo) && $pdo instanceof PDO) {
    try {
        $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalCareers = (int)$pdo->query("SELECT COUNT(*) FROM careers")->fetchColumn();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CareerScope Admin Dashboard</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f4f4f4;
  color: #333;
  line-height: 1.6;
  overflow-x: hidden;
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
header h1 { font-size: 2rem; margin-bottom: 8px; }
header p { font-size: 1rem; opacity: 0.9; }

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
.sidebar.active { left: 0; }
.sidebar a {
  color: white;
  text-decoration: none;
  font-size: 1.1rem;
  padding: 8px 0;
  display: block;
  transition: 0.3s;
}
.sidebar a:hover,
.sidebar a.active {
  color: #ffcc00;
  transform: translateX(5px);
}

/* OVERLAY */
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

/* MAIN CONTAINER */
.container {
  max-width: 1000px;
  margin: 60px auto;
  padding: 40px 25px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  text-align: center;
}
.container h2 {
  font-size: 1.8rem;
  color: #333;
  margin-bottom: 15px;
}
.container p {
  color: #555;
  font-size: 1.05rem;
  margin-bottom: 40px;
}

/* STAT CARDS */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
}
.stat-card {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: 1px solid #eee;
  text-decoration: none;
  color: inherit;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.15);
}
.stat-card img {
  width: 60px;
  margin-bottom: 15px;
}
.stat-number {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 8px;
}
.stat-label {
  color: #555;
  font-size: 1rem;
}

/* FOOTER */
footer {
  text-align: center;
  padding: 20px;
  background: #444;
  color: #ddd;
  margin-top: 60px;
  font-size: 0.95rem;
}
footer a {
  color: #ffcc00;
  text-decoration: none;
}
footer a:hover { text-decoration: underline; }

@media (max-width: 768px) {
  header h1 { font-size: 1.6rem; }
  .container { padding: 25px 15px; }
  .stat-number { font-size: 1.8rem; }
}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger">
    <span></span><span></span><span></span>
  </div>
  <h1>CareerScope Admin Dashboard</h1>
  <p>Manage and monitor platform activity</p>
</header>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <a href="dashboard.php" class="active">Dashboard</a>
  <a href="admin-users.php">User Management</a>
  <a href="admin-content.php">Career Content</a>
  <a href="admin-certificates.php">Certificates</a>
  <a href="admin-roadmaps.php">Career Roadmaps</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>
<div class="overlay" id="overlay"></div>

<!-- MAIN CONTENT -->
<div class="container">
  <h2>System Overview</h2>
  <p>Monitor key metrics and manage your CareerScope platform efficiently.</p>

  <div class="stats-grid">
    <a href="admin-users.php" class="stat-card">
      <img src="https://img.icons8.com/color/96/000000/user.png" alt="Users" />
      <div class="stat-number"><?= number_format($totalUsers) ?></div>
      <div class="stat-label">Total Users</div>
    </a>

    <a href="admin-roadmaps.php" class="stat-card">
      <img src="https://img.icons8.com/color/96/000000/content.png" alt="Careers" />
      <div class="stat-number"><?= number_format($totalCareers) ?></div>
      <div class="stat-label">Total Careers</div>
    </a>
  </div>
</div>

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
