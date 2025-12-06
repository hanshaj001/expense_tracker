<?php
// Including header file (usually contains HTML head, CSS links, navbar, etc.)
include("./includes/header.php");

// Including custom functions file (you can define reusable functions here)
include("./includes/function.php");

// Including database connection file
include("./includes/db_conn.php");

$userId = null;

if (isset($_REQUEST['user_id'])) {
    $userId = $_REQUEST['user_id'];
}
// Checking if the form is submitted using the "submit" button
if (isset($_REQUEST['submit'])) {

    // Accessing the uploaded file from the global $_FILES array
    // $_FILES is an associative array that stores information about uploaded files
    // Example: $_FILES['user_pic'] gives details like name, type, tmp_name, error, and size
    $user_img = $_FILES['user_img'];

    $img_name = $user_img['name'];
    $img_temp_name = $user_img['tmp_name'];

    // break name into two parts .
    // name = ['img','jpg']
    $img_extension = explode(".", $img_name);

    //    print_r($img_extension);
    $modify_name = round(microtime(true));


    //    echo $img_new_name . "." . end($img_extension) ;

    $img_path = "images/user_image/" . $modify_name . "." . end($img_extension);

    $img_new_name = $modify_name . "." . end($img_extension);

    $img_upload_result = move_uploaded_file($img_temp_name, $img_path);


    //
    if ($img_upload_result) {
        $run_image_query = "UPDATE reg_user  SET user_img = '$img_new_name' WHERE user_id = $userId";

        $img_update_result = mysqli_query($conn, $run_image_query);

        // checking if update
        if ($img_upload_result) {
            echo "Image update successfully ";
            header("Location: display_reg_user.php");
        } else {
            echo "Something Error";
        }
    } else {
        echo "Something went Wrong";
    }
}
?>

<!-- Main HTML structure for the image upload form -->
<div class="container">
    <div class="row py-5">
        <!-- Page Title -->
        <h1 class="text-center py-3">Upload Image</h1>

        <div>
            <!-- Form to upload image -->
            <!-- 'enctype="multipart/form-data"' is required for file upload -->
            <!-- 'method="POST"' sends the data securely -->
            <form method="POST" enctype="multipart/form-data">
                <div class="col-md-6 mb-3 mx-auto">
                    <!-- File input field where user selects an image -->
                    <input type="file" name="user_img" class="form-control" id="inputGroupFile02" required>
                </div>

                <div class="col-md-6 mb-3 mx-auto">
                    <!-- Submit button to send the form -->
                    <button type="submit" class="btn btn-primary" name="submit">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Including footer file (usually contains closing HTML tags or footer section)
include("./includes/footer.php");
?>