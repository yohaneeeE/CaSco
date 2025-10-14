<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';
include 'reset_mail.php'; // ✅ include your mail function

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$success = $error = "";

// ✅ Handle profile update
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
        // Refresh user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $error = "⚠️ Failed to update profile.";
    }
}

// ✅ Handle password change (with mail)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (password_verify($current, $user['password'])) {
        if ($new === $confirm) {
            // Generate reset verification code
            $resetCode = rand(100000, 999999);

            // Store the code in DB or session
            $_SESSION['pending_reset'] = [
                'user_id' => $user_id,
                'new_password' => password_hash($new, PASSWORD_DEFAULT),
                'reset_code' => $resetCode
            ];

            // Send email
            $mailSent = sendForgotPasswordEmail($user['fullname'], $user['email'], $resetCode);

            if ($mailSent) {
                header("Location: verify_reset.php");
                exit();
            } else {
                $error = "⚠️ Failed to send verification email. Please try again.";
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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Settings - eMentor</title>
<link rel="icon" type="image/x-icon" href="img/em.png">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #e6e6e6;
  color: #333;
}
header {
  background: linear-gradient(135deg, #444, #666);
  color: #fff;
  padding: 20px;
  text-align: center;
}
header h1 { font-size: 2rem; margin-bottom: 5px; }
header p { opacity: 0.9; }

.container {
  max-width: 900px;
  margin: 40px auto;
  background: #fff;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}


/* BUTTONS */
button {
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: 0.3s;
}
button#backBtn {
  background: #333; color: #ffcc00;
}
button#backBtn:hover { background: #555; }
button#printBtn {
  background: #555; color: #fff;
}
button#printBtn:hover { background: #777; }

h2 {
  text-align: center;
  color: #444;
  margin-bottom: 20px;
}

h3 {
  margin-top: 25px;
  color: #555;
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
  object-fit: cover;
  border: 3px solid #555;
  transition: 0.3s;
}
.profile-pic img:hover {
  transform: scale(1.05);
}

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

form {
  margin-top: 15px;
}

@media (max-width: 768px) {
  .container {
    width: 90%;
    padding: 20px;
  }
  header h1 {
    font-size: 1.6rem;
  }
  button {
    width: 100%;
    font-size: 1rem;
  }
  input {
    font-size: 0.95rem;
  }
}
</style>
</head>
<body>

<header>
  <h1>eMentor</h1>
  <p>Manage your profile and security settings</p>
</header>

<div class="container">
  <h2>👤 Account Settings</h2>

  <?php if($success): ?><div class="message success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if($error): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

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

</body>
</html>
