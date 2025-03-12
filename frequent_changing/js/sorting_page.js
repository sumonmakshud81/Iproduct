$(function () {
    "use strict";

    /**
     * @description This function is used to Calculate the counter
     */
    function counter() {
        let i = 1;
        $(".counters").each(function(){
            $(this).html(i);
            i++;
        });
    }
    //drag and sorting
});