$(document).ready(function () {
    "use strict";
    let baseUrl = $("#hidden_base_url").val();
    $(document).on("click", ".print_invoice", function () {
        viewChallan($(this).attr("data-id"));
    });

    function viewChallan(id) {
        open(
            baseUrl + "customer_payment_print/" + id,
            "Print Quotation",
            "width=1600,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
    }
});