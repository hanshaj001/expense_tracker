<?php
// Start session for auth checks
session_start();

// 1. Authentication check - Check BEFORE sending any HTML
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");
include("../includes/function.php");

$user_id = (int)$_SESSION['user_id'];

// Form variables
$amount       = '';
$category_id  = '';
$expense_date = date('Y-m-d');
$notes        = '';
$errors       = [];
$success      = '';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $amount       = trim($_POST['amount'] ?? '');
    $category_id  = trim($_POST['category'] ?? '');
    $expense_date = trim($_POST['expense_date'] ?? '');
    $notes        = trim($_POST['note'] ?? '');

    // Validate amount
    if (!is_numeric($amount) || $amount <= 0) {
        $errors['amount'] = "Amount must be a valid number greater than 0.";
    }

    // Validate category
    if (empty($category_id) || !is_numeric($category_id)) {
        $errors['category'] = "Please select an expense category.";
    }

    // Validate date
    if (empty($expense_date)) {
        $errors['expense_date'] = "Expense date is required.";
    } elseif ($expense_date > date('Y-m-d')) {
        $errors['expense_date'] = "Expense date cannot be in the future.";
    }

    // Insert transaction using prepared statement
    if (empty($errors)) {
        $sql = "INSERT INTO transactions (user_id, category_id, type, amount, notes, created_at)
                VALUES (?, ?, 'expense', ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iidss", $user_id, $category_id, $amount, $notes, $expense_date);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Expense recorded successfully!";
                // Reset form fields
                $amount       = '';
                $category_id  = '';
                $expense_date = date('Y-m-d');
                $notes        = '';
            } else {
                $errors['general'] = "Failed to record expense. Please try again.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors['general'] = "Database error. Please try again.";
        }
    }
}

// Fetch expense categories from DB
$cat_res = mysqli_query($conn, "SELECT id, name FROM categories WHERE type = 'expense' ORDER BY id ASC");
$categories = [];
while ($cat_row = mysqli_fetch_assoc($cat_res)) {
    $categories[] = $cat_row;
}

// Fetch user's active weekly budget limits & calculate this week's spent amount for progress bars
$limit_sql = "SELECT c.name, wl.weekly_limit, 
                     COALESCE(SUM(t.amount), 0) AS total_spent
              FROM weekly_limits wl
              JOIN categories c ON wl.category_id = c.id
              LEFT JOIN transactions t ON t.category_id = wl.category_id 
                   AND t.user_id = wl.user_id 
                   AND t.type = 'expense' 
                   AND YEARWEEK(t.created_at, 1) = YEARWEEK(CURDATE(), 1)
              WHERE wl.user_id = ?
              GROUP BY wl.category_id, c.name, wl.weekly_limit";

$limit_stmt = mysqli_prepare($conn, $limit_sql);
mysqli_stmt_bind_param($limit_stmt, "i", $user_id);
mysqli_stmt_execute($limit_stmt);
$limit_res = mysqli_stmt_get_result($limit_stmt);
$budget_progress = [];
while ($limit_row = mysqli_fetch_assoc($limit_res)) {
    $budget_progress[] = $limit_row;
}
mysqli_stmt_close($limit_stmt);

$page_title = "Add Expense - Expense Tracker";
include("../includes/header.php");
?>

<div class="main-content animate-fade-in">

    <div class="form-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="page-title mb-1">Add Expense</h1>
                <p class="subtitle mb-0">Record an expenditure and stay within your weekly budget.</p>
            </div>
            <a href="transaction.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> View Transactions
            </a>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($errors['general']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="glass-card mb-4">
            <form method="POST" action="">

                <!-- Amount Field -->
                <div class="form-group">
                    <label for="amount">Expense Amount (Rs.)</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-rupee-sign"></i></span>
                        <input 
                            type="number" 
                            id="amount"
                            name="amount" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($amount); ?>" 
                            placeholder="0.00" 
                            min="0.01" 
                            step="0.01" 
                            required
                        >
                    </div>
                    <?php if (!empty($errors['amount'])): ?>
                        <span class="error-msg"><?php echo htmlspecialchars($errors['amount']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Category Field -->
                <div class="form-group">
                    <label for="category">Category</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-folder"></i></span>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">-- Select Expense Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (!empty($errors['category'])): ?>
                        <span class="error-msg"><?php echo htmlspecialchars($errors['category']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Date Field -->
                <div class="form-group">
                    <label for="expense_date">Date Spent</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-calendar-alt"></i></span>
                        <input 
                            type="date" 
                            id="expense_date"
                            name="expense_date" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($expense_date); ?>" 
                            max="<?php echo date('Y-m-d'); ?>" 
                            required
                        >
                    </div>
                    <?php if (!empty($errors['expense_date'])): ?>
                        <span class="error-msg"><?php echo htmlspecialchars($errors['expense_date']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Note Field -->
                <div class="form-group">
                    <label for="note">Note / Description (Optional)</label>
                    <div class="input-wrapper">
                        <span class="input-icon" style="top: 20px;"><i class="fas fa-sticky-note"></i></span>
                        <textarea 
                            id="note"
                            name="note" 
                            class="form-control" 
                            rows="3" 
                            placeholder="e.g. Grocery store shopping, internet bill..."
                        ><?php echo htmlspecialchars($notes); ?></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient" style="background: var(--expense);">
                    <i class="bi bi-dash-circle"></i> Save Expense
                </button>

            </form>
        </div>

        <!-- Weekly Category Budgets Progress Section -->
        <?php if (!empty($budget_progress)): ?>
            <div class="glass-card">
                <h4 class="fw-bold mb-3" style="font-size: 16px;">
                    <i class="bi bi-speedometer2 text-primary me-2"></i> Your Weekly Budget Limits
                </h4>
                <div class="row g-3">
                    <?php foreach ($budget_progress as $bp): 
                        $limit = (float)$bp['weekly_limit'];
                        $spent = (float)$bp['total_spent'];
                        $percent = ($limit > 0) ? min(round(($spent / $limit) * 100), 100) : 0;
                        $bar_color = ($percent >= 100) ? 'bg-danger' : (($percent >= 75) ? 'bg-warning' : 'bg-success');
                    ?>
                        <div class="col-md-6 col-sm-12">
                            <div class="p-3 border rounded-3" style="background: #fafafa;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold" style="font-size: 14px;"><?php echo htmlspecialchars($bp['name']); ?></span>
                                    <span class="badge <?php echo ($percent >= 100) ? 'badge-expense' : 'badge-income'; ?>">
                                        <?php echo $percent; ?>%
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 4px;">
                                    <div class="progress-bar <?php echo $bar_color; ?>" role="progressbar" style="width: <?php echo $percent; ?>%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1 text-muted" style="font-size: 12px;">
                                    <span>Spent: Rs. <?php echo number_format($spent, 2); ?></span>
                                    <span>Limit: Rs. <?php echo number_format($limit, 2); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php
mysqli_close($conn);
include("../includes/footer.php");
?>
