$( document ).ready(function() {
    $("#token").on('input', function(){
        $('#error').addClass('hidden');
        var token = $("#token").val();
        if(token.length < 32) {
            $('#submit').attr("disabled", true);
        } else {
            $('#submit').attr("disabled", false);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const response = this.responseText;
                    if(response === 'false') {
                        console.log('not found');
                        $('#error').removeClass('hidden');
                    } else {
                        console.log('found')
                        $('#form-data').attr("action", 'product.php?token=' + token);
                    }
                }
            };
            xmlhttp.open("GET", "https://dev.arinnovations.io/api/product/index.php?token=" + token, true);
            xmlhttp.send();

        }
    });
});