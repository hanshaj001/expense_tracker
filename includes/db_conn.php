<?php
// ============================================================================
// DATABASE CONNECTION CONFIGURATION
// Establishes MySQL connection with utf8mb4 encoding & timezone configuration
// ============================================================================

$servername = "localhost:3306";
$username   = "root";
$password   = "";  // Default XAMPP password is empty
$db_name    = "expense_tracker";

// Connect to MySQL database
$conn = mysqli_connect($servername, $username, $password, $db_name);

// Handle connection errors gracefully
if (!$conn) {
    die("Database Connection failed: " . mysqli_connect_error());
}

// Set UTF-8 character encoding to support special characters and currencies
mysqli_set_charset($conn, "utf8mb4");
?>
