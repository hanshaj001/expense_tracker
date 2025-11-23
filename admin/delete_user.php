<?php
include("./includes/header.php");
include("./includes/function.php");
include("./includes/db_conn.php");
?>

<?php 
 if(isset($_REQUEST['id'])){
 $delete_id = $_REQUEST['id'];

  $run_delete_query = "DELETE FROM reg_user where id = $delete_id";
  $dlt_query_res = mysqli_query($conn,$run_delete_query);

  if($dlt_query_res){
    my_alert("success","Deleted SucccessFully");
    header("Location: ./display_reg_user.php");

}
else 
    my_alert("danger","Fail to Delte");
 }

  mysqli_close($conn);
?>






<?php
include("./includes/footer.php");
?>