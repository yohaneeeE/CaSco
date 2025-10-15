<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['career_id']) || !is_numeric($_GET['career_id'])) {
    die("Invalid career ID.");
}
$career_id = intval($_GET['career_id']);

// Fetch career info
$stmt = $conn->prepare("SELECT title, category, description FROM careers WHERE id = ?");
$stmt->bind_param("i", $career_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Career not found.");
$career = $result->fetch_assoc();
$stmt->close();

// Fetch roadmap steps
$stmt = $conn->prepare("SELECT step_number, step_title, step_detail FROM career_roadmaps WHERE career_id = ? ORDER BY step_number ASC");
$stmt->bind_param("i", $career_id);
$stmt->execute();
$roadmap_result = $stmt->get_result();
$roadmap_steps = $roadmap_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch certificates
$stmt = $conn->prepare("SELECT certificate_title, provider, description, skills FROM certificates WHERE career_id = ?");
$stmt->bind_param("i", $career_id);
$stmt->execute();
$cert_result = $stmt->get_result();
$certificates = $cert_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
$isLoggedIn = isset($_SESSION['fullName']);
$fullName   = $isLoggedIn ? $_SESSION['fullName'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CareerScope | <?= htmlspecialchars($career['title']) ?></title>
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#f4f4f4; color:#333; line-height:1.6;
}

/* HEADER */
header {
    background: linear-gradient(135deg,#666,#888);
    color:white;
    padding:25px 0;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.2);
    position: relative;
}
header h1 { font-size:2.5rem; margin-bottom:10px; }
header p { font-size:1.1rem; opacity:0.9; }

/* HAMBURGER */
.hamburger {
    position:absolute; top:20px; left:20px;
    width:30px; height:22px;
    display:flex; flex-direction:column; justify-content:space-between;
    cursor:pointer; z-index:300;
}
.hamburger span { height:4px; background:white; border-radius:2px; }

/* SIDEBAR */
.sidebar {
    position:fixed; top:0; left:-250px;
    width:250px; height:100%;
    background:#444; color:white;
    padding:60px 20px; display:flex; flex-direction:column; gap:20px;
    transition:left 0.3s ease; z-index:200;
}
.sidebar.active { left:0; }
.sidebar a {
    color:white; text-decoration:none; font-size:1.1rem; padding:8px 0; display:block;
    transition:0.3s;
}
.sidebar a:hover { color:#ffcc00; transform:translateX(5px); }

/* OVERLAY */
.overlay {
    position:fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.4); opacity:0; visibility:hidden;
    transition: opacity 0.3s ease; z-index:100;
}
.overlay.active { opacity:1; visibility:visible; }

/* MAIN CONTENT */
main {
    max-width:900px; margin:60px auto; padding:35px 40px;
}
.hero-like {
    background:#fff; padding:35px 30px; border-radius:10px;
    box-shadow:0 4px 20px rgba(0,0,0,0.1); margin-bottom:40px;
}
.hero-like h2 { font-size:2rem; color:#004080; margin-bottom:15px; text-align:center; }
.hero-like p { font-size:1.1rem; color:#555; margin-bottom:25px; text-align:center; }
.hero-like a {
    display:inline-block; padding:12px 25px; background:#ffcc00;
    color:#333; border-radius:6px; text-decoration:none; font-weight:600; transition:0.3s;
}
.hero-like a:hover { background:#e6b800; }

.category { display:inline-block; padding:6px 14px; border-radius:15px; background:#004080; color:white; font-weight:600; margin-bottom:20px; }
.description { font-size:1.1rem; color:#555; text-align:justify; margin-bottom:30px; }

/* ROADMAP STEPS */
h3 { font-size:1.5rem; color:#004080; margin:25px 0 10px; text-align:center; }
.roadmap-step { background:#f5f5f5; border-left:6px solid #004080;
    padding:20px 25px; margin-bottom:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.05);
}
.step-number { font-weight:700; color:grey; font-size:1.1rem; margin-bottom:6px; }
.step-title { font-weight:600; font-size:1.2rem; margin-bottom:8px; color:#222; }
.step-description { font-size:1rem; color:#333; line-height:1.5; }

/* CERTIFICATES */
.certificate { background:#fff; border-left:6px solid #ffcc00; padding:20px 25px; margin-bottom:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
.cert-title { font-weight:600; font-size:1.1rem; color:#333; margin-bottom:6px; }
.cert-provider { font-size:0.95rem; font-style:italic; margin-bottom:8px; color:#666; }
.cert-description { margin-bottom:6px; color:#444; }
.cert-skills { font-size:0.95rem; color:#333; }

/* BACK BUTTON */
a.back-link { display:inline-block; margin-top:25px; background:#ffcc00; color:#333; padding:10px 18px; border-radius:8px; text-decoration:none; font-weight:600; transition:0.3s; }
a.back-link:hover { background:#e6b800; }

/* FOOTER */
footer { text-align:center; padding:20px; background:#444; color:#ddd; font-size:0.95rem; margin-top:40px; }
footer a { color:#ffcc00; text-decoration:none; }
footer a:hover { text-decoration:underline; }

/* RESPONSIVE */
@media(max-width:768px){header h1{font-size:2rem;} header p{font-size:1rem;}}
@media(max-width:600px){.hero-like h2{font-size:1.7rem;}}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>CareerScope Roadmap</h1>
  <p>Empowering students with data-driven career guidance</p>
</header>

<div class="sidebar" id="sidebar">
  <a href="index.php">Home</a>
  <a href="career-guidance.php">Career Guidance</a>
  <a href="careerpath.php">Career Path</a>
  <a href="about.php">About</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <?php if($isLoggedIn): ?>
      <span style="color:#ffcc00;">👋 Welcome, <?= htmlspecialchars($fullName) ?>!</span>
      <a href="settings.php">Settings</a>
      <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
  <?php else: ?>
      <a href="login.php">Login</a>
  <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<main>
  <section class="hero-like">
    <h2><?= htmlspecialchars($career['title']) ?></h2>
    <div class="category"><?= htmlspecialchars($career['category']) ?></div>
    <div class="description"><?= nl2br(htmlspecialchars($career['description'])) ?></div>
    <a class="back-link" href="career-guidance.php">&larr; Back to Careers</a>
  </section>

  <h3>Roadmap</h3>
  <?php if(empty($roadmap_steps)): ?>
    <p style="text-align:center; color:#999; font-style:italic;">No roadmap steps available yet for this career.</p>
  <?php else: foreach($roadmap_steps as $step): ?>
    <div class="roadmap-step">
      <div class="step-number">Step <?= $step['step_number'] ?></div>
      <div class="step-title"><?= htmlspecialchars($step['step_title']) ?></div>
      <div class="step-description"><?= nl2br(htmlspecialchars($step['step_detail'])) ?></div>
    </div>
  <?php endforeach; endif; ?>

  <h3>Recommended Certificates</h3>
  <?php if(empty($certificates)): ?>
    <p style="text-align:center; color:#999; font-style:italic;">No certificates available yet for this career.</p>
  <?php else: foreach($certificates as $cert): ?>
    <div class="certificate">
      <div class="cert-title"><?= htmlspecialchars($cert['certificate_title']) ?></div>
      <div class="cert-provider">Offered by <?= htmlspecialchars($cert['provider']) ?></div>
      <div class="cert-description"><?= nl2br(htmlspecialchars($cert['description'])) ?></div>
      <div class="cert-skills"><strong>Skills:</strong> <?= htmlspecialchars($cert['skills']) ?></div>
    </div>
  <?php endforeach; endif; ?>
</main>

<footer>
  <p>&copy; <?= date("Y") ?> CareerScope | Empowering students with data-driven guidance |
     <a href="about.php">Learn More</a></p>
</footer>

<script>
const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
hamburger.addEventListener('click',()=>{sidebar.classList.toggle('active'); overlay.classList.toggle('active');});
overlay.addEventListener('click',()=>{sidebar.classList.remove('active'); overlay.classList.remove('active');});
</script>
</body>
</html>
