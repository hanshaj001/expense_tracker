<?php
session_start();
include("../includes/db_conn.php");
include("../includes/header.php");
$user_id = $_SESSION['user_id'];
?>

<div class="container-fluid py-4" style="margin-left: 260px; width: calc(100% - 260px);">

    <div class="py-3">
        <h2 class="text-center">Transactions</h2>

        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="btn btn-primary" href="#">Search</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center"> 
            <table class="table table-bordered table-striped w-75">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Income ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                <?php 
                $count = 1;
                $result = mysqli_query($conn, "SELECT * FROM add_income WHERE user_id = $user_id");

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $row['income_id']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td class="px-0">
                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                            <a href="edit_income.php?income_id=<?php echo $row['income_id'];?>" class="btn btn-sm btn-primary">Update</a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5'>No Records Found</td></tr>";
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>

</div>

<?php
include("../includes/footer.php");
?>