<?php
session_start();
include("../includes/db_conn.php");
include("../includes/header.php");

if(!isset($_SESSION['name'])){
    header("Location: ../index.php");
}

$user_id = $_SESSION['user_id'];

// Total income this week
$total_income_sql = "SELECT SUM(amount) AS total_income FROM transactions
WHERE user_id = $user_id AND type = 'income'
AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";

// Total expense this week
$total_expense_sql = "SELECT SUM(amount) AS total_expense FROM transactions
WHERE user_id = $user_id AND type = 'expense'
AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";

$income_result  = mysqli_query($conn, $total_income_sql);
$expense_result = mysqli_query($conn, $total_expense_sql);

if(mysqli_num_rows($income_result) == 1)  $in_row = mysqli_fetch_assoc($income_result);
if(mysqli_num_rows($expense_result) == 1) $ex_row = mysqli_fetch_assoc($expense_result);

// Rent expenses
$category_amt_fetch     = mysqli_query($conn,"SELECT SUM(amount) as total_rent FROM transactions WHERE category_id = 12 AND user_id = $user_id AND type = 'expense'");
$category_amt_fetch_row = mysqli_fetch_assoc($category_amt_fetch);
$total_rent             = $category_amt_fetch_row['total_rent'];

// ===== CHART DATA =====

// Income vs Expense - Last 7 days
$trend_sql = "SELECT DATE(created_at) as date, type, SUM(amount) as total
              FROM transactions WHERE user_id = $user_id
              AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
              GROUP BY DATE(created_at), type ORDER BY date ASC";
$trend_result = mysqli_query($conn, $trend_sql);
$trend_data = [];
while($row = mysqli_fetch_assoc($trend_result)) $trend_data[] = $row;

// Expense by Category
$expense_cat_sql = "SELECT c.name, SUM(t.amount) as total
                    FROM transactions t JOIN categories c ON t.category_id = c.id
                    WHERE t.user_id = $user_id AND t.type = 'expense'
                    GROUP BY c.name ORDER BY total DESC";
$expense_cat_result = mysqli_query($conn, $expense_cat_sql);
$expense_cat_data = [];
while($row = mysqli_fetch_assoc($expense_cat_result)) $expense_cat_data[] = $row;
$total_expense_sum = array_sum(array_column($expense_cat_data, 'total'));

// Income by Category
$income_cat_sql = "SELECT c.name, SUM(t.amount) as total
                   FROM transactions t JOIN categories c ON t.category_id = c.id
                   WHERE t.user_id = $user_id AND t.type = 'income'
                   GROUP BY c.name ORDER BY total DESC";
