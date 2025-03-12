$(document).ready(function () {
    "use strict";

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

    $(document).on("change", "#rmaterial", function (e) {
        let params = $(this).find(":selected").val();
        let separate_params = params.split("|");
        let html = `<tr class="rowCount" data-id="${separate_params[0]}">
                <td class="width_1_p"><p class="set_sn">1</p></td>
                <td>
                    <input type="hidden" value="${
                        separate_params[0]
                    }" name="rm_id[]">
                    <span class="name_id_${separate_params[0]}">${
            separate_params[1]
        }</span>
                </td>
                <td>                    
                    ${
                        separate_params[9] == "1"
                            ? `<span>Current Stock: ${separate_params[7]}</span>`
                            : `<span>Current Stock: ${separate_params[7]}${separate_params[4]}</span>`
                    }<br>
                    <span>Total Floating Stock: ${separate_params[8]}${
            separate_params[4]
        }</span>
                </td>
                <td>
                    <input type="hidden" tabindex="5" name="unit_price[]" onfocus="this.select();" class="form-control integerchk input_aligning unit_price_c cal_row" placeholder="Unit Price" value="${
                        separate_params[3]
                    }">
                    <div class="input-group">
                        <input type="number" data-countid="1" tabindex="51" id="quantity_amount_1" name="quantity_amount[]" data-stock="${
                            separate_params[6]
                        }" data-unit="${
            separate_params[4]
        }" onfocus="this.select();" class="check_required form-control integerchk aligning qty_c cal_row" value="1" placeholder="Qty/Amount">
                        <span class="input-group-text">${
                            separate_params[4]
                        }</span>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" id="total_1" name="total[]" class="form-control input_aligning total_c cal_total integerchk" onfocus="select();" placeholder="Total">
                        <span class="input-group-text">${
                            separate_params[5]
                        }</span>
                    </div>
                </td>
                <td class="ir_txt_center align-middle">
                    <span class="remove_this_tax_row del_row dlt_button" style="cursor:pointer;">
                        <iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon>
                    </span>
                </td>
            </tr>`;

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
                $("#rmaterial").val("").change();
                return false;
            }
        } else {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text: "This Raw Material already added",
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
            $("#rmaterial").val("").change();
            return false;
        }
    });

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
        let responsible_person = $("#res_person").val();

        if (!date) {
            $("#date").addClass("is-invalid");
            $("#date_error").removeClass("d-none");
            $("#date_error").text("The Date field is required");
            status = false;
        }else{
            $("#date_error").addClass("d-none");
            $("#date").removeClass("is-invalid");
        }

        if (!responsible_person) {
            $("#responsible_person").addClass("is-invalid");
            $("#responsible_person_error").removeClass("d-none");
            $("#responsible_person_error").text("The Responsible Person field is required");
            status = false;
        }else{
            $("#responsible_person_error").addClass("d-none");
            $("#responsible_person").removeClass("is-invalid");
        }

        let rowCount = $(".rowCount").length;
        if (!Number(rowCount)) {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            status = false;
            $(".add_tr").html(
                `<tr><td colspan="6" class="text-danger rmError">Please add minimum one Raw Material</td></tr>`
            );
        }else{
            $(".rmError").remove();
        }

        if (status == false) {
            return false;
        }
    });
    setAttribute();
    cal_row();
});
