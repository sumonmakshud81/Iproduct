
$(document).ready(function() {
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

    //call dragsort function
    $('#sortProdctCat').dragsort({
        cursor:'move',
        dragEnd: function() {
            counter();
            console.log('Drag End');
            let data = $("form#sorting_form").serialize();
            $.ajax({
                url     : base_url+'Ajax/sortingCategory',
                method  : 'get',
                dataType: 'json',
                data    : data,
                success:function(data){

                }
            });

        },
    });
});