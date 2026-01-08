<?php
include("../includes/db_conn.php");
include("../includes/header.php");
include("../includes/function.php");

session_start();
$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD']=="POST"){
    
    $amount = $_POST['amount'];
    $category_id = $_POST['category'];
    $income_date = $_POST['income_date'];
    $notes = $_POST['note'];

    if(mysqli_query($conn,"INSERT into add_income(user_id,category_id,amount,notes)
    values($user_id,$category_id,$amount,'$notes'); ")){
        my_alert("green","Income recoded Successfully");
    }else
    my_alert("red","Error while recording ");
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
                <label>Amount</label>
                <input type="number" name="amount" placeholder="Enter amount" required>
            </div>

            <div class="et-form-group">
                <label>Category</label>
                <select name="category" required>
                            <option value="1">Salary</option>
                            <option value="2">Business Income</option>
                            <option value="3">Freelance Work</option>
                            <option value="4">Investment Returns</option>
                            <option value="5">Rental Income</option>
                            <option value="6">Bonus</option>
                            <option value="7">Interest Income</option>
                            <option value="8">Gifts Received</option>
                            <option value="9">Dividends</option>
                            <option value="10">Other Income</option>

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
