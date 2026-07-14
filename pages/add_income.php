<?php

session_start();
include("../includes/db_conn.php");
include("../includes/header.php");
include("../includes/function.php");


// check if the user is logged in or not

if(!isset($_SESSION['name'])){
    header("Location: ../index.php");
}

$user_id = $_SESSION['user_id'];
    // instalizing the variables
    $amount = '';
    $category_id = '';
    $income_date='';
    $notes = '';
    $errors= [];
    $success = '';
if($_SERVER['REQUEST_METHOD']=="POST"){
    
    $amount = $_POST['amount'];
    if($amount<0){
        $errors['amount'] = "Amount cannot be less than 0"; 
    }
    
    // category id 
    $category_id = $_POST['category'];
    if(empty($category_id)){
        $errors['category'] = "Categorty is required";
    }

    // income date
    $income_date = $_POST['income_date'];

        if (empty($income_date)) {
            $errors['income_date'] = "Income Date is required";
        } elseif ($income_date > date('Y-m-d')) {
            $errors['date_excessed'] = "Income date cannot be more than current date";
        }
    $notes = $_POST['note'];

    if(empty($errors)){
    
    if(mysqli_query($conn,"INSERT into transactions(user_id,category_id,type,amount,notes,created_at)
    values($user_id,$category_id,'income',$amount,'$notes','$income_date');")){
        $success = "Income recoded Successfully";
        $amount = '';
        $category_id = '';
        $income_date = '';
        $notes = '';
    }else
    echo "Error while recording ";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Income </title>
    <link rel="stylesheet" href="../assets/income_expense.css">
    <link rel="stylesheet" href="../assets/global.css">

</head>

<div class="main-content animate-fade-in">

    <div class="glass-card">
        <h4 class="page-title">Add Income</h4>

        <form method="POST" action="" class="et-form">

        
            <div class="form-group">
                <label>Amount</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-rupee-sign"></i></span>
                    <input type="number" name="amount" class="form-control" value="<?php echo $amount ?? '' ;?>" placeholder="Enter amount" min="0.01" step="0.01" required>
                </div>
                <span class="error-msg"><?php echo $errors['amount'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label>Category</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-folder"></i></span>
                    <select name="category" class="form-control" required>
                        <option value="">-- Select Income type --</option>
                        <option value="1" <?php if($category_id == 1) echo 'selected';?>>Salary</option>
                        <option value="2" <?php if($category_id == 2) echo 'selected';?>>Business Income</option>
                        <option value="3"<?php if($category_id == 3) echo 'selected';?>>Freelance Work</option>
                        <option value="4"<?php if($category_id == 4) echo 'selected';?>>Investment Returns</option>
                        <option value="5"<?php if($category_id == 5) echo 'selected';?>>Rental Income</option>
                        <option value="6"<?php if($category_id == 6) echo 'selected';?>>Bonus</option>
                        <option value="7"<?php if($category_id == 7) echo 'selected';?>>Interest Income</option>
                        <option value="8"<?php if($category_id == 8) echo 'selected';?>>Gifts Received</option>
                        <option value="9"<?php if($category_id == 9) echo 'selected';?>>Dividends</option>
                        <option value="10"<?php if($category_id == 10) echo 'selected';?>>Other Income</option>
                    </select>
                </div>
                <span class="error-msg"><?php echo $errors['category'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label>Date</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" name="income_date" class="form-control" required value="<?php echo $income_date;?>" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <span class="error-msg"><?php echo $errors['income_date'] ?? ''; ?></span>
                <span class="error-msg"><?php echo $errors['date_excessed'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label>Note (Optional)</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fas fa-sticky-note"></i></span>
                    <textarea name="note" class="form-control" rows="3"><?php echo $notes;?></textarea>
                </div>
            </div>

            <button type="submit" class="btn-gradient">
                <i class="bi bi-plus-circle"></i> Save Income
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
