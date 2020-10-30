<?php
    $name = $_FILES["file"]["name"];
    $tmp_name = $_FILES["file"]["tmp_name"];
    $uploads_dir = "dashboard/storage/items/skybox_env/" . $name;
    if(move_uploaded_file($tmp_name, $uploads_dir)) {
        echo $name;
    } else {
        echo 'not uploaded';
    };
?>