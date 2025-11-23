<?php
include("./includes/header.php");
include("./includes/function.php");
include("./includes/db_conn.php");
?>

<?php 
    $username = null;

if(isset($_REQUEST['password_update_id'])){
   $password_update_id = $_REQUEST['password_update_id'];

  
   // for getting username
   $fetch = "SELECT user_name from reg_user where id = $password_update_id";
   $run_fetch_query = mysqli_query($conn,$fetch);

   // show array    
   $row = mysqli_fetch_assoc($run_fetch_query);

   // fetching username from array
   $username = $row['user_name'];

// for updating password
   if(mysqli_num_rows($run_fetch_query) == 1){

    if(($_SERVER["REQUEST_METHOD"] == "POST")){

        // getting user new password
        $update_user_password = $_POST['update_user_password'];
        
        // query to change pass
         $password_update_query = " UPDATE reg_user set user_password = '$update_user_password' where id = $password_update_id ";

        $run_password_query= mysqli_query($conn,$password_update_query);

        if($run_password_query){
            my_alert("success","Password Changed SucccessFully");
            header("Location: ./display_reg_user.php");
        }else 
            my_alert("danger","Something went wrong while changing the password");

        }
            ?> 

            
  <!-- form for update -->

   <div class="container">
    <div class="card register-card">
        <div class="card-header bg-primary text-center text-white">
            Change Password
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- Note: The form must submit to this same file -->
                    <form action="" method="post">
                        <div class="mb-3">
                            <label class="form-label">User Name</label>
                            <input type="text"  value=" <?php echo "$username";?>" class="form-control" required disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="update_user_password" placeholder="Enter password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="update" class="btn btn-primary w-100">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

   } else
   echo "Not Found";

  }
   mysqli_close($conn);
?>
<?php
// Including footer file (usually contains closing HTML tags or footer section)
include("./includes/footer.php");
?>