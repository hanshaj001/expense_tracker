<?php

session_start();
include("../includes/db_conn.php");
include("../includes/header.php");
?>

<?php 

    $user_id = $_SESSION['user_id'];
    $errors  = [];
    $success;
    $category_id;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $category_name      = $_POST['categoryName'];
        $category_limit_amt = $_POST['weeklyLimit'];
     
        if($category_limit_amt < 0){
            $errors['limit_amt'] = "Amount cannot be less than 0";
        }

        $display_cat[$category_name] = $category_limit_amt;    
        $category_result = mysqli_query($conn, "SELECT * FROM categories WHERE name = '$category_name';");
        if(mysqli_num_rows($category_result) == 1){
            $category_row = mysqli_fetch_assoc($category_result);
            $category_id  = $category_row['id'];
        }

        // Check if limit already exists
        $check = mysqli_query($conn, "SELECT id FROM weekly_limits WHERE category_id = $category_id AND user_id = $user_id;");

        if(mysqli_num_rows($check) > 0){
            // Update existing limit
            $update_result = mysqli_query($conn, "UPDATE weekly_limits 
                SET weekly_limit = $category_limit_amt
                WHERE user_id = $user_id AND category_id = $category_id;");

            if($update_result)
                $success = "Updated Category Limit.";
            else
                $errors['limit_error'] = "Error while updating limit";

        } else {

            if(empty($errors)){
                // Insert new limit
                $insert_result = mysqli_query($conn, "INSERT INTO weekly_limits(user_id, category_id, weekly_limit)
                    VALUES($user_id, $category_id, $category_limit_amt);");

                if($insert_result)
                    $success = "Limit Set Successfully";
                else
                    $errors['limit_error'] = "Error while setting new limit";
            }
        }
    }
    
    // Fetch all limits for display
    $display_result = mysqli_query($conn, "SELECT c.name, wl.weekly_limit
        FROM weekly_limits wl JOIN categories c ON wl.category_id = c.id 
        WHERE wl.user_id = $user_id;");

    if(mysqli_num_rows($display_result) > 0){
        while($display_row = mysqli_fetch_assoc($display_result)){
            $display_cat[$display_row['name']] = $display_row['weekly_limit'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Expense Tracker</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/categories.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>

<!-- Main Content -->
<div class="main-content animate-fade-in categories-container">

    <!-- Page Header -->
    <div class="page-header" style="margin-top: 20px;">
        <h2 class="page-title">Categories</h2>

        <?php if(!empty($success)): ?>
            <div id="flashMessage" class="alert alert-success text-center">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($errors)): ?>
            <div id="flashMessageError" class="alert alert-danger text-center">
                <?php echo $errors['limit_amt'] ?? $errors['limit_error'] ?? ''; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ===== EXPENSE CATEGORIES ===== -->
    <div class="section-title expense-title">
        <i class="bi bi-credit-card"></i>
        Expense Categories
    </div>

    <div class="expense-cards">

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-shopping-cart"></i></div>
                <div><div class="expense-name">Food & Groceries</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Food & Groceries'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Food & Groceries', 5000)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-home"></i></div>
                <div><div class="expense-name">Rent</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Rent'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Rent', 15000)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-bus"></i></div>
                <div><div class="expense-name">Transportation</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Transportation'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Transportation', 2000)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-lightbulb"></i></div>
                <div><div class="expense-name">Utilities</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Utilities'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Utilities', 3000)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-wifi"></i></div>
                <div><div class="expense-name">Internet & Mobile</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Internet & Mobile'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Internet & Mobile', 1500)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-graduation-cap"></i></div>
                <div><div class="expense-name">Education</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Education'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Education', 4000)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-heartbeat"></i></div>
                <div><div class="expense-name">Healthcare</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Healthcare'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Healthcare', 3500)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-film"></i></div>
                <div><div class="expense-name">Entertainment</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Entertainment'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Entertainment', 2500)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-shopping-bag"></i></div>
                <div><div class="expense-name">Shopping</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Shopping'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Shopping', 4500)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

        <div class="expense-card">
            <div class="expense-card-header">
                <div class="expense-icon"><i class="fas fa-ellipsis-h"></i></div>
                <div><div class="expense-name">Other Expenses</div></div>
            </div>
            <div class="expense-limit">Weekly Limit: <span class="limit-value"><?php echo $display_cat['Other Expenses'] ?? 'Not set'; ?></span></div>
            <button class="set-limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal" onclick="setCategory('Other Expenses', 2000)">
                <i class="fas fa-edit"></i> Set Limit
            </button>
        </div>

    </div>

    <!-- ===== INCOME CATEGORIES ===== -->
    <div class="section-title income-title">
        <i class="bi bi-cash"></i>
        Income Categories
    </div>

    <div class="income-cards">

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="income-name">Salary</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-briefcase"></i></div>
                <div class="income-name">Business Income</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-laptop-code"></i></div>
                <div class="income-name">Freelance Work</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-chart-line"></i></div>
                <div class="income-name">Investment Returns</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-building"></i></div>
                <div class="income-name">Rental Income</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-award"></i></div>
                <div class="income-name">Bonus</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-percentage"></i></div>
                <div class="income-name">Interest Income</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-gift"></i></div>
                <div class="income-name">Gifts Received</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-coins"></i></div>
                <div class="income-name">Dividends</div>
            </div>
        </div>

        <div class="income-card">
            <div class="income-card-content">
                <div class="income-icon"><i class="fas fa-plus-circle"></i></div>
                <div class="income-name">Other Income</div>
            </div>
        </div>

    </div>
</div>

<!-- ===== SET LIMIT MODAL ===== -->
<div class="modal fade" id="limitModal" tabindex="-1" aria-labelledby="limitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="limitModalLabel">Set Weekly Limit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" id="limitForm">

                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category</label>
                        <input type="text" class="form-control" name="categoryName" id="categoryName" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="weeklyLimit" class="form-label">Weekly Limit Amount (Rs.)</label>
                        <input type="number" class="form-control" name="weeklyLimit" id="weeklyLimit"
                               placeholder="Enter weekly limit" min="0" step="100" required>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-gradient" name="submit" style="width: auto;">
                            <i class="fas fa-save"></i> Save Limit
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function setCategory(categoryName, currentLimit) {
        document.getElementById('categoryName').value = categoryName;
        document.getElementById('weeklyLimit').value  = currentLimit;
    }

    // Auto-hide flash messages after 3 seconds
    setTimeout(function () {
        var successMsg = document.getElementById('flashMessage');
        var errorMsg   = document.getElementById('flashMessageError');
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg)   errorMsg.style.display   = 'none';
    }, 3000);
</script>
</body>
</html>

<?php include("../includes/footer.php"); ?>