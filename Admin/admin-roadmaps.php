<?php
include 'db_admin.php';

if (!isset($servername, $dbusername, $dbpassword, $dbname)) {
  die("❌ Missing DB credentials in db_admin.php");
}

try {
  $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
  $pdo = new PDO($dsn, $dbusername, $dbpassword, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);
} catch (PDOException $e) {
  die("❌ DB connection failed: " . htmlspecialchars($e->getMessage()));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['edit_id'])) {
    $id = (int) $_POST['edit_id'];
    $career_id = $_POST['career_id'] ?? '';
    $step_number = $_POST['step_number'] ?? '';
    $step_title = $_POST['step_title'] ?? '';
    $step_detail = $_POST['step_detail'] ?? '';

    if ($career_id && $step_number && $step_title) {
      $stmt = $pdo->prepare("UPDATE career_roadmaps SET career_id=?, step_number=?, step_title=?, step_detail=? WHERE id=?");
      $stmt->execute([$career_id, $step_number, $step_title, $step_detail, $id]);
      $message = "✅ Roadmap ID $id updated.";
    } else {
      $error = "⚠️ Required: Career ID, Step Number, Step Title.";
    }
  }

  if (isset($_POST['add_new'])) {
    $career_id = $_POST['career_id'] ?? '';
    $step_number = $_POST['step_number'] ?? '';
    $step_title = $_POST['step_title'] ?? '';
    $step_detail = $_POST['step_detail'] ?? '';

    if ($career_id && $step_number && $step_title) {
      $stmt = $pdo->prepare("INSERT INTO career_roadmaps (career_id, step_number, step_title, step_detail) VALUES (?,?,?,?)");
      $stmt->execute([$career_id, $step_number, $step_title, $step_detail]);
      $message = "✅ New roadmap step added.";
    } else {
      $error = "⚠️ Required: Career ID, Step Number, Step Title.";
    }
  }

  if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM career_roadmaps WHERE id=?");
    $stmt->execute([$id]);
    $message = "🗑️ Roadmap ID $id deleted.";
  }
}

