$(document).ready(function () {
    "use strict";
    $("#add_order").click(function () {
        let order = $("#order_id").val();
        if (order == '') {
            $("#order_id").addClass('is-invalid');
            $("#order_error").removeClass('d-none');
            return false;
        }else{
            $("#order_id").removeClass('is-invalid');
            $("#order_error").addClass('d-none');
        }
    });

    $("#order_list").on('click', '.dlt_button', function () {
        $(this).closest('tr').remove();
    });

    $("#forecast_order").click(function () {
        let order_list = $("#order_list tr").length;    
        if(order_list == 0){
            $("#order_list_error").removeClass('d-none');
            $(this).attr('disabled', true);
            return;
        }else{
            $("#order_list_error").addClass('d-none');
            $(this).attr('disabled', false);
        }
    });

    $("#add_product").click(function () {
        let product = $("#product_id").val();
        let quantity = $("#quantity").val();
        if (product == '') {
            $("#product_id").addClass('is-invalid');
            $("#product_error").removeClass('d-none');
            return;
        }else{
            $("#product_id").removeClass('is-invalid');
            $("#product_error").addClass('d-none');
        }
        
        if (quantity == '') {
            $("#quantity").addClass('is-invalid');
            $("#quantity_error").removeClass('d-none');
            return;
        }else{
            $("#quantity").removeClass('is-invalid');
            $("#quantity_error").addClass('d-none');
        }

        let product_id = product.split('|')[0];
        let product_name = product.split('|')[1];
        let product_code = product.split('|')[2];
        let rowCount = $("#product_list tr").length;
        if(rowCount > 0){
            let lastRow = $("#product_list tr:last");
            let lastRowId = lastRow.data('id');
            if(lastRowId == product_id){
                $("#product_duplicate_error").removeClass('d-none');
                return;
            }else{
                $("#product_duplicate_error").addClass('d-none');
            }
        }
        let table = `<tr id="row_${rowCount + 1}" data-id="${product_id}">
            <td>${rowCount + 1}</td>
            <td>${product_name}(${product_code})</td>
            <td>${quantity}</td>
            <td>
                <input type="hidden" name="product_id[]" value="${product_id}">
                <input type="hidden" name="quantity[]" value="${quantity}">
                <a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a>
            </td>
        </tr>`;
        $("#product_list").append(table);
        $("#product_id").val('').change();
        $("#quantity").val(''); 
        $("#forecast_product").attr('disabled', false);
        $("#product_list_error").addClass('d-none');
    });

    $("#product_list").on('click', '.dlt_button', function () {
        $(this).closest('tr').remove();
    });

    $("#forecast_product").click(function () {
        let product_list = $("#product_list tr").length;    
        if(product_list == 0){
            $("#product_list_error").removeClass('d-none');
            $(this).attr('disabled', true);
            return;
        }else{
            $("#product_list_error").addClass('d-none');
            $(this).attr('disabled', false);
        }
    });


});
