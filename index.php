<?php
// Start session for login management
session_start();

// If user is already logged in, redirect straight to user dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ./pages/user_dashboard.php");
    exit();
}

include("./includes/function.php");
include("./includes/db_conn.php");

$errors = [];
$email = '';
$password = '';

// Handle login submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Safely collect and trim inputs
    $email = trim($_POST['email'] ?? '');
    $user_password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($user_password)) {
        $errors['invalid'] = "Please enter both email and password.";
    } else {
        // Query user using prepared statement to prevent SQL injection
        $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                // Verify hashed password
                if (password_verify($user_password, $row['password'])) {
                    // Set session variables upon successful authentication
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['is_login'] = true;

                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);

                    header("Location: ./pages/user_dashboard.php");
                    exit();
                } else {
                    $errors['invalid'] = "Invalid email or password.";
                }
            } else {
                $errors['invalid'] = "No account found with this email.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $errors['invalid'] = "Database error. Please try again later.";
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Expense Tracker</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Global CSS & Auth Specific Styling -->
    <link rel="stylesheet" href="./assets/global.css">
    <link rel="stylesheet" href="./assets/index.css">
</head>
<body class="auth-page">

    <!-- Ambient background decorative blur circles -->
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>

    <div class="auth-container animate-fade-in">

        <!-- Welcome Banner Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <div class="brand-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h1>Expense Tracker</h1>
                <p class="subtitle">Take full control of your income, expenses, and weekly budgets in one place.</p>
            </div>
        </div>

        <!-- Login Form Section -->
        <div class="login-section">
            <h2>Welcome Back</h2>
            <p class="description">Enter your credentials to access your financial dashboard.</p>

            <form id="loginForm" method="POST" action="">
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="name@example.com" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($email); ?>" 
                            required
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper password-wrapper">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password" 
                            class="form-control" 
                            required
                        >
                        <button type="button" class="show-password" onclick="togglePassword()">SHOW</button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient" style="margin-top: 10px;">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>

                <!-- Error Messages -->
                <?php if (!empty($errors['invalid'])): ?>
                    <span class="error-msg" style="text-align: center; margin-top: 12px;">
                        <?php echo htmlspecialchars($errors['invalid']); ?>
                    </span>
                <?php endif; ?>
            </form>

            <!-- Navigation to Sign Up -->
            <div class="signup-link">
                Don't have an account? <a href="./pages/register_user.php">Create Account</a>
            </div>
        </div>

    </div>

    <!-- Password visibility toggle script -->
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