$limit = 8;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total = $pdo->query("SELECT COUNT(*) FROM career_roadmaps")->fetchColumn();
$stmt = $pdo->prepare("SELECT * FROM career_roadmaps ORDER BY career_id, step_number LIMIT :l OFFSET :o");
$stmt->bindValue(':l', $limit, PDO::PARAM_INT);
$stmt->bindValue(':o', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();
$total_pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Career Roadmap Management</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:#f4f6f8;color:#333;}
header{background:linear-gradient(135deg,#444,#111);color:#fff;text-align:center;padding:20px;position:relative;}
header h1{margin:0;font-size:1.8rem;}
.hamburger{position:absolute;left:20px;top:22px;width:28px;height:20px;cursor:pointer;display:flex;flex-direction:column;justify-content:space-between;}
.hamburger span{height:3px;width:100%;background:#fff;border-radius:2px;transition:.3s;}

.sidebar{position:fixed;top:0;left:-240px;width:220px;height:100%;background:#222;color:#fff;transition:left .3s;padding-top:60px;z-index:1000;}
.sidebar.show{left:0;}
.sidebar a{display:block;padding:12px 20px;color:#ddd;text-decoration:none;border-bottom:1px solid #333;}
.sidebar a:hover,.sidebar a.active{background:#444;color:#fff;}

.container{max-width:1200px;margin:40px auto;padding:25px;background:#fff;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.1);}
.container h2{text-align:center;margin-bottom:15px;font-size:1.5rem;}

.btn{padding:8px 14px;border:none;border-radius:6px;cursor:pointer;font-size:.9rem;color:#fff;margin:3px;transition:.3s;}
.btn:hover{opacity:.9;}
.btn-save{background:#28a745;}
.btn-cancel{background:#c0392b;}
.btn-edit{background:#666;}
.btn-add{background:#222;position:sticky;top:0;width:100%;display:block;margin-bottom:15px;padding:12px;font-size:1rem;}
.btn-delete{background:#a93226;}

.table-wrapper{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#555;color:#fff;font-weight:600;}
tr:hover{background:#f9f9f9;}
textarea,input[type=text],input[type=number]{width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;}

.message{padding:10px;margin-bottom:15px;border-radius:6px;color:#fff;text-align:center;}
.success{background:#28a745;}
.error{background:#e74c3c;}

.pagination{display:flex;justify-content:center;gap:8px;margin-top:20px;flex-wrap:wrap;}
.pagination a{padding:8px 12px;border:1px solid #ccc;background:#eee;color:#333;text-decoration:none;border-radius:4px;}
.pagination a.active,.pagination a:hover{background:#444;color:#fff;}

.modal{display:none;position:fixed;z-index:2000;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);align-items:center;justify-content:center;}
.modal-content{background:#fff;padding:20px;border-radius:10px;max-width:500px;width:90%;box-shadow:0 5px 15px rgba(0,0,0,0.3);}
.close{float:right;font-size:22px;cursor:pointer;color:#555;}
.close:hover{color:#000;}
.modal-content input,.modal-content textarea{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px;}

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
  textarea,input[type=text],input[type=number]{font-size:.9rem;}
  .modal-content{padding:15px;}
}
</style>
</head>
<body>

<header>
  <div class="hamburger" onclick="document.querySelector('.sidebar').classList.toggle('show')">
    <span></span><span></span><span></span>
  </div>
  <h1>Career Roadmap Management</h1>
</header>

<div class="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php">User Management</a>
  <a href="admin-content.php">Career Content</a>
  <a href="admin-certificates.php">Certificates</a>
  <a href="admin-roadmaps.php" class="active">Career Roadmaps</a>
  <a href="logout.php">Logout</a>
</div>

<div class="container">
  <h2>Career Roadmaps</h2>
  <?php if(!empty($message)):?><div class="message success"><?=$message?></div><?php endif;?>
  <?php if(!empty($error)):?><div class="message error"><?=$error?></div><?php endif;?>

  <button class="btn btn-add" onclick="document.getElementById('addModal').style.display='flex'">+ Add New Roadmap Step</button>

  <div class="table-wrapper">
    <table>
      <thead><tr><th>ID</th><th>Career ID</th><th>Step #</th><th>Title</th><th>Detail</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if($rows): foreach($rows as $r):?>
      <tr>
        <form method="POST">
          <td data-label="ID"><?=$r['id']?><input type="hidden" name="edit_id" value="<?=$r['id']?>"></td>
          <td data-label="Career ID"><input type="number" name="career_id" value="<?=$r['career_id']?>"></td>
          <td data-label="Step #"><input type="number" name="step_number" value="<?=$r['step_number']?>"></td>
          <td data-label="Title"><input type="text" name="step_title" value="<?=htmlspecialchars($r['step_title'])?>"></td>
          <td data-label="Detail"><textarea name="step_detail"><?=htmlspecialchars($r['step_detail'])?></textarea></td>
          <td data-label="Actions">
            <button type="submit" class="btn btn-save">Save</button>
        </form>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="delete_id" value="<?=$r['id']?>">
          <button type="submit" class="btn btn-cancel" onclick="return confirm('Delete this roadmap step?');">Delete</button>
        </form>
          </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="6" style="text-align:center;">No roadmap steps found.</td></tr>
      <?php endif;?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <?php for($i=1;$i<=$total_pages;$i++):?>
      <a href="?page=<?=$i?>" class="<?=($i==$page)?'active':''?>"><?=$i?></a>
    <?php endfor;?>
  </div>
</div>

<!-- Modal -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
    <h3>Add New Roadmap Step</h3>
    <form method="POST">
      <input type="hidden" name="add_new" value="1">
      <label>Career ID:</label>
      <input type="number" name="career_id" required>
      <label>Step #:</label>
      <input type="number" name="step_number" required>
      <label>Title:</label>
      <input type="text" name="step_title" required>
      <label>Detail:</label>
      <textarea name="step_detail"></textarea>
      <button type="submit" class="btn btn-save" style="width:100%;">Add Step</button>
    </form>
  </div>
</div>

<script>
window.onclick=function(e){
  const modal=document.getElementById('addModal');
  if(e.target==modal){modal.style.display="none";}
}
</script>
</body>
</html>
