<?php
include 'db_admin.php';

// ✅ CRUD Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addCareer'])) {
        $stmt = $conn->prepare("INSERT INTO careers (title, category, description, skills) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['title'], $_POST['category'], $_POST['description'], $_POST['skills']);
        $stmt->execute();
        $message = "✅ Career added successfully.";
    }
    if (isset($_POST['edit_id'])) {
        $stmt = $conn->prepare("UPDATE careers SET title=?, category=?, description=?, skills=? WHERE id=?");
        $stmt->bind_param("ssssi", $_POST['title'], $_POST['category'], $_POST['description'], $_POST['skills'], $_POST['edit_id']);
        $stmt->execute();
        $message = "✏️ Career updated.";
    }
    if (isset($_POST['delete_id'])) {
        $stmt = $conn->prepare("DELETE FROM careers WHERE id=?");
        $stmt->bind_param("i", $_POST['delete_id']);
        $stmt->execute();
        $message = "🗑️ Career deleted.";
    }
}

// ✅ Pagination
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get total rows
$result = $conn->query("SELECT COUNT(*) AS total FROM careers");
$row = $result->fetch_assoc();
$total = (int)$row['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated data
$stmt = $conn->prepare("SELECT * FROM careers ORDER BY id DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$res = $stmt->get_result();
$careers = $res->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Career Management - CareerScope</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

body {
  background: #f4f4f4;
  color: #333;
  line-height: 1.6;
  overflow-x: hidden;
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
header h1 { font-size: 1.8rem; margin-bottom: 6px; }
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
  top: 0; left: -250px;
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
.sidebar a:hover, .sidebar a.active {
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
  text-align: center;
  color: #333;
  font-size: 1.7rem;
  margin-bottom: 25px;
  position: relative;
}
.container h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 3px;
  background: #ffcc00;
  border-radius: 3px;
}

/* MESSAGES */
.message {
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 20px;
  text-align: center;
  color: white;
}
.success { background: #28a745; }
.error { background: #e74c3c; }

/* BUTTONS */
.btn {
  padding: 8px 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
  color: white;
  transition: background 0.3s;
}
.btn:hover { opacity: 0.9; }
.btn-save { background: #28a745; }
.btn-cancel { background: #c0392b; }
.btn-add {
  background: #666;
  width: 100%;
  font-size: 1rem;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 15px;
}

/* TABLE */
.table-wrapper { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
th, td {
  padding: 12px 14px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
th {
  background: #666;
  color: #fff;
}
tr:hover { background: #fafafa; }
input[type=text], textarea {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

/* PAGINATION */
.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 25px;
  flex-wrap: wrap;
}
.pagination a {
  padding: 8px 12px;
  border: 1px solid #ccc;
  background: #eee;
  color: #333;
  text-decoration: none;
  border-radius: 4px;
}
.pagination a.active, .pagination a:hover {
  background: #666;
  color: #fff;
}

/* MODAL */
.modal {
  display: none;
  position: fixed;
  z-index: 2000;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  max-width: 500px;
  width: 90%;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.modal-content h3 { margin-bottom: 15px; color: #333; }
.modal-content label { font-weight: 600; }
.modal-content input, .modal-content textarea {
  margin-bottom: 10px;
  border-radius: 6px;
}
.close {
  float: right;
  font-size: 22px;
  cursor: pointer;
  color: #555;
}
.close:hover { color: #000; }

/* FOOTER */
footer {
  text-align: center;
  padding: 20px;
  background: #444;
  color: #ddd;
  margin-top: 60px;
  font-size: 0.95rem;
}
footer a { color: #ffcc00; text-decoration: none; }
footer a:hover { text-decoration: underline; }

@media(max-width:768px){
  .container { padding: 25px 15px; margin: 30px; }
  header h1 { font-size: 1.4rem; }
  th, td { font-size: 0.9rem; }
}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>Career Management</h1>
  <p>Manage and update available career paths</p>
</header>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php">User Management</a>
  <a href="admin-content.php" class="active">Career Content</a>
  <a href="admin-certificates.php">Certificates</a>
  <a href="admin-roadmaps.php">Career Roadmaps</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <a href="logout.php">Logout</a>
</div>
<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>Career List</h2>
  <?php if (!empty($message)): ?>
    <div class="message success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <button class="btn-add" onclick="openModal()">+ Add Career</button>

  <!-- ADD MODAL -->
  <div class="modal" id="addModal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Add New Career</h3>
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
            <td><?= $c['id'] ?></td>
            <td><input type="text" name="title" value="<?= htmlspecialchars($c['title']) ?>"></td>
            <td><input type="text" name="category" value="<?= htmlspecialchars($c['category']) ?>"></td>
            <td><textarea name="description"><?= htmlspecialchars($c['description']) ?></textarea></td>
            <td><textarea name="skills"><?= htmlspecialchars($c['skills']) ?></textarea></td>
            <td>
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

<footer>
  <p>&copy; <?= date("Y") ?> CareerScope | Empowering students with data-driven guidance</p>
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
</script>
</body>
</html>
