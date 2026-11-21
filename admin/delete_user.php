<?php
// Start session and check auth
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $delete_id = (int)$_GET['id'];

    // Prevent user from accidentally deleting their own currently logged-in account through this link
    if ($delete_id === (int)$_SESSION['user_id']) {
        $_SESSION['flash_error'] = "You cannot delete your own account while logged in.";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $delete_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

mysqli_close($conn);

header("Location: display_reg_user.php");
exit();
?>