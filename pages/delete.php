<?php
    include "../includes/db_conn.php";
    if(isset($_GET['id'])){
        $transaction_id = $_GET['id'];

        if(mysqli_query($conn,"DELETE from transactions where id = $transaction_id;")){
            $_POST['delete_transaction'] = "Deleted Transaction Successfully";
            header("Location: transaction.php");
        }
    }
?>