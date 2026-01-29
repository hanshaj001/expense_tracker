<?php
session_start();
include("../includes/db_conn.php");
include("../includes/header.php");

// check if the user is logged in or not

if(!isset($_SESSION['name'])){
    header("Location: ../index.php");
}

$user_id = $_SESSION['user_id'];
?>

<div class="container-fluid py-4" style="margin-left: 260px; width: calc(100% - 260px);">

    <div class="py-3">
        <h2 class="text-center">Transactions</h2>
       <p> <?php echo $_POST['delete_transaction'] ?? ''; ?></p>
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
                        <th>Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                <?php 
                $count = 1;
                $result = mysqli_query($conn, "SELECT * FROM transactions WHERE user_id = $user_id");

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $count++; ?></td>

                        <?php 
                            $category_id = $row['category_id'];
                            $category_result = (mysqli_query($conn,"SELECT name,type from categories where id = $category_id;"));
                                $category_row = mysqli_fetch_assoc($category_result); 
                        ?>

                        <td><?php echo $category_row['name']; ?></td>
                        <td><?php echo $category_row['type']; ?></td>

                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['updated_at']; ?></td>
                        <td class="px-0">
                                        <button 
                                            class="btn btn-sm btn-danger"
                                            onclick="openDeleteModal(<?php echo $row['id']; ?>)">
                                            Delete
                                        </button>
                            <a href="edit_income.php?id=<?php echo $row['id'];?>" class="btn btn-sm btn-primary">Update</a>
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

<!-- confirmation -->
 <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      
      <div class="modal-body">
        <div class="mb-3">
          ⚠️
        </div>
        <h4>Are you sure?</h4>
        <p>This action cannot be undone.</p>

        <div class="d-flex justify-content-center gap-3 mt-4">
          <button class="btn btn-danger" id="confirmDeleteBtn">
            Delete Transaction
          </button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

<!--  -->
<script>
    let deleteId = null;

    function openDeleteModal(id) {
        deleteId = id;
        let modal = new bootstrap.Modal(
            document.getElementById('deleteConfirmModal')
        );
        modal.show();
    }

    document.getElementById("confirmDeleteBtn").onclick = function () {
        window.location.href = "delete.php?id=" + deleteId;
    };
</script>
<?php
include("../includes/footer.php");
?>