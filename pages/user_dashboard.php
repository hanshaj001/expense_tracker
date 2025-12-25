<?php
include("../includes/db_conn.php");
include("../includes/header.php");

// total income fetiching
$total_income_sql = "SELECT sum(amount)  as total_income from add_income
where month(created_at) = month(current_date())
and year(created_at) = year(curdate())";

// fetching expense
$total_expense_sql = "SELECT sum(amount) as total_expense from add_expense
where month(created_at) = month(current_date())
and year(created_at) = year(current_date())";

// query running
$income_result = mysqli_query($conn,$total_income_sql);
$expense_result = mysqli_query($conn,$total_expense_sql);

// converting reault into array assoc
if(mysqli_num_rows($income_result) == 1){
    $in_row = mysqli_fetch_assoc($income_result);
}

if(mysqli_num_rows($expense_result)==1){
    $ex_row = mysqli_fetch_assoc($expense_result);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/user_dashboard.css">
</head>
<body>


<!-- Main Content -->
<div class="main-content">
    
    <!-- Header Section -->
    <div class="dashboard-header">
        <div>
            <h1>Welcome Back, <?php echo $_SESSION['userName'] ?? 'User';?></h1>
            <p class="subtitle">Here's your financial overview for this month</p>
        </div>
        <div class="header-date">
            <span id="currentDate"></span>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="summary-grid">
        
        <!-- Total Income Card -->
        <div class="summary-card income-card">
            <div class="card-icon income-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div class="card-content">
                <p class="card-label">Total Income</p>
                <h2 class="card-amount" id="totalIncome"><?php echo $in_row['total_income'];?></h2>
            </div>
        </div>

        <!-- Total Expense Card -->
        <div class="summary-card expense-card">
            <div class="card-icon expense-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                    <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                </svg>
            </div>
            <div class="card-content">
                <p class="card-label">Total Expense</p>
                <h2 class="card-amount" id="totalExpense"><?php echo $ex_row ['total_expense']; ?></h2>
            </div>
        </div>
        

    </div>

    <!-- Charts Section -->
    <div class="charts-container">
        
        <!-- Left Column - Large Chart -->
        <div class="chart-large">
            <div class="chart-header">
                <h3>Income vs Expense Trend</h3>
                <div class="chart-controls">
                    <button class="chart-btn active">6M</button>
                    <button class="chart-btn">1Y</button>
                    <button class="chart-btn">All</button>
                </div>
            </div>
            <canvas id="trendChart"></canvas>
        </div>

        <!-- Right Column - Two Smaller Charts -->
        <div class="chart-column">
            
            <!-- Category Distribution -->
            <div class="chart-medium">
                <div class="chart-header">
                    <h3>Expense by Category</h3>
                </div>
                <canvas id="categoryChart"></canvas>
            </div>

            <!-- Recent Transactions -->
            <div class="recent-transactions">
                <div class="chart-header">
                    <h3>Recent Transactions</h3>
                    <a href="#" class="view-all">View All →</a>
                </div>
                <div class="transaction-list" id="transactionList">
                    <!-- Transactions will be inserted here by JS -->
                </div>
            </div>

        </div>

    </div>

    <!-- Bottom Row - Additional Charts -->
    <div class="bottom-charts">
        
        <!-- Monthly Comparison -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Monthly Comparison</h3>
            </div>
            <canvas id="monthlyChart"></canvas>
        </div>

        <!-- Top Categories -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Top Spending Categories</h3>
            </div>
            <canvas id="topCategoriesChart"></canvas>
        </div>

    </div>

</div>


</body>
</html>


<?php
include("../includes/footer.php");

?>