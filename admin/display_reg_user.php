<?php
// Start session and check auth
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");
include("../includes/function.php");

$page_title = "Registered Users - Admin";
include("../includes/header.php");
?>

<div class="main-content animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Registered Users</h1>
            <p class="subtitle mb-0">Manage registered user accounts.</p>
        </div>
        <a class="btn-gradient" href="../pages/register_user.php">
            <i class="bi bi-person-plus-fill"></i> Add User
        </a>
    </div>

    <!-- Table of registered users -->
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="premium-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Joined Date</th>
                        <th style="text-align: right; width: 140px;">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = mysqli_prepare($conn, "SELECT id, name, email, created_at FROM users ORDER BY id DESC");
                    mysqli_stmt_execute($stmt);
                    $run_fetch_data = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($run_fetch_data) > 0) {
                        while ($row = mysqli_fetch_assoc($run_fetch_data)) {
                    ?>
                            <tr>
                                <td><strong>#<?php echo $row['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="text-muted"><?php echo !empty($row['created_at']) ? date("M d, Y", strtotime($row['created_at'])) : '-'; ?></td>
                                <td style="text-align: right;">
                                    <a 
                                        href="delete_user.php?id=<?php echo $row['id']; ?>" 
                                        class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to delete this user? All their transactions will be affected.');"
                                    >
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No registered users found.
                            </td>
                        </tr>
                    <?php
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include("../includes/footer.php");
?>