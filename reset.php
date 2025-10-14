<?php
session_start();
include 'db_connect.php';
include 'reset_mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🟦 STEP 1: SEND RESET CODE
    if (isset($_POST['sendCode'])) {
        $email = trim($_POST['resetEmail']);

        if (empty($email)) {
            echo "<script>alert('Please enter your email address.');</script>";
        } else {
            // Check if email exists in users
            $stmt = $conn->prepare("SELECT fullName FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($fullName);
            if ($stmt->fetch()) {
                $stmt->close();

                // Generate reset code
                $resetCode = rand(100000, 999999);

                // Remove any existing pending reset
                $conn->query("DELETE FROM pending_users WHERE email = '$email'");

                // Insert pending record for this reset
                $stmt = $conn->prepare("INSERT INTO pending_users (fullName, email, verification_code) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $fullName, $email, $resetCode);
                $stmt->execute();

                // Send reset email
                if (sendForgotPasswordEmail($fullName, $email, $resetCode)) {
                    echo "<script>alert('A password reset code was sent to your email.');</script>";
                } else {
                    echo "<script>alert('Failed to send reset email. Please try again later.');</script>";
                }
            } else {
                echo "<script>alert('Email not found in our records.');</script>";
            }
        }
    }

    // 🟩 STEP 2: VERIFY CODE AND UPDATE PASSWORD
    if (isset($_POST['resetPassword'])) {
        $email = trim($_POST['resetEmail']);
        $code = trim($_POST['resetCode']);
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if (!$email || !$code || !$newPassword || !$confirmPassword) {
            echo "<script>alert('All fields are required.');</script>";
        } elseif ($newPassword !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.');</script>";
        } else {
            // Verify reset code in pending_users
            $stmt = $conn->prepare("SELECT id FROM pending_users WHERE email = ? AND verification_code = ?");
            $stmt->bind_param("ss", $email, $code);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->close();
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update password in main users table
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param("ss", $hashedPassword, $email);
                if ($stmt->execute()) {
                    // Remove the pending record
                    $conn->query("DELETE FROM pending_users WHERE email = '$email'");
                    echo "<script>alert('Password reset successful! You can now log in.'); window.location='login.php';</script>";
                } else {
                    echo "<script>alert('Error updating password.');</script>";
                }
            } else {
                echo "<script>alert('Invalid reset code or email.');</script>";
            }
            $stmt->close();
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
<title>Reset Password | eMentor</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#e6e6e6;
    color: grey;
    line-height:1.6;
}

/* Header */
header {
    background: linear-gradient(135deg, #444, #666);
    color:white;
    padding:25px 0;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.2);
    position:relative;
}
header h1 { font-size:2.5rem; margin-bottom:10px; }
header p { font-size:1.1rem; opacity:0.9; }

/* Hamburger */
.hamburger {
    position:absolute; top:20px; left:20px;
    width:30px; height:22px;
    display:flex; flex-direction:column;
    justify-content:space-between;
    cursor:pointer; z-index:300;
    transition: transform 0.3s ease;
}
.hamburger span {
    height:4px; background:white;
    border-radius:2px;
    transition: all 0.3s ease;
}
.hamburger:hover { transform: scale(1.1); }
.hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
.hamburger.active span:nth-child(2) { opacity:0; }
.hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px,-6px); }

/* Sidebar */
.sidebar {
    position: fixed; top:0; left:-250px;
    width:250px; height:100%;
    background:#333; color:white;
    padding:60px 20px; display:flex; flex-direction:column; gap:20px;
    transition:left 0.3s ease; z-index:200;
}
.sidebar.active { left:0; }
.sidebar a {
    color:white; text-decoration:none; font-size:1.1rem; padding:8px 0;
    display:block; transition: color 0.3s ease, transform 0.2s ease;
}
.sidebar a:hover { color:#ffcc00; transform:translateX(5px); }

/* Overlay */
.overlay {
    position:fixed; top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.4);
    opacity:0; visibility:hidden;
    transition: opacity 0.3s ease; z-index:100;
}
.overlay.active { opacity:1; visibility:visible; }

/* Container */
.container {
    max-width:420px; margin:50px auto;
    background:#fff; padding:25px; border-radius:8px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
}
h2 { text-align:center; color:#444; margin-bottom:20px; }
label { font-weight:bold; display:block; margin-top:10px; }
input { width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; border-radius:5px; }
button {
    margin-top:15px; width:100%; padding:10px;
    background:#ffcc00; color:#004080; border:none; border-radius:5px;
    font-weight:600; cursor:pointer; transition: all 0.3s ease;
}
button:hover { background:#e6b800; }
.links { margin-top:15px; text-align:center; }
.links a { color:#004080; text-decoration:none; transition: all 0.3s ease; }
.links a:hover { color:#ffcc00; }

/* Responsive */
@media(max-width:480px) { .container { margin:30px 15px; } }
</style>
</head>
<body>

<header>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <h1>eMentor</h1>
  <p>Empowering students with data-driven career guidance</p>
</header>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="dashboard.php">Home</a>
    <a href="career-guidance.php">Career Guidance</a>
    <a href="careerpath.php">Career Path</a>
    <a href="about.php">About</a>
    <hr style="border:1px solid rgba(255,255,255,0.2);">
    <?php if(isset($_SESSION['fullName'])): ?>
        <a href="settings.php">Settings</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</div>

<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>Forgot Password</h2>
  <form method="post">
    <label>Email</label>
    <input type="email" name="resetEmail" required>
    <button type="submit" name="sendCode">Send Reset Code</button>
  </form>

  <hr style="margin:25px 0; border:1px solid #ddd;">

  <h2>Reset Password</h2>
  <form method="post">
    <label>Email</label>
    <input type="email" name="resetEmail" required>
    <label>Reset Code</label>
    <input type="text" name="resetCode" required>
    <label>New Password</label>
    <input type="password" name="newPassword" required>
    <label>Confirm New Password</label>
    <input type="password" name="confirmPassword" required>
    <button type="submit" name="resetPassword">Confirm Reset</button>
  </form>

  <div class="links">
    <p><a href="login.php">Back to Login</a></p>
  </div>
</div>

<script>
const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    hamburger.classList.toggle('active');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    hamburger.classList.remove('active');
});
</script>

</body>
</html>
