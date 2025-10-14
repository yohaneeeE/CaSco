<?php
session_start();
include 'db_connect.php';
include 'header.php';
include 'sidebar.php';
?>

<main class="container my-5" style="margin-left:260px;">
  <div class="card shadow border-0 p-4 text-center">
    <h2 class="fw-bold text-dark mb-3">Welcome to Your Dashboard</h2>
    <p class="lead text-muted mx-auto" style="max-width: 700px;">
      This dashboard is your hub for navigating eMentor’s resources.
      From discovering tailored career paths to gaining insights into the tech industry’s
      most in-demand skills, you're in the right place to plan your future in Information Technology.
    </p>
    <a href="career-guidance.php" class="btn btn-warning btn-lg mt-4 shadow-sm">
      <i class="bi bi-diagram-3 me-2"></i>Explore Career Roadmaps
    </a>
  </div>
</main>

<?php include 'footer.php'; ?>
