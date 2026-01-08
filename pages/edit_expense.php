<?php
include("../includes/db_conn.php");
include("../includes/footer.php");
include("../includes/header.php");
?>

<?php 
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $amount = $_POST['amount'];
        $category = $_POST['category'];
        $expense_date = $_POST['expense_date'];
        $note = $_POST['note'];


        $result = mysqli_query($conn,"INSERT into add_expense(amount,,)");
    }


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
                <label>Amount</label>
                <input type="number" name="amount" placeholder="Enter amount" required>
            </div>

            <div class="et-form-group">
                <label>Category</label>
                <select name="category" required>
                  <option value="">--Select Expense Category--</option>
                            <option value="food_groceries">Food & Groceries</option>
                            <option value="rent_mortgage">Rent / Mortgage</option>
                            <option value="utilities">Utilities (Electricity, Water, Gas)</option>
                            <option value="transportation">Transportation</option>
                            <option value="entertainment">Entertainment</option>
                            <option value="health_medical">Health / Medical</option>
                            <option value="education_tuition">Education / Tuition</option>
                            <option value="shopping_personal_care">Shopping / Personal Care</option>
                            <option value="travel_vacation">Travel / Vacation</option>
                            <option value="other_expenses">Other Expenses</option>
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
