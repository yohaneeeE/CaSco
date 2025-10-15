<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['reset_email'], $_SESSION['code_verified'])) {
    header("Location: request_reset.php");
    exit;
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetPassword'])) {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!$newPassword || !$confirmPassword) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            $conn->query("DELETE FROM pending_users WHERE email='$email'");
            unset($_SESSION['reset_email'], $_SESSION['code_verified']);
            echo "<script>alert('Password reset successful! You can now log in.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Error updating password.');</script>";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password | CareerScope</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; background:#f4f4f4; color:#333; line-height:1.6; }

/* HEADER */
header { background: linear-gradient(135deg,#666,#888); color:white; padding:25px 0; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.2); position:relative; }
header h1 { font-size:2.5rem; margin-bottom:10px; }
header p { font-size:1.1rem; opacity:0.9; }

/* HAMBURGER */
.hamburger { position:absolute; top:20px; left:20px; width:30px; height:22px; display:flex; flex-direction:column; justify-content:space-between; cursor:pointer; z-index:300; transition: transform 0.3s ease; }
.hamburger span { height:4px; background:white; border-radius:2px; transition: all 0.3s ease; }
.hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
.hamburger.active span:nth-child(2) { opacity:0; }
.hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px,-6px); }

/* SIDEBAR */
.sidebar { position:fixed; top:0; left:-250px; width:250px; height:100%; background:#444; color:white; padding:60px 20px; display:flex; flex-direction:column; gap:20px; transition:left 0.3s ease; z-index:200; }
.sidebar.active { left:0; }
.sidebar a { color:white; text-decoration:none; font-size:1.1rem; padding:8px 0; display:block; transition: color 0.3s ease, transform 0.2s ease; }
.sidebar a:hover { color:#ffcc00; transform:translateX(5px); }

/* OVERLAY */
.overlay { position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4); opacity:0; visibility:hidden; transition:opacity 0.3s ease; z-index:100; }
.overlay.active { opacity:1; visibility:visible; }

/* CONTAINER */
.container { max-width:420px; margin:60px auto; background:#fff; padding:35px 30px; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,0.1); }
h2 { text-align:center; font-size:2rem; color:#333; margin-bottom:20px; font-weight:600; }
label { font-weight:600; display:block; margin-top:12px; color:#333; }
input { width:100%; padding:12px; margin-top:6px; border:1px solid #ccc; border-radius:6px; font-size:1rem; transition:border-color 0.3s ease; }
input:focus { border-color:#ffcc00; outline:none; }
button { margin-top:20px; width:100%; padding:12px; background:#ffcc00; color:#333; border:none; border-radius:6px; font-weight:600; cursor:pointer; transition: all 0.3s ease; }
button:hover { background:#e6b800; transform:scale(1.02); }
.links { margin-top:18px; text-align:center; }
.links a { color:#333; text-decoration:none; transition:0.3s; }
.links a:hover { color:#ffcc00; }
</style>
</head>
<body>
<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>CareerScope</h1>
  <p>Set your new password</p>
</header>

<div class="sidebar" id="sidebar">
    <a href="index.php">Home</a>
    <a href="career-guidance.php">Career Guidance</a>
    <a href="careerpath.php">Career Path</a>
    <a href="about.php">About</a>
    <hr style="border:1px solid rgba(255,255,255,0.2);">
    <?php if(isset($_SESSION['fullName'])): ?>
        <a href="settings.php">Settings</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    <?php else: ?>
        <a href="login.php" style="color:#ffcc00;">Login</a>
    <?php endif; ?>
</div>
<div class="overlay" id="overlay"></div>

<div class="container">
    <form method="post">
        <h2>Reset Password</h2>
        <label>Email</label>
        <input type="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
        <label>New Password</label>
        <input type="password" name="newPassword" placeholder="Enter new password" required>
        <label>Confirm New Password</label>
        <input type="password" name="confirmPassword" placeholder="Confirm new password" required>
        <button type="submit" name="resetPassword">Set Password</button>
    </form>
    <div class="links"><a href="login.php">Back to Login</a></div>
</div>

<script>
const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
hamburger.addEventListener('click', () => { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); hamburger.classList.toggle('active'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('active'); overlay.classList.remove('active'); hamburger.classList.remove('active'); });
</script>
</body>
</html>
