$(document).ready(function () {
    "use strict";

    let base_url = $("#hidden_base_url").val();

    $(document).on("click", ".print_invoice", function () {
        viewChallan();
    });

    function viewChallan() {
        open(
            base_url + "forecasting/product/print",
            "Print Customer Order",
            "width=1600,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
    }
});