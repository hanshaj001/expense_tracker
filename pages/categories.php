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
            <h1>Categories</h1>
            <p class="subtitle">Select a category to add a transaction</p>
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
            
            <!-- Income Categories Section -->
            <section class="categories-section">
                <h2 class="section-title">Income Categories</h2>
                <div class="categories-grid">
                    
                    <!-- Salary -->
                    <a href="add_income.php?category=Salary" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #4CAF50;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <h3 class="category-name">Salary</h3>
                        </div>
                        <p class="category-description">Monthly or regular employment income</p>
                    </a>

                    <!-- Bonus -->
                    <a href="add_income.php?category=Bonus" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #2196F3;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L15 8.5L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L9 8.5L12 2Z"></path>
                                </svg>
                            </div>
                            <h3 class="category-name">Bonus</h3>
                        </div>
                        <p class="category-description">Performance bonuses and incentives</p>
                    </a>

                    <!-- Freelance -->
                    <a href="add_income.php?category=Freelance" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #FF9800;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <h3 class="category-name">Freelance</h3>
                        </div>
                        <p class="category-description">Freelance work and side projects</p>
                    </a>

                    <!-- Investment -->
                    <a href="add_income.php?category=Investment" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #9C27B0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <h3 class="category-name">Investment</h3>
                        </div>
                        <p class="category-description">Returns from stocks, bonds, or other investments</p>
                    </a>

                </div>
            </section>

            <!-- Expense Categories Section -->
            <section class="categories-section">
                <h2 class="section-title">Expense Categories</h2>
                <div class="categories-grid">
                    
                    <!-- Food & Dining -->
                    <a href="add_expense.php?category=Food" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #F44336;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                    <line x1="6" y1="1" x2="6" y2="4"></line>
                                    <line x1="10" y1="1" x2="10" y2="4"></line>
                                    <line x1="14" y1="1" x2="14" y2="4"></line>
                                </svg>
                            </div>
                            <h3 class="category-name">Food & Dining</h3>
                        </div>
                        <p class="category-description">Groceries, restaurants, and food delivery</p>
                    </a>

                    <!-- Transportation -->
                    <a href="add_expense.php?category=Transportation" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #00BCD4;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                            </div>
                            <h3 class="category-name">Transportation</h3>
                        </div>
                        <p class="category-description">Gas, public transport, taxi, and vehicle maintenance</p>
                    </a>

                    <!-- Housing/Rent -->
                    <a href="add_expense.php?category=Housing" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #795548;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </div>
                            <h3 class="category-name">Housing/Rent</h3>
                        </div>
                        <p class="category-description">Rent, mortgage, and housing expenses</p>
                    </a>

                    <!-- Utilities -->
                    <a href="add_expense.php?category=Utilities" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #FF9800;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                            </div>
                            <h3 class="category-name">Utilities</h3>
                        </div>
                        <p class="category-description">Electricity, water, internet, and phone bills</p>
                    </a>

                    <!-- Medical/Health -->
                    <a href="add_expense.php?category=Medical" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #E91E63;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                </svg>
                            </div>
                            <h3 class="category-name">Medical/Health</h3>
                        </div>
                        <p class="category-description">Doctor visits, medicines, and healthcare</p>
                    </a>

                    <!-- Shopping -->
                    <a href="add_expense.php?category=Shopping" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #9C27B0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                            </div>
                            <h3 class="category-name">Shopping</h3>
                        </div>
                        <p class="category-description">Clothing, accessories, and general shopping</p>
                    </a>

                    <!-- Entertainment -->
                    <a href="add_expense.php?category=Entertainment" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #FF5722;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                </svg>
                            </div>
                            <h3 class="category-name">Entertainment</h3>
                        </div>
                        <p class="category-description">Movies, games, events, and hobbies</p>
                    </a>

                    <!-- Education -->
                    <a href="add_expense.php?category=Education" class="category-card">
                        <div class="category-header">
                            <div class="category-color" style="background: #3F51B5;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </div>
                            <h3 class="category-name">Education</h3>
                        </div>
                        <p class="category-description">Tuition, books, courses, and learning materials</p>
                    </a>

                </div>
            </section>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>