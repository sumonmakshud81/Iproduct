$(document).ready(function () {
    "use strict";
    let baseUrl = $("#hidden_base_url").val();
    $(document).on("click", ".print_invoice", function () {
        viewInvoice($(this).attr("data-id"));
    });

    $(document).on("click", ".print_challan", function () {
        viewChallan($(this).attr("data-id"));
    });

    function viewInvoice(id) {
        open(
            baseUrl + "invoice/" + id,
            "Print Challan",
            "width=1600,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
    }

    function viewChallan(id) {
        open(
            baseUrl + "challan/" + id,
            "Print Challan",
            "width=1600,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
    }
});
