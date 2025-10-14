<?php
// --- Database credentials (from Hostinger MySQL settings) ---
$servername = "localhost"; // stays localhost on Hostinger
$dbusername = "u963833099_em_mentor"; 
$dbpassword = "Capstone2025;";
$dbname     = "u963833099_em_mentor";
$port       = 3306; // default MySQL port


try {
    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname, $port);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // ❌ Log actual error to server logs (safe)
    error_log("Database connection failed: " . $e->getMessage());
    die("<p style='color:red;'>❌ Database connection failed. Please try again later.</p>");
}
?>
