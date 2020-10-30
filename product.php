<?php
    $skybox_env = glob("dashboard/storage/items/skybox_env/*.*");
    $token = $_GET['token'];
    $response = file_get_contents('https://dev.arinnovations.io/api/product/index.php?token=' . $token);
    if($response !== 'false') {
        $p = json_decode($response);
        $images = $p->image;
        $exposure = $p->exposure;
        $intensity = $p->shadow_intensity;
        $softness = $p->shadow_softness;
        $hotspot = $p->hotspot;
        $hotspotPosition = explode("/", "$hotspot");
        $annotation = $p->annotations;
        $field = $p->field_of_view;
        $env = $p->environment_image;
        $skybox =$p->skybox_image;
        $target = $p->camera_target;
        $orbit = $p->camera_orbit;
        $orbit = explode(" ", $orbit);
        if($target === 'auto') {
            $target = explode(" ", $target);
        }
        $delay = $p->auto_rotate_delay;
        $auto = $p->auto_rotate;
        $bgColor = $p->background_color;
        if($bgColor === "") {
            $backGround = "lightsteelblue";
        }
//        else {
//            $sRegex     = '/rgba?(\s?([0-9]{1,3}),\s?([0-9]{1,3}),\s?([0-9]{1,3})/i';
//
//            preg_match($sRegex, $bgColor, $matches);
//            if(count($matches) != 4){
//                die('The color count does not match.');
//            }
//            $iRed   = (int) $matches[1];
//            $iGreen = (int) $matches[2];
//            $iBlue  = (int) $matches[3];
//
//            $backGround = '#' . dechex($iRed) . dechex($iGreen) . dechex($iBlue);
//        }
        $customARButton = $p->ar_button_image;
        if($customARButton === "") {
            $customARButton = "./assets/img/AR Button ARI8.png";
        }
        $images = explode("/", $images);
        $ios_image = "dashboard/storage/items/" . $images[1];
        $src = "dashboard/storage/items/" . $images[0];
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
    <script src="attributes.js"></script>
    <script src="sky_env_upload.js"></script>
    <title>Product view</title>
</head>
<body style="background-color: <?php echo $backGround; ?>">
<div id="interactions" class="interactions">
    <h3>Model Viewer interactions</h3>
    <span id="arrow" class="arrow_down">&#8595;</span>
    <div id="controls">
        <input type="hidden" id="token_number" value="<?php echo $token ?>">
        <button id="add-hotspot">Add hotspot</button>
        <button id="remove-hotspot">Remove hotspot</button>
        <p id="add-annotation" class="hidden">Annotation : <input type="text" id="annotation-input"></p>
        <p>Metalness : <span id="metalness-value">1</span></p>
        <input id="metalness" type="range" min="0" max="1" step="0.01" value="1">
        <p>Roughness : <span id="roughness-value"> 0 </span></p>
        <input id="roughness" type="range" min="0" max="1" step="0.01" value="0">
        <p>Exposure : <span id="exposure_value"><?php echo $exposure; ?></span></p>
        <input id="exposure" type="range" min="0" max="2" step="0.01" value="<?php echo $exposure; ?>">
        <p>Shadow-intensity : <span id="shadow_value"><?php echo $intensity; ?></span></p>
        <input id="shadow" type="range" min="0" max="1" step="0.1" value="<?php echo $intensity; ?>">
        <p>Shadow-softness : <span id="softness_value"><?php echo $softness; ?></span></p>
        <input id="softness" type="range" min="0" max="1" step="0.1" value="<?php echo $softness; ?>">
        <p>Auto rotate <input id="auto_rotate" type="checkbox" checked /> </p>
        <p>Auto rotate delay: <input id="delay" type="number" value="<?php echo $delay; ?>" min="1" max="5"></p>
        <p>BG color: <input id="bg-color" type="text" placeholder="#ffffff" value="<?php echo $backGround; ?>"></p>
        <input type="checkbox" id="skybox" class="checkbox"/>
        <label>Skybox Images</label>
        <br />
        <input type="checkbox" id="environment" class="checkbox" />
        <label>Environment Images</label>
        <br />
        <input type="checkbox" id="custom_ar_button" class="checkbox" />
        <input type="hidden" id="ar_button_image" value="">
        <label>Custom AR Button Image</label>
        <br />
        <input type="checkbox" id="manage" />
        <label>Manage Images</label>
        <br>
        <select id="select_image" class="select_image">
            <option value="">Select Image</option>
            <?php
            for ($x = 0; $x < count($skybox_env); $x++) {
                ?>
                <option> <?php echo $skybox_env[$x]; ?></option>
                <?php
            }
            ?>
        </select>
        <br />
        <div id="manageImages" class="manageImages">
            <form method="post" enctype="multipart/form-data" id="myform">
                <input type="file" name="skybox_env" id="file_sky_env">
                <input type="submit" class="hidden">
            </form>
            <?php
            for ($x = 0; $x < count($skybox_env); $x++) {
                ?>
                <p> <?php echo str_replace("dashboard/storage/items/skybox_env/", "", $skybox_env[$x]); ?>
                    <span class="deleteImage">
                            <i
                                    class="fa fa-trash trash"
                            ></i>
                            </span>
                </p>
                <?php
            }
            ?>
        </div>

        <p>Camera orbit</p>
        <div>
            <span class="camera-axis">X: <input id="orbit-x" type="number" min="0" max="360" step="1" value="<?php echo str_replace("deg", "", $orbit[0]); ?>"></span>
            <span class="camera-axis">Y: <input id="orbit-y" type="number" min="20" max="180" step="1" value="<?php echo str_replace("deg", "", $orbit[1]); ?>"></span>
            <span class="camera-axis">Z: <input id="orbit-z" type="number" min="40" max="100" step="1" value="<?php echo str_replace("%", "", $orbit[2]); ?>"></span>
        </div>
        <p>Camera target</p>
        <div>
            <span class="camera-axis">X: <input id="target-x" type="number" min="0" max="3" step="0.1" value="<?php echo str_replace("m", "", $target[0]); ?>"></span>
            <span class="camera-axis">Y: <input id="target-y" type="number" min="0" max="5" step="0.1" value="<?php echo str_replace("m", "", $target[1]); ?>"></span>
            <span class="camera-axis">Z: <input id="target-z" type="number" min="-0.5" max="1.5" step="0.1" value="<?php echo str_replace("m", "", $target[2]); ?>"></span>
        </div>

        <p>Field of view: <span id="field_of_view"><?php echo str_replace("deg", "", $field); ?></span></p>
        <input id="field_value" type="range" min="10" max="45" step="5" value="<?php echo str_replace("deg", "", $field); ?>">
        <div>
            <div id="notification"></div>
            <button id="save" class="save_attributes">Save</button>
        </div>
    </div>
</div>
    <model-viewer
        id="model" class="hidden" src="<?php echo $src ?>" alt="A 3D model"
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
        ar-scale="fixed"
        ios-src="<?php echo $ios_image ?>"
        camera-controls
        quick-look-browsers="safari chrome firefox"
        shadow-intensity="<?php echo $intensity; ?>"
        shadow-softness="<?php echo $softness; ?>"
        exposure="<?php echo $exposure; ?>"
        camera-orbit="<?php echo $orbit; ?>"
        camera-target="auto"
        field-of-view="<?php echo $field; ?>"
        environment-image="<?php echo $env; ?>"
        skybox-image="<?php echo $skybox; ?>"
    >
        <input type="image" src="<?php echo $customARButton; ?>" slot="ar-button" id="ar-button" style="width: 50%"/>
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
            >
                <div id="annotation" style="background-color: grey"><?php echo $annotation; ?></div>
            </button>
            <?php
        }
        ?>
    </model-viewer>
    <script src="graph-scene.js"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script nomodule src="https://unpkg.com/@google/model-viewer/dist/model-viewer-legacy.js"></script>
</body>
</html>

