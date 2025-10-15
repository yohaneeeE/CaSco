<?php
session_start();
include 'db_connect.php';

// --- Session vars for sidebar ---
$isLoggedIn = $_SESSION['logged_in'] ?? false;
$fullName   = $_SESSION['full_name'] ?? '';

// --- Get JSON data from API or JS fetch ---
$input = json_decode(file_get_contents('php://input'), true);

$careerPrediction = $input['careerPrediction'] ?? '';
$careerOptions    = $input['careerOptions'] ?? [];
$rawSubjects      = $input['rawSubjects'] ?? [];
$mappedSkills     = $input['mappedSkills'] ?? [];
$certificates     = $input['certificates'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>CareerScope | Career Suggestions</title>
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
    color:white; text-align:center; padding:25px 0;
    box-shadow:0 4px 12px rgba(0,0,0,0.2); position:relative;
}
header h1 { font-size:2.5rem; margin-bottom:10px; }
header p { font-size:1.1rem; opacity:0.9; }

/* HAMBURGER */
.hamburger {
    position:absolute; top:20px; left:20px;
    width:30px; height:22px; display:flex;
    flex-direction:column; justify-content:space-between;
    cursor:pointer; z-index:300;
}
.hamburger span { height:4px; background:white; border-radius:2px; }
.hamburger.active span:nth-child(1){ transform:rotate(45deg) translate(5px,5px); }
.hamburger.active span:nth-child(2){ opacity:0; }
.hamburger.active span:nth-child(3){ transform:rotate(-45deg) translate(6px,-6px); }

