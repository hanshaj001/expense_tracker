<?php
session_start();
include("../includes/db_conn.php");
include("../includes/header.php");
include("../includes/function.php");


$user_id = $_SESSION['user_id'];

// when click on edit 
if(isset($_GET['income_id'])){
        $income_id = $_GET['income_id'];

     $result = (mysqli_query($conn,"SELECT * from add_income where income_id = $income_id"));
     if($result){
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
        }
     }

}

$user_id = $row['user_id'];
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
    
    if(mysqli_query($conn,"UPDATE add_income 
    set category_id = $category_id,
     amount = $amount ,
      updated_at = '$income_date',
       notes = '$notes' 
       where income_id = $income_id
       and user_id = $user_id;")){
        $success = "Income Updated Successfully";
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
    <title>Update Income </title>
    <link rel="stylesheet" href="/EXPANSE_TRACKER/assets/income_expense.css">

</head>
<body>

<div class="et-main-content">

    <div class="et-form-card">
        <h4 class="et-form-title">Update Income</h4>

        <form method="POST" action="" class="et-form">

        
            <div class="et-form-group">
                <label>Amount</label>
                <input type="number" name="amount" value="<?php echo $row['amount'] ;?>" placeholder="Enter amount" required>
                <span style="color: red;"><?php echo $errors['amount'] ?? ''; ?></span>
            </div>

            <div class="et-form-group">
                <label>Category</label>
                <select name="category" required>
                            <option value="">-- Select Income type --</option>
                            <option value="1" <?php if($row['category_id'] == 1) echo 'selected';?>>Salary</option>
                            <option value="2" <?php if($row['category_id']== 2) echo 'selected';?>>Business Income</option>
                            <option value="3"<?php if($row['category_id']== 3) echo 'selected';?>>Freelance Work</option>
                            <option value="4"<?php if($row['category_id'] == 4) echo 'selected';?>>Investment Returns</option>
                            <option value="5"<?php if($row['category_id'] == 5) echo 'selected';?>>Rental Income</option>
                            <option value="6"<?php if($row['category_id'] == 6) echo 'selected';?>>Bonus</option>
                            <option value="7"<?php if($row['category_id'] == 7) echo 'selected';?>>Interest Income</option>
                            <option value="8"<?php if($row['category_id'] == 8) echo 'selected';?>>Gifts Received</option>
                            <option value="9"<?php if($row['category_id']== 9) echo 'selected';?>>Dividends</option>
                            <option value="10"<?php if($row['category_id'] == 10) echo 'selected';?>>Other Income</option>

           </select>
            </div>

            <div class="et-form-group">
                <label>Date</label>
                <input type="date" name="income_date" required value="<?php echo $row['created_at'];?>">
                <span style="color: red;"><?php echo $errors['income_date'] ?? ''; ?></span>
                <span style="color: red;"><?php echo $errors['date_excessed'] ?? ''; ?></span>
            </div>

            <div class="et-form-group">
                <label>Note (Optional)</label>
                <textarea name="note" rows="3"><?php echo $row['notes'];?></textarea>
            </div>

            <button type="submit" class="et-btn-primary">
                <i class="bi bi-plus-circle"></i> Save Income
            </button>
        <span class="et-form-group text-success"><p><?php echo $success ;?></p></span>
        </form>
    </div>

</div>
<?php include '../includes/footer.php'; ?> 

</body>
</html>
