<?php
session_start();
include 'db_admin.php';

if (!isset($conn) || !$conn instanceof mysqli) {
    die("❌ Database connection not initialized. Check db_admin.php path or variable name.");
}

// ===== CRUD ===== //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_certificate'])) {
    $career_id  = trim($_POST['career_id']);
    $title      = trim($_POST['certificate_title']);
    $provider   = trim($_POST['provider']);
    $description= trim($_POST['description']);
    $skills     = trim($_POST['skills']);

    if ($career_id && $title && $provider) {
        $stmt = $conn->prepare("INSERT INTO certificates (career_id, certificate_title, provider, description, skills) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $career_id, $title, $provider, $description, $skills);
        $stmt->execute();
        $stmt->close();
        $message = "✅ New certificate added successfully.";
    } else $error = "⚠️ Please fill in all required fields.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = (int)$_POST['edit_id'];
    $career_id  = trim($_POST['career_id']);
    $title      = trim($_POST['certificate_title']);
    $provider   = trim($_POST['provider']);
    $description= trim($_POST['description']);
    $skills     = trim($_POST['skills']);
    if ($career_id && $title && $provider) {
        $stmt = $conn->prepare("UPDATE certificates SET career_id=?, certificate_title=?, provider=?, description=?, skills=? WHERE id=?");
        $stmt->bind_param("sssssi", $career_id, $title, $provider, $description, $skills, $id);
        $stmt->execute();
        $stmt->close();
        $message = "✏️ Certificate ID $id updated successfully.";
    } else $error = "⚠️ Please fill in all required fields.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM certificates WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $message = "🗑️ Certificate deleted successfully.";
}

// ===== Pagination & Filter ===== //
$filterCareerId = $_GET['career_id'] ?? '';
$where = $filterCareerId ? "WHERE career_id=?" : "";
$limit = 5;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// count total
if ($where) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM certificates $where");
    $stmt->bind_param("s", $filterCareerId);
    $stmt->execute();
    $stmt->bind_result($totalRows);
    $stmt->fetch();
    $stmt->close();
} else {
    $res = $conn->query("SELECT COUNT(*) AS c FROM certificates");
    $totalRows = (int)$res->fetch_assoc()['c'];
}
$totalPages = max(1, ceil($totalRows / $limit));

