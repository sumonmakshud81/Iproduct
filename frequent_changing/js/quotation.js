$(document).ready(function () {
    "use strict";
    let inputField = $("#button_click_type");
    let baseUrl = $("#hidden_base_url").val();
    $(document).on("click", ".print_invoice", function () {
        viewChallan($(this).attr("data-id"));
    });

    function viewChallan(id) {
        open(
            baseUrl + "print-quotation/" + id,
            "Print Quotation",
            "width=1600,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
    }
    $(document).on("click", "#download_btn", function(){
        inputField.val('download');
        $(this).attr('type', 'submit');
        $(this).click();
    });

    $(document).on("click", "#email_btn", function () {
        inputField.val("email");
        $(this).attr("type", "submit");
        $(this).click();
    });

    $(document).on("click", "#print_btn", function () {
        inputField.val("print");
        $(this).attr("type", "submit");
        $(this).click();
    });
});
