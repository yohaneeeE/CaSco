<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "em_mentor";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
