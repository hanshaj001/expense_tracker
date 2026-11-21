<?php
// Start session for auth checks
session_start();

// 1. Authentication check - Check BEFORE sending any HTML output
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../includes/db_conn.php");
include("../includes/function.php");

$user_id = (int)$_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? 'User';

// 2. Fetch Weekly Metrics using prepared queries
// Total income this week
$in_stmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(amount), 0) AS total_income FROM transactions
                                  WHERE user_id = ? AND type = 'income'
                                  AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)");
mysqli_stmt_bind_param($in_stmt, "i", $user_id);
mysqli_stmt_execute($in_stmt);
$in_row = mysqli_fetch_assoc(mysqli_stmt_get_result($in_stmt));
mysqli_stmt_close($in_stmt);
$weekly_income = (float)($in_row['total_income'] ?? 0);

// Total expense this week
$ex_stmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(amount), 0) AS total_expense FROM transactions
                                  WHERE user_id = ? AND type = 'expense'
                                  AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)");
mysqli_stmt_bind_param($ex_stmt, "i", $user_id);
mysqli_stmt_execute($ex_stmt);
$ex_row = mysqli_fetch_assoc(mysqli_stmt_get_result($ex_stmt));
mysqli_stmt_close($ex_stmt);
$weekly_expense = (float)($ex_row['total_expense'] ?? 0);

// Weekly Net Balance
$weekly_net = $weekly_income - $weekly_expense;

// 3. Chart Data: Income vs Expense - Last 30 days trend
$trend_stmt = mysqli_prepare($conn, "SELECT DATE(created_at) as date, type, SUM(amount) as total
                                     FROM transactions 
                                     WHERE user_id = ? 
                                       AND created_at >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)
                                     GROUP BY DATE(created_at), type 
                                     ORDER BY date ASC");
mysqli_stmt_bind_param($trend_stmt, "i", $user_id);
mysqli_stmt_execute($trend_stmt);
$trend_res = mysqli_stmt_get_result($trend_stmt);
$trend_data = [];
while ($t_row = mysqli_fetch_assoc($trend_res)) {
    $trend_data[] = $t_row;
}
mysqli_stmt_close($trend_stmt);

