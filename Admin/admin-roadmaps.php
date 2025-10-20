<?php
// ====================================================
// ✅ DATABASE CONNECTION
// ====================================================
require_once __DIR__ . '/db_admin.php';

// ====================================================
// ✅ HANDLE CRUD ACTIONS
// ====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ➕ Add New Step
    if (isset($_POST['add_roadmap'])) {
        $career_id = trim($_POST['career_id']);
        $step_number = trim($_POST['step_number']);
        $step_title = trim($_POST['step_title']);
        $step_detail = trim($_POST['step_detail']);

        if ($career_id && $step_number && $step_title) {
            $stmt = $conn->prepare("INSERT INTO career_roadmaps (career_id, step_number, step_title, step_detail) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $career_id, $step_number, $step_title, $step_detail);
            $stmt->execute();
            $message = "✅ New roadmap step added successfully.";
        } else {
            $error = "⚠️ Please fill in all required fields.";
        }
    }

    // ✏️ Edit Step
    if (isset($_POST['edit_id'])) {
        $id = (int)$_POST['edit_id'];
        $career_id = trim($_POST['career_id']);
        $step_number = trim($_POST['step_number']);
        $step_title = trim($_POST['step_title']);
        $step_detail = trim($_POST['step_detail']);

        if ($career_id && $step_number && $step_title) {
            $stmt = $conn->prepare("UPDATE career_roadmaps SET career_id=?, step_number=?, step_title=?, step_detail=? WHERE id=?");
            $stmt->bind_param("iissi", $career_id, $step_number, $step_title, $step_detail, $id);
            $stmt->execute();
            $message = "✏️ Roadmap ID $id updated successfully.";
        } else {
            $error = "⚠️ Please fill in all required fields.";
        }
    }

    // 🗑️ Delete Step
    if (isset($_POST['delete_id'])) {
        $id = (int)$_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM career_roadmaps WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $message = "🗑️ Roadmap ID $id deleted successfully.";
    }
}

// ====================================================
// ✅ PAGINATION
// ====================================================
$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$totalQuery = $conn->query("SELECT COUNT(*) AS cnt FROM career_roadmaps");
$total = $totalQuery->fetch_assoc()['cnt'] ?? 0;
$totalPages = max(1, ceil($total / $limit));

