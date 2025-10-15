<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';
include 'reset_mail.php'; // Mail function

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$success = $error = "";

// ✅ Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $fullname = !empty($_POST['fullname']) ? $_POST['fullname'] : $user['fullname'];
    $email = !empty($_POST['email']) ? $_POST['email'] : $user['email'];

    // Check if profile_image column exists
    $hasProfileImage = false;
    $colCheck = $conn->query("SHOW COLUMNS FROM users LIKE 'profile_image'");
    if ($colCheck && $colCheck->num_rows > 0) $hasProfileImage = true;

    if ($hasProfileImage) {
        $profile_image = $user['profile_image'];
        if (!empty($_FILES['profile_image']['name'])) {
            $target_dir = "uploads/profile_images/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $filename = time() . "_" . basename($_FILES["profile_image"]["name"]);
            $target_file = $target_dir . $filename;
            move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
            $profile_image = $filename;
        }

        $update = $conn->prepare("UPDATE users SET fullname=?, email=?, profile_image=? WHERE id=?");
        $update->bind_param("sssi", $fullname, $email, $profile_image, $user_id);
    } else {
        $update = $conn->prepare("UPDATE users SET fullname=?, email=? WHERE id=?");
        $update->bind_param("ssi", $fullname, $email, $user_id);
    }

    if ($update->execute()) {
        $success = "✅ Profile updated successfully!";
        $_SESSION['user_name'] = $fullname;
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $error = "⚠️ Failed to update profile.";
    }
}

// ✅ Password Change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (password_verify($current, $user['password'])) {
        if ($new === $confirm) {
            $resetCode = rand(100000, 999999);
            $_SESSION['pending_reset'] = [
                'user_id' => $user_id,
                'new_password' => password_hash($new, PASSWORD_DEFAULT),
                'reset_code' => $resetCode
            ];

            $mailSent = sendForgotPasswordEmail($user['fullname'], $user['email'], $resetCode);
            if ($mailSent) {
                header("Location: verify_reset.php");
                exit();
            } else {
                $error = "⚠️ Failed to send verification email.";
            }
        } else {
            $error = "⚠️ New passwords do not match.";
        }
    } else {
        $error = "⚠️ Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account Settings | CareerScope</title>
  <link rel="icon" type="image/x-icon" href="img/cs.png" />

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      color: #333;
    }

    /* HEADER */
    header {
      background: linear-gradient(135deg, #666, #888);
      color: white;
      padding: 25px 0;
      text-align: center;
      position: relative;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    header h1 {
      font-size: 2.5rem;
      margin-bottom: 8px;
    }

    header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

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

    .sidebar.active {
      left: 0;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      font-size: 1.1rem;
      padding: 8px 0;
      display: block;
      transition: 0.3s;
    }

    .sidebar a:hover {
      color: #ffcc00;
      transform: translateX(5px);
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease;
      z-index: 100;
    }

    .overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* MAIN CONTAINER */
    .container {
      max-width: 900px;
      margin: 60px auto;
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
      position: relative;
    }

    h2::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(90deg, #666, #ffcc00);
      border-radius: 3px;
    }

    h3 {
      margin-top: 25px;
      color: #444;
      border-bottom: 2px solid #eee;
      padding-bottom: 5px;
      font-size: 1.2rem;
    }

    .profile-pic {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .profile-pic img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 3px solid #555;
      object-fit: cover;
      transition: 0.3s;
    }

    .profile-pic img:hover { transform: scale(1.05); }

    input[type=text],
    input[type=email],
    input[type=password],
    input[type=file] {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin: 8px 0 15px 0;
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    input:focus {
      border-color: #ffcc00;
      outline: none;
    }

    button {
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      background: #ffcc00;
      color: #333;
      font-weight: bold;
      cursor: pointer;
      display: block;
      width: 60%;
      max-width: 250px;
      margin: 15px auto;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #ffdb4d;
      transform: scale(1.05);
    }

    .message {
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 15px;
      text-align: center;
      font-weight: 500;
    }

    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }

    footer {
      text-align: center;
      padding: 20px;
      background: #444;
      color: #ddd;
      margin-top: 40px;
      font-size: 0.95rem;
    }

    footer a {
      color: #ffcc00;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .container { width: 90%; padding: 25px; }
      header h1 { font-size: 1.8rem; }
      button { width: 100%; }
    }
  </style>
</head>

<body>

<header>
  <div class="hamburger" id="hamburger">
    <span></span><span></span><span></span>
  </div>
  <h1>CareerScope</h1>
  <p>Manage your profile and security settings</p>
</header>

<div class="sidebar" id="sidebar">
  <a href="index.php">Home</a>
  <a href="career-guidance.php">Career Guidance</a>
  <a href="careerpath.php">Career Path</a>
  <a href="about.php">About</a>
  <hr style="border:1px solid rgba(255,255,255,0.2);">
  <a href="settings.php" style="color:#ffcc00;">⚙️ Settings</a>
  <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>

<div class="overlay" id="overlay"></div>

<div class="container">
  <h2>👤 Account Settings</h2>

  <?php if ($success): ?><div class="message success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <div class="profile-pic">
    <img src="<?= isset($user['profile_image']) && !empty($user['profile_image']) ? 'uploads/profile_images/' . htmlspecialchars($user['profile_image']) : 'img/default.png' ?>" alt="Profile Picture">
  </div>

  <form method="POST" enctype="multipart/form-data">
    <h3>Profile Information</h3>
    <label>Full Name</label>
    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">

    <label>Profile Picture</label>
    <input type="file" name="profile_image" accept="image/*">

    <button type="submit" name="update_profile">💾 Save Changes</button>
  </form>

  <form method="POST">
    <h3>Change Password</h3>
    <label>Current Password</label>
    <input type="password" name="current_password">

    <label>New Password</label>
    <input type="password" name="new_password">

    <label>Confirm Password</label>
    <input type="password" name="confirm_password">

    <button type="submit" name="change_password">🔑 Update Password</button>
  </form>
</div>

<footer>
  &copy; <?= date("Y") ?> CareerScope | A Capstone Project by IT Students of Bulacan State University - Bustos Campus
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
</script>

</body>
</html>
