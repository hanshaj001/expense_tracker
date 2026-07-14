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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/index.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <!-- Decorative circles -->
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>
    <div class="circle circle-4"></div>

    <div class="container animate-fade-in">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Join Us</h1>
                <p class="subtitle">Start managing your finances today</p>
            </div>
        </div>

        <!-- Register Section -->
        <div class="login-section">
            <h2>Sign Up</h2>
            <p class="description">Create your account to get started.</p>

            <form action="" method="post">
                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                        <input type="text" name="name" placeholder="User Name" class="form-control" value="<?php echo $name ?? ''; ?>" required minlength="3" pattern="[a-zA-Z ]+">
                    </div>
                    <span class="error-msg"><?php echo $errors['name'] ?? ''; ?></span>
                    <span class="error-msg"><?php echo $errors['name_length'] ?? ''; ?></span>
                    <span class="error-msg"><?php echo $errors['name_pattern'] ?? ''; ?></span>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" placeholder="Email Address" class="form-control" value="<?php echo $email ?? ''; ?>" required>
                    </div>
                    <span class="error-msg"><?php echo $errors['email'] ?? ''; ?></span>
                    <span class="error-msg"><?php echo $errors['email_validate'] ?? ''; ?></span>
                </div>

                <div class="form-group">
                    <div class="input-wrapper password-wrapper">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control" required minlength="6">
                        <button type="button" class="show-password" onclick="togglePassword()">SHOW</button>
                    </div>
                    <span class="error-msg"><?php echo $errors['password'] ?? ''; ?></span>
                    <span class="error-msg"><?php echo $errors['password_length'] ?? ''; ?></span>
                </div>

                <button type="submit" class="btn-gradient">Register</button>
                <span class="error-msg" style="text-align: center; display: block; margin-top: 10px;"><?php echo $errors['register'] ?? ''; ?></span>
            </form>

            <div class="signup-link">
                Already have an account? <a href="../index.php" class="styled-link">Log In</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const btn = document.querySelector('.show-password');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                btn.textContent = 'HIDE';
            } else {
                passwordField.type = 'password';
                btn.textContent = 'SHOW';
            }
        }
    </script>
</body>
</html>

<?php
include("../includes/footer.php");
?>
