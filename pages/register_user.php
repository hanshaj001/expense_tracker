<?php
// Start session for register flow
session_start();

// If user is already logged in, redirect directly to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: user_dashboard.php");
    exit();
}

include("../includes/function.php");
include("../includes/db_conn.php");

$name     = '';
$password = '';
$email    = '';
$errors   = [];

// Handle sign up submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Safely collect and trim inputs
    $name     = trim($_POST['name'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    // 1. Validate full name (Multiple users can have the same name)
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (strlen($name) < 3) {
        $errors['name'] = "Name must be at least 3 characters long.";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $errors['name'] = "Name can contain only letters and spaces.";
    }

    // 2. Validate email address (Must be unique for authentication/login)
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please provide a valid email address.";
    } else {
        // Check if email is already registered in DB
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        if ($check_stmt) {
            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);
            $check_res = mysqli_stmt_get_result($check_stmt);
            if (mysqli_num_rows($check_res) > 0) {
                $errors['email'] = "An account with this email address already exists.";
            }
            mysqli_stmt_close($check_stmt);
        }
    }

    // 3. Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long.";
    }

    // If no validation errors, proceed to insert into database
    if (empty($errors)) {
        // Hash password securely with BCRYPT
        $enc_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert using prepared statement to prevent SQL injection
        $insert_sql  = "INSERT INTO users (name, password, email) VALUES (?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);

        if ($insert_stmt) {
            mysqli_stmt_bind_param($insert_stmt, "sss", $name, $enc_password, $email);

            try {
                if (mysqli_stmt_execute($insert_stmt)) {
                    mysqli_stmt_close($insert_stmt);
                    mysqli_close($conn);

                    // Redirect to login page upon success
                    header("Location: ../index.php");
                    exit();
                } else {
                    $errors['register'] = "Error while creating your account. Please try again.";
                }
            } catch (mysqli_sql_exception $e) {
                // Handle duplicate email gracefully without fatal crash
                if ($e->getCode() === 1062) {
                    $errors['email'] = "An account with this email address already exists.";
                } else {
                    $errors['register'] = "Database error during registration. Please try again.";
                }
            }
            mysqli_stmt_close($insert_stmt);
        } else {
            $errors['register'] = "Database error. Please try again later.";
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
    <title>Sign Up - Expense Tracker</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Global CSS & Auth Specific Styling -->
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/index.css">
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
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Join Us Today</h1>
                <p class="subtitle">Start managing your personal finances, track every penny, and stay within your weekly budgets effortlessly.</p>
            </div>
        </div>

        <!-- Registration Form Section -->
        <div class="login-section">
            <h2>Create Account</h2>
            <p class="description">Fill in the details below to create your free account.</p>

            <form action="" method="post">
                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            placeholder="John Doe" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($name); ?>" 
                            required 
                            minlength="3"
                        >
                    </div>
                    <?php if (!empty($errors['name'])): ?>
                        <span class="error-msg"><?php echo htmlspecialchars($errors['name']); ?></span>
                    <?php endif; ?>
                </div>

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
                    <?php if (!empty($errors['email'])): ?>
                        <span class="error-msg"><?php echo htmlspecialchars($errors['email']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper password-wrapper">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="At least 6 characters" 
                            class="form-control" 
                            required 
                            minlength="6"
                        >
                        <button type="button" class="show-password" onclick="togglePassword()">SHOW</button>
                    </div>
                    <?php if (!empty($errors['password'])): ?>
                        <span class="error-msg"><?php echo htmlspecialchars($errors['password']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- General Register Error -->
                <?php if (!empty($errors['register'])): ?>
                    <span class="error-msg" style="text-align: center; margin-bottom: 10px;"><?php echo htmlspecialchars($errors['register']); ?></span>
                <?php endif; ?>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient" style="margin-top: 10px;">
                    <i class="fas fa-user-check"></i> Register Account
                </button>
            </form>

            <!-- Navigation to Login -->
            <div class="signup-link">
                Already have an account? <a href="../index.php">Log In</a>
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
