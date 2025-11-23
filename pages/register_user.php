<?php
include("../includes/header.php");
include("../includes/function.php");


// taking inputs 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include("./includes/db_conn.php");
    // collecting data safely and trimming whitespace
    $user_name = trim($_POST['user_name']);
    $user_password = trim($_POST['user_password']);

    // enc password
    $enc_password = password_hash($user_password,PASSWORD_BCRYPT);

    // inserting data into databases
    $sql = "INSERT into reg_user(user_name,user_password)
    values ('$user_name','$enc_password')";

    if(mysqli_query($conn,$sql)){
       my_alert("success","New Record recorded Successfully " );
    }
    else 
        my_alert("danger","Error while inserting values");

    mysqli_close($conn);
}
?>

<!-- HTML form -->
</div>
<div class="container">
    <div class="card register-card">
        <div class="card-header bg-primary text-center text-white">
            Register
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- Note: The form must submit to this same file -->
                    <form action="" method="post">
                        <div class="mb-3">
                            <label class="form-label">User Name</label>
                            <input type="text" name="user_name" placeholder="Enter username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="user_password" placeholder="Enter password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("../includes/footer.php");
?>
