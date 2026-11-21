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
$amount      = '';
$category_id = '';
$income_date = date('Y-m-d');
$notes       = '';
$errors      = [];
$success     = '';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $amount      = trim($_POST['amount'] ?? '');
    $category_id = trim($_POST['category'] ?? '');
    $income_date = trim($_POST['income_date'] ?? '');
    $notes       = trim($_POST['note'] ?? '');

    // Validate amount
    if (!is_numeric($amount) || $amount <= 0) {
        $errors['amount'] = "Amount must be a valid number greater than 0.";
    }

    // Validate category
    if (empty($category_id) || !is_numeric($category_id)) {
        $errors['category'] = "Please select an income category.";
    }

    // Validate date
    if (empty($income_date)) {
        $errors['income_date'] = "Income date is required.";
    } elseif ($income_date > date('Y-m-d')) {
        $errors['income_date'] = "Income date cannot be in the future.";
    }

    // Insert transaction using prepared statement
    if (empty($errors)) {
        $sql = "INSERT INTO transactions (user_id, category_id, type, amount, notes, created_at)
                VALUES (?, ?, 'income', ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iidss", $user_id, $category_id, $amount, $notes, $income_date);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Income recorded successfully!";
                // Reset form fields
                $amount      = '';
                $category_id = '';
                $income_date = date('Y-m-d');
                $notes       = '';
            } else {
                $errors['general'] = "Failed to record income. Please try again.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors['general'] = "Database error. Please try again.";
        }
    }
}

// Fetch income categories from DB
$cat_res = mysqli_query($conn, "SELECT id, name FROM categories WHERE type = 'income' ORDER BY id ASC");
$categories = [];
while ($cat_row = mysqli_fetch_assoc($cat_res)) {
    $categories[] = $cat_row;
}

$page_title = "Add Income - Expense Tracker";
include("../includes/header.php");
?>

<div class="main-content animate-fade-in">

    <div class="form-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="page-title mb-1">Add Income</h1>
                <p class="subtitle mb-0">Record your earnings and monitor your incoming cashflow.</p>
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

        <div class="glass-card">
            <form method="POST" action="">

                <!-- Amount Field -->
                <div class="form-group">
                    <label for="amount">Income Amount (Rs.)</label>
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
                            <option value="">-- Select Income Category --</option>
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
                    <label for="income_date">Date Received</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-calendar-alt"></i></span>
                        <input 
                            type="date" 
                            id="income_date"
                            name="income_date" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($income_date); ?>" 
                            max="<?php echo date('Y-m-d'); ?>" 
                            required
                        >
                    </div>
                    <?php if (!empty($errors['income_date'])): ?>
                        <span class="error-msg"><?php echo htmlspecialchars($errors['income_date']); ?></span>
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
                            placeholder="e.g. Monthly salary, consulting project..."
                        ><?php echo htmlspecialchars($notes); ?></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient" style="background: var(--income);">
                    <i class="bi bi-plus-circle"></i> Save Income
                </button>

            </form>
        </div>
    </div>

</div>

<?php
mysqli_close($conn);
include("../includes/footer.php");
?>
