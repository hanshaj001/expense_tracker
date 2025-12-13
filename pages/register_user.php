<?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/db_conn.php");
?>

<?php

// taking inputs 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // collecting data safely and trimming whitespace
    $user_name = trim($_POST['user_name']);
    $user_password = trim($_POST['password']);
    $email = trim(htmlspecialchars($_POST['email']));
    $phone = trim(htmlspecialchars($_POST['phone'] ?? ''));

    // enc password
    $enc_password = password_hash($user_password,PASSWORD_BCRYPT);

    // inserting data into databases
    $sql = "INSERT into users(user_name,password,email,phone)
    values ('$user_name','$enc_password','$email',$phone)";

    if(mysqli_query($conn,$sql)){
       my_alert("success","New Record recorded Successfully " );
       header("Location: ../index.php")
;    }
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

                        <!-- for email -->
                      <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" placeholder="Enter email" class="form-control" required>
                        </div>

                        <!-- pass -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" placeholder="Enter password" class="form-control" required>
                        </div>

                        <!-- phone -->
                     <div class="mb-3">
                            <label class="form-label">Phone no</label>
                            <input type="text" name="phone" placeholder="Enter phone No" class="form-control" >
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
