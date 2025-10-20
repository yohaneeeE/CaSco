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
<title>Career Suggestions | CareerScope</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f4f4f4;
  color: #333;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* HEADER */
header {
  background: linear-gradient(135deg, #666, #888);
  color: #fff;
  text-align: center;
  padding: 25px 0;
  position: relative;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
header h1 { margin: 0; font-size: 2.4rem; }
header p { font-size: 1.05rem; opacity: 0.9; }

/* HAMBURGER */
.hamburger {
  position: absolute; top: 20px; left: 20px;
  width: 30px; height: 22px;
  display: flex; flex-direction: column; justify-content: space-between;
  cursor: pointer; z-index: 1100;
}
.hamburger span {
  height: 4px; background: white; border-radius: 2px;
}

/* SIDEBAR */
.sidebar {
  position: fixed; top: 0; left: -250px;
  width: 250px; height: 100%;
  background: #444; color: white;
  padding: 60px 20px;
  display: flex; flex-direction: column; gap: 20px;
  transition: left 0.3s ease;
  z-index: 1000;
}
.sidebar.active { left: 0; }
.sidebar a {
  color: white;
  text-decoration: none;
  font-size: 1.1rem;
  padding: 8px 0;
  transition: 0.3s;
}
.sidebar a:hover { color: #ffcc00; transform: translateX(5px); }
.sidebar hr { border: 1px solid rgba(255,255,255,0.2); }
.sidebar .user-info {
  margin-top: auto;
  font-size: 0.9rem;
  color: #ddd;
  text-align: center;
  border-top: 1px solid rgba(255,255,255,0.2);
  padding-top: 10px;
}

/* OVERLAY */
.overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  opacity: 0; visibility: hidden;
  transition: opacity 0.3s ease;
  z-index: 900;
}
.overlay.active { opacity: 1; visibility: visible; }

/* MAIN CONTAINER */
.container {
  flex: 1;
  max-width: 1100px;
  margin: 40px auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
  padding: 40px;
}

/* TITLE */
h2 {
  text-align: center;
  color: #333;
  font-size: 2rem;
  margin-bottom: 25px;
  position: relative;
}
h2::after {
  content: '';
  position: absolute;
  bottom: -10px; left: 50%;
  transform: translateX(-50%);
  width: 90px; height: 3px;
  background: linear-gradient(90deg, #666, #ffcc00);
  border-radius: 3px;
}

/* BOXES */
.box {
  background: #f8f8f8;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 25px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.box h3 {
  color: #333;
  margin-bottom: 15px;
  font-size: 1.3rem;
  display: flex; align-items: center; gap: 8px;
}

/* TABLE */
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.95rem;
}
th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}
th {
  background: #333;
  color: #fff;
  font-weight: 600;
}
tr:nth-child(even) td { background: #f9f9f9; }

/* BUTTONS */
button {
  padding: 12px 25px;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  cursor: pointer;
  transition: 0.3s;
  margin: 5px;
}
#backBtn, #printBtn {
  background: #333;
  color: #ffcc00;
}
#backBtn:hover, #printBtn:hover {
  background: #555;
}

/* FOOTER */
footer {
  text-align: center;
  padding: 20px;
  background: #444;
  color: #ddd;
  margin-top: auto;
  font-size: 0.95rem;
}
footer a {
  color: #ffcc00;
  text-decoration: none;
}
footer a:hover { text-decoration: underline; }

@media (max-width: 768px) {
  .container { padding: 25px; margin: 20px; }
  header h1 { font-size: 2rem; }
  button { width: 100%; margin-top: 10px; }
}
</style>
</head>
<body>

<div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>

<header>
  <h1>CareerScope</h1>
  <p>Your Digital Career Guidance Assistant</p>
</header>

