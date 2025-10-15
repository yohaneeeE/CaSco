<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connect.php';

$success = $error = "";

// ✅ Ensure there is a pending registration
if (!isset($_SESSION['pending_verification_email'])) {
    header("Location: register.php");
    exit();
}

$email = $_SESSION['pending_verification_email'];

// Initialize attempt counter
if (!isset($_SESSION['verification_attempts'])) {
    $_SESSION['verification_attempts'] = 0;
}

// ✅ Handle verification submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_code'])) {
    $enteredCode = trim($_POST['code']);
    
    $stmt = $conn->prepare("SELECT * FROM pending_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user) {
        if ($enteredCode == $user['verification_code']) {
            // ✅ Move user to the main users table
            $insert = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $user['fullName'], $user['email'], $user['password']);
            if ($insert->execute()) {
                // Remove pending user
                $del = $conn->prepare("DELETE FROM pending_users WHERE email=?");
                $del->bind_param("s", $email);
                $del->execute();
                $del->close();

                unset($_SESSION['pending_verification_email']);
                unset($_SESSION['verification_attempts']);

                $success = "✅ Your account has been successfully verified!";
                header("refresh:2; url=login.php");
            } else {
                $error = "⚠️ Failed to save your account. Please try again.";
            }
        } else {
            $_SESSION['verification_attempts']++;
            $remaining = 3 - $_SESSION['verification_attempts'];

            if ($remaining <= 0) {
                unset($_SESSION['pending_verification_email']);
                unset($_SESSION['verification_attempts']);
                echo "<script>alert('❌ Too many failed attempts. Please register again.'); window.location.href='register.php';</script>";
                exit();
            } else {
                $error = "⚠️ Incorrect code. You have {$remaining} attempt(s) left.";
            }
        }
    } else {
        $error = "⚠️ No pending verification found. Please register again.";
        header("refresh:2; url=register.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Email Verification | CareerScope</title>
<link rel="icon" type="image/x-icon" href="img/cs.png">
<style>
/* === CareerScope Theme Consistency === */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f2f2f2;
    color: #333;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.container {
    background: #fff;
    padding: 40px 35px;
    width: 100%;
    max-width: 420px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-align: center;
    animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}

.logo {
    width: 70px;
    height: 70px;
    margin-bottom: 15px;
}

h2 {
    color: #444;
    font-weight: 600;
    margin-bottom: 10px;
}

p {
    color: #555;
    font-size: 15px;
    margin-bottom: 25px;
}

input[type="text"] {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 16px;
    text-align: center;
    letter-spacing: 3px;
    margin-bottom: 20px;
    outline: none;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus {
    border-color: #ffcc00;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}

.verify-btn {
    background-color: #ffcc00;
    color: #333;
}
.verify-btn:hover {
    background-color: #e6b800;
    box-shadow: 0 0 10px rgba(255,204,0,0.5);
}

.back-btn {
    background-color: #d9d9d9;
    color: #333;
    margin-top: 10px;
}
.back-btn:hover {
    background-color: #c2c2c2;
}

.message {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}
.success {
    background: #d4edda;
    color: #155724;
}
.error {
    background: #f8d7da;
    color: #721c24;
}

/* Responsive */
@media (max-width: 480px) {
    .container {
        padding: 30px 25px;
    }
    h2 {
        font-size: 1.4rem;
    }
}
</style>
</head>
<body>

<div class="container">
    <img src="img/cs.png" alt="CareerScope Logo" class="logo">
    <h2>Verify Your Account</h2>
    <p>Enter the 6-digit verification code we sent to your email.</p>

    <?php if($success): ?><div class="message success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if($error): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <?php if(empty($success)): ?>
    <form method="POST">
        <input type="text" name="code" placeholder="Enter Code" maxlength="6" required>
        <button type="submit" name="verify_code" class="verify-btn">Verify</button>
    </form>
    <a href="register.php"><button type="button" class="back-btn">Back to Register</button></a>
    <?php else: ?>
    <a href="login.php"><button type="button" class="verify-btn">Continue to Login</button></a>
    <?php endif; ?>
</div>

</body>
</html>
