<?php
// Start session for auth checks
session_start();

// 1. Authentication check - Must be checked BEFORE any HTML is sent
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");
include("../includes/function.php");

$user_id = (int)$_SESSION['user_id'];
$errors  = [];
$success = '';

// Handle setting / updating weekly category limit
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['save_limit'])) {
    $category_id = trim($_POST['categoryId'] ?? '');
    $limit_amt   = trim($_POST['weeklyLimit'] ?? '');

    if (!is_numeric($limit_amt) || $limit_amt < 0) {
        $errors['limit'] = "Weekly limit must be a positive number or 0.";
    } elseif (empty($category_id) || !is_numeric($category_id)) {
        $errors['limit'] = "Invalid category selected.";
    } else {
        $cat_id = (int)$category_id;
        $amt    = (float)$limit_amt;

        // Check if a weekly limit already exists for this user and category
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM weekly_limits WHERE category_id = ? AND user_id = ?");
        mysqli_stmt_bind_param($check_stmt, "ii", $cat_id, $user_id);
        mysqli_stmt_execute($check_stmt);
        $check_res = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_res) > 0) {
            // Update existing limit
            $update_stmt = mysqli_prepare($conn, "UPDATE weekly_limits SET weekly_limit = ?, updated_at = NOW() WHERE category_id = ? AND user_id = ?");
            mysqli_stmt_bind_param($update_stmt, "dii", $amt, $cat_id, $user_id);
            if (mysqli_stmt_execute($update_stmt)) {
                $success = "Category limit updated successfully!";
            } else {
                $errors['limit'] = "Failed to update category limit.";
            }
            mysqli_stmt_close($update_stmt);
        } else {
            // Insert new limit
            $insert_stmt = mysqli_prepare($conn, "INSERT INTO weekly_limits (user_id, category_id, weekly_limit) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "iid", $user_id, $cat_id, $amt);
            if (mysqli_stmt_execute($insert_stmt)) {
                $success = "Category limit set successfully!";
            } else {
                $errors['limit'] = "Failed to set category limit.";
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}

// Fetch all active weekly limits for the current user
$limits_map = [];
$limit_stmt = mysqli_prepare($conn, "SELECT category_id, weekly_limit FROM weekly_limits WHERE user_id = ?");
mysqli_stmt_bind_param($limit_stmt, "i", $user_id);
mysqli_stmt_execute($limit_stmt);
$limit_res = mysqli_stmt_get_result($limit_stmt);
while ($l_row = mysqli_fetch_assoc($limit_res)) {
    $limits_map[$l_row['category_id']] = (float)$l_row['weekly_limit'];
}
mysqli_stmt_close($limit_stmt);

// Fetch categories from DB grouped by type (Expense & Income)
$expense_cats = [];
$income_cats  = [];
$cat_query = mysqli_query($conn, "SELECT id, name, type FROM categories ORDER BY id ASC");
while ($c_row = mysqli_fetch_assoc($cat_query)) {
    if ($c_row['type'] === 'expense') {
        $expense_cats[] = $c_row;
    } else {
        $income_cats[] = $c_row;
    }
}

// Icon helper for standard categories
function getCategoryIcon($name) {
    $map = [
        'Food & Groceries'   => 'fas fa-shopping-cart',
        'Rent'               => 'fas fa-home',
        'Transportation'     => 'fas fa-bus',
        'Utilities'          => 'fas fa-lightbulb',
        'Internet & Mobile'  => 'fas fa-wifi',
        'Education'          => 'fas fa-graduation-cap',
        'Healthcare'         => 'fas fa-heartbeat',
        'Entertainment'      => 'fas fa-film',
        'Shopping'           => 'fas fa-shopping-bag',
        'Other Expenses'     => 'fas fa-ellipsis-h',
        'Salary'             => 'fas fa-money-bill-wave',
        'Business Income'    => 'fas fa-briefcase',
        'Freelance Work'     => 'fas fa-laptop-code',
        'Investment Returns' => 'fas fa-chart-line',
        'Rental Income'      => 'fas fa-building',
        'Bonus'              => 'fas fa-award',
        'Interest Income'    => 'fas fa-percentage',
        'Gifts Received'     => 'fas fa-gift',
        'Dividends'          => 'fas fa-coins',
        'Other Income'       => 'fas fa-plus-circle'
    ];
    return $map[$name] ?? 'fas fa-tag';
}

$page_title = "Categories & Limits - Expense Tracker";
include("../includes/header.php");
?>

<div class="main-content animate-fade-in">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Categories & Budget Limits</h1>
            <p class="subtitle mb-0">Set weekly expenditure limits on categories to keep your spending in check.</p>
        </div>
    </div>

    <!-- Notification Alerts -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors['limit'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($errors['limit']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- ==================== EXPENSE CATEGORIES ==================== -->
    <div class="d-flex align-items-center gap-2 mb-3 mt-2">
        <i class="bi bi-credit-card-2-front text-danger fs-5"></i>
        <h4 class="fw-bold mb-0" style="font-size: 18px;">Expense Categories (Weekly Limits)</h4>
    </div>

    <div class="row g-3 mb-5">
        <?php foreach ($expense_cats as $cat): 
            $cat_id   = $cat['id'];
            $has_limit = isset($limits_map[$cat_id]);
            $limit_val = $has_limit ? $limits_map[$cat_id] : null;
        ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="glass-card h-100 d-flex flex-column justify-content-between" style="padding: 20px; border-left: 4px solid var(--expense);">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: var(--expense-light); color: var(--expense); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="<?php echo getCategoryIcon($cat['name']); ?>"></i>
                            </div>
                            <div class="fw-bold text-dark" style="font-size: 15px;"><?php echo htmlspecialchars($cat['name']); ?></div>
                        </div>

                        <div class="text-muted" style="font-size: 13px; margin-bottom: 12px;">
                            Weekly Limit: 
                            <?php if ($has_limit): ?>
                                <strong class="text-danger">Rs. <?php echo number_format($limit_val, 2); ?></strong>
                            <?php else: ?>
                                <span class="badge bg-light text-muted">Not Set</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        class="btn btn-sm btn-outline-primary w-100"
                        onclick="openLimitModal(<?php echo $cat_id; ?>, '<?php echo addslashes(htmlspecialchars($cat['name'])); ?>', <?php echo $has_limit ? $limit_val : 0; ?>)"
                    >
                        <i class="bi bi-pencil-square"></i> <?php echo $has_limit ? 'Update Limit' : 'Set Limit'; ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ==================== INCOME CATEGORIES ==================== -->
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-cash-coin text-success fs-5"></i>
        <h4 class="fw-bold mb-0" style="font-size: 18px;">Income Categories</h4>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach ($income_cats as $cat): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="glass-card" style="padding: 16px 20px; border-left: 4px solid var(--income);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 38px; height: 38px; border-radius: var(--radius-sm); background: var(--income-light); color: var(--income); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                            <i class="<?php echo getCategoryIcon($cat['name']); ?>"></i>
                        </div>
                        <div class="fw-bold text-dark" style="font-size: 14px;"><?php echo htmlspecialchars($cat['name']); ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- Set Weekly Limit Modal -->
<div class="modal fade" id="limitModal" tabindex="-1" aria-labelledby="limitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--radius-lg); border: none; overflow: hidden;">

            <div class="modal-header" style="background: var(--bg-main); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title fw-bold" id="limitModalLabel">Set Weekly Budget Limit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="">
                <div class="modal-body p-4">
                    <input type="hidden" name="categoryId" id="modalCategoryId">

                    <div class="form-group mb-3">
                        <label for="modalCategoryName">Category</label>
                        <input type="text" class="form-control" id="modalCategoryName" readonly style="background: #f1f5f9;">
                    </div>

                    <div class="form-group mb-0">
                        <label for="modalWeeklyLimit">Weekly Limit Amount (Rs.)</label>
                        <div class="input-wrapper">
                            <span class="input-icon"><i class="fas fa-rupee-sign"></i></span>
                            <input 
                                type="number" 
                                class="form-control" 
                                name="weeklyLimit" 
                                id="modalWeeklyLimit"
                                placeholder="Enter limit (e.g. 5000)" 
                                min="0" 
                                step="50" 
                                required
                            >
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3" style="background: var(--bg-main); border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_limit" class="btn-gradient">
                        <i class="bi bi-check2"></i> Save Limit
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function openLimitModal(categoryId, categoryName, currentLimit) {
        document.getElementById('modalCategoryId').value = categoryId;
        document.getElementById('modalCategoryName').value = categoryName;
        document.getElementById('modalWeeklyLimit').value = currentLimit > 0 ? currentLimit : '';

        let modal = new bootstrap.Modal(document.getElementById('limitModal'));
        modal.show();
    }
</script>

<?php
mysqli_close($conn);
include("../includes/footer.php");
?>