/* SIDEBAR */
.sidebar {
    position:fixed; top:0; left:-250px;
    width:250px; height:100%; background:#444;
    color:white; padding:60px 20px; display:flex; flex-direction:column; gap:20px;
    transition:left 0.3s ease; z-index:200;
}
.sidebar.active { left:0; }
.sidebar a {
    color:white; text-decoration:none; font-size:1.1rem;
    padding:8px 0; display:block; transition:0.3s;
}
.sidebar a:hover { color:#ffcc00; transform:translateX(5px); }
.user-info { margin-top:auto; padding-top:15px; border-top:1px solid rgba(255,255,255,0.2); color:#ffcc00; text-align:center; font-size:0.95rem; }

/* OVERLAY */
.overlay {
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.4); opacity:0; visibility:hidden; transition:0.3s; z-index:100;
}
.overlay.active { opacity:1; visibility:visible; }

/* MAIN CONTENT */
.container {
    max-width:900px; margin:60px auto; padding:35px 40px;
}
.hero-like {
    background:#fff; padding:35px 30px; border-radius:10px;
    box-shadow:0 4px 20px rgba(0,0,0,0.1); margin-bottom:40px;
}
.hero-like h2 { font-size:2rem; color:#004080; margin-bottom:15px; text-align:center; }
.hero-like a.back-link {
    display:inline-block; padding:12px 25px; background:#ffcc00;
    color:#333; border-radius:6px; text-decoration:none; font-weight:600; transition:0.3s;
}
.hero-like a.back-link:hover { background:#e6b800; }

/* BOXES */
.box {
    background:#fff; padding:25px; margin-bottom:25px; border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
.box h3 { font-size:1.3rem; margin-bottom:15px; color:#004080; display:flex; align-items:center; gap:8px; }

/* TABLE */
table { width:100%; border-collapse:collapse; font-size:0.95rem; }
th, td { border:1px solid #ccc; padding:10px; text-align:center; }
th { background:#004080; color:#fff; font-weight:600; }
tr:nth-child(even) td { background:#f9f9f9; }

/* BUTTONS */
button { padding:12px 25px; border:none; border-radius:8px; font-size:1rem; cursor:pointer; transition:0.3s; }
#backBtn { background:#ffcc00; color:#333; }
#backBtn:hover { background:#e6b800; }
#printBtn { background:#004080; color:#fff; }
#printBtn:hover { background:#003366; }

/* FOOTER */
footer { text-align:center; padding:20px; background:#444; color:#ddd; font-size:0.95rem; margin-top:40px; }

/* RESPONSIVE */
@media(max-width:768px){.container{padding:25px 20px;} header h1{font-size:2rem;}}
@media(max-width:600px){.hero-like h2{font-size:1.7rem;} table th, table td{font-size:0.85rem; padding:8px;} button{width:100%; margin-top:10px;}}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>CareerScope</h1>
  <p>Your Trusted Career Guidance</p>
</header>

<div class="sidebar" id="sidebar">
  <a href="index.php">Home</a>
  <a href="career-guidance.php">Career Guidance</a>
  <a href="careerpath.php">Career Path</a>
  <a href="about.php">About</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <?php if ($isLoggedIn): ?>
      <span class="user-info">👋 Welcome, <?= htmlspecialchars($fullName) ?></span>
      <a href="settings.php">Settings</a>
      <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
  <?php else: ?>
      <a href="login.php">Login</a>
  <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<div class="container">
  <section class="hero-like">
    <h2>Career Suggestions Based on Your Transcript</h2>
  </section>

  <div class="box">
    <h3>📄 All Subjects</h3>
    <table><thead><tr><th>Subject</th><th>Grade</th></tr></thead>
    <tbody id="rawTableBody"></tbody></table>
  </div>

  <div class="box">
    <h3>🧠 Skill Mapping</h3>
    <table><thead><tr><th>Skill</th><th>Level</th></tr></thead>
    <tbody id="skillsTableBody"></tbody></table>
  </div>

  <div class="box" id="suggestBox" style="display:none;">
    <h3>💡 Suggestions</h3>
    <ul id="suggestList"></ul>
  </div>

  <div class="box" id="careerMatchesBox" style="display:none;">
    <h3>🏆 Top Career Matches</h3>
    <ul id="careerMatchesList"></ul>
  </div>

  <div class="box" style="text-align:center;">
    <button id="backBtn">🔙 Back</button>
    <button id="printBtn">🖨️ Print Results</button>
  </div>
</div>

<footer>
  <p>&copy; <?= date("Y") ?> CareerScope. All rights reserved.</p>
</footer>

<script>
let rawSubjects   = <?= json_encode($rawSubjects) ?>;
let mappedSkills  = <?= json_encode($mappedSkills) ?>;
let careerOptions = <?= json_encode($careerOptions) ?>;

// Render Subjects
const rawTableBody = document.getElementById("rawTableBody");
if (Array.isArray(rawSubjects) && rawSubjects.length) {
  rawSubjects.forEach(([subject, grade]) => {
    rawTableBody.innerHTML += `<tr><td>${subject}</td><td>${grade}</td></tr>`;
  });
} else rawTableBody.innerHTML="<tr><td colspan='2'>No subjects detected.</td></tr>";

// Render Skills
const skillsTableBody = document.getElementById("skillsTableBody");
if (mappedSkills && Object.keys(mappedSkills).length) {
  Object.entries(mappedSkills).forEach(([skill, level])=>{
    skillsTableBody.innerHTML+=`<tr><td>${skill}</td><td>${level}</td></tr>`;
  });
} else skillsTableBody.innerHTML="<tr><td colspan='2'>No skills detected.</td></tr>";

// Render Career Matches + Suggestions
const careerBox = document.getElementById("careerMatchesBox");
const careerList = document.getElementById("careerMatchesList");
const suggestBox = document.getElementById("suggestBox");
const suggestList = document.getElementById("suggestList");

if (Array.isArray(careerOptions) && careerOptions.length) {
  careerBox.style.display = "block";
  suggestBox.style.display = "block";
  let suggestionSet = new Set();

  careerList.innerHTML = careerOptions.map(c=>{
    if(c.suggestion) suggestionSet.add(c.suggestion);
    return `<li><strong>${c.career}</strong> - Confidence: ${c.confidence||"N/A"}%<br><em>${c.suggestion||""}</em></li>`;
  }).join("");

  suggestList.innerHTML = [...suggestionSet].map(s=>`<li>${s}</li>`).join("");
}

// Back button
document.getElementById("backBtn").addEventListener("click",()=>window.history.back());
document.getElementById("printBtn").addEventListener("click",()=>window.print());

// Sidebar toggle
const hamburger = document.getElementById("hamburger");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

hamburger.addEventListener("click",()=>{
  hamburger.classList.toggle("active");
  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");
});
overlay.addEventListener("click",()=>{
  hamburger.classList.remove("active");
  sidebar.classList.remove("active");
  overlay.classList.remove("active");
});
</script>
</body>
</html>
