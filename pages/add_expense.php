<?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/sidebar.php");
?>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="main-content">
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <h1>Add Expense</h1>
            <p class="subtitle">Record your expense transactions</p>
        </div>
        <div class="header-right">
            <div class="user-profile">
                <div class="avatar">HR</div>
                <span>UserName</span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="pages-container">
        <div class="page active">
            <div class="form-container">
                <h2>Expense Details</h2>
                <form method="POST" action="" class="transaction-form">
                    <div class="form-group">
                        <label for="expenseTitle">Title</label>
                        <input type="text" id="expenseTitle" name="expenseTitle" required placeholder="e.g., Groceries, Gas">
                    </div>
                    <div class="form-group">
                        <label for="expenseAmount">Amount</label>
                        <input type="number" id="expenseAmount" name="expenseAmount" required placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="expenseCategory">Category</label>
                        <select id="expenseCategory" name="expenseCategory" required>
                            <option value="">Select Category</option>
                            <option value="Food">Food</option>
                            <option value="Transport">Transport</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Healthcare">Healthcare</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expenseDate">Date</label>
                        <input type="date" id="expenseDate" name="expenseDate" required>
                    </div>
                    <div class="form-group">
                        <label for="expenseNotes">Notes (Optional)</label>
                        <textarea id="expenseNotes" name="expenseNotes" placeholder="Add any notes..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Expense</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>