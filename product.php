<?php
    $skybox_env = glob("dashboard/storage/items/skybox_env/*.*");
    $token = $_GET['token'];
    $response = file_get_contents('https://dev.arinnovations.io/api/product/index.php?token=' . $token);
    if($response !== 'false') {
        $product_data = json_decode($response);
        $images = $product_data->image;
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
<body>
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
        <p>Exposure : <span id="exposure_value">1</span></p>
        <input id="exposure" type="range" min="0" max="2" step="0.01" value="1">
        <p>Shadow-intensity : <span id="shadow_value">0</span></p>
        <input id="shadow" type="range" min="0" max="1" step="0.1" value="0">
        <p>Shadow-softness : <span id="softness_value">1</span></p>
        <input id="softness" type="range" min="0" max="1" step="0.1" value="1">
        <p>Auto rotate <input id="auto_rotate" type="checkbox" checked /> </p>
        <p>Auto rotate delay: <input id="delay" type="number" value="3" min="1" max="5"></p>
        <p>BG color: <input id="bg-color" type="text" placeholder="#ffffff"></p>
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
            <span class="camera-axis">X: <input id="orbit-x" type="number" min="0" max="360" step="1" value="0"></span>
            <span class="camera-axis">Y: <input id="orbit-y" type="number" min="20" max="180" step="1" value="75"></span>
            <span class="camera-axis">Z: <input id="orbit-z" type="number" min="40" max="100" step="1" value="90"></span>
        </div>
        <p>Camera target</p>
        <div>
            <span class="camera-axis">X: <input id="target-x" type="number" min="0" max="3" step="0.1" value="0"></span>
            <span class="camera-axis">Y: <input id="target-y" type="number" min="0" max="5" step="0.1" value="0"></span>
            <span class="camera-axis">Z: <input id="target-z" type="number" min="-0.5" max="1.5" step="0.1" value="4"></span>
        </div>

        <p>Field of view: <span id="field_of_view">10</span></p>
        <input id="field_value" type="range" min="10" max="45" step="5" value="45">
        <div>
            <span id="notification"></span>
            <button id="save" class="save_attributes">Save attributes as default</button>
        </div>
    </div>
</div>
    <model-viewer
        id="model" class="hidden" src="<?php echo $src ?>" alt="A 3D model"
        style="width: 100%; height: 100vh; border: none;"
        auto-rotate camera-controls
        auto-rotate-delay="3000"
        autoplay
        ar
        ar-mode="webxr scene-viewer quick-look"
        ar-scale="fixed"
        ios-src="<?php echo $ios_image ?>"
        camera-controls
        quick-look-browsers="safari chrome firefox"
        shadow-intensity="0"
        shadow-softness="1"
        exposure="1"
        camera-orbit="0deg 75deg 90%"
        camera-target="auto"
        field-of-view="45deg"
        environment-image=""
        skybox-image=""
    >
        <input type="image" src="./assets/img/AR Button ARI8.png" slot="ar-button" id="ar-button" style="width: 50%"/>
    </model-viewer>
    <script src="graph-scene.js"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script nomodule src="https://unpkg.com/@google/model-viewer/dist/model-viewer-legacy.js"></script>
</body>
</html>