<div class="sidebar" id="sidebar">
  <a href="index.php">Home</a>
  <a href="career-guidance.php">Career Guidance</a>
  <a href="careerpath.php" class="active">Career Path</a>
  <a href="about.php">About</a>
  <hr>
  <?php if ($isLoggedIn): ?>
    <a href="settings.php">Settings</a>
    <a href="logout.php" onclick="return confirm('Logout now?');">Logout</a>
    <div class="user-info">
      Logged in as <br><strong><?= htmlspecialchars($fullName) ?></strong>
    </div>
  <?php else: ?>
    <a href="login.php">Login</a>
  <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>Career Suggestions Based on Your Transcript</h2>

  <div class="box">
    <h3>📄 All Subjects</h3>
    <table><thead><tr><th>Subject</th><th>Grade</th></tr></thead><tbody id="rawTableBody"></tbody></table>
  </div>

  <div class="box">
    <h3>🧠 Skill Mapping</h3>
    <table><thead><tr><th>Skill</th><th>Level</th></tr></thead><tbody id="skillsTableBody"></tbody></table>
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
    <button id="backBtn">⬅️ Back</button>
    <button id="printBtn">🖨️ Print Results</button>
  </div>
</div>

<footer>
  <p>&copy; <?= date("Y") ?> CareerScope | Empowering students with data-driven guidance |
    <a href="about.php">Learn More</a>
  </p>
</footer>

<script>
let rawSubjects   = <?= json_encode($rawSubjects) ?>;
let mappedSkills  = <?= json_encode($mappedSkills) ?>;
let careerOptions = <?= json_encode($careerOptions) ?>;
let certificates  = <?= json_encode($certificates) ?>;

if ((!rawSubjects || rawSubjects.length === 0) && sessionStorage.apiResult) {
  const apiResult = JSON.parse(sessionStorage.apiResult);
  rawSubjects   = apiResult.rawSubjects   || [];
  mappedSkills  = apiResult.mappedSkills  || {};
  careerOptions = apiResult.careerOptions || [];
}

// Render Subjects
const rawTableBody = document.getElementById("rawTableBody");
if (Array.isArray(rawSubjects) && rawSubjects.length > 0) {
  rawSubjects.forEach(([subject, grade]) => {
    rawTableBody.innerHTML += `<tr><td>${subject}</td><td>${grade}</td></tr>`;
  });
} else {
  rawTableBody.innerHTML = "<tr><td colspan='2'>No subjects detected.</td></tr>";
}

// Render Skills
const skillsTableBody = document.getElementById("skillsTableBody");
if (Object.keys(mappedSkills).length > 0) {
  for (const [skill, level] of Object.entries(mappedSkills)) {
    skillsTableBody.innerHTML += `<tr><td>${skill}</td><td>${level}</td></tr>`;
  }
} else {
  skillsTableBody.innerHTML = "<tr><td colspan='2'>No skills detected.</td></tr>";
}

// Render Career Matches + Suggestions
const careerBox = document.getElementById("careerMatchesBox");
const careerList = document.getElementById("careerMatchesList");
const suggestBox = document.getElementById("suggestBox");
const suggestList = document.getElementById("suggestList");

if (Array.isArray(careerOptions) && careerOptions.length > 0) {
  careerBox.style.display = "block";
  suggestBox.style.display = "block";
  let suggestionSet = new Set();

  careerList.innerHTML = careerOptions.map(c => {
    if (c.suggestion) suggestionSet.add(c.suggestion);
    return `<li><strong>${c.career}</strong> - Confidence: ${c.confidence || "N/A"}%<br><em>${c.suggestion || ""}</em></li>`;
  }).join("");

  suggestList.innerHTML = [...suggestionSet].map(s => `<li>${s}</li>`).join("");
}

// Back button
document.getElementById("backBtn").addEventListener("click", () => {
  window.location.href = "careerpath.php";
});

// Print button
document.getElementById("printBtn").addEventListener("click", () => window.print());

// Sidebar toggle
const hamburger = document.getElementById("hamburger");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

hamburger.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");
});
overlay.addEventListener("click", () => {
  sidebar.classList.remove("active");
  overlay.classList.remove("active");
});
</script>
</body>
</html>
