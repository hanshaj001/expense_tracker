<?php
// Start session to access session data
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy session cookies if present
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect safely to login page
header("Location: ../index.php");
exit();
?>