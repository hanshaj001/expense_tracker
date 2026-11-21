<?php
// Start session for auth and flash messages
session_start();

// 1. Authentication check - User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");

$user_id = (int)$_SESSION['user_id'];

// 2. Authorization check - Only delete transaction if it belongs to logged-in user
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $transaction_id = (int)$_GET['id'];

    // Prepared statement checking both transaction ID and user_id (prevents IDOR vulnerability)
    $stmt = mysqli_prepare($conn, "DELETE FROM transactions WHERE id = ? AND user_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $transaction_id, $user_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['flash_success'] = "Transaction deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Transaction not found or you don't have permission to delete it.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['flash_error'] = "Failed to prepare database statement.";
    }
} else {
    $_SESSION['flash_error'] = "Invalid transaction ID.";
}

mysqli_close($conn);

// Redirect back to transaction list with exit
header("Location: transaction.php");
exit();
?>