<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Dashboard - Digital Career Guidance</title>
<link rel="icon" type="image/x-icon" href="img/em.png">

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background-color: #f8f9fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Sidebar styling */
.offcanvas {
  background-color: #212529;
  color: #fff;
}
.offcanvas a {
  color: #f8f9fa;
  text-decoration: none;
}
.offcanvas a:hover {
  color: #ffcc00;
}

/* Sidebar footer */
.sidebar-footer {
  border-top: 1px solid #444;
  padding-top: 1rem;
  margin-top: auto;
  font-size: 0.9rem;
}
.sidebar-footer a {
  display: block;
  color: #ffcc00;
  text-decoration: none;
}
.sidebar-footer a:hover {
  color: #fff;
}

/* Buttons */
.btn-warning {
  background-color: #ffcc00;
  border: none;
}
.btn-warning:hover {
  background-color: #003060;
  color: #fff;
}

/* Page Footer */
footer.page-footer {
  background: linear-gradient(135deg, #343a40, #495057);
  color: #fff;
  margin-top: auto;
  padding: 1rem 0;
}
.footer-links a {
  color: #ffcc00;
  text-decoration: none;
}
.footer-links a:hover {
  color: #fff;
}

/* Card */
.card {
  border-radius: 1rem;
}
.card h2 {
  font-size: 1.6rem;
}
</style>
</head>
<body>
