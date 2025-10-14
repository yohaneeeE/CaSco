<?php
session_start();
include 'db_connect.php';

$isLoggedIn = isset($_SESSION['fullName']);
$fullName = $isLoggedIn ? $_SESSION['fullName'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Dashboard - Digital Career Guidance</title>
<link rel="icon" type="image/x-icon" href="img/em.png">

<style>
    /* Keep all your existing CSS here (header, sidebar, overlay, footer, etc.) */
</style>
</head>
<body>

<header>
    <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
    </div>
    <div>
        <h1>eMentor</h1>
        <p>Your personalized IT career dashboard</p>
    </div>
</header>

<!-- Include sidebar + footer -->
<?php include 'sidebar.php'; ?>

<main>
    <div class="container">
        <section class="description">
            <h2>Welcome to Your Dashboard</h2>
            <p style="text-align:center; max-width:800px; margin:auto;">
                This dashboard is your hub for navigating eMentor’s resources. 
                From discovering tailored career paths to gaining insights into the tech industry's 
                most in-demand skills, you're in the right place to plan your future in Information Technology.
            </p>
        </section>
    </div>

    <button class="career-readmaps-btn" onclick="window.location.href='career-guidance.php'">
        Click here to Explore Career Roadmaps
    </button>
</main>

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
