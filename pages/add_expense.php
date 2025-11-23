<?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/sidebar.php");
?>

<link rel="stylesheet" href="../assets/css/style.css">

<div id="add-expense-page" class="page">
    <div class="form-container">
        <h2>Add Expense</h2>
        <form id="expenseForm" class="transaction-form">
            <div class="form-group">
                <label for="expenseTitle">Title</label>
                <input type="text" id="expenseTitle" required placeholder="e.g., Groceries, Gas">
            </div>
            <div class="form-group">
                <label for="expenseAmount">Amount</label>
                <input type="number" id="expenseAmount" required placeholder="0.00" step="0.01" min="0">
            </div>
            <div class="form-group">
                <label for="expenseCategory">Category</label>
                <select id="expenseCategory" required></select>
            </div>
            <div class="form-group">
                <label for="expenseDate">Date</label>
                <input type="date" id="expenseDate" required>
            </div>
            <div class="form-group">
                <label for="expenseNotes">Notes (Optional)</label>
                <textarea id="expenseNotes" placeholder="Add any notes..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Expense</button>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
