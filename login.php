<?php
session_start();
include 'db_connect.php';

$loginMessage = "";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === "admin" && $password === "admin") {
        $_SESSION['admin_id'] = 1;
        $_SESSION['adminName'] = "Administrator";
        header("Location: Admin/dashboard.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, fullName, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $fullName, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['fullName'] = $fullName;
            header("Location: index.php");
            exit;
        } else {
            $loginMessage = "❌ Invalid password.";
        }
    } else {
        $loginMessage = "❌ Email not found.";
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | CareerScope</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">

<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#f4f4f4;
    color:#333;
    line-height:1.6;
}

/* HEADER */
header {
    background: linear-gradient(135deg, #666, #888);
    color:white;
    padding:25px 0;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.2);
    position:relative;
}
header h1 { font-size:2.5rem; margin-bottom:10px; }
header p { font-size:1.1rem; opacity:0.9; }

/* HAMBURGER */
.hamburger {
    position:absolute; top:20px; left:20px;
    width:30px; height:22px;
    display:flex; flex-direction:column; justify-content:space-between;
    cursor:pointer; z-index:300;
}
.hamburger span { height:4px; background:white; border-radius:2px; }

/* SIDEBAR */
.sidebar {
    position: fixed; top:0; left:-250px;
    width:250px; height:100%;
    background:#444; color:white;
    padding:60px 20px; display:flex; flex-direction:column; gap:20px;
    transition:left 0.3s ease; z-index:200;
}
.sidebar.active { left:0; }
.sidebar a {
    color:white; text-decoration:none; font-size:1.1rem;
    padding:8px 0; display:block; transition:0.3s;
}
.sidebar a:hover { color:#ffcc00; transform:translateX(5px); }

.overlay {
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.4); opacity:0; visibility:hidden;
    transition:opacity 0.3s ease; z-index:100;
}
.overlay.active { opacity:1; visibility:visible; }

/* LOGIN CONTAINER */
.container {
    max-width:420px;
    margin:80px auto;
    background:#fff;
    padding:35px 30px;
    border-radius:10px;
    box-shadow:0 4px 20px rgba(0,0,0,0.1);
    text-align:left;
}
h2 {
    text-align:center;
    font-size:2rem;
    color:#333;
    margin-bottom:25px;
    font-weight:600;
}
label {
    font-weight:600;
    display:block;
    margin-top:12px;
    color:#333;
}
input {
    width:100%;
    padding:12px;
    margin-top:6px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:1rem;
    transition:border-color 0.3s ease;
}
input:focus { border-color:#ffcc00; outline:none; }

/* BUTTON */
button {
    margin-top:25px;
    width:100%;
    padding:12px;
    background:#ffcc00;
    color:#333;
    border:none;
    border-radius:6px;
    font-weight:600;
    cursor:pointer;
    transition:all 0.3s ease;
}
button:hover { background:#e6b800; transform:scale(1.02); }

/* LINKS */
.links {
    margin-top:18px;
    text-align:center;
}
.links a { color:#333; text-decoration:none; transition:0.3s; }
.links a:hover { color:#ffcc00; }

/* MESSAGE */
.message {
    text-align:center;
    margin-bottom:15px;
    color:red;
    font-weight:500;
}
</style>
</head>
<body>

<header>
    <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
    <h1>CareerScope</h1>
    <p>Empowering students with data-driven career guidance</p>
</header>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="index.php" style="color:#ffcc00;">Home</a>
    <a href="career-guidance.php">Career Guidance</a>
    <a href="careerpath.php">Career Path</a>
    <a href="about.php">About</a>
    <hr style="border:1px solid rgba(255,255,255,0.2);">
    <?php if(isset($_SESSION['fullName'])): ?>
        <span style="color:#ffcc00;">👋 Welcome, <?= htmlspecialchars($_SESSION['fullName']) ?>!</span>
        <a href="settings.php">Settings</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    <?php else: ?>
        <a href="login.php" style="color:#ffcc00;">Login</a>
    <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<!-- LOGIN FORM -->
<div class="container">
    <h2>Login</h2>

    <?php if ($loginMessage): ?>
        <p class="message"><?= htmlspecialchars($loginMessage) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Email</label>
        <input type="text" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="links">
        <p><a href="register.php">Create Account</a> | <a href="reset.php">Forgot Password?</a></p>
    </div>
</div>

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
</script>

</body>
</html>
