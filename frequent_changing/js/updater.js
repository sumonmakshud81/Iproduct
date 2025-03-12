$(document).ready(function(){
    "use strict";
    $('#ci_update_now.prevent_default').on('click', function(e){
        if($(this).hasClass('prevent_default')){
            e.preventDefault();
            let url = $(this).attr('href');
            let btnUpgrade = $(this);
            if(btnUpgrade.html() == 'UPDATE NOW'){
                btnUpgrade.html('downloading...')
            }else{
                btnUpgrade.html('installing...')
            }

            $.ajax({
                type: "GET",
                url: url,
                success: function(response){
                    response = JSON.parse(response);
                    $('#ci_message').html(response.message).effect('bounce');
                    btnUpgrade.attr('href', response.action)
                    btnUpgrade.html(response.caption)

                    if((response.status != 'success') || (response.caption == "Login Now")){
                        btnUpgrade.removeClass('prevent_default');
                    }
                },
                error: function(response, v){
                    $('#ci_message').addClass('error');
                    $('#ci_message').html(response.statusText).effect('bounce');
                }
            });
        }
    });
});