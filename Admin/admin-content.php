<?php
require_once __DIR__ . '/db_admin.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// ✅ CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addCareer'])) {
        $stmt = $pdo->prepare("INSERT INTO careers (title, category, description, skills) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['category'], $_POST['description'], $_POST['skills']]);
        $message = "✅ Career added successfully.";
    }
    if (isset($_POST['edit_id'])) {
        $stmt = $pdo->prepare("UPDATE careers SET title=?, category=?, description=?, skills=? WHERE id=?");
        $stmt->execute([$_POST['title'], $_POST['category'], $_POST['description'], $_POST['skills'], $_POST['edit_id']]);
        $message = "✏️ Career updated.";
    }
    if (isset($_POST['delete_id'])) {
        $stmt = $pdo->prepare("DELETE FROM careers WHERE id=?");
        $stmt->execute([$_POST['delete_id']]);
        $message = "🗑️ Career deleted.";
    }
}

// ✅ Pagination
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$total = $pdo->query("SELECT COUNT(*) FROM careers")->fetchColumn();
$totalPages = ceil($total / $limit);
$stmt = $pdo->prepare("SELECT * FROM careers ORDER BY id DESC LIMIT :l OFFSET :o");
$stmt->bindValue(':l', $limit, PDO::PARAM_INT);
$stmt->bindValue(':o', $offset, PDO::PARAM_INT);
$stmt->execute();
$careers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Career Management</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:#f4f6f8;color:#333;}

/* HEADER */
header{background:linear-gradient(135deg,#444,#111);color:#fff;text-align:center;padding:20px;position:relative;}
header h1{margin:0;font-size:1.8rem;}
header p{opacity:.8;font-size:.9rem;}
.hamburger{position:absolute;left:20px;top:22px;width:28px;height:20px;cursor:pointer;display:flex;flex-direction:column;justify-content:space-between;}
.hamburger span{height:3px;width:100%;background:#fff;border-radius:2px;transition:.3s;}

/* SIDEBAR */
.sidebar{position:fixed;top:0;left:-240px;width:220px;height:100%;background:#222;color:#fff;transition:left .3s;padding-top:60px;z-index:1000;}
.sidebar.show{left:0;}
.sidebar a{display:block;padding:12px 20px;color:#ddd;text-decoration:none;border-bottom:1px solid #333;}
.sidebar a:hover,.sidebar a.active{background:#444;color:#fff;}

/* CONTAINER */
.container{max-width:1200px;margin:40px auto;padding:25px;background:#fff;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.1);}
.container h2{text-align:center;margin-bottom:15px;font-size:1.5rem;}

/* MESSAGES */
.message{padding:10px;margin-bottom:15px;border-radius:6px;color:#fff;text-align:center;}
.success{background:#28a745;}
.error{background:#e74c3c;}

/* BUTTONS */
.btn{padding:8px 14px;border:none;border-radius:6px;cursor:pointer;font-size:.9rem;color:#fff;margin:3px;transition:.3s;}
.btn:hover{opacity:.9;}
.btn-save{background:#28a745;}
.btn-cancel{background:#c0392b;}
.btn-add{background:#222;position:sticky;top:0;width:100%;display:block;margin-bottom:15px;padding:12px;font-size:1rem;}

/* TABLE */
.table-wrapper{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#555;color:#fff;font-weight:600;}
tr:hover{background:#f9f9f9;}
textarea,input[type=text]{width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;}

/* PAGINATION */
.pagination{display:flex;justify-content:center;gap:8px;margin-top:20px;flex-wrap:wrap;}
.pagination a{padding:8px 12px;border:1px solid #ccc;background:#eee;color:#333;text-decoration:none;border-radius:4px;}
.pagination a.active,.pagination a:hover{background:#444;color:#fff;}

/* MODAL */
.modal{display:none;position:fixed;z-index:2000;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);align-items:center;justify-content:center;}
.modal-content{background:#fff;padding:20px;border-radius:10px;max-width:500px;width:90%;box-shadow:0 5px 15px rgba(0,0,0,0.3);}
.modal-content h3{margin-bottom:15px;}
.modal-content input,.modal-content textarea{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px;}
.close{float:right;font-size:22px;cursor:pointer;color:#555;}
.close:hover{color:#000;}

/* RESPONSIVE */
@media(max-width:768px){
  header h1{font-size:1.4rem;}
  .container{margin:20px;padding:15px;}
  th,td{padding:10px;font-size:0.9rem;}
}
@media(max-width:600px){
  table,thead,tbody,th,td,tr{display:block;}
  thead{display:none;}
  tr{margin-bottom:15px;background:#fff;border:1px solid #ddd;border-radius:8px;padding:10px;}
  td{display:flex;justify-content:space-between;padding:6px 0;}
  td::before{content:attr(data-label);font-weight:600;color:#333;}
  .btn{width:48%;margin:3px 1%;font-size:.85rem;padding:10px;}
  .btn-add{width:100%;position:relative;top:auto;}
  textarea,input[type=text]{font-size:.9rem;}
  .modal-content{padding:15px;}
}
</style>
</head>
<body>

<header>
  <div class="hamburger" onclick="document.querySelector('.sidebar').classList.toggle('show')">
    <span></span><span></span><span></span>
  </div>
  <h1>Career Management</h1>
  <p>Manage and update available career paths</p>
</header>

<div class="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php">User Management</a>
  <a href="admin-content.php" class="active">Career Content</a>
  <a href="admin-certificates.php">Certificates</a>
  <a href="admin-roadmaps.php">Career Roadmaps</a>
  <a href="logout.php">Logout</a>
</div>

<div class="container">
  <?php if (!empty($message)): ?>
    <div class="message success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <button class="btn-add" onclick="openModal()">+ Add Career</button>

  <!-- MODAL -->
  <div class="modal" id="addModal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Add Career</h3>
      <form method="POST">
        <input type="hidden" name="addCareer" value="1">
        <label>Title</label>
        <input type="text" name="title" required>
        <label>Category</label>
        <input type="text" name="category" required>
        <label>Description</label>
        <textarea name="description"></textarea>
        <label>Skills</label>
        <textarea name="skills"></textarea>
        <button type="submit" class="btn btn-save" style="width:100%;">Save Career</button>
      </form>
    </div>
  </div>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr><th>ID</th><th>Title</th><th>Category</th><th>Description</th><th>Skills</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if ($careers): foreach ($careers as $c): ?>
        <tr>
          <form method="POST">
          <td data-label="ID"><?= $c['id'] ?></td>
          <td data-label="Title"><input type="text" name="title" value="<?= htmlspecialchars($c['title']) ?>"></td>
          <td data-label="Category"><input type="text" name="category" value="<?= htmlspecialchars($c['category']) ?>"></td>
          <td data-label="Description"><textarea name="description"><?= htmlspecialchars($c['description']) ?></textarea></td>
          <td data-label="Skills"><textarea name="skills"><?= htmlspecialchars($c['skills']) ?></textarea></td>
          <td data-label="Actions">
            <input type="hidden" name="edit_id" value="<?= $c['id'] ?>">
            <button type="submit" class="btn btn-save">Save</button>
          </form>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
            <button type="submit" class="btn btn-cancel" onclick="return confirm('Delete this career?')">Delete</button>
          </form>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="6" style="text-align:center;">No data found.</td></tr>
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

<script>
function openModal(){document.getElementById('addModal').style.display='flex';}
function closeModal(){document.getElementById('addModal').style.display='none';}
</script>
</body>
</html>
