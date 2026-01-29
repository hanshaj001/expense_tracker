<?php
$servername = "localhost:3306";
$username = "root";
$password = "";  // no password
$db_name = "expense_tracker";

$conn = mysqli_connect($servername, $username, $password, $db_name);

if (!$conn) {
    die(" Connection failed: " . mysqli_connect_error());
}
?>
