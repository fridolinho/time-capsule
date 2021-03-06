// manipulate model attributes
$( document ).ready(function() {

    // disable right-click
    $(document).bind("contextmenu", function(e) {
        return false;
    });


    // load hotspot if any
    const hotspot = $('#hotspot');
    if(hotspot.length) {
        const color = $('#hotspot_color').find('option:selected').text();
        $('#add-hotspot').hide();
        hotspot.css('background-color', color);
    }

    // min and max camera orbit

    $('#min-orbit-x').on('input change', function () {
        const value = $(this).val() || -Infinity;
        let orbit_data = $('#model').attr('min-camera-orbit');
        orbit_data = orbit_data.split(' ');
        if(value !== -Infinity) {
            $('#model').attr('min-camera-orbit', + value + 'deg' + ' ' + orbit_data[1] + ' ' + orbit_data[2]);
            $('#orbit-x').attr('min', value);
        } else {
            $('#model').attr('min-camera-orbit', + value + ' ' + orbit_data[1] + ' ' + orbit_data[2]);
        }
    })


    $('#min-orbit-y').on('input change', function () {
        const value = $(this).val() || 22.5;
        let orbit_data = $('#model').attr('min-camera-orbit');
        orbit_data = orbit_data.split(' ');
        $('#model').attr('min-camera-orbit', orbit_data[0] + ' ' + value + 'deg' + ' ' + orbit_data[2]);
        $('#orbit-y').attr('min', value);
    })

    $('#min-orbit-z').on('input change', function () {
        const value = $(this).val() || "auto";
        let orbit_data = $('#model').attr('min-camera-orbit');
        orbit_data = orbit_data.split(' ');
        if(value !== "auto") {
            $('#model').attr('min-camera-orbit', orbit_data[0] + ' ' + orbit_data[1] + ' ' + value + '%');
            $('#orbit-z').attr('min', value);
        } else {
            $('#model').attr('min-camera-orbit', orbit_data[0] + ' ' + orbit_data[1] + ' ' + value);
        }
    })

    $('#max-orbit-x').on('input change', function () {
        const value = $(this).val() || Infinity;
        let orbit_data = $('#model').attr('max-camera-orbit');
        orbit_data = orbit_data.split(' ');
        if(value !== Infinity) {
            $('#model').attr('max-camera-orbit', + value + 'deg' + ' ' + orbit_data[1] + ' ' + orbit_data[2]);
            $('#orbit-x').attr('max', value);
        } else {
            $('#model').attr('max-camera-orbit', + value + ' ' + orbit_data[1] + ' ' + orbit_data[2]);
        }
    })


    $('#max-orbit-y').on('input change', function () {
        const value = $(this).val() || 157.5;
        let orbit_data = $('#model').attr('max-camera-orbit');
        orbit_data = orbit_data.split(' ');
        $('#model').attr('max-camera-orbit', orbit_data[0] + ' ' + value + 'deg' + ' ' + orbit_data[2]);
        $('#orbit-y').attr('max', value);
    })

    $('#max-orbit-z').on('input change', function () {
        const value = $(this).val() || "auto";
        let orbit_data = $('#model').attr('max-camera-orbit');
        orbit_data = orbit_data.split(' ');
        if(value !== "auto") {
            $('#model').attr('max-camera-orbit', orbit_data[0] + ' ' + orbit_data[1] + ' ' + value + '%');
            $('#orbit-z').attr('max', value);
        } else {
            $('#model').attr('max-camera-orbit', orbit_data[0] + ' ' + orbit_data[1] + ' ' + value);
        }
    })

    // poster
    let posterUrl = '';
    const link = document.createElement('a');
    const downloadButton = document.getElementById('download');
    downloadButton.disabled = true;
    const orbitString = $('#cameraOrbit');
    $('#createPoster').on('click', async function(){
        const viewer = document.querySelector("model-viewer#model");
        const orbit = viewer.getCameraOrbit();
        orbitString.attr('value', `${orbit.theta}rad ${orbit.phi}rad auto`);
        viewer.fieldOfView = 'auto';
        viewer.jumpCameraToGoal();
        await new Promise(resolve => requestAnimationFrame(() => resolve()));
        URL.revokeObjectURL(posterUrl);
        const blob = await viewer.toBlob({ mimeType: 'image/png', idealAspect: true });
        posterUrl = URL.createObjectURL(blob);
        console.log(posterUrl);
        downloadButton.disabled = false;
    })

    $('#download').on('click', function() {
        link.href = posterUrl;
        link.download = 'poster.png';
        link.click();
        downloadButton.disabled = true;
    })
       



    // upload attributes details
    $('#save').on('click', function() {
        // get all attributes
        let attributes = {};
        let model = $('#model');
        attributes.token = $('#token_number').val();
        attributes.arCustomImage = $('#ar_button_image').val();
        // hotspot data
        let hotspot_data = $('#hotspot').attr('data-position') + '/' + $('#hotspot').attr('data-normal');
        let annotation_data = $('#annotation-input').val();
        if(!hotspot_data.includes("undefined")){
            attributes.hotspot = hotspot_data;
            attributes.annotation = annotation_data;
        }
        attributes.hotspot_color = $('#hotspot_color').find('option:selected').text();


        // exposure and shadow
        attributes.exposure = model.attr('exposure');
        attributes.intensity = model.attr('shadow-intensity');
        attributes.softness = model.attr('shadow-softness');

        // graph scene


        // auto-rotate
        let auto = model.attr('auto-rotate');
        if(auto !== undefined) {
            attributes.auto_rotate = true;
        } else {
            attributes.auto_rotate = false;
        }

        attributes.delay = model.attr('auto-rotate-delay');

        attributes.alt = $('#alt-text').val();
        attributes.ar_scale = $('#ar_scale_selector').find('option:selected').text();

        // background color
        attributes.bgColor = $('#hex_color').val();

        // skybox and environment images
        attributes.skybox = model.attr('skybox-image');
        attributes.environment = model.attr('environment-image');

        //camera
        attributes.orbit = model.attr('camera-orbit');
        attributes.minOrbit = model.attr('min-camera-orbit');
        attributes.maxOrbit = model.attr('max-camera-orbit');
        attributes.target = model.attr('camera-target');
        attributes.fieldOfView = model.attr('field-of-view');

        // update product details on the db
        let formData = new FormData();
        for ( let key in attributes ) {
            formData.append(key, attributes[key]);
        }

        $.ajax({
            url         : 'update-model.php',
            data        : formData,
            processData : false,
            contentType : false,
            type: 'POST'
        }).done(function(data){
            if(data === "done") {
                $('#notification').text('attributes saved');
                setTimeout(function(){
                    $('#notification').text('');
                }, 5000);

            }
        });
    })

    // arrow scroll content available
    $('#interactions').scroll(function() {
        if($('#interactions').scrollTop() + $('#interactions').height() == $(document).height()) {
            $('#arrow').show();
        } else {
            $('#arrow').hide();
        }
    });

    // model attributes

    if($('#model').attr('src').length > 0) {
        $('#model').removeClass('hidden');

        // hotspot

        $('#add-hotspot').on('click', function() {
            if($(this).val() === 'on') {
                $(this).attr('value', 'off');
            } else {
                $(this).attr('value', 'on');
            };
        })

        $('#remove-hotspot').on('click', function (){
            const ready = $('#hotspot-ready-remove').val();
            if(ready === "") {
                $('#hotspot-ready-remove').remove();
                $('#add-hotspot').show();
                $('#add-annotation').hide();
                $('#hotspot_color').hide();
            }
        })

        $("#model").on("click", function(e) {
            let el = $(this);
            const isAdd = $('#add-hotspot').val();
            if(isAdd === 'on'){
                const viewer =  document.querySelector("model-viewer#model");
                const rect = viewer.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const positionAndNormal = viewer.positionAndNormalFromPoint(x, y);
                const {position, normal} = positionAndNormal;
                const hotspot = document.createElement('button');
                hotspot.slot = 'hotspot-foot';
                hotspot.setAttribute('data-visibility-attribute', 'visible');
                hotspot.setAttribute('id', 'hotspot');
                hotspot.classList.add('hotspot');
                hotspot.dataset.position = position.toString();
                if (normal != null) {
                    hotspot.dataset.normal = normal.toString();
                }
                const annotation = document.createElement('div');
                annotation.setAttribute('id', 'annotation');
                hotspot.appendChild(annotation);
                viewer.appendChild(hotspot);
                $('#add-hotspot').attr('value', 'off');
                $('#add-hotspot').hide();
                $('#add-annotation').show()
                $('#hotspot_color').show();
            };

            $('.hotspot').on('click', function (){
                $('#hotspot-ready-remove').removeAttr('id');
                $(this).attr('id', 'hotspot-ready-remove');
            })

        });

        // add annotation

        $('#annotation-input').on('input change', function (){
            const annotationInput = $('#annotation-input').val();
            if(annotationInput.length > 0) {
                $('#annotation').html('<p>' + annotationInput + '</p>');
                $('#annotation').css('background-color', 'grey');
            }
        })

        // hotspot color change

        $('#hotspot_color_selector').on('change', function(){
            color = $(this).find('option:selected').text();
            $('#hotspot').css('background-color', color);
        })

        // shadow intensity
        $('#shadow').on('input change', function () {
            const shadow_val = $('#shadow').val();
            $('#shadow_value').html(shadow_val);
            $('#model').attr("shadow-intensity", shadow_val*5);
        })

        // softness
        $('#softness').on('input change', function () {
            const soft_val = $('#softness').val();
            $('#softness_value').html(soft_val);
            $('#model').attr("shadow-softness", soft_val*2);
        })

        // exposure
        $('#exposure').on('input change', function () {
            const exp_val = $('#exposure').val();
            $('#exposure_value').html(exp_val);
            $('#model').attr("exposure", exp_val);
        })

        // activate and disable auto rotate
        $('#auto_rotate').change(function() {
            const valrot = $('#auto_rotate').val();
            if(this.checked) {
                $('#model').attr('auto-rotate', '');
            } else {
                $('#model').removeAttr('auto-rotate');
            }
        });

        //auto rotate delay

        $('#delay').change(function() {
            const delay = $('#delay').val();
            $('#model').attr('auto-rotate-delay', delay*1000);
        });

        //background color change
        $('#bg-color').on('input', function() {
            const color = $(this).val();
            if(color.length === 7&&color.includes('#')) {
                $('#hex_color').attr('value', color);
                $('body').css('background-color', color);
            }
        })

        // camera orbit
        $('#orbit-x').change(function () {
            const value = $('#orbit-x').val();
            let orbit_data = $('#model').attr('camera-orbit');
            orbit_data = orbit_data.split(' ');
            $('#model').attr('camera-orbit', + value + 'deg' + ' ' + orbit_data[1] + ' ' + orbit_data[2]);
        })


        $('#orbit-y').change(function () {
            const value = $('#orbit-y').val();
            let orbit_data = $('#model').attr('camera-orbit');
            orbit_data = orbit_data.split(' ');
            $('#model').attr('camera-orbit', orbit_data[0] + ' ' + value + 'deg' + ' ' + orbit_data[2]);
        })

        $('#orbit-z').change(function () {
            const value = $('#orbit-z').val();
            let orbit_data = $('#model').attr('camera-orbit');
            orbit_data = orbit_data.split(' ');
            $('#model').attr('camera-orbit', orbit_data[0] + ' ' + orbit_data[1] + ' ' + value + '%');
        })

        // camera target
        $('#target-x').change(function () {
            const value = $('#target-x').val();
            let target_data = $('#model').attr('camera-target');
            target_data = target_data.split(' ');
            $('#model').attr('camera-target', + value + 'm' + ' ' + target_data[1] + ' ' + target_data[2]);
        })

        $('#target-y').change(function () {
            const value = $('#target-y').val();
            let target_data = $('#model').attr('camera-target');
            target_data = target_data.split(' ');
            $('#model').attr('camera-target', target_data[0] + ' ' + value + 'm' + ' ' + target_data[2]);
        })

        $('#target-z').change(function () {
            const value = $('#target-z').val();
            let target_data = $('#model').attr('camera-target');
            target_data = target_data.split(' ');
            $('#model').attr('camera-target', target_data[0] + ' ' + target_data[1] + ' ' + value + 'm');
        })

        // field of view
        $('#field_value').on('input change', function () {
            const field_val = $('#field_value').val();
            $('#field_of_view').html(field_val);
            $('#model').attr("field-of-view", field_val + 'deg');
        })

        $('.checkbox').on('change', function() {
            $('.checkbox').not(this).prop('checked', false);
            const id = $(this).attr('id');
            const checked = $(this).prop('checked');
            if(!checked) {
                $('#select_image').hide();
                if(id === "skybox") {
                    $('#model').attr("skybox-image", "");
                } else {
                    $('#model').attr("environment-image", "");
                };
            } else {
                $('#select_image').show();
            }
        });

        $('#select_image').on('change', function() {
            $img = $(this).children("option:selected").val();
            $('.checkbox').each(function() {
                let $var = $(this);
                if($var.is(":checked")) {
                    if($var.attr("id") === "skybox") {
                        $('#model').attr("skybox-image", $img);
                    } else if($var.attr("id") === "environment")  {
                        $('#model').attr("environment-image", $img);
                    } else {
                        $('#ar_button_image').attr('value', $img);
                    };
                }
            });
        })

        $('#manage').on('change', function() {
            if($(this).is(":checked")) {
                $('#manageImages').show();
            } else {
                $('#manageImages').hide()
            };
        })



    };
});