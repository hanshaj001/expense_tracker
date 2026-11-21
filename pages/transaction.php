<?php
// Start session for auth and messages
session_start();

// 1. Authentication check - Must be checked BEFORE any HTML is sent
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");
include("../includes/function.php");

$user_id = (int)$_SESSION['user_id'];

// Flash messages from operations (e.g., delete, edit)
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Filter & Search parameters
$search_term = trim($_GET['search'] ?? '');
$filter_type = trim($_GET['type'] ?? '');

// Build parameterized query with LEFT JOIN for optimal performance and avoiding N+1 queries
$sql = "SELECT t.id, t.user_id, t.category_id, t.type, t.amount, t.notes, t.created_at, t.updated_at,
               c.name AS category_name, c.type AS category_type
        FROM transactions t
        LEFT JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ?";

$params = [$user_id];
$types  = "i";

// Filter by type if provided (income or expense)
if (!empty($filter_type) && in_array($filter_type, ['income', 'expense'])) {
    $sql .= " AND t.type = ?";
    $params[] = $filter_type;
    $types   .= "s";
}

// Search by category name or notes if provided
if (!empty($search_term)) {
    $sql .= " AND (c.name LIKE ? OR t.notes LIKE ?)";
    $like_term = "%" . $search_term . "%";
    $params[] = $like_term;
    $params[] = $like_term;
    $types   .= "ss";
}

$sql .= " ORDER BY t.created_at DESC, t.id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Set page title and load unified header with sidebar
$page_title = "Transactions - Expense Tracker";
include("../includes/header.php");
?>

<!-- Main Content Area -->
<div class="main-content animate-fade-in">

    <!-- Page Header & Flash Messages -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">Transactions</h1>
            <p class="subtitle mb-0">View, search, and manage all your income and expense records.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="add_income.php" class="btn-gradient" style="background: var(--income);">
                <i class="bi bi-plus-circle"></i> Add Income
            </a>
            <a href="add_expense.php" class="btn-gradient" style="background: var(--expense);">
                <i class="bi bi-dash-circle"></i> Add Expense
            </a>
        </div>
    </div>

    <!-- Notification Alerts -->
    <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($flash_success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($flash_error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Search & Filter Card -->
    <div class="glass-card mb-4" style="padding: 20px;">
        <form method="GET" action="" class="row g-3 align-items-center">
            <!-- Search Keyword -->
            <div class="col-md-5 col-sm-12">
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-search"></i></span>
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Search by category or note..." 
                        value="<?php echo htmlspecialchars($search_term); ?>"
                    >
                </div>
            </div>

            <!-- Type Filter Dropdown -->
            <div class="col-md-4 col-sm-8">
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-filter"></i></span>
                    <select name="type" class="form-control">
                        <option value="">All Transaction Types</option>
                        <option value="income" <?php echo ($filter_type === 'income') ? 'selected' : ''; ?>>Income Only</option>
                        <option value="expense" <?php echo ($filter_type === 'expense') ? 'selected' : ''; ?>>Expense Only</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-3 col-sm-4 d-flex gap-2">
                <button type="submit" class="btn-gradient" style="flex: 1;">
                    Filter
                </button>
                <?php if (!empty($search_term) || !empty($filter_type)): ?>
                    <a href="transaction.php" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="Reset Filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Transactions Data Table Card -->
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="premium-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Notes</th>
                        <th style="text-align: right; width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $count = 1;
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $is_expense = ($row['type'] === 'expense');
                        $badge_class = $is_expense ? 'badge-expense' : 'badge-income';
                        $edit_url = $is_expense ? 'edit_expense.php?id=' . $row['id'] : 'edit_income.php?id=' . $row['id'];
                        $formatted_date = !empty($row['created_at']) ? date("M d, Y", strtotime($row['created_at'])) : '-';
                        $category_display = !empty($row['category_name']) ? htmlspecialchars($row['category_name']) : 'Uncategorized';
                ?>
                    <tr>
                        <td class="text-muted"><?php echo $count++; ?></td>
                        <td><strong><?php echo $category_display; ?></strong></td>
                        <td>
                            <span class="<?php echo $badge_class; ?>">
                                <i class="bi bi-<?php echo $is_expense ? 'arrow-down-circle' : 'arrow-up-circle'; ?>"></i>
                                <?php echo ucfirst($row['type']); ?>
                            </span>
                        </td>
                        <td>
                            <strong style="color: <?php echo $is_expense ? 'var(--expense)' : 'var(--income)'; ?>;">
                                <?php echo ($is_expense ? '-' : '+') . ' Rs. ' . number_format($row['amount'], 2); ?>
                            </strong>
                        </td>
                        <td class="text-muted"><?php echo $formatted_date; ?></td>
                        <td class="text-muted" style="max-width: 220px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            <?php echo !empty($row['notes']) ? htmlspecialchars($row['notes']) : '<span class="text-muted fst-italic">None</span>'; ?>
                        </td>
                        <td style="text-align: right;">
                            <div class="d-inline-flex gap-2">
                                <a href="<?php echo $edit_url; ?>" class="btn btn-sm btn-outline-primary" title="Edit Transaction">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="openDeleteModal(<?php echo $row['id']; ?>)" 
                                    title="Delete Transaction"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                            No transactions found.
                        </td>
                    </tr>
                <?php 
                }
                mysqli_stmt_close($stmt);
                ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4" style="border-radius: var(--radius-lg); border: none;">
            <div class="modal-body">
                <div class="mb-3" style="font-size: 40px; color: var(--expense);">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h4 class="fw-bold">Delete Transaction?</h4>
                <p class="text-muted">Are you sure you want to delete this record? This action cannot be undone.</p>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Script -->
<script>
    let deleteId = null;

    function openDeleteModal(id) {
        deleteId = id;
        let modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    document.getElementById("confirmDeleteBtn").onclick = function () {
        if (deleteId) {
            window.location.href = "delete.php?id=" + deleteId;
        }
    };
</script>

<?php
// Close DB and include unified footer
mysqli_close($conn);
include("../includes/footer.php");
?>