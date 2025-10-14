<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';

// ✅ Check if there's a pending reset from settings.php
if (!isset($_SESSION['pending_reset'])) {
    header("Location: settings.php");
    exit();
}

$pending = $_SESSION['pending_reset'];
$user_id = $pending['user_id'];
$reset_code = $pending['reset_code'];
$new_password = $pending['new_password'];

$success = $error = "";

// ✅ Verify the submitted code
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_code'])) {
    $entered_code = trim($_POST['code']);

    if ($entered_code == $reset_code) {
        // ✅ Update the password in DB
        $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si", $new_password, $user_id);

        if ($update->execute()) {
            unset($_SESSION['pending_reset']); // Clear session
            $success = "✅ Your password has been updated successfully!";
        } else {
            $error = "⚠️ Failed to update password. Please try again.";
        }
    } else {
        $error = "⚠️ Incorrect verification code.";
    }
}

// ✅ Handle resend code
if (isset($_POST['resend_code'])) {
    include 'reset_mail.php';
    $new_code = rand(100000, 999999);
    $_SESSION['pending_reset']['reset_code'] = $new_code;

    // Fetch user info
    $stmt = $conn->prepare("SELECT fullname, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if (sendForgotPasswordEmail($user['fullname'], $user['email'], $new_code)) {
        $success = "📩 A new verification code was sent to your email.";
    } else {
        $error = "⚠️ Failed to resend verification code.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Reset Code - eMentor</title>
<link rel="icon" type="image/x-icon" href="img/em.png">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #e6e6e6;
  color: #333;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

.container {
  background: #fff;
  width: 100%;
  max-width: 420px;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  text-align: center;
}

h2 {
  color: #444;
  margin-bottom: 10px;
}

p {
  color: #666;
  font-size: 15px;
  margin-bottom: 25px;
}

input[type="text"] {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 1rem;
  margin-bottom: 20px;
  text-align: center;
  letter-spacing: 4px;
}

button {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  background: #ffcc00;
  color: #333;
  font-weight: bold;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.3s ease;
}
button:hover {
  background: #ffdb4d;
  transform: scale(1.03);
}

.secondary-btn {
  margin-top: 10px;
  background: #ddd;
}
.secondary-btn:hover {
  background: #ccc;
}

.message {
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
  font-weight: 500;
}
.success { background: #d4edda; color: #155724; }
.error { background: #f8d7da; color: #721c24; }

@media (max-width: 480px) {
  .container { width: 90%; padding: 20px; }
  h2 { font-size: 1.4rem; }
}
</style>
</head>
<body>

<div class="container">
  <h2>🔐 Verify Your Code</h2>
  <p>Enter the 6-digit code we sent to your email to confirm your password change.</p>

  <?php if($success): ?><div class="message success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if($error): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <?php if(empty($success)): ?>
  <form method="POST">
    <input type="text" name="code" placeholder="Enter Code" maxlength="6" required>
    <button type="submit" name="verify_code">✅ Verify Code</button>
  </form>

  <form method="POST">
    <button type="submit" name="resend_code" class="secondary-btn">🔁 Resend Code</button>
  </form>
  <?php else: ?>
  <a href="settings.php"><button>⬅️ Back to Settings</button></a>
  <?php endif; ?>
</div>

</body>
</html>
