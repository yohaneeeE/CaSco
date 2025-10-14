<?php
$isLoggedIn = isset($_SESSION['fullName']);
$fullName = $isLoggedIn ? $_SESSION['fullName'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eMentor Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Sidebar Styling */
    #sidebar {
      width: 250px;
      transition: all 0.3s ease;
      margin-top: 56px; /* height of navbar */
    }

    #sidebar .nav-link {
      border-radius: 6px;
      transition: 0.2s;
    }
    #sidebar .nav-link:hover {
      background-color: rgba(255, 204, 0, 0.2);
      color: #ffcc00;
    }

    /* Main Content Adjustment */
    @media (min-width: 992px) {
      main {
        margin-left: 250px;
        transition: margin-left 0.3s ease;
      }
    }
    @media (max-width: 991.98px) {
      #sidebar {
        display: none !important;
      }
    }

    /* Ensure toggle always clickable */
    #sidebarToggle {
      z-index: 1100;
      position: relative;
    }
  </style>
</head>

<body class="bg-light">

  <!-- NAVBAR -->
  <nav class="navbar navbar-dark bg-dark shadow sticky-top" style="z-index: 1040;">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <!-- Sidebar Toggle Button -->
      <button id="sidebarToggle" class="btn btn-outline-light" type="button">
        <i class="bi bi-list fs-5"></i>
      </button>

      <!-- Title -->
      <span class="navbar-brand fw-bold text-warning">eMentor Dashboard</span>

      <!-- Logo -->
      <img src="img/em.png" alt="Logo" width="40" height="40" class="rounded-circle border border-light">
    </div>
  </nav>

  <!-- SIDEBAR (Desktop) -->
  <nav id="sidebar" class="bg-dark text-light position-fixed top-0 start-0 vh-100 p-3 d-flex flex-column" style="z-index:1030;">
    <h5 class="text-warning mb-4">eMentor</h5>

    <ul class="nav flex-column mb-4">
      <li class="nav-item"><a href="index.php" class="nav-link text-light"><i class="bi bi-house-door me-2"></i>Home</a></li>
      <li class="nav-item"><a href="career-guidance.php" class="nav-link text-light"><i class="bi bi-diagram-3 me-2"></i>Career Guidance</a></li>
      <li class="nav-item"><a href="careerpath.php" class="nav-link text-light"><i class="bi bi-signpost me-2"></i>Career Path</a></li>
      <li class="nav-item"><a href="about.php" class="nav-link text-light"><i class="bi bi-info-circle me-2"></i>About</a></li>
      <hr class="border-secondary">
      <?php if ($isLoggedIn): ?>
        <li class="nav-item"><a href="settings.php" class="nav-link text-light"><i class="bi bi-gear me-2"></i>Settings</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      <?php else: ?>
        <li class="nav-item"><a href="login.php" class="nav-link text-light"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
      <?php endif; ?>
    </ul>

    <?php if ($isLoggedIn): ?>
      <div class="text-center border-top border-secondary pt-3 small text-warning">
        Logged in as <br><strong><?php echo htmlspecialchars($fullName); ?></strong>
      </div>
    <?php endif; ?>
  </nav>

  <!-- OFFCANVAS (Mobile Sidebar) -->
  <div class="offcanvas offcanvas-start bg-dark text-light" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header border-bottom border-secondary">
      <h5 class="offcanvas-title text-warning" id="mobileSidebarLabel">eMentor</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column mb-4">
        <li class="nav-item"><a href="index.php" class="nav-link text-light"><i class="bi bi-house-door me-2"></i>Home</a></li>
        <li class="nav-item"><a href="career-guidance.php" class="nav-link text-light"><i class="bi bi-diagram-3 me-2"></i>Career Guidance</a></li>
        <li class="nav-item"><a href="careerpath.php" class="nav-link text-light"><i class="bi bi-signpost me-2"></i>Career Path</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link text-light"><i class="bi bi-info-circle me-2"></i>About</a></li>
        <hr class="border-secondary">
        <?php if ($isLoggedIn): ?>
          <li class="nav-item"><a href="settings.php" class="nav-link text-light"><i class="bi bi-gear me-2"></i>Settings</a></li>
          <li class="nav-item"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a href="login.php" class="nav-link text-light"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <main class="p-4">
    <div class="card p-4 shadow-sm">
      <h3>Welcome to eMentor</h3>
      <p>This is your dashboard content.</p>
    </div>
  </main>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar Toggle Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const mobileSidebar = document.getElementById('mobileSidebar');
      let sidebarVisible = true;

      sidebarToggle.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (window.innerWidth < 992) {
          const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(mobileSidebar);
          offcanvas.toggle();
          return;
        }

        // Desktop Sidebar Slide
        if (sidebarVisible) {
          sidebar.style.marginLeft = "-250px";
          document.querySelector('main').style.marginLeft = "0";
        } else {
          sidebar.style.marginLeft = "0";
          document.querySelector('main').style.marginLeft = "250px";
        }
        sidebarVisible = !sidebarVisible;
      });
    });
  </script>

</body>
</html>
