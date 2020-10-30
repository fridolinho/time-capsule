<?php
    $name = trim($_POST["name"]);
    $path = 'dashboard/storage/items/skybox_env/'.$name;
    if( file_exists($path) ) {
        unlink($path);
        echo 'done';
    } else {
        echo 'not deleted';
    }
?>
