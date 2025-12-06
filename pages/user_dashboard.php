<?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/sidebar.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h1>Expense Tracker</h1>
                    <p class="subtitle">Manage your finances efficiently</p>
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
                <!-- Dashboard Page -->
                <div class="page active">
                    <!-- Summary Cards -->
                    <section class="summary-section">
                        <h2 class="section-title">Financial Overview</h2>
                        <div class="cards-grid">
                            <div class="summary-card income">
                                <div class="card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L22 7V17L12 22L2 17V7L12 2Z"></path>
                                        <path d="M12 12V22"></path>
                                        <path d="M12 12L2 7"></path>
                                        <path d="M12 12L22 7"></path>
                                    </svg>
                                </div>
                                <div class="card-content">
                                    <p class="card-label">Total Income</p>
                                    <h3 class="card-amount">$5,250.00</h3>
                                    <span class="card-meta">All time</span>
                                </div>
                            </div>

                            <div class="summary-card expense">
                                <div class="card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <path d="M12 1V3M12 21V23M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M1 12H3M21 12H23M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22"></path>
                                    </svg>
                                </div>
                                <div class="card-content">
                                    <p class="card-label">Total Expense</p>
                                    <h3 class="card-amount">$3,180.00</h3>
                                    <span class="card-meta">All time</span>
                                </div>
                            </div>

                            <div class="summary-card balance">
                                <div class="card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 21H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h18a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2z"></path>
                                        <path d="M3 10h18"></path>
                                    </svg>
                                </div>
                                <div class="card-content">
                                    <p class="card-label">Balance</p>
                                    <h3 class="card-amount">$2,070.00</h3>
                                    <span class="card-meta">Current</span>
                                </div>
                            </div>

                            <div class="summary-card today">
                                <div class="card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="card-content">
                                    <p class="card-label">Today's Expense</p>
                                    <h3 class="card-amount">$125.00</h3>
                                    <span class="card-meta">Today</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Recent Transactions -->
                    <section class="transactions-section">
                        <h2 class="section-title">Recent Transactions</h2>
                        <div class="table-wrapper">
                            <table class="transactions-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Salary Payment</td>
                                        <td><span class="type-badge income">Income</span></td>
                                        <td>Salary</td>
                                        <td>$5,000.00</td>
                                        <td>Nov 28, 2025</td>
                                    </tr>
                                    <tr>
                                        <td>Grocery Shopping</td>
                                        <td><span class="type-badge expense">Expense</span></td>
                                        <td>Food</td>
                                        <td>$85.50</td>
                                        <td>Nov 27, 2025</td>
                                    </tr>
                                    <tr>
                                        <td>Electric Bill</td>
                                        <td><span class="type-badge expense">Expense</span></td>
                                        <td>Utilities</td>
                                        <td>$120.00</td>
                                        <td>Nov 26, 2025</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>