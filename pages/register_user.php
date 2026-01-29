<?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/db_conn.php");
?>

<?php
    $name = '';
    $password = '';
    $email = '';
    $errors = [];
    $success = '';
// taking inputs 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // collecting data safely and trimming whitespace
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // validating name
    if(empty($name)){
        $errors['name'] = "Name is required .";
    }

    if(strlen($name)<3){
        $errors['name_length'] = "Name must be at least 3 characters long";
    }

    if(!preg_match("/^[a-zA-Z ]+$/",$name)){
            $errors['name_pattern'] = "Name can contain only letters and spaces";
    }

    // password
    if(empty($password)){
        $errors['password'] ="Password is required";
    }
    if(strlen($password)<6){
        $errors['password_length'] = "Password must be at least 6 characters long";
    }
    // enc password
    $enc_password = password_hash($password,PASSWORD_BCRYPT);

    // emails 
    if(empty($email)){
        $errors['email'] = "Email is required";
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors['email_validate'] = "Need a valid email";
    }

    // inserting data into databases
    if(empty($errors)){
    $sql = "INSERT into users(name,password,email)
    values ('$name','$enc_password','$email')";

    if(mysqli_query($conn,$sql)){
        header("Location: ../index.php")
;    }
    else 
        $errors['register'] = "Error while registering....";

    mysqli_close($conn);
}
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
                            <input type="text" name="name" placeholder="Enter username" class="form-control" value="<?php echo $name ?? ''; ?>" required>
                            <span style="color: red; text-align: center;"><?php echo $errors['name'] ?? ''; ?></span>
                            <span style="color: red; text-align: center;"><?php echo $errors['name_length'] ?? ''; ?></span>
                            <span style="color: red; text-align: center;"><?php echo $errors['name_pattern'] ?? ''; ?></span>
                        </div>

                        <!-- for email -->
                      <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" placeholder="Enter email" class="form-control" value="<?php echo $email ?? ''; ?>" required>
                            <span style="color: red; text-align: center;"><?php echo $errors['email'] ?? ''; ?></span>
                            <span style="color: red; text-align: center;"><?php echo $errors['email_validate'] ?? ''; ?></span>

                        </div>

                        <!-- pass -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" placeholder="Enter password" class="form-control" value="<?php echo $password ?? ''; ?>" required>
                            <span style="color: red; text-align: center;"><?php echo $errors['password'] ?? ''; ?></span>
                            <span style="color: red; text-align: center;"><?php echo $errors['password_length'] ?? ''; ?></span>

                        </div>
            

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                            <span style="color: red; text-align: center;"><?php echo $errors['register'] ?? ''; ?></span>
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
