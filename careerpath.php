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
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Career Path Assessment - eMentor</title>
<link rel="icon" type="image/x-icon" href="img/em.png">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<style>
/* ===== RESET ===== */
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f2f2f2;
  color: #333;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
/* ===== HEADER ===== */
header {
  background: linear-gradient(135deg, #444, #666);
  color: #fff;
  padding: 15px 10px;
  text-align: center;
  position: relative;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
header h1 { font-size: 1.7rem; margin-bottom: 5px; }
header p { font-size: 0.95rem; opacity: 0.9; }
/* ===== HAMBURGER ===== */
.hamburger {
  position: absolute;
  top: 20px; left: 20px;
  width: 28px; height: 22px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  cursor: pointer;
  z-index: 300;
  transition: transform 0.3s ease;
}
.hamburger span {
  height: 4px; background: white;
  border-radius: 2px; transition: all 0.3s ease;
}
.hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
.hamburger.active span:nth-child(2) { opacity: 0; }
.hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px, -6px); }
/* ===== SIDEBAR ===== */
.sidebar {
  position: fixed;
  top: 0; left: -250px;
  width: 250px; height: 100%;
  background: #222; color: white;
  padding: 60px 20px;
  display: flex; flex-direction: column;
  gap: 15px;
  transition: left 0.3s ease;
  z-index: 500;
}
.sidebar.active { left: 0; }
.sidebar a {
  color: white; text-decoration: none;
  font-size: 1rem; padding: 6px 0;
  transition: 0.2s;
}
.sidebar a:hover { color: #ffcc00; transform: translateX(4px); }
.sidebar hr { border: 1px solid rgba(255,255,255,0.2); }
.sidebar .user-info {
  margin-top: auto; padding-top: 10px;
  border-top: 1px solid rgba(255,255,255,0.2);
  color: #ffcc00; font-size: 0.9rem; text-align: center;
}
/* ===== OVERLAY ===== */
.overlay {
  position: fixed; top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  opacity: 0; visibility: hidden;
  transition: opacity 0.3s ease;
  z-index: 400;
}
.overlay.active { opacity: 1; visibility: visible; }
/* ===== MAIN CONTENT ===== */
.container {
  width: 95%; max-width: 800px;
  margin: 20px auto; padding: 20px;
  background: #fff; border-radius: 12px;
  box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}
h2 { text-align: center; margin-bottom: 15px; color: #333; }
label {
  font-weight: bold; display: block;
  margin-top: 10px; margin-bottom: 5px;
}
input[type="file"] {
  width: 100%; padding: 10px;
  border-radius: 6px; border: 1px solid #ccc;
}
button {
  padding: 10px 16px; border: none; border-radius: 8px;
  background: #ffcc00; font-weight: bold;
  cursor: pointer; transition: background 0.3s;
}
button:hover { background: #ffd633; }
.preview-container {
  display: flex; flex-wrap: wrap;
  gap: 8px; margin-top: 10px;
}
.preview-item {
  border: 1px solid #ddd; border-radius: 8px;
  background: #fff; text-align: center;
  padding: 6px; width: 110px; font-size: 0.85rem;
}
.preview-item img {
  max-width: 100%; max-height: 90px; border-radius: 4px;
}
.preview-pdf { font-size: 1.5rem; color: #d32f2f; }
#resultBox {
  margin-top: 20px; padding: 12px; border-radius: 6px;
  background: #f9f9f9; color: #333;
  font-size: 0.95rem; overflow-y: auto;
}
/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  header h1 { font-size: 1.4rem; }
  header p { font-size: 0.85rem; padding: 0 10px; }
  .container { width: 95%; margin: 15px auto; padding: 15px; }
  .preview-item { width: 45%; }
  .sidebar { width: 70%; padding: 50px 15px; }
}
@media (max-width: 480px) {
  .hamburger { top: 15px; left: 15px; width: 26px; }
  button { width: 100%; margin-top: 10px; }
  .preview-item { width: 100%; }
}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>eMentor</h1>
  <p>Upload your Academic Grades & Certificates to get personalized career recommendations</p>
</header>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
  <a href="dashboard.php">Home</a>
  <a href="career-guidance.php">Career Guidance</a>
  <a href="careerpath.php" class="active">Career Path</a>
  <a href="about.php">About</a>
  <hr>
  <?php if ($isLoggedIn): ?>
      <a href="settings.php">Settings</a>
      <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
      <div class="user-info">Logged in as <br><strong><?= htmlspecialchars($fullName) ?></strong></div>
  <?php else: ?>
      <a href="login.php">Login</a>
  <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<!-- MAIN CONTENT -->
<div class="container">
  <h2>Career Path Assessment</h2>
  <form id="careerForm" enctype="multipart/form-data">
    <label for="torInput">Upload TOR (Image or PDF):</label>
    <input type="file" id="torInput" name="torFile" accept="image/*,.pdf">
    <div id="torPreview" class="preview-container"></div>

    <label>Certificates:</label>
    <div id="certContainer"></div>
    <button type="button" id="addCertBtn">Add Certificate</button>
    <div id="certPreview" class="preview-container"></div>

    <button type="button" id="submitTorBtn">Submit</button>
  </form>
  <div id="resultBox"></div>
</div>

<script>
// Sidebar toggle
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

// ---- Enhanced PDF-to-Image Conversion ----
async function pdfToImages(file) {
  const pdfData = await file.arrayBuffer();
  const pdfDoc = await pdfjsLib.getDocument({ data: pdfData }).promise;
  const images = [];
  for (let i = 1; i <= pdfDoc.numPages; i++) {
    const page = await pdfDoc.getPage(i);
    const viewport = page.getViewport({ scale: 3 }); // higher DPI for OCR clarity
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");
    canvas.width = viewport.width;
    canvas.height = viewport.height;
    await page.render({ canvasContext: context, viewport }).promise;
    const blob = await new Promise(resolve => canvas.toBlob(resolve, "image/png", 1.0));
    images.push(new File([blob], `${file.name}-page${i}.png`, { type: "image/png" }));
    // free memory after each page
    canvas.width = 0;
    canvas.height = 0;
  }
  return images;
}

// ---- Preview Files ----
function previewFile(file, container) {
  const item = document.createElement("div");
  item.className = "preview-item";
  if (file.type.startsWith("image/")) {
    const img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    item.appendChild(img);
  } else {
    const icon = document.createElement("div");
    icon.className = "preview-pdf";
    icon.textContent = "PDF";
    item.appendChild(icon);
  }
  const label = document.createElement("p");
  label.textContent = file.name;
  item.appendChild(label);
  container.appendChild(item);
}

// ---- File Input Setup ----
const torInput = document.getElementById("torInput"),
      torPreview = document.getElementById("torPreview");
torInput.addEventListener("change", () => {
  torPreview.innerHTML = "";
  if (torInput.files[0]) previewFile(torInput.files[0], torPreview);
});

const addCertBtn = document.getElementById("addCertBtn"),
      certContainer = document.getElementById("certContainer"),
      certPreview = document.getElementById("certPreview");

addCertBtn.addEventListener("click", () => {
  const div = document.createElement("div");
  div.innerHTML = `<input type="file" name="certificateFiles[]" accept="image/*,.pdf">
                   <button type="button" class="remove-btn">Remove</button>`;
  const input = div.querySelector("input");
  input.addEventListener("change", () => { if (input.files[0]) previewFile(input.files[0], certPreview); });
  div.querySelector(".remove-btn").addEventListener("click", () => div.remove());
  certContainer.appendChild(div);
});

// ---- Submit & Upload ----
document.getElementById("submitTorBtn").addEventListener("click", async () => {
  const file = torInput.files[0];
  if (!file) { alert("Please upload a TOR."); return; }

  const formData = new FormData();

  // Convert TOR PDF to images
  if (file.type === "application/pdf") {
    const images = await pdfToImages(file);
    images.forEach(img => formData.append("file", img));
  } else {
    formData.append("file", file);
  }

  // Handle certificates (convert PDFs to images too)
  const certInputs = document.querySelectorAll('input[name="certificateFiles[]"]');
  for (const input of certInputs) {
    if (input.files[0]) {
      const certFile = input.files[0];
      if (certFile.type === "application/pdf") {
        const imgs = await pdfToImages(certFile);
        imgs.forEach(img => formData.append("certificateFiles[]", img));
      } else {
        formData.append("certificateFiles[]", certFile);
      }
    }
  }

  document.getElementById("resultBox").innerHTML = "<p>Uploading & processing...</p>";
  try {
    const response = await fetch("https://python-api-k98f.onrender.com/predict", { method: "POST", body: formData });
    const msg = await response.json();
    if (msg.error) {
      document.getElementById("resultBox").innerHTML = `<p style='color:red'>Error: ${msg.error}</p>`;
      return;
    }
    sessionStorage.setItem("apiResult", JSON.stringify(msg));
    document.getElementById("resultBox").innerHTML = "<p>Done! Redirecting...</p>";
    setTimeout(() => window.location.href = "career-suggestions.php", 1000);
  } catch (err) {
    document.getElementById("resultBox").innerHTML = `<p style='color:red'>Network error: ${err.message}</p>`;
  }
});
</script>
</body>
</html>
