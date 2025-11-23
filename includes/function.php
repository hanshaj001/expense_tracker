<?php

//    <!-- here a variable created and put that as a color -->
function my_alert($color, $msg)
{
?>
    <div style="position: absolute; top:20px; right: 20px;" class="alert alert-<?php echo $color; ?> alert-dismissible fade show" role="alert">

        <!-- printing the message of success or failure while submitting -->
        <?php echo "$msg"; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

    <?php
}
    ?>