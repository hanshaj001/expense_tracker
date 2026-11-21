<?php
// ==========================================================================
// HELPER FUNCTIONS
// Simple reusable functions for notifications, security, and auth checks
// ==========================================================================

// Check if user is authenticated; if not, safely redirect to login page
function check_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }
}

// Display Bootstrap dismissible alert message with proper closing tag
function my_alert($color, $msg)
{
?>
    <div class="alert alert-<?php echo htmlspecialchars($color); ?> alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
        <?php echo $msg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>