<?php
include("../includes/header.php");
include("../includes/function.php");
include("../includes/db_conn.php");

session_start();
/// taking inputs 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // collecting data safely and trimming whitespace
    $user_name = trim($_POST['user_name']);
    $user_password = trim($_POST['user_password']);
    
    // SQL query with placeholder
    $sql = "SELECT * FROM reg_user WHERE user_name = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // binds the value to the placeholder
    mysqli_stmt_bind_param($stmt, "s", $user_name);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // checking if there is only one row
    if ( mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);
        $db_user_password = $row['user_password'];
        $db_user_name = $row['user_name'];
        $db_user_img = $row['user_img'];
        
        // comparing password
        if (password_verify($user_password, $db_user_password)) {

            // creating a variable to store in session
            $_SESSION['user_name'] = $db_user_name;
            $_SESSION['user_img'] = $db_user_img;
            $_SESSION['is_login'] = true;


            my_alert("success", "Login Successfully");
            header("Location: user_dashboard.php");
            exit();
        } else {
            my_alert("danger", "Invalid username or password");
        }

    } else {
        my_alert("danger", "Username not found");
    }

    // proper close inside POST block
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!-- HTML form -->


</div>
<div class="container">
    <div class="card register-card">
        <div class="card-header bg-primary text-center text-white">
            Login
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
                            <button type="submit" class="btn btn-primary w-100">Login</button>
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
