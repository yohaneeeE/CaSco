<?php
// ======================================
// ✅ Database Connection Setup
// ======================================
include 'db_admin.php';

// Build PDO connection details from mysqli vars (Hostinger compatible)
if (isset($servername, $dbusername, $dbpassword, $dbname)) {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $user = $dbusername;
    $pass = $dbpassword;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
} else {
    die("❌ Database credentials missing. Please check db_admin.php.");
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// ======================================
// ✅ Add Certificate
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_certificate'])) {
    $career_id = $_POST['career_id'] ?? '';
    $title = $_POST['certificate_title'] ?? '';
    $provider = $_POST['provider'] ?? '';
    $description = $_POST['description'] ?? '';
    $skills = $_POST['skills'] ?? '';

    if ($career_id && $title && $provider) {
        $stmt = $pdo->prepare("INSERT INTO certificates (career_id, certificate_title, provider, description, skills) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$career_id, $title, $provider, $description, $skills]);
        $message = "✅ New certificate added successfully.";
    } else {
        $error = "⚠️ Please fill in all required fields.";
    }
}

// ======================================
// ✅ Edit Certificate
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = (int)$_POST['edit_id'];
    $career_id = $_POST['career_id'] ?? '';
    $title = $_POST['certificate_title'] ?? '';
    $provider = $_POST['provider'] ?? '';
    $description = $_POST['description'] ?? '';
    $skills = $_POST['skills'] ?? '';

    if ($career_id && $title && $provider) {
        $stmt = $pdo->prepare("UPDATE certificates SET career_id=?, certificate_title=?, provider=?, description=?, skills=? WHERE id=?");
        $stmt->execute([$career_id, $title, $provider, $description, $skills, $id]);
        $message = "✏️ Certificate ID $id updated successfully.";
    } else {
        $error = "⚠️ Please fill in all required fields.";
    }
}

// ======================================
// ✅ Delete Certificate
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM certificates WHERE id=?");
    $stmt->execute([$id]);
    $message = "🗑️ Certificate ID $id deleted successfully.";
}

// ======================================
// ✅ Filtering + Pagination
// ======================================
$filterCareerId = $_GET['career_id'] ?? '';
$whereClause = "";
$params = [];

if ($filterCareerId !== '') {
    $whereClause = "WHERE career_id = ?";
    $params[] = $filterCareerId;
}

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM certificates $whereClause");
$countStmt->execute($params);
$totalRows = $countStmt->fetchColumn();
$totalPages = $totalRows > 0 ? ceil($totalRows / $limit) : 1;

$sql = "SELECT * FROM certificates $whereClause ORDER BY id DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$certs = $stmt->fetchAll();

