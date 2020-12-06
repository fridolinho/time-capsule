<?php
    $skybox_env = glob("dashboard/storage/skybox_env_images/*.*");
    $token = $_GET['token'];
    $user= $_GET['usertype'];
    $response = file_get_contents('https://dev.arinnovations.io/api/product/index.php?token=' . $token);
    if($response !== 'false') {
        $p = json_decode($response);
        $images = $p->image;
        $alt = $p->alt ?: $p->name;
        $exposure = $p->exposure;
        $intensity = $p->shadow_intensity;
        $softness = $p->shadow_softness;
        $hotspot = $p->hotspot;
        $hotspotPosition = explode("/", "$hotspot");
        $annotation = $p->annotations;
        $field = $p->field_of_view;
        $env = $p->environment_image;
        $skybox =$p->skybox_image;
        $target_og = $p->camera_target;
        $ar_scale = $p->ar_scale;
        $orbit_og = $p->camera_orbit;
        $minOrbit_og = $p->min_orbit;
        $maxOrbit_og = $p->max_orbit;
        $delay = $p->auto_rotate_delay;
        $auto = $p->auto_rotate;
        $bgColor = $p->background_color;
        if($bgColor === "") {
            $bgColor = "lightsteelblue";
        }
        $hotspot_color = $p->hotspot_color;
       
        $customARButton = $p->ar_button_image;
        $customARButton = ($p->ar_button_image == "") ? "./dashboard/storage/ar_images/ARI_Button.png" : "./dashboard/storage/ar_images/" . $p->ar_button_image;
   
        $images = explode("/", $images);
        $ios_image = "./dashboard/storage/items/" . $images[1];
        $src = "./dashboard/storage/items/" . $images[0];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <meta
        name="description"
        content="product preview"
    />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <title>Product view</title>
</head>
<body style="background-color: <?php echo $bgColor; ?>">
    <model-viewer
        id="model" src="<?php echo $src ?>" alt="<?php echo $alt; ?>"
        style="width: 100%; height: 100vh; border: none;"
        <?php
        if($auto !== 0) {
            echo 'auto-rotate';
        }
        ?>
        auto-rotate camera-controls
        auto-rotate-delay="<?php echo $delay; ?>"
        autoplay
        ar
        ar-mode="webxr scene-viewer quick-look"
        ar-scale="<?php echo $ar_scale; ?>"
        ios-src="<?php echo $ios_image ?>"
        camera-controls
        quick-look-browsers="safari chrome firefox"
        shadow-intensity="<?php echo $intensity; ?>"
        shadow-softness="<?php echo $softness; ?>"
        exposure="<?php echo $exposure; ?>"
        camera-orbit="<?php echo $orbit_og; ?>"
        min-camera-orbit="<?php echo $minOrbit_og; ?>"
        max-camera-orbit="<?php echo $maxOrbit_og; ?>"
        camera-target="<?php echo $target_og; ?>"
        field-of-view="<?php echo $field; ?>"
        environment-image="<?php echo $env; ?>"
        skybox-image="<?php echo $skybox; ?>"
    >
        <input 
            type="image" 
            id="ar-button" 
            style="width: 50%"
            src="<?php echo $customARButton; ?>" 
            slot="ar-button" 
        />
        <?php
        if($hotspot !== "") {
            ?>
            <button
                id="hotspot"
                class="hotspot"
                slot="hotspot-foot"
                data-position="<?php echo $hotspotPosition[0]; ?>"
                data-normal="<?php echo $hotspotPosition[1] ?>"
                data-visibility-attribute="visible"
                style="background-color: <?php echo $hotspot_color; ?>"
            >
            <?php if ($annotation !== "") {
            ?>
            <div id="annotation" style="background-color: grey">
                <?php echo $annotation; ?>
            </div>
            <?php
            } ?>             
            </button>
            <?php
        }
        ?>
    </model-viewer>
    <script>
    
    </script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script nomodule src="https://unpkg.com/@google/model-viewer/dist/model-viewer-legacy.js"></script>
</body>
</html>