$income_cat_result = mysqli_query($conn, $income_cat_sql);
$income_cat_data = [];
while($row = mysqli_fetch_assoc($income_cat_result)) $income_cat_data[] = $row;
$total_income_sum = array_sum(array_column($income_cat_data, 'total'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/user_dashboard.css">
    <style>
        * { box-sizing: border-box; }

        .main-content {
            margin-left: 260px;
            padding: 40px;
        }

        /* Summary cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Trend chart full width */
        .chart-fullwidth {
            background: var(--white);
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--shadow);
            margin-bottom: 28px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .chart-controls { display: flex; gap: 8px; }

        .chart-btn {
            padding: 6px 14px;
            border: 2px solid var(--gray-200);
            background: var(--white);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--gray-600);
            font-size: 13px;
        }
        .chart-btn:hover  { border-color: var(--primary); color: var(--primary); }
        .chart-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* Three donut charts row */
        .triple-charts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .triple-chart-card {
            background: var(--white);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
        }

        .chart-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .badge-neutral { background: #f3f4f6; color: #6b7280; }
        .badge-expense { background: #fee2e2; color: #dc2626; }
        .badge-income  { background: #d1fae5; color: #059669; }

        .chart-stats-row {
            display: flex;
            justify-content: space-around;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #f3f4f6;
        }

        .chart-stat-item { text-align: center; }

        .chart-stat-label {
            font-size: 11px;
            color: var(--gray-600);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
        }

        .chart-stat-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark);
            word-break: break-all;
        }
        .income-val  { color: #10b981; }
        .expense-val { color: #ef4444; }

        /* Responsive */
        @media (max-width: 1200px) {
            .triple-charts { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 900px) {
            .main-content { margin-left: 0; padding: 20px; }
            .triple-charts { grid-template-columns: 1fr; }
            .dashboard-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        }

        @media (max-width: 600px) {
            .summary-grid { grid-template-columns: 1fr; }
            .card-amount  { font-size: 26px; }
            .chart-fullwidth, .triple-chart-card { padding: 18px; }
            .chart-header h3 { font-size: 15px; }
        }
    </style>
</head>
<body>

<div class="main-content">

    <!-- Header -->
    <div class="dashboard-header">
        <div>
            <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <p class="subtitle">Here's your financial overview for this week</p>
        </div>
        <div class="header-date">
            <span><?php echo date("F d, Y"); ?></span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">

        <div class="summary-card income-card">
            <div class="card-icon income-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div class="card-content">
                <p class="card-label">Total Income</p>
                <h2 class="card-amount">Rs. <?php echo number_format($in_row['total_income'] ?? 0, 2); ?></h2>
            </div>
        </div>

        <div class="summary-card expense-card">
            <div class="card-icon expense-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                    <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                </svg>
            </div>
            <div class="card-content">
                <p class="card-label">Total Expense</p>
                <h2 class="card-amount">Rs. <?php echo number_format($ex_row['total_expense'] ?? 0, 2); ?></h2>
            </div>
        </div>

    </div>

    <!-- Trend Chart (Full Width) -->
    <div class="chart-fullwidth">
        <div class="chart-header">
            <h3>Income vs Expense Trend</h3>
            <div class="chart-controls">
                <button class="chart-btn active" onclick="loadTrend(7, this)">7D</button>
                <button class="chart-btn" onclick="loadTrend(30, this)">1M</button>
                <button class="chart-btn" onclick="loadTrend(365, this)">All</button>
            </div>
        </div>
        <canvas id="trendChart" height="90"></canvas>
    </div>

    <!-- Three Donut Charts -->
    <div class="triple-charts">

        <!-- Income vs Expense Overview -->
        <div class="triple-chart-card">
            <div class="chart-header">
                <h3>Income vs Expense</h3>
                <span class="chart-badge badge-neutral">Overview</span>
            </div>
            <canvas id="incomeExpenseDonut" height="180"></canvas>
            <div class="chart-stats-row">
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Income</div>
                    <div class="chart-stat-value income-val">Rs. <?php echo number_format($total_income_sum, 0); ?></div>
                </div>
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Expense</div>
                    <div class="chart-stat-value expense-val">Rs. <?php echo number_format($total_expense_sum, 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Expense by Category -->
        <div class="triple-chart-card">
            <div class="chart-header">
                <h3>Expense Breakdown</h3>
                <span class="chart-badge badge-expense">by Category</span>
            </div>
            <canvas id="expenseCategoryDonut" height="180"></canvas>
            <div class="chart-stats-row">
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Total</div>
                    <div class="chart-stat-value expense-val">Rs. <?php echo number_format($total_expense_sum, 0); ?></div>
                </div>
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Categories</div>
                    <div class="chart-stat-value"><?php echo count($expense_cat_data); ?></div>
                </div>
            </div>
        </div>

        <!-- Income by Category -->
        <div class="triple-chart-card">
            <div class="chart-header">
                <h3>Income Breakdown</h3>
                <span class="chart-badge badge-income">by Category</span>
            </div>
            <canvas id="incomeCategoryDonut" height="180"></canvas>
            <div class="chart-stats-row">
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Total</div>
                    <div class="chart-stat-value income-val">Rs. <?php echo number_format($total_income_sum, 0); ?></div>
                </div>
                <div class="chart-stat-item">
                    <div class="chart-stat-label">Categories</div>
                    <div class="chart-stat-value"><?php echo count($income_cat_data); ?></div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
var trendRaw        = <?php echo json_encode($trend_data); ?>;
var expenseCatRaw   = <?php echo json_encode($expense_cat_data); ?>;
var incomeCatRaw    = <?php echo json_encode($income_cat_data); ?>;
var totalExpenseSum = <?php echo (float)($total_expense_sum ?: 0); ?>;
var totalIncomeSum  = <?php echo (float)($total_income_sum ?: 0); ?>;

var EXPENSE_COLORS = ['#ef4444','#f97316','#f59e0b','#84cc16','#14b8a6','#8b5cf6','#ec4899','#06b6d4'];
var INCOME_COLORS  = ['#10b981','#3b82f6','#a855f7','#22d3ee','#f59e0b','#6366f1','#34d399','#60a5fa'];

Chart.defaults.font.family = "'Inter', -apple-system, sans-serif";
Chart.defaults.color        = '#6b7280';

function formatRs(val) {
    return 'Rs. ' + parseFloat(val).toLocaleString('en-IN');
}

function trimData(labels, values, colors, max) {
    if (labels.length <= max) return { labels: labels, values: values, colors: colors };
    var other = values.slice(max - 1).reduce(function(a, b){ return a + b; }, 0);
    return {
        labels: labels.slice(0, max - 1).concat('Other'),
        values: values.slice(0, max - 1).concat(other),
        colors: colors.slice(0, max - 1).concat('#9ca3af')
    };
}

function makeDoughnut(id, labels, values, colors, total) {
    new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 8, font: { size: 10 }, usePointStyle: true, boxWidth: 8 }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            var pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                            return ' ' + formatRs(ctx.parsed) + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
}

// ===== 1. TREND CHART =====
var trendInstance = null;

function loadTrend(days, btn) {
    document.querySelectorAll('.chart-btn').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');

    var cutoff = new Date();
    cutoff.setDate(cutoff.getDate() - (days - 1));
    cutoff.setHours(0,0,0,0);

    var incomeMap = {}, expenseMap = {};
    trendRaw.forEach(function(r) {
        if (new Date(r.date) >= cutoff) {
            if (r.type === 'income') incomeMap[r.date] = parseFloat(r.total);
            else expenseMap[r.date] = parseFloat(r.total);
        }
    });

    // Build date list from available data
    var allDates = {};
    Object.keys(incomeMap).forEach(function(d){ allDates[d] = 1; });
    Object.keys(expenseMap).forEach(function(d){ allDates[d] = 1; });

    // If no data in range, build last 7 days skeleton
    if (Object.keys(allDates).length === 0) {
        for (var i = 6; i >= 0; i--) {
            var d = new Date();
            d.setDate(d.getDate() - i);
            allDates[d.toISOString().slice(0,10)] = 1;
        }
    }

    var useDates = Object.keys(allDates).sort();
    var labels      = useDates.map(function(d){ var dt = new Date(d); return dt.toLocaleDateString('en', {month:'short', day:'numeric'}); });
    var incomeVals  = useDates.map(function(d){ return incomeMap[d]  || 0; });
    var expenseVals = useDates.map(function(d){ return expenseMap[d] || 0; });

    if (trendInstance) trendInstance.destroy();

    trendInstance = new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeVals,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    fill: true, tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff', pointBorderWidth: 2,
                    pointRadius: 5, borderWidth: 3
                },
                {
                    label: 'Expense',
                    data: expenseVals,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.08)',
                    fill: true, tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff', pointBorderWidth: 2,
                    pointRadius: 5, borderWidth: 3
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 20 } },
                tooltip: { callbacks: { label: function(ctx){ return ' ' + formatRs(ctx.parsed.y); } } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { callback: function(v){ return 'Rs.' + v.toLocaleString('en-IN'); } }
                },
                x: { grid: { display: false } }
            }
        }
    });
}

