<?php
session_start();
include 'db_admin.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Default values
$total = 0;
$users = [];
$total_pages = 1;

// Fetch data
try {
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM users");
    if ($result && $row = $result->fetch_assoc()) {
        $total = (int)$row['cnt'];
    }

    $query = "SELECT id, fullname, email FROM users ORDER BY id DESC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $res = $stmt->get_result();
    $users = $res->fetch_all(MYSQLI_ASSOC);

    $total_pages = ($total > 0) ? ceil($total / $limit) : 1;
} catch (mysqli_sql_exception $e) {
    error_log("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>CareerScope | User Management</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">
<style>
* {margin: 0; padding: 0; box-sizing: border-box;}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f4f4f4;
  color: #333;
  overflow-x: hidden;
  line-height: 1.6;
}

/* HEADER */
header {
  background: linear-gradient(135deg, #666, #888);
  color: white;
  text-align: center;
  padding: 25px 0;
  position: relative;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
header h1 {
  font-size: 2rem;
  margin-bottom: 8px;
}
header p {
  font-size: 1rem;
  opacity: 0.9;
}

/* HAMBURGER */
.hamburger {
  position: absolute;
  left: 20px;
  top: 22px;
  width: 30px;
  height: 22px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
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

/* CONTAINER */
.container {
  max-width: 1000px;
  margin: 60px auto;
  padding: 40px 25px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.container h2 {
  text-align: center;
  color: #333;
  margin-bottom: 25px;
  font-size: 1.8rem;
  position: relative;
  padding-bottom: 10px;
}
.container h2::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 3px;
  background: linear-gradient(90deg, #666, #888);
  border-radius: 3px;
}

/* TABLE */
.table-wrapper { overflow-x: auto; }
.user-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 600px;
}
.user-table th, .user-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
.user-table th {
  background: #666;
  color: #fff;
  font-weight: 500;
}
.user-table tr:hover {
  background: #f9f9f9;
}

/* BUTTONS */
.btn-small {
  padding: 6px 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
  background: #ffcc00;
  color: #333;
  transition: 0.3s;
  font-weight: 600;
}
.btn-small:hover { background: #e6b800; }

/* PAGINATION */
.pagination {
  display: flex;
  justify-content: center;
  margin: 25px 0;
  gap: 8px;
  flex-wrap: wrap;
}
.pagination a {
  padding: 8px 12px;
  border: 1px solid #ccc;
  background: #fff;
  color: #333;
  text-decoration: none;
  border-radius: 6px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.pagination a.active, .pagination a:hover {
  background: #ffcc00;
  color: #333;
}

/* FOOTER */
footer {
  text-align: center;
  padding: 20px;
  background: #444;
  color: #ddd;
  margin-top: 60px;
  font-size: 0.95rem;
}
footer a {
  color: #ffcc00;
  text-decoration: none;
}
footer a:hover { text-decoration: underline; }

@media (max-width:768px) {
  header h1 { font-size: 1.6rem; }
  .container { padding: 25px 15px; }
  .user-table th, .user-table td { padding: 10px 8px; font-size: 0.9rem; }
}
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger">
    <span></span><span></span><span></span>
  </div>
  <h1>CareerScope - User Management</h1>
  <p>Manage registered users efficiently</p>
</header>

<div class="sidebar" id="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php" class="active">User Management</a>
  <a href="admin-content.php">Career Content</a>
  <a href="admin-certificates.php">Certificates</a>
  <a href="admin-roadmaps.php">Career Roadmaps</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>
<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>Users List</h2>
  <div class="table-wrapper">
    <table class="user-table">
      <thead>
        <tr>
          <th>User</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($users)): foreach($users as $user): ?>
          <tr onclick="window.location='admin-roadmaps.php?user=<?= $user['id'] ?>'" style="cursor:pointer;">
            <td><?= htmlspecialchars($user['fullname']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
              <button class="btn-small" onclick="event.stopPropagation(); editUser(<?= $user['id'] ?>)">Edit</button>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="3" style="text-align:center;">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <?php for($i=1;$i<=$total_pages;$i++): ?>
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

function editUser(id){
  event.stopPropagation();
  alert("Edit user with ID: " + id);
}
</script>
</body>
</html>
