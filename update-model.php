<?php
    // mysql connection
    include 'dashboard/application/config.php';
    ini_set('display_errors', 1);
    $conn = new mysqli(
        "$db_host",
        "$db_user",
        "$db_password",
        $db_name
    );
    // Check connection
    if ($conn->connect_errno) {
        die("Database connection failed: " . $conn->connect_error);
    } else {
    // update product
        $token = $_POST['token'];
        $intensity = $_POST['intensity'];
        $softness = $_POST['softness'];
        if(isset($_POST['hotspot'])) {
            $annotation = $_POST['annotation'];
            $hotspot = $_POST['hotspot'];
        } else {
            $annotation = "";
            $hotspot = "";
        }
        $hotspot_color = $_POST['hotspot_color'];
        $field = $_POST['fieldOfView'];
        $exposure = $_POST['exposure'];
        $env = $_POST['environment'];
        $sky = $_POST['skybox'];
        $ar_button = $_POST['arCustomImage'];
        $orbit = $_POST['orbit'];
        $target = $_POST['target'];
        $auto = $_POST['auto_rotate'];
        $delay = $_POST['delay'];
        $bgColor = $_POST['bgColor'];

        $results = $conn->query("
            UPDATE sys_items 
            SET shadow_intensity = '".$intensity."',
            shadow_softness = '".$softness."',
            hotspot = '".$hotspot."',
            annotations = '".$annotation."',
            hotspot_color = '".$hotspot_color."',
            field_of_view = '".$field."',
            exposure = '".$exposure."',
            environment_image = '".$env."',
            skybox_image = '".$sky."',
            ar_button_image = '".$ar_button."',
            camera_orbit = '".$orbit."',
            camera_target = '".$target."',
            auto_rotate = '".$auto."',
            auto_rotate_delay = '".$delay."',
            background_color = '".$bgColor."'
            WHERE token_number = '".$token."'"
        );
        if($results == 1) {
            echo 'done';
        };
        $conn->close();

    }
?>