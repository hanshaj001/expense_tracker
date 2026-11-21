<?php
// Determine the current active page for sidebar active link highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle = $page_title ?? "Expense Tracker - Personal Finance";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Global Root Theme & Unified Colors -->
    <link rel="stylesheet" href="../assets/global.css">

    <!-- Sidebar & Layout Structure CSS -->
    <link rel="stylesheet" href="../assets/styles.css">
</head>

<body>

<!-- ==========================================================================
     MOBILE TOP NAVIGATION BAR
     Visible only on mobile screens (<= 768px)
     ========================================================================== -->
<div class="topbar">
    <button class="hamburger-btn" id="hamburgerBtn" aria-label="Open sidebar menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="topbar-brand">
        <i class="bi bi-wallet2"></i>
        <span>Expense Tracker</span>
    </div>
</div>

<!-- Dark Backdrop Overlay when mobile sidebar is open -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ==========================================================================
     MAIN SIDEBAR NAVIGATION
     Consistent across all authenticated pages
     ========================================================================== -->
<aside class="sidebar" id="sidebar">

    <!-- Brand Header -->
    <div class="app-header">
        <div class="logo">
            <i class="bi bi-wallet2"></i>
            <span>Expense Tracker</span>
        </div>
        <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Navigation Menu: Main Section -->
    <div class="section-title">Overview</div>
    <ul class="nav flex-column menu">
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'user_dashboard.php') ? 'active' : '' ?>" href="user_dashboard.php">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>

    <!-- Navigation Menu: Finance Management -->
    <div class="section-title">Finance</div>
    <ul class="nav flex-column menu">
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'add_income.php' || $currentPage == 'edit_income.php') ? 'active' : '' ?>" href="add_income.php">
                <i class="bi bi-cash-coin"></i>
                <span>Income</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'add_expense.php' || $currentPage == 'edit_expense.php') ? 'active' : '' ?>" href="add_expense.php">
                <i class="bi bi-credit-card-2-front"></i>
                <span>Expense</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'categories.php') ? 'active' : '' ?>" href="categories.php">
                <i class="bi bi-tags-fill"></i>
                <span>Categories</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'transaction.php') ? 'active' : '' ?>" href="transaction.php">
                <i class="bi bi-arrow-left-right"></i>
                <span>Transactions</span>
            </a>
        </li>
    </ul>

    <!-- Navigation Menu: Account / Others -->
    <div class="section-title" style="margin-top: auto;">Account</div>
    <ul class="nav flex-column menu">
        <li class="nav-item">
            <a class="nav-link logout" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>

</aside>