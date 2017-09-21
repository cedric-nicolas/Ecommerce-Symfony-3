$('document').ready(function(){
    $(".cp").keyup(function () {
        if($(this).val().length === 5){
            $.ajax({
                type: 'GET',
                // Route dynamique JS
                url: Routing.generate('villes', {cp: $(this).val()} ),
                //url: 'http://localhost:8888/EcommerceSymfony/web/app_dev.php/villes/' + $(this).val(),
                beforeSend: function () {
                    if($('.loading').length == 0) {
                        $('form .ville').parent().append('<div class="loading"></div>')
                    }
                     $('.ville option').remove();

                },
                success: function(data) {
                    //$(".ville").val(data.ville);
                    $.each(data.ville , function (index, value) {
                        $('.ville').append($('<option>', {value: value, text: value}));
                    });
                    $('.loading').remove();
                },

            });
        } else {
            $('.ville').val('');
        }
    })
});