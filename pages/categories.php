<?php
include("../includes/db_conn.php");
include("../includes/footer.php");
include("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="../assets/form_style.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #111827;
            color: #fff;
            padding: 20px;
        }
        .sidebar h2 { margin-top: 0; font-size: 20px; }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px 0;
            text-decoration: none;
        }
        .sidebar a:hover { background-color: #1f2937; padding-left: 5px; }

        /* Main content */
        .main-content {
            flex: 1;
            padding: 40px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th { background-color: #37add1; color: #fff; }
        tr:hover { background-color: #f1f5f9; }

        .income-row { color: #15803d; cursor: pointer; }
        .income-row:hover { background-color: #d1fae5; }

        .expense-row { color: #b91c1c; cursor: pointer; }
        .expense-row:hover { background-color: #fecaca; }

        .set-limit-btn {
            padding: 5px 10px;
            background-color: #f59e0b;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }
        .set-limit-btn:hover { background-color: #d97706; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="#">Home</a>
        <a href="#">Categories</a>
        <a href="#">Income</a>
        <a href="#">Expenses</a>
    </div>

    <div class="main-content">
        <h2>Categories</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="income-row">
                    <td>Salary</td>
                    <td>Income</td>
                    <td></td>
                </tr>
                <tr class="income-row">
                    <td>Freelance</td>
                    <td>Income</td>
                    <td></td>
                </tr>
                <tr class="expense-row">
                    <td>Food</td>
                    <td>Expense</td>
                    <td><button class="set-limit-btn" onclick="setLimit('Food')">Set Limit</button></td>
                </tr>
                <tr class="expense-row">
                    <td>Transport</td>
                    <td>Expense</td>
                    <td><button class="set-limit-btn" onclick="setLimit('Transport')">Set Limit</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function setLimit(category) {
            let limit = prompt("Set limit for " + category + ":", "1000");
            if(limit) {
                alert(category + " limit set to $" + limit);
                // Here you can save this to backend via AJAX later
            }
        }
    </script>
</body>
</html>
