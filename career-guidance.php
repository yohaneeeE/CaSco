<?php
session_start();
include 'db_connect.php';

$isLoggedIn = isset($_SESSION['fullName']);
$fullName = $isLoggedIn ? $_SESSION['fullName'] : null;

$careers = [];
mysqli_report(MYSQLI_REPORT_OFF);

$sql = "SELECT id, title, category, description, skills FROM careers ORDER BY category, title";
$result = $conn->query($sql);
if ($result === false) {
    error_log('Career ordered query failed: ' . $conn->error);
    $sql = "SELECT id, title, category, description, skills FROM careers";
    $result = $conn->query($sql);
}

if ($result && $result->num_rows > 0) {
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
<title>Career Guidance | eMentor</title>
<link rel="icon" type="image/x-icon" href="img/em.png">

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f4f8;
        color: #333;
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    header {
        background: linear-gradient(135deg, #444, #666);
        color: #fff;
        text-align: center;
        padding: 30px 20px;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    header h1 {
        font-size: 2.2rem;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    header p { opacity: 0.9; font-size: 1.05rem; }

    /* Hamburger Menu */
    .hamburger {
        position: absolute;
        top: 25px; left: 25px;
        width: 30px; height: 22px;
        display: flex; flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
        z-index: 300;
        transition: transform 0.3s ease;
    }
    .hamburger span {
        height: 4px;
        background: white;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    .hamburger:hover { transform: scale(1.1); }
    .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px, -6px); }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0; left: -250px;
        width: 250px; height: 100%;
        background: #333;
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
        transition: color 0.3s ease, transform 0.2s ease;
        display: block;
    }
    .sidebar a:hover { color: #ffcc00; transform: translateX(5px); }
    .sidebar hr { border: 1px solid rgba(255,255,255,0.2); }
    .sidebar .user-info {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.2);
        font-size: 0.95rem;
        color: #ffcc00;
        text-align: center;
    }

    /* Overlay */
    .overlay {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.4);
        opacity: 0; visibility: hidden;
        transition: opacity 0.3s ease;
        z-index: 100;
    }
    .overlay.active { opacity: 1; visibility: visible; }

    main {
        flex: 1;
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    h2 {
        color: #444;
        text-align: center;
        margin-bottom: 30px;
        font-size: 2rem;
        position: relative;
        padding-bottom: 10px;
    }
    h2::after {
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

    /* Career Cards Grid */
    .career-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
    }

    .career-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .career-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }
    .career-category {
        display: inline-block;
        background: #ffcc00;
        color: #333;
        font-weight: 600;
        border-radius: 12px;
        padding: 5px 12px;
        font-size: 0.85rem;
        margin-bottom: 12px;
    }
    .career-title {
        font-size: 1.4rem;
        color: #222;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .career-description {
        color: #555;
        margin-bottom: 12px;
        font-size: 0.97rem;
    }
    .career-skills {
        font-size: 0.95rem;
        color: #333;
        margin-bottom: 18px;
    }
    .view-link {
        display: inline-block;
        background: #444;
        color: #ffcc00;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        transition: background 0.3s ease, color 0.3s ease;
    }
    .view-link:hover {
        background: #ffcc00;
        color: #333;
    }

    footer {
        background: linear-gradient(135deg, #444, #666);
        color: #fff;
        text-align: center;
        padding: 25px 10px;
        font-size: 0.95rem;
        margin-top: 40px;
    }
    .footer-links {
        display: flex;
        justify-content: center;
        gap: 25px;
        margin-bottom: 10px;
    }
    .footer-links a {
        color: #ffcc00;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .footer-links a:hover { color: #fff; }
</style>
</head>
<body>

<header>
    <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
    </div>
    <h1>eMentor</h1>
    <p>Explore career paths tailored for IT students</p>
</header>

<div class="sidebar" id="sidebar">
    <a href="index.php">Home</a>
    <a href="career-guidance.php">Career Guidance</a>
    <a href="careerpath.php">Career Path</a>
    <a href="about.php">About</a>
    <hr>
    <?php if ($isLoggedIn): ?>
        <a href="settings.php">Settings</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        <div class="user-info">Logged in as<br><strong><?= htmlspecialchars($fullName) ?></strong></div>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<main>
    <h2>Available Careers</h2>
    <?php if (empty($careers)): ?>
        <p style="text-align:center; color:#999;">No career data available at the moment.</p>
    <?php else: ?>
        <div class="career-grid">
            <?php foreach ($careers as $career): ?>
                <div class="career-card">
                    <div class="career-category"><?= htmlspecialchars($career['category']) ?></div>
                    <div class="career-title"><?= htmlspecialchars($career['title']) ?></div>
                    <div class="career-description"><?= nl2br(htmlspecialchars($career['description'])) ?></div>
                    <div class="career-skills"><strong>Skills:</strong> <?= htmlspecialchars($career['skills']) ?></div>
                    <a class="view-link" href="career-roadmap.php?career_id=<?= $career['id'] ?>">View Roadmap →</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

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
