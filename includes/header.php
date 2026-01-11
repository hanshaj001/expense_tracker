<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>

    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <!-- Font Awesome Icons -->
    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    <!-- Bootstrap Icons -->
    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    >

    <!-- Custom CSS -->
   <link rel="stylesheet" href="/EXPANSE_TRACKER/assets/styles.css">

<body>
 <?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="et-sidebar">

    <div class="et-app-header">
        <span class="et-logo">Expense Tracker</span>
    </div>

    <div class="et-section-title">Business</div>

    <ul class="nav flex-column et-menu">

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage=='user_dashboard.php')?'active':'' ?>" href="user_dashboard.php">
                <i class="bi bi-grid"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage=='add_income.php')?'active':'' ?>" href="add_income.php">
                <i class="bi bi-cash"></i>
                Income
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage=='add_expense.php')?'active':'' ?>" href="add_expense.php">
                <i class="bi bi-credit-card"></i>
                Expense
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage=='categories.php')?'active':'' ?>" href="categories.php">
                <i class="bi bi-bar-chart"></i>
                Categories
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage=='transaction.php')?'active':'' ?>" href="transaction.php">
                <i class="bi bi-bar-chart"></i>
               Transactions
            </a>
        </li>

    </ul>

    <div class="et-section-title">Others</div>

    <ul class="nav flex-column et-menu">
    
        <li class="nav-item">
            <a class="nav-link logout" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </li>
    </ul>

</div>
