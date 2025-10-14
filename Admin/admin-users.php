<?php
session_start();
include 'db_admin.php';

// Hide warnings from UI (production-safe)
error_reporting(0);
ini_set('display_errors', 0);

// Check connection
if (!$conn || $conn->connect_errno) {
    die("<p style='color:red;'>❌ Database connection failed: " . $conn->connect_error . "</p>");
}

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
    // Get total users count
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM users");
    if ($result && $row = $result->fetch_assoc()) {
        $total = (int)$row['cnt'];
    }

    // Get paginated user data (no created_at)
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
<title>User Management - Career Trends Admin</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background:#e9ecef;
  color:#333;
  overflow-x:hidden;
}

/* Sidebar */
.sidebar{
  position:fixed;
  top:0;
  left:-250px;
  width:250px;
  height:100%;
  background:#2f2f2f;
  color:#fff;
  padding-top:60px;
  transition:0.3s;
  z-index:1000;
  overflow:auto;
}
.sidebar a{
  display:block;
  padding:14px 20px;
  color:#ddd;
  text-decoration:none;
  border-bottom:1px solid #444;
}
.sidebar a:hover, .sidebar a.active{
  background:#444;
  color:#fff;
}
.sidebar.show{left:0;}

/* Header */
header{
  background:linear-gradient(135deg,#444,#222);
  color:#fff;
  text-align:center;
  padding:20px 0;
  position:relative;
  box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
header h1{font-size:1.8rem;margin-bottom:6px;}
header p{font-size:0.95rem;opacity:0.85;}

/* Hamburger */
.hamburger{
  position:absolute;
  left:20px;
  top:22px;
  width:28px;
  height:20px;
  cursor:pointer;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
}
.hamburger span{
  height:3px;
  width:100%;
  background:#fff;
  border-radius:2px;
  transition:.3s;
}

/* Overlay (for mobile sidebar) */
.overlay{
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.5);
  z-index:900;
}

/* Container */
.container{
  max-width:1200px;
  margin:40px auto;
  padding:30px;
  background:#fff;
  border-radius:15px;
  box-shadow:0 4px 20px rgba(0,0,0,0.08);
}
h2{
  text-align:center;
  color:#333;
  margin-bottom:20px;
  font-size:1.8rem;
  position:relative;
  padding-bottom:10px;
}
h2::after{
  content:'';
  position:absolute;
  bottom:0;
  left:50%;
  transform:translateX(-50%);
  width:100px;
  height:3px;
  background:linear-gradient(90deg,#444,#888);
  border-radius:3px;
}

/* Table */
.table-wrapper{overflow-x:auto;}
.user-table{
  width:100%;
  border-collapse:collapse;
  min-width:600px;
}
.user-table th, .user-table td{
  padding:12px 15px;
  text-align:left;
  border-bottom:1px solid #ddd;
}
.user-table th{
  background:#444;
  color:#fff;
  font-weight:500;
}
.user-table tr:hover{background:#f9f9f9;}

/* Buttons */
.btn-small{
  padding:6px 14px;
  border:none;
  border-radius:6px;
  cursor:pointer;
  font-size:0.9rem;
  background:#666;
  color:#fff;
  transition:0.3s;
}
.btn-small:hover{background:#444;}
.btn-danger{background:#c0392b;}
.btn-danger:hover{background:#a93226;}

/* Pagination */
.pagination{
  display:flex;
  justify-content:center;
  margin:25px 0;
  gap:8px;
  flex-wrap:wrap;
}
.pagination a{
  padding:8px 12px;
  border:1px solid #ccc;
  background:#eee;
  color:#333;
  text-decoration:none;
  border-radius:4px;
}
.pagination a.active, .pagination a:hover{
  background:#444;
  color:#fff;
}

/* Footer */
footer{
  text-align:center;
  padding:25px 0;
  background:linear-gradient(135deg,#333,#222);
  color:#fff;
  font-size:0.9rem;
  margin-top:50px;
}

/* --- Responsive Styles --- */
@media(max-width:992px){
  .container{margin:20px;padding:20px;}
  header h1{font-size:1.6rem;}
}
@media(max-width:768px){
  .container{padding:20px 16px;}
  header{padding:16px;}
  .sidebar{width:220px;}
  .user-table th, .user-table td{padding:10px 8px;font-size:0.9rem;}
  .btn-small{padding:5px 10px;font-size:0.85rem;}
}
@media(max-width:576px){
  header h1{font-size:1.4rem;}
  header p{font-size:0.9rem;}
  .table-wrapper{overflow-x:auto;padding-bottom:10px;}
  .user-table{font-size:0.9rem;}
  .btn-small{display:block;width:100%;margin:4px 0;}
}
</style>
</head>
<body>
<header>
  <div class="hamburger" onclick="toggleSidebar()">
    <span></span><span></span><span></span>
  </div>
  <h1>User Management</h1>
  <p>Manage registered user accounts efficiently</p>
</header>

<div class="sidebar" id="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="admin-users.php" class="active">User Management</a>
  <a href="admin-content.php">Career Content</a>
  <a href="admin-certificates.php">Certificates</a>
  <a href="admin-roadmaps.php">Career Roadmaps</a>
  <a href="logout.php">Logout</a>
</div>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

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
  <p>&copy; <?= date("Y") ?> Mapping The Future System | Bulacan State University - Bustos Campus</p>
</footer>

<script>
function toggleSidebar(){
  const sidebar=document.getElementById("sidebar");
  const overlay=document.getElementById("overlay");
  const isOpen=sidebar.style.left==="0px";
  sidebar.style.left=isOpen?"-250px":"0px";
  overlay.style.display=isOpen?"none":"block";
  document.body.style.overflow=isOpen?"auto":"hidden";
}

function editUser(id){
  event.stopPropagation();
  alert("Edit user with ID: " + id);
}
</script>
</body>
</html>
