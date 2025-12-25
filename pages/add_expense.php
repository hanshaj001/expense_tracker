<?php
include("../includes/db_conn.php");
include("../includes/footer.php");
include("../includes/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link rel="stylesheet" href="../assets/income_expense.css"> <!-- Link your CSS file -->
</head>
<body>
<div class="et-main-content">

    <div class="et-form-card">
        <h4 class="et-form-title">Add Expense</h4>

        <form method="POST" action="add_income.php" class="et-form">

            <div class="et-form-group">
                <label>Expense Title</label>
                <input type="text" name="title" placeholder="Salary, Business, Freelance" required>
            </div>

            <div class="et-form-group">
                <label>Amount</label>
                <input type="number" name="amount" placeholder="Enter amount" required>
            </div>

            <div class="et-form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="Rent">Rent</option>
                    <option value="Education">Education</option>
                    <option value="Travel">Travel</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="et-form-group">
                <label>Date</label>
                <input type="date" name="expense_date" required>
            </div>

            <div class="et-form-group">
                <label>Note (Optional)</label>
                <textarea name="note" rows="3"></textarea>
            </div>

            <button type="submit" class="et-btn-primary">
                <i class="bi bi-plus-circle"></i> Save Expense</button>

        </form>
    </div>

</div>

<?php include '../includes/footer.php'; ?> 

</body>
</html>
