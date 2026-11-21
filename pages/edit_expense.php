<?php
// Start session for auth checks
session_start();

// 1. Authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");
include("../includes/function.php");

$user_id = (int)$_SESSION['user_id'];
$errors  = [];
$success = '';

// Check if valid transaction ID is passed
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['flash_error'] = "Invalid transaction ID.";
    header("Location: transaction.php");
    exit();
}

$transaction_id = (int)$_GET['id'];

// 2. Authorization check - Fetch only if it belongs to logged-in user and is type 'expense'
$stmt = mysqli_prepare($conn, "SELECT * FROM transactions WHERE id = ? AND user_id = ? AND type = 'expense'");
mysqli_stmt_bind_param($stmt, "ii", $transaction_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    $_SESSION['flash_error'] = "Expense record not found or access denied.";
    header("Location: transaction.php");
    exit();
}

$transaction = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Pre-fill form values
$amount       = $transaction['amount'];
$category_id  = $transaction['category_id'];
$expense_date = $transaction['created_at'];
$notes        = $transaction['notes'];

// Handle form submission
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
        $errors['category'] = "Please select a valid expense category.";
    }

    // Validate date
    if (empty($expense_date)) {
        $errors['expense_date'] = "Expense date is required.";
    } elseif ($expense_date > date('Y-m-d')) {
        $errors['expense_date'] = "Expense date cannot be in the future.";
    }

    // Update if no errors
    if (empty($errors)) {
        $update_sql = "UPDATE transactions 
                       SET category_id = ?, amount = ?, notes = ?, created_at = ? 
                       WHERE id = ? AND user_id = ? AND type = 'expense'";
        $update_stmt = mysqli_prepare($conn, $update_sql);

        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, "idssii", $category_id, $amount, $notes, $expense_date, $transaction_id, $user_id);
            if (mysqli_stmt_execute($update_stmt)) {
                $success = "Expense record updated successfully!";
            } else {
                $errors['general'] = "Failed to update expense record. Please try again.";
            }
            mysqli_stmt_close($update_stmt);
        }
    }
}

// Fetch expense categories from DB
$cat_res = mysqli_query($conn, "SELECT id, name FROM categories WHERE type = 'expense' ORDER BY id ASC");
$categories = [];
while ($cat_row = mysqli_fetch_assoc($cat_res)) {
    $categories[] = $cat_row;
}

$page_title = "Edit Expense - Expense Tracker";
include("../includes/header.php");
?>

<div class="main-content animate-fade-in">

    <div class="form-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="page-title mb-1">Edit Expense</h1>
                <p class="subtitle mb-0">Modify details for this expense entry.</p>
            </div>
            <a href="transaction.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Transactions
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

        <div class="glass-card">
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
                    <label for="category">Expense Category</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-folder"></i></span>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">-- Select Category --</option>
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
                    <label for="expense_date">Spent Date</label>
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
                            placeholder="Add brief details about this expense..."
                        ><?php echo htmlspecialchars($notes); ?></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient" style="background: var(--expense);">
                    <i class="bi bi-check2-circle"></i> Update Expense
                </button>

            </form>
        </div>
    </div>

</div>

<?php
mysqli_close($conn);
include("../includes/footer.php");
?>
