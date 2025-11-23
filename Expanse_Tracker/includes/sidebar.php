 <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <rect x="2" y="2" width="12" height="12" rx="2" fill="currentColor" opacity="0.8"/>
                        <rect x="18" y="2" width="12" height="12" rx="2" fill="currentColor"/>
                        <rect x="2" y="18" width="12" height="12" rx="2" fill="currentColor"/>
                        <rect x="18" y="18" width="12" height="12" rx="2" fill="currentColor" opacity="0.6"/>
                    </svg>
                    <span>Expense Tracker</span>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
            <nav class="sidebar-nav">
                <a href="user_dashboard.php" class="nav-item active" data-page="dashboard">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="add_income.php" class="nav-item" data-page="add-income">
                    <svg width="20" height="20" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Add Income</span>
                </a>
                <a href="add_expense.php" class="nav-item" data-page="add-expense">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Add Expense</span>
                </a>
                <a href="daily_transactions.php" class="nav-item" data-page="transactions">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 2H15V6H9Z"></path>
                        <rect x="3" y="6" width="18" height="14" rx="2"></rect>
                        <line x1="12" y1="10" x2="12" y2="16"></line>
                    </svg>
                    <span>Daily Transactions</span>
                </a>
                <a href="#categories" class="nav-item" data-page="categories">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="8" height="8"></rect>
                        <rect x="13" y="3" width="8" height="8"></rect>
                        <rect x="13" y="13" width="8" height="8"></rect>
                        <rect x="3" y="13" width="8" height="8"></rect>
                    </svg>
                    <span>Categories</span>
                </a>
                
            </nav>
        </aside>