<?php
include("../includes/function.php");
include("../includes/db_conn.php");

session_start();

$errors = [];
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


           $errors['success'] = "Login Successfull";
            header("Location: user_dashboard.php");
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