// data fetch
if ($where) {
    $stmt = $conn->prepare("SELECT * FROM certificates $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
    $stmt->bind_param("s", $filterCareerId);
} else {
    $stmt = $conn->prepare("SELECT * FROM certificates ORDER BY id DESC LIMIT $limit OFFSET $offset");
}
$stmt->execute();
$res = $stmt->get_result();
$certs = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$careerIds = [];
$q = $conn->query("SELECT DISTINCT career_id FROM certificates ORDER BY career_id");
while ($r = $q->fetch_assoc()) $careerIds[] = $r['career_id'];
$q->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CareerScope Admin | Certificates</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Segoe UI',Tahoma,Verdana,sans-serif; background:#f4f4f4; color:#333; }

/* HEADER */
header { background: linear-gradient(135deg,#666,#888); color:white; text-align:center; padding:25px 0; box-shadow:0 4px 12px rgba(0,0,0,0.2); position:relative; }
header h1 { font-size:2rem; margin-bottom:5px; }
header p { opacity:.9; font-size:1rem; }

/* SIDEBAR + HAMBURGER */
.hamburger { position:absolute; top:20px; left:20px; width:30px; height:22px; display:flex; flex-direction:column; justify-content:space-between; cursor:pointer; z-index:300; }
.hamburger span { height:4px; background:white; border-radius:2px; }
.sidebar { position:fixed; top:0; left:-250px; width:250px; height:100%; background:#444; color:white; padding:60px 20px; display:flex; flex-direction:column; gap:20px; transition:0.3s; z-index:200; }
.sidebar.active { left:0; }
.sidebar a { color:white; text-decoration:none; font-size:1.1rem; transition:.3s; }
.sidebar a:hover, .sidebar a.active { color:#ffcc00; transform:translateX(5px); }
.overlay { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.4); opacity:0; visibility:hidden; transition:.3s; z-index:100; }
.overlay.active { opacity:1; visibility:visible; }

/* CONTAINER */
.container { max-width:1100px; margin:60px auto; padding:40px 30px; background:white; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); }
.container h2 { font-size:1.8rem; color:#333; margin-bottom:10px; }
.container p { color:#666; margin-bottom:25px; }

/* ALERTS */
.message { padding:12px 15px; border-radius:8px; margin-bottom:20px; font-weight:500; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }

/* BUTTONS */
.btn { padding:10px 16px; border:none; border-radius:6px; cursor:pointer; font-weight:500; transition:.2s; }
.btn-add { background:#444; color:white; margin-bottom:15px; }
.btn-add:hover { background:#555; }
.btn-edit { background:#0066cc; color:white; }
.btn-edit:hover { background:#005bb5; }
.btn-save { background:#28a745; color:white; display:none; }
.btn-save:hover { background:#218838; }
.btn-cancel { background:#6c757d; color:white; display:none; }
.btn-cancel:hover { background:#5a6268; }
.btn-delete { background:#e74c3c; color:white; }
.btn-delete:hover { background:#c0392b; }

/* TABLE */
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { padding:12px; border:1px solid #ddd; text-align:left; vertical-align:top; }
th { background:#555; color:white; }
td input, td textarea { width:100%; border:1px solid #ccc; border-radius:5px; padding:6px; }
td textarea { height:60px; resize:vertical; }

/* FILTER + PAGINATION */
.filter-box { margin-bottom:15px; display:flex; flex-wrap:wrap; align-items:center; gap:10px; }
.filter-box select { padding:6px 10px; border-radius:6px; border:1px solid #ccc; }
.pagination { text-align:center; margin-top:25px; }
.pagination a { margin:0 5px; padding:6px 12px; border:1px solid #ccc; border-radius:4px; text-decoration:none; color:#333; transition:.2s; }
.pagination a:hover { background:#eee; }
.pagination a.active { background:#444; color:white; border-color:#444; }

/* FOOTER */
footer { text-align:center; padding:20px; background:#444; color:#ddd; margin-top:60px; }
footer a { color:#ffcc00; text-decoration:none; }
footer a:hover { text-decoration:underline; }

@media (max-width:768px) {
  header h1 { font-size:1.6rem; }
  .container { padding:25px 15px; }
  table, td, th { font-size:0.95rem; }
}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>Career Certificates</h1>
  <p>Manage and update professional certifications</p>
</header>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php">User Management</a>
  <a href="admin-content.php">Career Content</a>
  <a href="admin-certificates.php" class="active">Certificates</a>
  <a href="admin-roadmaps.php">Career Roadmaps</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>
<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>Certificates Management</h2>
  <?php if(!empty($message)): ?><div class="message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if(!empty($error)): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <button class="btn btn-add" onclick="openModal()">+ Add Certificate</button>

  <div class="filter-box">
    <form method="GET">
      <label for="career_id">Filter by Career ID:</label>
      <select name="career_id" id="career_id" onchange="this.form.submit()">
        <option value="">-- All --</option>
        <?php foreach($careerIds as $cid): ?>
          <option value="<?= htmlspecialchars($cid) ?>" <?= $cid==$filterCareerId?'selected':'' ?>><?= htmlspecialchars($cid) ?></option>
        <?php endforeach; ?>
      </select>
      <div style="margin-left:auto;color:#666;font-size:.95rem;">Showing <?= (int)$totalRows ?> result(s)</div>
    </form>
  </div>

  <table>
    <thead>
      <tr><th>ID</th><th>Career ID</th><th>Title</th><th>Provider</th><th>Description</th><th>Skills</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach($certs as $c): ?>
      <tr>
        <form method="POST">
          <td><?= $c['id'] ?><input type="hidden" name="edit_id" value="<?= $c['id'] ?>"></td>
          <td><input name="career_id" value="<?= htmlspecialchars($c['career_id']) ?>" disabled></td>
          <td><input name="certificate_title" value="<?= htmlspecialchars($c['certificate_title']) ?>" disabled></td>
          <td><input name="provider" value="<?= htmlspecialchars($c['provider']) ?>" disabled></td>
          <td><textarea name="description" disabled><?= htmlspecialchars($c['description']) ?></textarea></td>
          <td><textarea name="skills" disabled><?= htmlspecialchars($c['skills']) ?></textarea></td>
          <td>
            <button type="button" class="btn btn-edit" onclick="enableEdit(this)">Edit</button>
            <button type="submit" class="btn btn-save">Save</button>
            <button type="button" class="btn btn-cancel" onclick="cancelEdit(this)">Cancel</button>
        </form>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
          <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this certificate?')">Delete</button>
        </form>
          </td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($certs)): ?><tr><td colspan="7" style="text-align:center;color:#666;padding:20px;">No certificates found.</td></tr><?php endif; ?>
    </tbody>
  </table>

  <div class="pagination">
    <?php $base = $filterCareerId ? '&career_id='.urlencode($filterCareerId) : '';
      for($i=1;$i<=$totalPages;$i++): ?>
      <a href="?page=<?= $i.$base ?>" class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
</div>

<footer>
  <p>&copy; <?= date("Y") ?> CareerScope | Empowering students with data-driven guidance | <a href="about.php">Learn More</a></p>
</footer>

<script>
const hamburger=document.getElementById('hamburger');
const sidebar=document.getElementById('sidebar');
const overlay=document.getElementById('overlay');
hamburger.onclick=()=>{sidebar.classList.toggle('active');overlay.classList.toggle('active');};
overlay.onclick=()=>{sidebar.classList.remove('active');overlay.classList.remove('active');};

function openModal(){alert("Modal for adding certificate (use your existing modal)");}

function enableEdit(btn){
  let row=btn.closest('tr');
  row.querySelectorAll('input,textarea').forEach(i=>i.disabled=false);
  row.querySelector('.btn-edit').style.display='none';
  row.querySelector('.btn-save').style.display='inline-block';
  row.querySelector('.btn-cancel').style.display='inline-block';
}

function cancelEdit(btn){
  let row=btn.closest('tr');
  row.querySelectorAll('input,textarea').forEach(i=>i.disabled=true);
  row.querySelector('.btn-edit').style.display='inline-block';
  row.querySelector('.btn-save').style.display='none';
  row.querySelector('.btn-cancel').style.display='none';
}
</script>

</body>
</html>
