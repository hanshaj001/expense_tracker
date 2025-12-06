<?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/sidebar.php");
?>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="main-content">
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <h1>Add Income</h1>
            <p class="subtitle">Record your income transactions</p>
        </div>
        <div class="header-right">
            <div class="user-profile">
                <div class="avatar">HR</div>
                <span>UserName</span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="pages-container">
        <div class="page active">
            <div class="form-container">
                <h2>Income Details</h2>
                <form method="POST" action="" class="transaction-form">
                    <div class="form-group">
                        <label for="incomeTitle">Title</label>
                        <input type="text" id="incomeTitle" name="incomeTitle" required placeholder="e.g., Salary, Freelance">
                    </div>
                    <div class="form-group">
                        <label for="incomeAmount">Amount</label>
                        <input type="number" id="incomeAmount" name="incomeAmount" required placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="incomeDate">Date</label>
                        <input type="date" id="incomeDate" name="incomeDate" required>
                    </div>
                    <div class="form-group">
                        <label for="incomeNotes">Notes (Optional)</label>
                        <textarea id="incomeNotes" name="incomeNotes" placeholder="Add any notes..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Income</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>