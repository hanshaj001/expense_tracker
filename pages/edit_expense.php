<?php

include("../includes/db_conn.php");
include("../includes/footer.php");
include("../includes/header.php");
?>

<?php 
    $errors = [];
    $success = '';
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $amount = $_POST['amount'];
        $category = $_POST['category'];
        $expense_date = $_POST['expense_date'];
        $note = $_POST['note'];
        
        if($amount < 0) {
            $errors['amount'] = "Amount cannot be less than 0";
        }
        if(empty($category)) {
            $errors['category'] = "Category is required";
        }
        if(empty($expense_date)) {
            $errors['expense_date'] = "Date is required";
        }
        
        if(empty($errors)){
            // Placeholder for update query
            // $result = mysqli_query($conn,"UPDATE transactions SET amount=$amount, category_id=$category, created_at='$expense_date', notes='$note' WHERE id=$id");
            $success = "Expense updated successfully";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <link rel="stylesheet" href="../assets/income_expense.css"> 
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
<div class="main-content animate-fade-in">

    <div class="glass-card">
        <h4 class="page-title">Edit Expense</h4>

        <form method="POST" action="" class="et-form">

            <div class="form-group">
                <label>Amount</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-rupee-sign"></i></span>
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount" min="0.01" step="0.01" value="<?php echo $amount ?? ''; ?>" required>
                </div>
                <span class="error-msg"><?php echo $errors['amount'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label>Category</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-folder"></i></span>
                    <select name="category" class="form-control" required>
                        <option value="">--Select Expense Category--</option>
                        <option value="11">Food & Groceries</option>
                        <option value="12">Rent</option>
                        <option value="13">Transportation</option>
                        <option value="14">Utilities</option>
                        <option value="15">Internet & Mobile</option>
                        <option value="16">Education</option>
                        <option value="17">Healthcare</option>
                        <option value="18">Entertainment</option>
                        <option value="19">Shopping</option>
                        <option value="20">Other Expenses</option>
                    </select>
                </div>
                <span class="error-msg"><?php echo $errors['category'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label>Date</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" name="expense_date" class="form-control" value="<?php echo $expense_date ?? ''; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <span class="error-msg"><?php echo $errors['expense_date'] ?? ''; ?></span>
            </div> 

            <div class="form-group">
                <label>Note (Optional)</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-sticky-note"></i></span>
                    <textarea name="note" class="form-control" rows="3"><?php echo $note ?? ''; ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn-gradient">
                <i class="bi bi-save"></i> Update Expense
            </button>
            <?php if(!empty($success)): ?>
                <span class="success-msg"><?php echo $success; ?></span>
            <?php endif; ?>

        </form>
    </div>

</div>

<?php include '../includes/footer.php'; ?> 

</body>
</html>
