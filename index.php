<?php
include("./includes/function.php");
include("./includes/db_conn.php");

session_start();

$errors = [];
$email = '';
$password = '';
/// taking inputs 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // collecting data safely and trimming whitespace
    $email = trim($_POST['email']);
    $user_password = trim($_POST['password']);
    
    // SQL query with placeholder
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // binds the value to the placeholder
    mysqli_stmt_bind_param($stmt, "s", $email);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // checking if there is only one row
    if ( mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);
        $db_user_password = $row['password'];
        $db_user_name = $row['name'];
        $db_email = $row['email'];
        $user_id = $row['id'];
 
        // comparing password
        if (password_verify($user_password, $db_user_password)) {

            // creating a variable to store in session
            $_SESSION['name'] = $db_user_name;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['is_login'] = true;

            header("Location: ./pages/user_dashboard.php");
            exit();
        } else {
                 $errors['invalid'] ="Invalid username or password";
        }

    } else {
         $errors['not_found'] = "Username not found";
    }

    // proper close inside POST block
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="./assets/index.css">
</head>
<body>
    <!-- Decorative circles -->
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>
    <div class="circle circle-4"></div>

    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Expense Tracker</h1>
                <p class="subtitle">Manage Your Finance</p>
            </div>
        </div>

        <!-- Login Section -->
        <div class="login-section">
            <h2>Sign in</h2>
            <p class="description"></p>

            <!-- BACKEND FORM START -->
            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="email" id="email" name="email" placeholder="User email" value="<?php echo $email ?? '';?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper password-wrapper">
                        <span class="input-icon">🔑</span>
                        <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password ?? '';?>" required>
                        <button type="button" class="show-password" onclick="togglePassword()">SHOW</button>
                    </div>
                </div>
<!-- 
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div> -->

                <button type="submit" class="btn-signin">Log in</button>
                <span style="text-align: center; color: red; font-weight: bold;"><?php echo $errors['invalid'] ?? ''; ?></span>
                <span style="text-align: center; color: red; font-weight: bold;"><?php echo $errors['not_found'] ?? ''; ?></span>
            </form>
            <!-- BACKEND FORM END -->

            <div style="text-align: center;">OR</div>

            <div class="signup-link">
                Don't have an account? <a href="./pages/register_user.php">Sign Up</a>
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