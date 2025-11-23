
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
                        <div class="avatar">JD</div>
                        <span>John Doe</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="pages-container">
                <!-- Dashboard Page -->
                <div id="dashboard-page" class="page active">
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
                                    <h3 class="card-amount" id="totalIncome">$0.00</h3>
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
                                    <h3 class="card-amount" id="totalExpense">$0.00</h3>
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
                                    <h3 class="card-amount" id="balance">$0.00</h3>
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
                                    <h3 class="card-amount" id="todayExpense">$0.00</h3>
                                    <span class="card-meta">Today</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Charts Section -->
                    <section class="charts-section">
                        <h2 class="section-title">Analytics</h2>
                        <div class="charts-grid">
                            <div class="chart-container">
                                <h3>Income vs Expense</h3>
                                <div class="chart-wrapper">
                                    <canvas id="incomeExpenseChart"></canvas>
                                </div>
                            </div>
                            <div class="chart-container">
                                <h3>Expense by Category</h3>
                                <div class="chart-wrapper">
                                    <canvas id="categoryChart"></canvas>
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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="recentTransactions">
                                    <tr class="empty-state">
                                        <td colspan="6">No transactions yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- Daily Transactions Page -->
                <div id="transactions-page" class="page">
                    <div class="transactions-container">
                        <h2>Daily Transactions</h2>
                        <div class="filters">
                            <div class="filter-group">
                                <label for="transactionDate">Select Date</label>
                                <input type="date" id="transactionDate">
                            </div>
                            <div class="filter-group">
                                <label for="transactionType">Type</label>
                                <select id="transactionType">
                                    <option value="">All</option>
                                    <option value="Income">Income</option>
                                    <option value="Expense">Expense</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="transactionCategory">Category</label>
                                <select id="transactionCategory"></select>
                            </div>
                        </div>

                        <div class="daily-summary">
                            <div class="summary-stat">
                                <span class="stat-label">Daily Income</span>
                                <span class="stat-value income" id="dailyIncome">$0.00</span>
                            </div>
                            <div class="summary-stat">
                                <span class="stat-label">Daily Expense</span>
                                <span class="stat-value expense" id="dailyExpense">$0.00</span>
                            </div>
                            <div class="summary-stat">
                                <span class="stat-label">Daily Balance</span>
                                <span class="stat-value" id="dailyBalance">$0.00</span>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <table class="transactions-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="dailyTransactions">
                                    <tr class="empty-state">
                                        <td colspan="5">No transactions for this date</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Categories Page -->
                <div id="categories-page" class="page">
                    <div class="categories-container">
                        <h2>Manage Categories</h2>
                        <button class="btn btn-primary" id="addCategoryBtn">Add New Category</button>

                        <div class="categories-grid">
                            <div id="categoriesList"></div>
                        </div>
                    </div>
                </div>

                <!-- Settings Page -->
                <div id="settings-page" class="page">
                    <div class="settings-container">
                        <h2>Settings</h2>
                        <div class="settings-group">
                            <h3>Application Settings</h3>
                            <div class="setting-item">
                                <label>
                                    <input type="checkbox" id="darkMode"> Dark Mode
                                </label>
                            </div>
                            <div class="setting-item">
                                <button class="btn btn-secondary" id="exportData">Export Data as JSON</button>
                            </div>
                            <div class="setting-item">
                                <button class="btn btn-danger" id="clearData">Clear All Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <!-- Edit Transaction Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Transaction</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form id="editForm" class="transaction-form">
                <input type="hidden" id="editId">
                <div class="form-group">
                    <label for="editTitle">Title</label>
                    <input type="text" id="editTitle" required>
                </div>
                <div class="form-group">
                    <label for="editAmount">Amount</label>
                    <input type="number" id="editAmount" required step="0.01" min="0">
                </div>
                <div class="form-group" id="editCategoryGroup" style="display:none;">
                    <label for="editCategory">Category</label>
                    <select id="editCategory"></select>
                </div>
                <div class="form-group">
                    <label for="editDate">Date</label>
                    <input type="date" id="editDate" required>
                </div>
                <div class="form-group">
                    <label for="editNotes">Notes</label>
                    <textarea id="editNotes"></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary modal-close-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h3>Confirm Delete</h3>
                <button class="modal-close">&times;</button>
            </div>
            <p id="deleteMessage">Are you sure you want to delete this transaction?</p>
            <div class="form-actions">
                <button id="confirmDelete" class="btn btn-danger">Delete</button>
                <button class="btn btn-secondary modal-close-btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Category</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form id="addCategoryForm" class="transaction-form">
                <div class="form-group">
                    <label for="categoryName">Category Name</label>
                    <input type="text" id="categoryName" required placeholder="e.g., Entertainment">
                </div>
                <div class="form-group">
                    <label for="categoryColor">Color</label>
                    <input type="color" id="categoryColor" value="#3B82F6">
                </div>
                <div class="form-group">
                    <label for="categoryDescription">Description</label>
                    <textarea id="categoryDescription" placeholder="Brief description..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Category</button>
                    <button type="button" class="btn btn-secondary modal-close-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>