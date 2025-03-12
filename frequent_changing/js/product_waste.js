$(document).ready(function () {
    "use strict";
    let hidden_base_url = $("#hidden_base_url").val();
    $("#supplierModal").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
    });

    /**
     * @description This function is used to set the attribute of the element
     */
    function setAttribute() {
        let i = 1;
        $(".set_sn").each(function () {
            $(this).html(i);
            i++;
        });
        i = 1;
        $(".unit_price_c").each(function () {
            $(this).attr("id", "unit_price_" + i);
            i++;
        });
        i = 1;
        $(".qty_c").each(function () {
            $(this).attr("id", "qty_" + i);
            i++;
        });
        i = 1;
        $(".total_c").each(function () {
            $(this).attr("id", "total_" + i);
            i++;
        });
    }

    /**
     * @description This function is used to calculate the row
     */
    function cal_row() {
        let i = 1;
        let row_tatal = 0;
        let row_tatal_total = 0;
        $(".unit_price_c").each(function () {
            let unit_price = Number($("#unit_price_" + i).val());
            let qty = Number($("#qty_" + i).val());
            row_tatal = unit_price * qty;
            row_tatal_total += row_tatal;
            $("#total_" + i).val(row_tatal.toFixed(2));
            i++;
        });
        $("#grand_total").val(row_tatal_total.toFixed(2));
    }

    /**
     * @description This function is used to calculate the total
     */
    function cal_total() {
        let i = 1;
        let row_tatal_total = 0;
        $(".cal_total").each(function () {
            let total_amount = Number($(this).val());
            row_tatal_total += total_amount;
            i++;
        });
        $("#grand_total").val(row_tatal_total.toFixed(2));
    }

    $(document).on("change", "#finished_product", function (e) {
        let params = $(this).find(":selected").val();
        let separate_params = params.split("|");
        let stock_method = separate_params[7];
        let batch_number = "";
        let expiry_date = "";
        if (stock_method == "batchcontrol" || stock_method == "fefo") {
            $("#item_name_modal").text(separate_params[1]);
            $(".modal_unit_name").text(separate_params[4]);
            $("#params").val(params);
            $.ajax({
                url: hidden_base_url + "getBatchControlProduct",
                method: "GET",
                data: {
                    product_id: separate_params[0],
                },
                success: function (response) {
                    let data = JSON.parse(response);
                    if (stock_method == "batchcontrol") {
                        $("#batch_sec").removeClass("d-none");
                        $("#expiry_sec").addClass("d-none");
                        let html = "";
                        for (let i = 0; i < data.length; i++) {
                            html += `<option value="${data[i].id}|${data[i].product_quantity}">${data[i].batch_no}</option>`;
                        }
                        $("#batch_no").html(html);
                        $("#batch_no").select2({
                            dropdownParent: $("#cartPreviewModal"),
                        });
                    }
                    if (stock_method == "fefo") {
                        $("#expiry_sec").removeClass("d-none");
                        $("#batch_sec").addClass("d-none");
                        let html = "";
                        for (let i = 0; i < data.length; i++) {
                            html += `<option value="${data[i].id}|${data[i].product_quantity}">${data[i].expiry_date}</option>`;
                        }
                        $("#expiry_date").html(html);
                        $("#expiry_date").select2({
                            dropdownParent: $("#cartPreviewModal"),
                        });
                    }
                },
            });
            $("#cartPreviewModal").modal("show");
            return;
        }   
        
        appendToCart(separate_params);
    });

    $(document).on("click", "#addToCart", function (e) {
        let qty = $("#qty_modal").val();
        let batch_no = $("#batch_no").val();
        let expiry_date = $("#expiry_date").val();
        let id = "";
        let stock = "";
        if (batch_no != "" && batch_no != null) {
            let batch_no_params = batch_no.split("|");
            id = batch_no_params[0];
            stock = batch_no_params[1];
        }
        if (expiry_date != "" && expiry_date != null) {
            let expiry_date_params = expiry_date.split("|");
            id = expiry_date_params[0];
            stock = expiry_date_params[1];
        }
        if(qty > stock){
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text: "Current stock is " + stock,
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
            $("#finished_product").val("").change();
            $("#cartPreviewModal").modal("hide");
            return false;
        }
        let params = $("#params").val();
        let separate_params = params.split("|");
        appendToCart(separate_params, qty, id);
        $("#cartPreviewModal").modal("hide");
    });

    function appendToCart(separate_params, qty = 1, mid = null) {
        let html = `
            <tr class="rowCount" data-id="${separate_params[0]}">
                <td class="width_1_p text-start"><p class="set_sn">1</p></td>
                <td>
                    <input type="hidden" value="${separate_params[0]}" name="product_id[]">
                    <input type="hidden" value="${mid}" name="manufacture_id[]">
                    <span class="name_id_${separate_params[0]}">${separate_params[1]}(${separate_params[2]})</span>
                </td>
                <td>
                    <div class="input-group">
                        <input type="hidden" tabindex="5" name="unit_price[]" onfocus="this.select();" class="form-control integerchk unit_price_c cal_row" placeholder="Unit Price" value="${separate_params[3]}">
                        <input type="number" data-countid="1" tabindex="51" id="quantity_amount_1" name="quantity_amount[]" data-stock="${separate_params[6]}" data-unit="${separate_params[4]}" onfocus="this.select();" class="check_required form-control integerchk aligning qty_c cal_row" value="${qty}" placeholder="Qty/Amount">
                        <span class="input-group-text">${separate_params[4]}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" id="total_1" name="total[]" value="${separate_params[3]}" class="form-control total_c cal_total integerchk" onfocus="select();" placeholder="Total">
                        <span class="input-group-text">${separate_params[5]}</span>
                    </div>
                </td>
                <td class="ir_txt_center">
                    <a class="btn btn-xs del_row dlt_button">
                        <iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon>
                    </a>
                </td>
            </tr>
        `;

        let check_exist = true;

        $(".rowCount").each(function () {
            let id = $(this).attr("data-id");
            if (Number(id) == Number(separate_params[0])) {
                check_exist = false;
            }
        });

        if (check_exist == true) {
            if (separate_params[0]) {
                $(".add_tr").append(html);
                setAttribute();
                cal_row();
                $("#finished_product").val("").change();
                return false;
            }
        } else {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text: "This Product already added",
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
            $("#finished_product").val("").change();
            return false;
        }
    }

    $(document).on("click", ".del_row", function (e) {
        $(this).parent().parent().remove();
        setAttribute();
        cal_row();
    });

    $(document).on("keyup", ".cal_row", function (e) {
        let current_stock = Number($(this).attr("data-stock"));
        let this_value = Number($(this).val());
        let unit = $(this).attr("data-unit");
        if (current_stock < this_value) {
            $(this).val(current_stock);
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text: "Current stock is " + current_stock + unit,
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
        }
        cal_row();
    });

    $(document).on("click", ".cal_row", function (e) {
        let current_stock = Number($(this).attr("data-stock"));
        let this_value = Number($(this).val());
        let unit = $(this).attr("data-unit");
        if (current_stock < this_value) {
            $(this).val(current_stock);
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text: "Current stock is " + current_stock + unit,
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
        }
        cal_row();
    });

    $(document).on("focus", ".cal_row", function (e) {
        let current_stock = Number($(this).attr("data-stock"));
        let this_value = Number($(this).val());
        let unit = $(this).attr("data-unit");
        if (current_stock < this_value) {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            $(this).val(current_stock);
            swal({
                title: hidden_alert + "!",
                text: "Current stock is " + current_stock + unit,
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
        }

        cal_row();
    });

    $(document).on("keyup", ".cal_total", function (e) {
        cal_total();
    });

    $("#purchase_form").submit(function () {
        let status = true;
        let date = $("#date").val();
        let res_person = $("#res_person").val();
        if (date == "") {
            status = false;
            $("#date").addClass("is-invalid");
            $("#date_error").html("The Date field is required");
            $("#date_error").removeClass("d-none");
        }
        if (res_person == "") {
            status = false;
            $("#res_person").addClass("is-invalid");
            $("#res_person_error").html(
                "The Responsible person field is required"
            );
            $("#res_person_error").removeClass("d-none");
        }

        let rowCount = $(".rowCount").length;

        if (!Number(rowCount)) {
            status = false;
            $(".add_tr").html(
                `<tr>
                    <td colspan="5" class="text-danger errOrd">Please add at least one Product</td>
                </tr>`
            );
        } else {
            $(".errOrd").remove();
        }

        if (status == false) {
            return false;
        }
    });
    setAttribute();
    cal_row();
});