loadTrend(7, document.querySelector('.chart-btn.active'));

// ===== 2. INCOME VS EXPENSE OVERVIEW =====
makeDoughnut(
    'incomeExpenseDonut',
    ['Total Income', 'Total Expense'],
    [totalIncomeSum, totalExpenseSum],
    ['#10b981', '#ef4444'],
    totalIncomeSum + totalExpenseSum
);

// ===== 3. EXPENSE BY CATEGORY =====
(function(){
    var labels  = expenseCatRaw.map(function(r){ return r.name; });
    var values  = expenseCatRaw.map(function(r){ return parseFloat(r.total); });
    var trimmed = trimData(labels, values, EXPENSE_COLORS, 7);
    makeDoughnut('expenseCategoryDonut', trimmed.labels, trimmed.values, trimmed.colors, totalExpenseSum);
})();

// ===== 4. INCOME BY CATEGORY =====
(function(){
    var labels  = incomeCatRaw.map(function(r){ return r.name; });
    var values  = incomeCatRaw.map(function(r){ return parseFloat(r.total); });
    var trimmed = trimData(labels, values, INCOME_COLORS, 7);
    makeDoughnut('incomeCategoryDonut', trimmed.labels, trimmed.values, trimmed.colors, totalIncomeSum);
})();
</script>

</body>
</html>

<?php include("../includes/footer.php"); ?>