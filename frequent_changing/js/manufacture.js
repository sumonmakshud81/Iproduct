$(document).ready(function () {
    "use strict";
    let base_url = $("#hidden_base_url").val();

    $(document).on("click", ".print_invoice", function () {
        viewChallan($(this).attr("data-id"));
    });

    function viewChallan(id) {
        open(
            base_url + "print_productions_details/" + id,
            "Print Quotation",
            "width=1600,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
    }

    $(document).on("click", ".changePartillyDone", function () {
        let id = $(this).data("id");
        let total_quantity = $(this).data("total_quantity");
        let partially_done = $(this).data("partially_done");
        let remaining_quantity = total_quantity - partially_done;
        console.log(partially_done);
        $(".total_quantity").html(total_quantity);
        $(".partially_done_quantity").val(partially_done);
        $(".remaining_quantity").html(remaining_quantity);
        $(".manufacture_id").val(id);
    });

    $(document).on("click", ".updateProducedQuantity", function () {
        let id = $(this).data("id");
        $(".manufacture_id").val(id);
        $.ajax({
            url: base_url + "updateProducedQuantityData",
            type: "POST",
            data: {
                "csrf-token": $("[name='csrf-token']").attr("content"),
                id: id,
            },
            success: function (response) {
                $(".remaining_quantity").html(response.remainingQuantity);
                $(".producedHistoryBody").html(response.table);
            },
        });
    });

    $(".select2").select2({
        dropdownParent: $("#filterModal"),
    });
});
