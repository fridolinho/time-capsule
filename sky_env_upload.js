$( document ).ready(function() {
    $('#file_sky_env').on('change', function () {
        // upload image to use as skybox or environment image
        $('#myform').submit();
    })

    $('.deleteImage').on('click', function() {
        const filename = $(this).closest('p').text();

        var fd = new FormData();
        fd.append('name', filename);
        $.ajax({
            url:"/delete_file.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            type: "post",
            data: fd,
            success: function (response) {
                if(response == 'done') {
                    location.reload();
                };
            }
        })
    })

    $('#myform').on('submit', function (e) {
        e.preventDefault();
        const data = $('#file_sky_env').prop('files')[0];
        var fd = new FormData();
        fd.append('file', data)

        $.ajax({
            url:"/upload_file.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            type: "post",
            data: fd,
            success: function (response) {
                location.reload();
                // console.log('data submitted', response);
                // $('#manageImages').append("<span>" + response + "<i class='fa fa-trash trash'>"+"</i>"+"</span>");
                // $('#select_image').append("<option value='dashboard/storage/items/skybox_env'>" + "dashboard/storage/items/skybox_env/" + response + "</option>");
            }
        })
    })
});