
(function ($) {
    ("use strict");
    let hidden_alert = $("#hidden_alert").val();
    let hidden_cancel = $("#hidden_cancel").val();
    let hidden_ok = $("#hidden_ok").val();
    $(document).on("click", "#add_product_to_cart", function () {
        let params = $("#productModal").val();
        let product_id = params.split("|")[0];
        let productName = params.split("|")[1];
        let quantity = $("#qty_modal_product").val();
        let checkExists = true;
        if (product_id == "") {
            $("#productModal").addClass("is-invalid");
            $(".productErr")
                .text("The Product field is required")
                .fadeIn()
                .delay(3000)
                .fadeOut();
        }

        if (quantity == "") {
            $("#qty_modal_product").addClass("is-invalid");
            $(".qtyErr")
                .text("The Quantity field is required")
                .fadeIn()
                .delay(3000)
                .fadeOut();
        }

        $(".rowCount").each(function () {
            let id = $(this).attr("data-id");
            if (Number(id) == Number(product_id)) {
                $("#productModal").addClass("is-invalid");
                $(".productErr")
                    .text("The Product Already added")
                    .fadeIn()
                    .delay(3000)
                    .fadeOut();
                checkExists = false;
            }
        });
        let rowCount = Number($(".rowCount").length);
        if (product_id != "" && quantity != "" && checkExists) {
            let html = "<tr class='rowCount' data-id='" + product_id + "'>";
            html += "<td>" + (rowCount + 1) + "</td>";
            //hidden product id
            html +=
                "<input type='hidden' name='product_id[]' value='" +
                product_id +
                "'>";
            //hidden quantity
            html +=
                "<input type='hidden' name='quantity[]' value='" +
                quantity +
                "'>";
            html += "<td>" + productName + "</td>";
            html += "<td>" + quantity + "</td>";
            html +=
                '<td class="text-end"><a class="btn btn-xs del_row remove-tr dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>';
            html += "</tr>";
            $("#stock_table").removeClass("d-none");
            $("#cart_data").append(html);
            $("#productModal").val("").trigger("change");
            $("#qty_modal_product").val("");
        }
    });

    // Delete dlt_button
    $(document).on("click", ".dlt_button", function () {
        $(this).closest("tr").remove();
    });

    
})(jQuery);
