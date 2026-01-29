<?php
session_start();
include("../includes/db_conn.php");
include("../includes/footer.php");
include("../includes/header.php");

    // check if the user is logged in or not
    if(!isset($_SESSION['name'])){
        header("Location: ../index.php");
    }
    // userid form session
    $user_id = $_SESSION['user_id'];
?>

<?php 

    // instialing varaiables
    $rent ;

    $amount = '';
    $category_id = '';
    $expense_date = '';
    $notes = '';
    $success = '';
    $errors = [];


    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $amount = trim($_POST['amount']);
        if($amount<0){
        $errors['amount'] = "Amount Cannot be less than 0 .";
        }

        $category_id = trim($_POST['category']);
        if(empty($category_id)){
            $errors['category'] = "Category is required";
        }

        $expense_date = $_POST['expense_date'];

        if (empty($expense_date)) {
            $errors['expense_date'] = "Expense Date is required";
        } elseif ($expense_date > date('Y-m-d')) {
            $errors['date_excessed'] = "Expense date cannot be more than current date";
        }
        $note = $_POST['note'];

        if(empty($errors)){
            if(mysqli_query($conn,"INSERT into transactions(user_id,category_id,type,amount,notes,created_at)
            values( $user_id, $category_id, 'expense',$amount,'$notes','$expense_date');")){
                $success = "Expense Recorded Successfully";
                    $amount = '';
                    $category_id = '';
                    $expense_date = '';
                    $notes = '';
                    $errors = [];
            }
        }
    }

    // $category_row = mysqli_query($conn,"SELECT id from categories where name = 'Rent'");
    // print_r($category_row);
    $rent_amt_result = mysqli_query($conn,"SELECT sum(amount) as total_rent 
    from transactions where category_id = 12 and 
    user_id = $user_id and type='expense'");

    $rent_amt_row = mysqli_fetch_assoc($rent_amt_result);
    $rent_amount = $rent_amt_row['total_rent'];
  
    // for limi amt
    $rent_limit_result = mysqli_query($conn,"SELECT weekly_limit from weekly_limits
    where category_id = 12 and user_id = $user_id ");

    $rent_limit_row = mysqli_fetch_assoc($rent_limit_result);

    // print_r($rent_limit_row);
    $rent_limit_amount = $rent_limit_row['weekly_limit'];



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

        <form method="POST" action="" class="et-form">

            <div class="et-form-group">
                 <label>Amount</label>
                <input type="number" name="amount" value="<?php echo $amount ?? '' ;?>" placeholder="Enter amount" required>
                <span style="color: red;"><?php echo $errors['amount'] ?? ''; ?></span>
            </div>

            <div class="et-form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">-- Select Expense Type --</option>
                    <option value="11" <?php if($category_id == 11) echo 'selected'; ?>>Food & Groceries</option>
                    <option value="12" <?php if($category_id == 12) echo 'selected'; ?>>Rent</option>
                    <option value="13" <?php if($category_id == 13) echo 'selected'; ?>>Transportation</option>
                    <option value="14" <?php if($category_id == 14) echo 'selected'; ?>>Utilities</option>
                    <option value="15" <?php if($category_id == 15) echo 'selected'; ?>>Internet & Mobile</option>
                    <option value="16" <?php if($category_id == 16) echo 'selected'; ?>>Education</option>
                    <option value="17" <?php if($category_id == 17) echo 'selected'; ?>>Healthcare</option>
                    <option value="18" <?php if($category_id == 18) echo 'selected'; ?>>Entertainment</option>
                    <option value="19" <?php if($category_id == 19) echo 'selected'; ?>>Shopping</option>
                    <option value="20" <?php if($category_id == 20) echo 'selected'; ?>>Other Expenses</option>
                </select>
            </div>

            <div class="et-form-group">
                <label>Date</label>
                <input type="date" name="expense_date" required value="<?php echo $expense_date;?>">
                <span style="color: red;"><?php echo $errors['expense_date'] ?? ''; ?></span>
                <span style="color: red;"><?php echo $errors['date_excessed'] ?? ''; ?></span>
            </div>

           <div class="et-form-group">
                <label>Note (Optional)</label>
                <textarea name="note" rows="3"><?php echo $notes;?></textarea>
            </div>

            <button type="submit" class="et-btn-primary">
                <i class="bi bi-plus-circle"></i> Save Expense</button>
                <span class="et-form-group text-success"><p><?php echo $success ?? '';?></p></span>

        </form>
    </div>

</div>


<!-- bar -->
<div class="expense-container col-8 offset-2">
    <div class="expense-grid" id="expenseGrid"></div>
</div>



<script > 
    
const expenses = [
    { name: "Rent", spent: <?php echo $rent_amount ?? '';?>, limit: 80000 },
    { name: "Transportation", spent: 2200, limit: 34000 },
    { name: "Internet & Mobile", spent: 1200, limit: 2000 },
    { name: "Education", spent: 3500, limit: 4000 },
    { name: "Entertainment", spent: 2600, limit: 2500 },
    { name: "Shopping", spent: 1800, limit: 4000 }
];

const grid = document.getElementById("expenseGrid");

expenses.forEach(item => {
    const percent = Math.min((item.spent / item.limit) * 100, 100);

    let status = "success";
    if (percent >= 70 && percent < 100) status = "warning";
    if (percent >= 100) status = "danger";

    const card = document.createElement("div");
    card.className = `expense-item ${status}`;

    card.innerHTML = `
        <div class="expense-title">${item.name}</div>
        <div class="expense-spent">Spent: ${item.spent} / ${item.limit}</div>
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        <div class="expense-percent">${Math.round(percent)}% used</div>
        <div class="expense-action">Set / Update Limit</div>
    `;

    grid.appendChild(card);

    // animate AFTER DOM insert
    requestAnimationFrame(() => {
        card.querySelector(".progress-fill").style.width = percent + "%";
    });
});

</script>



<?php include '../includes/footer.php'; ?> 

</body>
</html>