$careerStmt = $pdo->query("SELECT DISTINCT career_id FROM certificates ORDER BY career_id ASC");
$careerIds = $careerStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate Management | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { margin:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f4f4f4; }
header { background: linear-gradient(135deg, #2c2c2c,#444); color:#fff; padding:20px; text-align:center; position:relative; }
header h1 { margin:0; font-size:1.8rem; }

.container { max-width:1200px; margin:20px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 3px 12px rgba(0,0,0,0.1); box-sizing:border-box; }

h2 { margin-top:0; color:#333; }

table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { padding:10px; border:1px solid #ddd; text-align:left; vertical-align:top; }
th { background:#555; color:#fff; }

.btn { padding:6px 12px; border:none; border-radius:5px; cursor:pointer; }
.btn-edit { background:#0066cc;color:#fff; }
.btn-save { background:#28a745;color:#fff; display:none; }
.btn-cancel { background:#6c757d;color:#fff; display:none; }
.btn-delete { background:#e74c3c;color:#fff; }
.btn-add { background:#444;color:#fff;margin-bottom:15px;padding:10px 16px;border-radius:6px; }

.filter-box { margin-bottom:15px; display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
.filter-box label { font-weight:600; color:#333; }

.pagination { margin-top:15px; text-align:center; }
.pagination a { margin:0 5px; padding:6px 12px; border:1px solid #ccc; text-decoration:none; color:#333; border-radius:4px; }
.pagination a.active { background:#444; color:#fff; border-color:#444; }

.sidebar { height:100vh; width:250px; position:fixed; top:0; left:-250px; background:#2c2c2c; color:#fff; padding-top:60px; transition:0.3s; overflow:auto; z-index:1000; }
.sidebar a { display:block; padding:12px 20px; color:#fff; text-decoration:none; }
.sidebar a:hover { background:#444; }
.open-btn { font-size:24px; cursor:pointer; background:none; border:none; color:#fff; position:absolute; left:20px; top:20px; z-index:1100; }

.modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background:rgba(0,0,0,0.5); }
.modal-content { background:#fff; margin:6% auto; padding:20px; border-radius:10px; width:420px; max-width:90%; box-shadow:0 5px 15px rgba(0,0,0,0.3); }
.modal-content h3 { margin-top:0; }
.close { color:#aaa; float:right; font-size:24px; font-weight:bold; cursor:pointer; }
.close:hover { color:#000; }
input[type=text], textarea, select { width:100%; padding:8px; margin:6px 0; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; }
.message { padding:10px; margin:15px 0; border-radius:5px; }
.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }

@media(max-width:768px){
  header h1{ font-size:1.5rem; padding:10px;}
  .container{ width:100%; margin:10px; padding:15px; box-sizing:border-box;}
  table, thead, tbody, th, td, tr{ display:block; }
  thead{ display:none; }
  tr{ margin-bottom:15px; border:1px solid #ddd; border-radius:8px; padding:10px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.05);}
  td{ border:none; display:flex; justify-content:space-between; padding:8px 0; }
  td::before{ content: attr(data-label); font-weight:bold; color:#333; flex-basis:40%; }
  .filter-box{ flex-direction:column; align-items:stretch; gap:8px; }
  .btn-add{ width:100%; margin-top:10px; }
  .open-btn{ top:15px; left:15px; }
  .sidebar{ width:220px; }
}
</style>
</head>
<body>

<header><h1>Certificate Management</h1></header>

<div id="sidebar" class="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php">Users</a>
  <a href="admin-content.php">Career Content</a>
  <a href="admin-certificates.php" class="active">Certificates</a>
  <a href="admin-roadmaps.php">Roadmaps</a>
  <a href="logout.php">Logout</a>
</div>

<button class="open-btn" onclick="toggleSidebar()">☰</button>

<div class="container">
  <h2>Certificates</h2>

  <?php if(!empty($message)): ?><div class="message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if(!empty($error)): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <button class="btn-add" onclick="openModal()">+ Add Certificate</button>

  <div id="addModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Add New Certificate</h3>
      <form method="POST">
        <input type="hidden" name="add_certificate" value="1">
        <input type="text" name="career_id" placeholder="Career ID" required>
        <input type="text" name="certificate_title" placeholder="Certificate Title" required>
        <input type="text" name="provider" placeholder="Provider" required>
        <textarea name="description" placeholder="Description"></textarea>
        <textarea name="skills" placeholder="Skills"></textarea>
        <div style="text-align:right;">
          <button type="button" class="btn" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-save">Add</button>
        </div>
      </form>
    </div>
  </div>

  <div class="filter-box">
    <form method="GET">
      <label for="career_id">Filter by Career ID:</label>
      <select name="career_id" id="career_id" onchange="this.form.submit()">
        <option value="">-- All --</option>
        <?php foreach($careerIds as $cid): ?>
          <option value="<?= htmlspecialchars($cid) ?>" <?= $cid==$filterCareerId?'selected':'' ?>><?= htmlspecialchars($cid) ?></option>
        <?php endforeach; ?>
      </select>
      <div style="margin-left:auto; font-size:0.95rem; color:#666;">Showing <?= (int)$totalRows ?> result(s)</div>
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
          <td><?= (int)$c['id'] ?><input type="hidden" name="edit_id" value="<?= (int)$c['id'] ?>"></td>
          <td><input type="text" name="career_id" value="<?= htmlspecialchars($c['career_id']) ?>" disabled></td>
          <td><input type="text" name="certificate_title" value="<?= htmlspecialchars($c['certificate_title']) ?>" disabled></td>
          <td><input type="text" name="provider" value="<?= htmlspecialchars($c['provider']) ?>" disabled></td>
          <td><textarea name="description" disabled><?= htmlspecialchars($c['description']) ?></textarea></td>
          <td><textarea name="skills" disabled><?= htmlspecialchars($c['skills']) ?></textarea></td>
          <td>
            <button type="button" class="btn btn-edit" onclick="enableEdit(this)">Edit</button>
            <button type="submit" class="btn btn-save">Save</button>
            <button type="button" class="btn btn-cancel" onclick="cancelEdit(this)">Cancel</button>
        </form>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="delete_id" value="<?= (int)$c['id'] ?>">
          <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this certificate?')">Delete</button>
        </form>
          </td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($certs)): ?>
        <tr><td colspan="7" style="text-align:center;color:#666;padding:20px;">No certificates found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="pagination">
    <?php
      $baseParam = $filterCareerId!=='' ? '&career_id='.urlencode($filterCareerId) : '';
      for($i=1;$i<=$totalPages;$i++):
    ?>
      <a href="?page=<?= $i.$baseParam ?>" class="<?= ($i==$page)?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
</div>

<script>
function openModal(){document.getElementById('addModal').style.display='block';}
function closeModal(){document.getElementById('addModal').style.display='none';}
function enableEdit(btn){
  const row=btn.closest('tr');
  row.querySelectorAll('input,textarea').forEach(el=>el.disabled=false);
  row.querySelector('.btn-edit').style.display='none';
  row.querySelector('.btn-save').style.display='inline-block';
  row.querySelector('.btn-cancel').style.display='inline-block';
}
function cancelEdit(btn){
  const row=btn.closest('tr');
  row.querySelectorAll('input,textarea').forEach(el=>el.disabled=true);
  row.querySelector('.btn-edit').style.display='inline-block';
  row.querySelector('.btn-save').style.display='none';
  row.querySelector('.btn-cancel').style.display='none';
}
function toggleSidebar(){
  const s=document.getElementById('sidebar');
  s.style.left=(s.style.left==='0px'?'-250px':'0px');
}
</script>
</body>
</html>
