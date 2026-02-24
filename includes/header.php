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
    <link rel="stylesheet" href="../assets/styles.css">
</head>

<body>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- 
     MOBILE TOP BAR
    -->
<div class="topbar">
    <button class="hamburger-btn" id="hamburgerBtn" aria-label="Open menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="topbar-brand">Expense Tracker</div>
</div>

<!-- Overlay  -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>


<!-- sidebar -->
<div class="sidebar" id="sidebar">

    <div class="app-header">
        <span class="logo">Expense Tracker</span>
        <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="section-title">Business</div>

    <ul class="nav flex-column menu">

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'user_dashboard.php') ? 'active' : '' ?>" href="user_dashboard.php">
                <i class="bi bi-grid"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'add_income.php') ? 'active' : '' ?>" href="add_income.php">
                <i class="bi bi-cash"></i>
                Income
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'add_expense.php') ? 'active' : '' ?>" href="add_expense.php">
                <i class="bi bi-credit-card"></i>
                Expense
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'categories.php') ? 'active' : '' ?>" href="categories.php">
                <i class="bi bi-bar-chart"></i>
                Categories
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'transaction.php') ? 'active' : '' ?>" href="transaction.php">
                <i class="bi bi-arrow-left-right"></i>
                Transactions
            </a>
        </li>

    </ul>

    <div class="section-title">Others</div>

    <ul class="nav flex-column menu">

        <li class="nav-item">
            <a class="nav-link logout" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </li>

    </ul>

</div>

<!-- ==============================
     SIDEBAR TOGGLE SCRIPT
     ============================== -->
<script>
    (function () {
        var sidebar        = document.getElementById('sidebar');
        var overlay        = document.getElementById('sidebarOverlay');
        var hamburgerBtn   = document.getElementById('hamburgerBtn');
        var closeBtn       = document.getElementById('sidebarCloseBtn');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (hamburgerBtn) hamburgerBtn.addEventListener('click', openSidebar);
        if (closeBtn)     closeBtn.addEventListener('click', closeSidebar);
        if (overlay)      overlay.addEventListener('click', closeSidebar);

        // Close sidebar when a nav link is clicked on mobile
        document.querySelectorAll('.menu .nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) closeSidebar();
            });
        });
    })();
</script>