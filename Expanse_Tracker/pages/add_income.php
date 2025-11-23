 <?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/sidebar.php");

?>
 
 <link rel="stylesheet" href="../assets/css/style.css">
 <!-- Add Income Page -->
                <div id="add-income-page"  class="page">
                    <div class="form-container ">
                        <h2>Add Income</h2>
                        <form id="incomeForm" class="transaction-form">
                            <div class="form-group">
                                <label for="incomeTitle">Title</label>
                                <input type="text" id="incomeTitle" required placeholder="e.g., Salary, Freelance">
                            </div>
                            <div class="form-group">
                                <label for="incomeAmount">Amount</label>
                                <input type="number" id="incomeAmount" required placeholder="0.00" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label for="incomeDate">Date</label>
                                <input type="date" id="incomeDate" required>
                            </div>
                            <div class="form-group">
                                <label for="incomeNotes">Notes (Optional)</label>
                                <textarea id="incomeNotes" placeholder="Add any notes..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Income</button>
                        </form>
                    </div>
                </div>
