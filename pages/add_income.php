<?php
include("../includes/db_conn.php");
include("../includes/header.php");

    
if($_SERVER['REMOTE_HOST']=="POST"){
    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Income </title>
    <link rel="stylesheet" href="/EXPANSE_TRACKER/assets/income_expense.css">

</head>
<body>

<div class="et-main-content">

    <div class="et-form-card">
        <h4 class="et-form-title">Add Income</h4>

        <form method="POST" action="" class="et-form">

            <div class="et-form-group">
                <label>Income Title</label>
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
                    <option value="Salary">Salary</option>
                    <option value="Business">Business</option>
                    <option value="Freelance">Freelance</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="et-form-group">
                <label>Date</label>
                <input type="date" name="income_date" required>
            </div>

            <div class="et-form-group">
                <label>Note (Optional)</label>
                <textarea name="note" rows="3"></textarea>
            </div>

            <button type="submit" class="et-btn-primary">
                <i class="bi bi-plus-circle"></i> Save Income
            </button>

        </form>
    </div>

</div>

<?php include '../includes/footer.php'; ?> 

</body>
</html>
