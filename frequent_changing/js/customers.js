$(document).ready(function () {
    "use strict";
    
    let default_currency = $("#default_currency").val();
    $(document).on("change", "#customer", function () {
        let p = $(this).find(":selected").val();
        let params = p.split("|");
        console.log(params);
        $("#customer_id").val(params[0]);
        $(".due_balance_show").removeClass("d-none");
        let msg = "Due Balance: ";
        msg += default_currency + Math.abs(params[1]).toFixed(2);
        if (Math.abs(params[1]) !== 0) {
            msg += `(${params[1] > 0 ? "Debit" : "Credit"})`;
        }

        $(".due_balance_show").html(msg);
    });
});
