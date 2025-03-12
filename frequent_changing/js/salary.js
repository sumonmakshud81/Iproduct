$(function() {
    "use strict";
    checkAll();
    /**
     * @description This function is used to check all checkboxes
     */
    function checkAll() {
        if ($(".checkbox_user").length == $(".checkbox_user:checked").length) {
            $(".checkbox_userAll").prop("checked", true);
        } else {
            $(".checkbox_userAll").prop("checked", false);
        }
    }
    // Check or Uncheck All checkboxes
    $(document).on('click', '.checkbox_userAll', function(e){
        let checked = $(this).is(':checked');
        if (checked) {
            $(".checkbox_user").each(function () {
                let menu_id = $(this).attr('data-menu_id');
                $(this).prop("checked", true);
                $("#qty"+menu_id).val(1);
                $("#qty"+menu_id).prop("disabled", false);
            });
            $(".checkbox_userAll").prop("checked", true);
        } else {
            $(".checkbox_user").each(function () {
                let menu_id = $(this).attr('data-menu_id');
                $(this).prop("checked", false);
                $("#qty"+menu_id).prop("disabled", true);
                $("#qty"+menu_id).val('');
            });
            $(".checkbox_userAll").prop("checked", false);
        }
    });
    $(document).on('click', '.checkbox_user', function(e){
        let menu_id = $(this).attr('data-menu_id');
        if ($(".checkbox_user").length == $(".checkbox_user:checked").length) {
            $(".checkbox_userAll").prop("checked", true);
            if($(this).is(':checked')){
                $("#qty"+menu_id).val(1);
                $("#qty"+menu_id).prop("disabled", false);
            }else{
                $("#qty"+menu_id).prop("disabled", true);
                $("#qty"+menu_id).val('');
            }
        } else {
            $(".checkbox_userAll").prop("checked", false);
            if($(this).is(':checked')){
                $("#qty"+menu_id).val(1);
                $("#qty"+menu_id).prop("disabled", false);
            }else{
                $("#qty"+menu_id).prop("disabled", true);
                $("#qty"+menu_id).val('');
            }
        }
    });
    $(document).on('keyup', '.cal_row', function(e){
        cal_row();
    });

});
cal_row();

/**
 * @description This function is used to calculate the total amount
 */
function cal_row() {
    let total = 0;
    $(".row_counter").each(function () {
        let id = $(this).attr("data-id");

        let salary = $("#salary_"+id).val();
        let additional = $("#additional_"+id).val();
        let subtraction = $("#subtraction_"+id).val();

        if($.trim(salary) == "" || $.isNumeric(salary) == false){
            salary=0;
        }
        if($.trim(additional) == "" || $.isNumeric(additional) == false){
            additional=0;
        }
        if($.trim(subtraction) == "" || $.isNumeric(subtraction) == false){
            subtraction=0;
        }
        let total_row = parseFloat($.trim(salary)) + parseFloat($.trim(additional)) - parseFloat($.trim(subtraction));
        total+=total_row;
        $("#total_"+id).val(total_row.toFixed(2));
    });
    $(".total_amount").html(total.toFixed(2));
}