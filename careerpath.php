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
<title>Career Path Assessment - CareerScope</title>
<link rel="icon" type="image/x-icon" href="img/em.png">

<!-- ✅ Add PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<style>
  /* --- KEEPING YOUR EXACT UI --- */
  * {
    margin: 0; padding: 0; box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  body { background-color: #f4f4f4; color: #333; line-height: 1.6; display: flex; flex-direction: column; min-height: 100vh; }
  header {
    background: linear-gradient(135deg, #666, #888);
    color: white; text-align: center; padding: 25px 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2); position: relative;
  }
  header h1 { font-size: 2.4rem; margin-bottom: 8px; }
  header p { font-size: 1.05rem; opacity: 0.9; }
  .hamburger {
    position: absolute; top: 20px; left: 20px; width: 30px; height: 22px;
    display: flex; flex-direction: column; justify-content: space-between;
    cursor: pointer; z-index: 300;
  }
  .hamburger span { height: 4px; background: white; border-radius: 2px; }
  .sidebar {
    position: fixed; top: 0; left: -250px; width: 250px; height: 100%;
    background: #444; color: white; padding: 60px 20px; display: flex;
    flex-direction: column; gap: 20px; transition: left 0.3s ease; z-index: 200;
  }
  .sidebar.active { left: 0; }
  .sidebar a {
    color: white; text-decoration: none; font-size: 1.1rem;
    padding: 8px 0; transition: 0.3s;
  }
  .sidebar a:hover { color: #ffcc00; transform: translateX(5px); }
  .sidebar hr { border: 1px solid rgba(255,255,255,0.2); }
  .sidebar .user-info {
    margin-top: auto; font-size: 0.9rem; color: #ddd;
    text-align: center; border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 10px;
  }
  .overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.4); opacity: 0; visibility: hidden;
    transition: opacity 0.3s ease; z-index: 100;
  }
  .overlay.active { opacity: 1; visibility: visible; }
  main {
    flex: 1;
    max-width: 1000px; margin: 70px auto; background: #fff; padding: 40px 30px;
    border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }
  h2 { text-align: center; font-size: 2rem; color: #333; margin-bottom: 10px; }
  .intro-text { text-align: center; color: #666; margin-bottom: 25px; }
  form label { font-weight: 600; color: #333; display: block; margin-bottom: 8px; }
  input[type="file"] {
    width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;
    background: #f9f9f9; cursor: pointer;
  }
  button {
    background: #ffcc00; color: #333; border: none; border-radius: 6px;
    padding: 12px 20px; font-weight: 600; cursor: pointer; transition: 0.3s;
    margin-top: 12px;
  }
  button:hover { background: #e6b800; }
  .certificate-card {
    display: flex; align-items: center; gap: 10px; background: #f4f4f4;
    padding: 10px; border: 1px solid #ddd; margin: 8px 0; border-radius: 6px;
  }
  .remove-btn {
    background: #c0392b; color: white; border: none;
    padding: 6px 10px; border-radius: 6px; cursor: pointer;
  }
  .preview-container {
    margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px;
  }
  .preview-item {
    background: #f8f8f8; border: 1px solid #ddd; border-radius: 8px;
    padding: 8px; width: 120px; text-align: center;
  }
  .preview-item img { max-width: 100%; max-height: 100px; border-radius: 6px; }
  #resultBox {
    margin-top: 25px; padding: 15px; border-radius: 10px; background: #f7f7f7;
    border: 1px solid #ddd; font-size: 0.95rem; color: #333; max-height: 250px;
    overflow-y: auto;
  }
  .error { color: #c0392b; }

  /* ✅ FOOTER (added back) */
  footer {
    background: #444; color: #f1f1f1; text-align: center;
    padding: 15px 0; font-size: 0.95rem;
  }
  footer a {
    color: #ffcc00; text-decoration: none;
  }
  footer a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>

<div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>

<header>
  <h1>CareerScope</h1>
  <p>Upload your academic records and certificates to receive career insights</p>
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
    <div class="user-info">Logged in as<br><strong><?= htmlspecialchars($fullName) ?></strong></div>
  <?php else: ?>
    <a href="login.php">Login</a>
  <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<main>
  <h2>Career Path Assessment</h2>
  <p class="intro-text">Upload your COG and certificates to get personalized career recommendations.</p>

  <form id="careerForm" enctype="multipart/form-data">
    <label for="torInput">Academic Grades (Certificate of Grades Image or PDF):</label>
    <input type="file" id="torInput" name="torFile" accept="image/*,application/pdf">
    <div id="torPreview" class="preview-container"></div><br>

    <div id="certificatesSection">
      <label>Certificates:</label>
      <div id="certContainer"></div>
      <button type="button" id="addCertBtn">Add Certificate</button><br><br>
      <div id="certPreview" class="preview-container"></div>
    </div>

    <button type="button" id="submitTorBtn">Submit</button>
  </form>

  <div id="resultBox"></div>
</main>

<!-- ✅ Footer added -->
<footer>
  <p>&copy; <?= date("Y") ?> CareerScope | Empowering students with data-driven guidance |
    <a href="about.php">Learn More</a>
  </p>
</footer>

<script>
// Sidebar toggle
const hamburger=document.getElementById('hamburger');
const sidebar=document.getElementById('sidebar');
const overlay=document.getElementById('overlay');
hamburger.addEventListener('click',()=>{sidebar.classList.toggle('active');overlay.classList.toggle('active');});
overlay.addEventListener('click',()=>{sidebar.classList.remove('active');overlay.classList.remove('active');});

const isLoggedIn=<?php echo json_encode($isLoggedIn); ?>;
const torInput=document.getElementById("torInput");
const torPreview=document.getElementById("torPreview");
const certContainer=document.getElementById("certContainer");
const certPreview=document.getElementById("certPreview");
const addCertBtn=document.getElementById("addCertBtn");
const resultBox=document.getElementById("resultBox");
const submitButton=document.getElementById("submitTorBtn");

// ✅ Preview function
function previewFile(file,container){
  const item=document.createElement("div");
  item.className="preview-item";
  if(file.type.startsWith("image/")){
    const img=document.createElement("img");
    img.src=URL.createObjectURL(file);
    item.appendChild(img);
  }else{
    const icon=document.createElement("div");
    icon.className="preview-pdf";
    icon.textContent="PDF";
    item.appendChild(icon);
  }
  const label=document.createElement("p");
  label.textContent=file.name;
  item.appendChild(label);
  container.appendChild(item);
}

// ✅ Convert PDF to image(s)
async function pdfToImages(pdfFile){
  const pdf=await pdfjsLib.getDocument(URL.createObjectURL(pdfFile)).promise;
  const images=[];
  for(let i=1;i<=pdf.numPages;i++){
    const page=await pdf.getPage(i);
    const viewport=page.getViewport({scale:2});
    const canvas=document.createElement("canvas");
    const context=canvas.getContext("2d");
    canvas.width=viewport.width;
    canvas.height=viewport.height;
    await page.render({canvasContext:context,viewport}).promise;
    const blob=await new Promise(res=>canvas.toBlob(res,"image/png"));
    images.push(new File([blob],`${pdfFile.name.replace('.pdf','')}_page${i}.png`,{type:"image/png"}));
  }
  return images;
}

// Input preview
torInput.addEventListener("change",()=>{
  torPreview.innerHTML="";
  if(torInput.files[0]) previewFile(torInput.files[0],torPreview);
});

addCertBtn.addEventListener("click",()=>{
  const certDiv=document.createElement("div");
  certDiv.className="certificate-card";
  certDiv.innerHTML=`
    <input type="file" name="certificateFiles[]" accept="image/*,application/pdf">
    <button type="button" class="remove-btn">Remove</button>`;
  const fileInput=certDiv.querySelector("input");
  fileInput.addEventListener("change",()=>{
    if(fileInput.files[0]) previewFile(fileInput.files[0],certPreview);
  });
  certDiv.querySelector(".remove-btn").addEventListener("click",()=>certDiv.remove());
  certContainer.appendChild(certDiv);
});

// ✅ Submit
submitButton.addEventListener("click",async()=>{
  if(!isLoggedIn){alert("You must be logged in.");window.location.href="login.php";return;}
  const torFile=torInput.files[0];
  if(!torFile){alert("Please upload your COG (image or PDF).");return;}

  resultBox.innerHTML="<p class='progress'>Processing files...</p>";

  try{
    const formData=new FormData();

    // Convert TOR PDF → Images
    if(torFile.type==="application/pdf"){
      const imgs=await pdfToImages(torFile);
      imgs.forEach((img,i)=>formData.append("file",img,`tor_page${i+1}.png`));
    }else{
      formData.append("file",torFile);
    }

    // Certificates
    const certs=Array.from(document.querySelectorAll('input[name="certificateFiles[]"]')).map(i=>i.files[0]).filter(Boolean);
    for(const c of certs){
      if(c.type==="application/pdf"){
        const certImgs=await pdfToImages(c);
        certImgs.forEach((img,i)=>formData.append("certificateFiles[]",img,`${c.name}_page${i+1}.png`));
      }else{
        formData.append("certificateFiles[]",c);
      }
    }

    resultBox.innerHTML="<p class='progress'>Uploading...</p>";
    const res=await fetch("https://python-api-k98f.onrender.com/predict",{method:"POST",body:formData});
    if(!res.ok) throw new Error("API failed");
    const msg=await res.json();

    if(msg.error){resultBox.innerHTML=`<p class='error'>Error: ${msg.error}</p>`;return;}
    sessionStorage.setItem("apiResult",JSON.stringify(msg));
    resultBox.innerHTML="<p class='progress'>Done! Redirecting...</p>";
    setTimeout(()=>window.location.href="career-suggestions.php",1000);
  }catch(e){
    resultBox.innerHTML=`<p class='error'>${e.message}</p>`;
  }
});
</script>
</body>
</html>