$stmt = $conn->prepare("SELECT * FROM career_roadmaps ORDER BY career_id, step_number ASC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$roadmaps = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Career Roadmap Management | CareerScope Admin</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* {margin:0;padding:0;box-sizing:border-box;}
body {
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color:#f4f4f4;
  color:#333;
  line-height:1.6;
  overflow-x:hidden;
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
  max-width: 1100px;
  margin: 60px auto;
  padding: 40px 25px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.container h2 {
  font-size: 1.8rem;
  color: #333;
  text-align:center;
  margin-bottom: 15px;
}

/* BUTTONS & MESSAGES */
.message {
  padding:10px;margin-bottom:15px;border-radius:6px;text-align:center;
}
.success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}

.btn {
  padding:8px 14px;
  border:none;
  border-radius:6px;
  cursor:pointer;
  font-size:.9rem;
  color:#fff;
  margin:3px;
  transition: all 0.3s ease;
}
.btn:hover{opacity:.9;}
.btn-add{background:#333;width:100%;margin-bottom:20px;padding:12px;font-size:1rem;}
.btn-edit{background:#007bff;}
.btn-save{background:#28a745;display:none;}
.btn-cancel{background:#6c757d;display:none;}
.btn-delete{background:#e74c3c;}

/* TABLE */
.table-wrapper{overflow-x:auto;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#555;color:#fff;}
tr:hover{background:#f9f9f9;}
textarea,input[type=text],input[type=number]{
  width:100%;
  padding:8px;
  border:1px solid #ccc;
  border-radius:6px;
}

/* PAGINATION */
.pagination {
  display:flex;
  justify-content:center;
  gap:8px;
  margin-top:20px;
  flex-wrap:wrap;
}
.pagination a {
  padding:8px 12px;
  border:1px solid #ccc;
  background:#eee;
  color:#333;
  text-decoration:none;
  border-radius:4px;
}
.pagination a.active,.pagination a:hover {
  background:#444;
  color:#fff;
}

/* MODAL */
.modal {
  display:none;
  position:fixed;
  z-index:2000;
  top:0;left:0;
  width:100%;height:100%;
  background:rgba(0,0,0,.5);
  align-items:center;
  justify-content:center;
}
.modal-content {
  background:#fff;
  padding:25px;
  border-radius:10px;
  max-width:500px;
  width:90%;
  box-shadow:0 5px 15px rgba(0,0,0,0.3);
}
.modal-content h3{margin-bottom:15px;}
.close {
  float:right;
  font-size:22px;
  cursor:pointer;
  color:#555;
}
.close:hover{color:#000;}
.modal-content input,.modal-content textarea{
  width:100%;
  padding:10px;
  margin:8px 0;
  border:1px solid #ccc;
  border-radius:6px;
}

/* FOOTER */
footer {
  text-align:center;
  padding:20px;
  background:#444;
  color:#ddd;
  margin-top:60px;
  font-size:0.95rem;
}
footer a {color:#ffcc00;text-decoration:none;}
footer a:hover{text-decoration:underline;}

@media(max-width:768px){
  header h1{font-size:1.6rem;}
  .container{padding:25px 15px;}
  table,thead,tbody,th,td,tr{display:block;}
  thead{display:none;}
  tr{margin-bottom:15px;background:#fff;border:1px solid #ddd;border-radius:8px;padding:10px;}
  td{display:flex;justify-content:space-between;padding:6px 0;}
  td::before{content:attr(data-label);font-weight:600;color:#333;}
  .btn{width:48%;margin:3px 1%;font-size:.85rem;}
  .btn-add{width:100%;}
}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger">
    <span></span><span></span><span></span>
  </div>
  <h1>Career Roadmap Management</h1>
  <p>Manage and organize step-by-step career guides</p>
</header>

<div class="sidebar" id="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php">User Management</a>
  <a href="admin-content.php">Career Content</a>
  <a href="admin-certificates.php">Certificates</a>
  <a href="admin-roadmaps.php" class="active">Career Roadmaps</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>
<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>Career Roadmaps</h2>

  <?php if(!empty($message)): ?><div class="message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if(!empty($error)): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <button class="btn btn-add" onclick="openModal()">+ Add Roadmap Step</button>

  <!-- Add Modal -->
  <div id="addModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Add New Roadmap Step</h3>
      <form method="POST">
        <input type="hidden" name="add_roadmap" value="1">
        <label>Career ID:</label>
        <input type="number" name="career_id" required>
        <label>Step #:</label>
        <input type="number" name="step_number" required>
        <label>Step Title:</label>
        <input type="text" name="step_title" required>
        <label>Step Detail:</label>
        <textarea name="step_detail"></textarea>
        <button type="submit" class="btn btn-save" style="display:block;width:100%;">Add Step</button>
      </form>
    </div>
  </div>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Career ID</th>
          <th>Step #</th>
          <th>Title</th>
          <th>Detail</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if($roadmaps->num_rows > 0): while($r = $roadmaps->fetch_assoc()): ?>
        <tr>
          <form method="POST">
            <td data-label="ID"><?= $r['id'] ?><input type="hidden" name="edit_id" value="<?= $r['id'] ?>"></td>
            <td data-label="Career ID"><input type="number" name="career_id" value="<?= htmlspecialchars($r['career_id']) ?>" disabled></td>
            <td data-label="Step #"><input type="number" name="step_number" value="<?= htmlspecialchars($r['step_number']) ?>" disabled></td>
            <td data-label="Title"><input type="text" name="step_title" value="<?= htmlspecialchars($r['step_title']) ?>" disabled></td>
            <td data-label="Detail"><textarea name="step_detail" disabled><?= htmlspecialchars($r['step_detail']) ?></textarea></td>
            <td data-label="Actions">
              <button type="button" class="btn btn-edit" onclick="enableEdit(this)">Edit</button>
              <button type="submit" class="btn btn-save">Save</button>
              <button type="button" class="btn btn-cancel" onclick="cancelEdit(this)">Cancel</button>
          </form>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this roadmap step?')">Delete</button>
          </form>
            </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="6" style="text-align:center;">No roadmap steps found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <?php for($i=1;$i<=$totalPages;$i++): ?>
      <a href="?page=<?= $i ?>" class="<?= ($i==$page)?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
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

function openModal(){document.getElementById('addModal').style.display='flex';}
function closeModal(){document.getElementById('addModal').style.display='none';}

function enableEdit(btn){
  const row = btn.closest('tr');
  row.querySelectorAll('input,textarea').forEach(el => el.disabled = false);
  row.querySelector('.btn-edit').style.display = 'none';
  row.querySelector('.btn-save').style.display = 'inline-block';
  row.querySelector('.btn-cancel').style.display = 'inline-block';
}
function cancelEdit(btn){
  const row = btn.closest('tr');
  row.querySelectorAll('input,textarea').forEach(el => el.disabled = true);
  row.querySelector('.btn-edit').style.display = 'inline-block';
  row.querySelector('.btn-save').style.display = 'none';
  row.querySelector('.btn-cancel').style.display = 'none';
}
window.onclick = function(e){
  const modal = document.getElementById('addModal');
  if(e.target == modal){closeModal();}
}
</script>

</body>
</html>
