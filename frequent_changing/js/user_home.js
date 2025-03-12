jQuery(document).ready(function($) {
    "use strict";
    let base_url = $('#base_url').val();
    let hidden_alert = $(".hidden_alert").val();
    let hidden_ok = $(".hidden_ok").val();
    let hidden_cancel = $(".hidden_cancel").val();
    let thischaracterisnotallowed = $(".thischaracterisnotallowed").val();
    let are_you_sure = $(".are_you_sure").val();
    /**
     * @description This function is used to validate the email
     * @param {*} email 
     * @returns 
     */
    function validateEmail(email) {
        let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    $('body').on('click', '.delete', function (e) {
        e.preventDefault();
        let form_class = $(this).attr('data-form_class');

        swal({
            title: hidden_alert+"!",
            text: are_you_sure,
            cancelButtonText:hidden_cancel,
            confirmButtonText:hidden_ok,
            confirmButtonColor: '#3c8dbc',
            showCancelButton: true
        }, function() {
            $("." + form_class).submit();
        });
    });

    $(document).on("click", ".user-info-box .user-avatar", function () {
        $(".user_profile_active").toggle();
    });
    $(document).on("click", function (e) {
        let menu = $(".user_profile_active");
        let toggleBtn = $(".user-info-box .user-avatar");
        if (
            !menu.is(e.target) &&
            !toggleBtn.is(e.target) &&
            menu.has(e.target).length === 0
        ) {
            menu.hide();
        }
    });

    $('body').on('click', '.delete_dummy_data', function (e) {
        e.preventDefault();
        let linkURL = this.href;
        warnBeforeRedirect1(linkURL);
    });
    /**
     * @description This function is used to show the warning before redirect
     * @param {*} linkURL 
     */
    function warnBeforeRedirect(linkURL) {
        swal({
            title: hidden_alert+"!",
            text: are_you_sure,
            cancelButtonText:hidden_cancel,
            confirmButtonText:hidden_ok,
            confirmButtonColor: '#3c8dbc',
            showCancelButton: true
        }, function() {
            window.location.href = linkURL;
        });
    }

    /**
     * @description This function is used to show the warning before redirect
     * @param {*} linkURL 
     */
    function warnBeforeRedirect1(linkURL) {
        swal({
            title: hidden_alert+"!",
            text: delete_msg_dummy_data,
            cancelButtonText:hidden_cancel,
            confirmButtonText:hidden_ok,
            confirmButtonColor: '#3c8dbc',
            showCancelButton: true
        }, function() {
            window.location.href = linkURL;
        });
    }

    $(document).on('keydown', '.integerchk', function(e){
        return (
            keys == 8 ||
            keys == 9 ||
            keys == 13 ||
            keys == 46 ||
            keys == 110 ||
            keys == 86 ||
            keys == 190 ||
            (keys >= 35 && keys <= 40) ||
            (keys >= 48 && keys <= 57) ||
            (keys >= 96 && keys <= 105));
    });
    $(document).on('keyup', '.integerchk', function(e){
        let input = $(this).val();
        let ponto = input.split('.').length;
        let slash = input.split('-').length;
        if (ponto > 2)
            $(this).val(input.substr(0,(input.length)-1));
        $(this).val(input.replace(/[^0-9]/,''));
        if(slash > 2)
            $(this).val(input.substr(0,(input.length)-1));
        if (ponto ==2)
            $(this).val(input.substr(0,(input.indexOf('.')+3)));
        if(input == '.')
            $(this).val("");

    });
    $(document).on('keyup', '.form-control', function(e){
        let input = $(this).val();
        let special_ignore = $(this).attr('data-special_ignore');
        let val = input.replace(/[!"#$%&'()*+\/;<=>?[\\\]^`{|}~]/g, "");
        if(special_ignore){
            $(this).val(val);
            let format = /[!"#$%&'()*+\/;<=>?[\\\]^`{|}~]/;
            if (format.test(input))
            {
                swal({
                    title: hidden_alert+"!",
                    text: thischaracterisnotallowed,
                    cancelButtonText:hidden_cancel,
                    confirmButtonText:hidden_ok,
                    confirmButtonColor: '#3c8dbc',
                });
            }
        }
    });
    $('table').addClass('table-responsive').removeClass('table-bordered');
    let window_height = $(window).height();
    let main_header_height = $('.main-header').height();
    let user_panel_height = $('.user-panel').height();
    let left_menu_height_should_be = (parseFloat(window_height)-(parseFloat(main_header_height)+parseFloat(user_panel_height))).toFixed(2);
    left_menu_height_should_be = (parseFloat(left_menu_height_should_be)-parseFloat(60)).toFixed(2);
    $(document).on('click', '.notif_class', function(e){
        let value = $(this).attr('data-value');
        if(value==1){
            $(".width_notification").show();
            $(this).attr('data-value',2);
        }else{
            $(".width_notification").hide();
            $(this).attr('data-value',1);
        }

    });

    /**
     * @description This function is used to check the string is json or not
     * @param {*} str 
     * @returns 
     */
    function IsJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
    //change notification status
    $(document).on('click', '.changeStatus', function(e){
        let id = $(this).attr("data-id");
        let value = $(this).attr("data-value");
        $.ajax({
            url     : base_url+'Ajax/change_status_notification',
            method  : 'get',
            dataType: 'json',
            data    : {
                'id' : id,
                'value' : value
            },
            success : function(data) {
                if(Number(value) == 3){
                    $(".delete_background").css("border-left", "unset");
                }
                $('#id_'+id).css("background-color", "transparent");
                $('#id_'+id).css("border-left", "unset");
                $('#totalNotifications').html(data.total_unread);
            },
            error   : function() {
                alert('somethingiswrong');
            }
        });
    });
    //delete notification row

    $(document).on('click', '.delete_notification', function(e){
        let id = $(this).attr("data-id");
        $.ajax({
            url     : base_url+'Ajax/delete_row_notification',
            method  : 'get',
            dataType: 'json',
            data    : {
                'id' : id
            },
            success : function(data) {
                $('#id_'+id).remove();
                $('#totalNotifications').html(data.total_unread);
            },
            error   : function() {
                alert('somethingiswrong');
            }
        });
    });

});






