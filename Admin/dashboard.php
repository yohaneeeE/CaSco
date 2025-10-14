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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - Career Trends in IT</title>
  <style>
    * {margin:0; padding:0; box-sizing:border-box;}
    body {
      font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background:#e9ecef;
      color:#333;
      overflow-x:hidden;
    }

    /* Sidebar */
    .sidebar {
      height:100vh;
      width:250px;
      position:fixed;
      top:0;
      left:-250px;
      background:#2f2f2f;
      color:#fff;
      padding-top:60px;
      transition:0.3s;
      overflow:auto;
      z-index:1000;
    }
    .sidebar a {
      display:block;
      padding:14px 20px;
      color:#fff;
      text-decoration:none;
      transition:0.3s;
    }
    .sidebar a:hover, .sidebar a.active {
      background:#444;
    }
    .open-btn {
      font-size:24px;
      cursor:pointer;
      background:none;
      border:none;
      color:#fff;
      position:absolute;
      left:20px;
      top:20px;
      z-index:1100;
    }

    header {
      background:linear-gradient(135deg,#444,#222);
      color:#fff;
      padding:25px 0;
      text-align:center;
      box-shadow:0 4px 12px rgba(0,0,0,0.1);
      position:relative;
    }
    header h1 {font-size:2rem; margin-bottom:10px;}
    header p {opacity:0.9; font-size:0.95rem;}

    .container {
      max-width:1200px;
      margin:40px auto;
      padding:30px;
      background:#fff;
      border-radius:15px;
      box-shadow:0 4px 20px rgba(0,0,0,0.08);
    }

    h2 {
      color:#333;
      margin-bottom:25px;
      text-align:center;
      font-size:1.8rem;
      position:relative;
      padding-bottom:15px;
    }
    h2::after {
      content:'';
      position:absolute;
      bottom:0;
      left:50%;
      transform:translateX(-50%);
      width:100px;
      height:3px;
      background:linear-gradient(90deg,#444,#888);
      border-radius:3px;
    }
    .intro-text {
      font-size:1em;
      margin-bottom:40px;
      text-align:center;
      color:#555;
      max-width:800px;
      margin-left:auto;
      margin-right:auto;
    }

    .stats-grid {
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
      gap:30px;
      margin-bottom:40px;
    }

    /* Clickable Stat Cards */
    .stat-card {
      background-color:#fdfdfd;
      padding:25px 20px;
      border-radius:12px;
      text-align:center;
      box-shadow:0 4px 10px rgba(0,0,0,0.05);
      transition:all 0.3s ease;
      border:1px solid #ddd;
      cursor:pointer;
      text-decoration:none;
      color:inherit;
      display:block;
    }
    .stat-card:hover {
      transform:translateY(-5px);
      box-shadow:0 8px 20px rgba(0,0,0,0.1);
      background-color:#f5f5f5;
    }
    .stat-card img {
      width:60px;
      height:60px;
      margin-bottom:15px;
    }
    .stat-number {
      font-size:2.2rem;
      font-weight:bold;
      color:#333;
      margin-bottom:10px;
    }
    .stat-label {
      font-size:1rem;
      color:#555;
    }

    footer {
      text-align:center;
      padding:30px 0;
      background:linear-gradient(135deg,#333,#222);
      color:#fff;
      font-size:0.95em;
      margin-top:60px;
    }
    .footer-links {
      display:flex;
      justify-content:center;
      flex-wrap:wrap;
      gap:15px;
      margin-bottom:15px;
    }
    .footer-links a {
      color:#ffcc00;
      text-decoration:none;
      transition:color 0.3s ease;
      font-size:0.95em;
    }
    .footer-links a:hover {color:white;}

    /* Overlay for mobile sidebar */
    .overlay {
      display:none;
      position:fixed;
      top:0;
      left:0;
      width:100%;
      height:100%;
      background:rgba(0,0,0,0.5);
      z-index:900;
    }

    @media (max-width:768px){
      header h1 {font-size:1.6rem;}
      .open-btn {font-size:22px; top:15px;}
      .container {padding:20px;}
      .stat-number {font-size:1.8rem;}
      .stat-card img {width:50px; height:50px;}
      .sidebar {width:220px;}
      h2 {font-size:1.5rem;}
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="admin-users.php">User Management</a>
    <a href="admin-content.php">Career Content</a>
    <a href="admin-certificates.php">Certificates</a>
    <a href="admin-roadmaps.php">Career Roadmaps</a>
    <a href="logout.php">Logout</a>
  </div>
  <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

  <header>
    <button class="open-btn" onclick="toggleSidebar()">☰</button>
    <h1>Admin Dashboard</h1>
    <p>Manage and monitor your Career Trends platform</p>
  </header>

  <div class="container">
    <h2>System Overview</h2>
    <p class="intro-text">
      Monitor key metrics and manage your platform efficiently with comprehensive administrative tools.
    </p>
    
    <div class="stats-grid">
      <a href="admin-users.php" class="stat-card">
        <img src="https://img.icons8.com/color/96/000000/user.png" alt="Users" />
        <div class="stat-number"><?= number_format($totalUsers) ?></div>
        <div class="stat-label">Total Users</div>
      </a>

      <a href="admin-roadmaps.php" class="stat-card">
        <img src="https://img.icons8.com/color/96/000000/content.png" alt="Careers" />
        <div class="stat-number"><?= number_format($totalCareers) ?></div>
        <div class="stat-label">Careers</div>
      </a>
    </div>
  </div>

  <footer>
    <div class="footer-links">
      <a href="privacy.php">Privacy Policy</a>
      <a href="terms.php">Terms of Service</a>
      <a href="contact.php">Contact Us</a>
    </div>
    <p>&copy; 2025 Mapping The Future System. All rights reserved.</p>
    <p>Bulacan State University - Bustos Campus</p>
  </footer>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const overlay = document.getElementById("overlay");
      const isOpen = sidebar.style.left === "0px";

      sidebar.style.left = isOpen ? "-250px" : "0px";
      overlay.style.display = isOpen ? "none" : "block";
      document.body.style.overflow = isOpen ? "auto" : "hidden";
    }
  </script>
</body>
</html>
