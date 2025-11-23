<?php
include("./includes/header.php");
include("./includes/function.php");
include("./includes/db_conn.php");
?>


<!-- tables -->

<div class="container">

    <div class="col-12 py-5">
        <h2 class="col-12 text-center">Registerd Users</h2>

        <!-- for redirecting admin to register_user for manually updating user -->
        <div class="col-12">
            <a class="btn btn-primary " href="./register_user.php">Add User</a>
        </div>
    </div>

    <!-- table for the record of user -->
    <table class="table table-bordered table-striped table">
        <thead>
            <tr class="table table-dark">
                <th scope="col">ID</th>
                <th scope="col">Username</th>
                <th scope="col">Picture</th>
                <th scope="col">Operations</th>
            </tr>
        </thead>

        <tbody>
            <!-- used php to connect with mysql and run a query -->
            <?php
            $fetch_data = "SELECT * FROM reg_user";
            $run_fetch_data = mysqli_query($conn, $fetch_data);

            if (mysqli_num_rows($run_fetch_data) > 0) {
                while ($row = mysqli_fetch_assoc($run_fetch_data)) {
            ?>
                    <tr>
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><a href="upload_image.php?id=<?php echo $row['id']; ?>" class="link-primary  
                        link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
                                <?php

                                if ($row['user_img'] == NULL) {
                                ?>
                                    <img height="30px" src="./images/user_image/dummy.png" alt="Dummmy">
                                <?php
                                } else {
                                ?>
                                    <img height="30px" src="./images/user_image/<?php echo $row['user_img'] ?>" alt="Dummmy">
                                <?php

                                }
                                ?>
                            </a></td>

                       <!-- Delete Operation -->
                            <td>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
                                class="btn btn-danger" 
                                onclick="return confirm('Are you sure you want to delete this user?');">
                                Delete
                                </a>
                           
                            <!-- Update Operation -->
                  
                                <a href="update_password.php?password_update_id=<?php echo $row['id']; ?>" 
                                class="btn btn-primary" 
                                onclick="return confirm('Are you sure you want to Change Password of this user?');">
                                Update Password
                                </a>
                            </td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="2">
                        <h3 class="text-danger text-center">No record Found </h3>
                    </td>
                </tr>
            <?php
            }
              mysqli_close($conn);
            ?>

        </tbody>
    </table>
</div>



<?php
include("./includes/footer.php");
?>