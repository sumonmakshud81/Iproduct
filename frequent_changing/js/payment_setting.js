
"use strict";
$(document).on('change', '#enable_status', function(e){
    checkRequried();
});
checkRequried();
/**
 * @description This function is used to check the required field
 */
function  checkRequried() {
    let this_value = $("#enable_status").val();
    if(Number(this_value)==1){
        $(".required_star").show();
    }else{
        $(".required_star").hide();
    }
}