const modelViewerParameters = document.querySelector("model-viewer#model");

modelViewerParameters.addEventListener("scene-graph-ready", (ev) => {
    let material = modelViewerParameters.model.materials[0];
    let metalnessDisplay = document.querySelector("#metalness-value");
    let roughnessDisplay = document.querySelector("#roughness-value");

    metalnessDisplay.textContent = material.pbrMetallicRoughness.metallicFactor;
    roughnessDisplay.textContent = material.pbrMetallicRoughness.roughnessFactor;

    document.querySelector('#metalness').addEventListener('input', (event) => {
        // Defaults to gold
        material.pbrMetallicRoughness.setBaseColorFactor([0.0000, 0.0000, 0.0000]);
        material.pbrMetallicRoughness.setMetallicFactor(event.target.value);
        metalnessDisplay.textContent = event.target.value;
    });

    document.querySelector('#roughness').addEventListener('input', (event) => {
        material.pbrMetallicRoughness.setRoughnessFactor(event.target.value);
        roughnessDisplay.textContent = event.target.value;
    });
})