// Expense by Category (All time or recent)
$exp_cat_stmt = mysqli_prepare($conn, "SELECT c.name, SUM(t.amount) as total
                                       FROM transactions t 
                                       JOIN categories c ON t.category_id = c.id
                                       WHERE t.user_id = ? AND t.type = 'expense'
                                       GROUP BY c.name 
                                       ORDER BY total DESC");
mysqli_stmt_bind_param($exp_cat_stmt, "i", $user_id);
mysqli_stmt_execute($exp_cat_stmt);
$exp_cat_res = mysqli_stmt_get_result($exp_cat_stmt);
$expense_cat_data = [];
while ($ec_row = mysqli_fetch_assoc($exp_cat_res)) {
    $expense_cat_data[] = $ec_row;
}
mysqli_stmt_close($exp_cat_stmt);
$total_expense_sum = array_sum(array_column($expense_cat_data, 'total'));

// Income by Category
$inc_cat_stmt = mysqli_prepare($conn, "SELECT c.name, SUM(t.amount) as total
                                       FROM transactions t 
                                       JOIN categories c ON t.category_id = c.id
                                       WHERE t.user_id = ? AND t.type = 'income'
                                       GROUP BY c.name 
                                       ORDER BY total DESC");
mysqli_stmt_bind_param($inc_cat_stmt, "i", $user_id);
mysqli_stmt_execute($inc_cat_stmt);
$inc_cat_res = mysqli_stmt_get_result($inc_cat_stmt);
$income_cat_data = [];
while ($ic_row = mysqli_fetch_assoc($inc_cat_res)) {
    $income_cat_data[] = $ic_row;
}
mysqli_stmt_close($inc_cat_stmt);
$total_income_sum = array_sum(array_column($income_cat_data, 'total'));

$page_title = "Dashboard - Expense Tracker";
include("../includes/header.php");
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-content animate-fade-in">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p class="subtitle mb-0">Here is your financial summary and spending analytics.</p>
        </div>
        <div>
            <span class="badge bg-white text-dark shadow-sm border px-3 py-2 fw-semibold" style="font-size: 13px;">
                <i class="bi bi-calendar-check me-2 text-primary"></i> <?php echo date("F d, Y"); ?>
            </span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Weekly Income Card -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="glass-card d-flex align-items-center gap-3">
                <div style="width: 54px; height: 54px; border-radius: var(--radius-md); background: var(--income-light); color: var(--income); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="bi bi-arrow-down-left-circle-fill"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Weekly Income</div>
                    <h3 class="fw-bold mb-0 text-success" style="font-size: 24px;">
                        Rs. <?php echo number_format($weekly_income, 2); ?>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Weekly Expense Card -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="glass-card d-flex align-items-center gap-3">
                <div style="width: 54px; height: 54px; border-radius: var(--radius-md); background: var(--expense-light); color: var(--expense); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Weekly Expense</div>
                    <h3 class="fw-bold mb-0 text-danger" style="font-size: 24px;">
                        Rs. <?php echo number_format($weekly_expense, 2); ?>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Weekly Net Balance Card -->
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="glass-card d-flex align-items-center gap-3">
                <div style="width: 54px; height: 54px; border-radius: var(--radius-md); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="bi bi-wallet-fill"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Weekly Net Balance</div>
                    <h3 class="fw-bold mb-0 <?php echo ($weekly_net >= 0) ? 'text-dark' : 'text-danger'; ?>" style="font-size: 24px;">
                        Rs. <?php echo number_format($weekly_net, 2); ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Trend Line Chart -->
    <div class="glass-card mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h4 class="fw-bold mb-0" style="font-size: 17px;">Income vs Expense Trend</h4>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary active" id="btn7d" onclick="filterTrend(7, this)">7 Days</button>
                <button type="button" class="btn btn-outline-primary" id="btn30d" onclick="filterTrend(30, this)">30 Days</button>
            </div>
        </div>
        <div style="position: relative; height: 280px; width: 100%;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Three Donut Breakdown Charts -->
    <div class="row g-4">
        <!-- Income vs Expense Overall -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="font-size: 15px;">All-Time Cashflow</h5>
                    <span class="badge bg-light text-muted">Overview</span>
                </div>
                <div style="position: relative; height: 200px;">
                    <canvas id="overviewDonut"></canvas>
                </div>
                <div class="d-flex justify-content-around mt-3 pt-3 border-top text-center" style="font-size: 13px;">
                    <div>
                        <div class="text-muted">Total Income</div>
                        <strong class="text-success">Rs. <?php echo number_format($total_income_sum, 0); ?></strong>
                    </div>
                    <div>
                        <div class="text-muted">Total Expense</div>
                        <strong class="text-danger">Rs. <?php echo number_format($total_expense_sum, 0); ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense by Category -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="font-size: 15px;">Expense by Category</h5>
                    <span class="badge-expense"><?php echo count($expense_cat_data); ?> Categories</span>
                </div>
                <div style="position: relative; height: 200px;">
                    <canvas id="expenseCategoryDonut"></canvas>
                </div>
                <div class="d-flex justify-content-around mt-3 pt-3 border-top text-center" style="font-size: 13px;">
                    <div>
                        <div class="text-muted">Total Spent</div>
                        <strong class="text-danger">Rs. <?php echo number_format($total_expense_sum, 0); ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income by Category -->
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="font-size: 15px;">Income by Category</h5>
                    <span class="badge-income"><?php echo count($income_cat_data); ?> Categories</span>
                </div>
                <div style="position: relative; height: 200px;">
                    <canvas id="incomeCategoryDonut"></canvas>
                </div>
                <div class="d-flex justify-content-around mt-3 pt-3 border-top text-center" style="font-size: 13px;">
                    <div>
                        <div class="text-muted">Total Earned</div>
                        <strong class="text-success">Rs. <?php echo number_format($total_income_sum, 0); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Charts Script -->
<script>
    const trendRaw        = <?php echo json_encode($trend_data); ?>;
    const expenseCatRaw   = <?php echo json_encode($expense_cat_data); ?>;
    const incomeCatRaw    = <?php echo json_encode($income_cat_data); ?>;
    const totalExpenseSum = <?php echo (float)($total_expense_sum ?: 0); ?>;
    const totalIncomeSum  = <?php echo (float)($total_income_sum ?: 0); ?>;

    const EXPENSE_PALETTE = ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#14b8a6', '#8b5cf6', '#ec4899', '#06b6d4'];
    const INCOME_PALETTE  = ['#10b981', '#3b82f6', '#a855f7', '#22d3ee', '#f59e0b', '#6366f1', '#34d399', '#60a5fa'];

    Chart.defaults.font.family = "'Inter', -apple-system, sans-serif";
    Chart.defaults.color        = '#64748b';

    let trendChartInstance = null;

    function filterTrend(days, button) {
        document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
        if (button) button.classList.add('active');

        let cutoff = new Date();
        cutoff.setDate(cutoff.getDate() - (days - 1));
        cutoff.setHours(0, 0, 0, 0);

        let incomeMap = {}, expenseMap = {};
        trendRaw.forEach(item => {
            let itemDate = new Date(item.date);
            if (itemDate >= cutoff) {
                if (item.type === 'income') {
                    incomeMap[item.date] = parseFloat(item.total);
                } else {
                    expenseMap[item.date] = parseFloat(item.total);
                }
            }
        });

        let dateList = [];
        for (let i = days - 1; i >= 0; i--) {
            let d = new Date();
            d.setDate(d.getDate() - i);
            dateList.push(d.toISOString().slice(0, 10));
        }

        let labels = dateList.map(d => {
            let dt = new Date(d);
            return dt.toLocaleDateString('en', { month: 'short', day: 'numeric' });
        });
        let incomeVals  = dateList.map(d => incomeMap[d] || 0);
        let expenseVals = dateList.map(d => expenseMap[d] || 0);

        if (trendChartInstance) {
            trendChartInstance.destroy();
        }

        const ctx = document.getElementById('trendChart').getContext('2d');
        trendChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Income',
                        data: incomeVals,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 4
                    },
                    {
                        label: 'Expense',
                        data: expenseVals,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#ef4444',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, usePointStyle: true } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: v => 'Rs. ' + v.toLocaleString()
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Initialize trend with 7 days
    filterTrend(7, document.getElementById('btn7d'));

    // Overview Donut Chart
    new Chart(document.getElementById('overviewDonut'), {
        type: 'doughnut',
        data: {
            labels: ['Income', 'Expense'],
            datasets: [{
                data: [totalIncomeSum, totalExpenseSum],
                backgroundColor: ['#10b981', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } }
            }
        }
    });

    // Helper for category donuts
    function renderCategoryDonut(canvasId, rawData, colors) {
        let labels = rawData.map(r => r.name);
        let values = rawData.map(r => parseFloat(r.total));
        
        if (labels.length === 0) {
            labels = ['No Data'];
            values = [1];
            colors = ['#e2e8f0'];
        }

        new Chart(document.getElementById(canvasId), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, usePointStyle: true, font: { size: 11 } }
                    }
                }
            }
        });
    }

    renderCategoryDonut('expenseCategoryDonut', expenseCatRaw, EXPENSE_PALETTE);
    renderCategoryDonut('incomeCategoryDonut', incomeCatRaw, INCOME_PALETTE);
</script>

<?php
mysqli_close($conn);
include("../includes/footer.php");
?>