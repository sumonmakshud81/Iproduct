$(document).ready(function () {
    "use strict";

    let base_url = $("#hidden_base_url").val();

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
        let rtotal = 0;
        let row_tatal_total = 0;
        $(".unit_price_c").each(function () {
            let unit_price = Number($("#punit_price_" + i).val());
            let qty = Number($("#pqty_" + i).val());
            row_tatal = unit_price * qty;
            row_tatal_total += row_tatal;
            let runit_price = Number($("#runit_price_" + i).val());
            let rqty = Number($("#rqty_" + i).val());
            rtotal = runit_price * rqty;
            row_tatal_total += rtotal;
            $("#rtotal_" + i).val(rtotal.toFixed(2));
            $("#ptotal_" + i).val(row_tatal.toFixed(2));
            i++;
            
        });
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
        console.log(row_tatal_total);
        $("#grand_total").val(row_tatal_total.toFixed(2));
    }

    $(document).on("change", "#manufacture", function (e) {
        let params = $(this).find(":selected").val();
        $.ajax({
            url: base_url + "production_data",
            type: "POST",
            data: {
                manufacture: params,
            },
            success: function (data) {
                $(".product_body").html(data.product_table);
                $(".rm_body").html(data.materials_table);
                cal_row();
                cal_total();
            },
        });
    });

    $(document).on("click", ".del_row", function (e) {
        $(this).parent().parent().remove();
        setAttribute();
        cal_row();
    });

    $(document).on("keyup", ".cal_row", function (e) {
        cal_row();
        cal_total();
    });

    $(document).on("keyup", ".cal_total", function (e) {
        cal_total();
    });

    $("#attendance_form").submit(function () {
        let status = true;
        let date = $("#date").val();
        let responsible_person = $("#res_person").val();

        if (date == "") {
            status = false;
            $("#date").addClass("is-invalid");
            $(".date_error").text('The Date field is required');
        }else{
            $("#date").removeClass("is-invalid");
            $(".date_error").text('');
        }

        if (responsible_person == "") {
            status = false;
            $("#res_person").addClass("is-invalid");
            $(".res_person_error").text('The Responsible Person field is required');
        }else{
            $("#res_person").removeClass("is-invalid");
            $(".res_person_error").text('');
        }

        let rowCount = $(".rowCount").length;

        if (!Number(rowCount)) {
            status = false;
            $("#manufacture").addClass("is-invalid");
            $(".manufacture_error").text('Add at least one Production item');
            }else{
            $("#manufacture").removeClass("is-invalid");
            $(".manufacture_error").text('');
        }
        
        let manufacture_qty = $("#pmanufacture_qty_1").val();
        let loss_qty = $("#pqty_1").val();
        if (loss_qty == "") {
            status = false;
            $("#pqty_1").addClass("is-invalid");
            $(".loss_qty_error").text("The Loss Quantity field is required");
            }else{
            $("#pqty_1").removeClass("is-invalid");
            $(".loss_qty_error").text('');
        }
        if (manufacture_qty < loss_qty) {
            status = false;
            $("#pqty_1").addClass("is-invalid");
            $(".loss_qty_error").text("The Loss Quantity must be less than Production Quantity");
        }else{
            $("#pqty_1").removeClass("is-invalid");
            $(".loss_qty_error").text('');
        }

        let loss_amount = $("#ptotal_1").val();
        if (loss_amount == "") {
            status = false;
            $("#ptotal_1").addClass("is-invalid");
            $(".p_loss_total_error").text("The Loss Amount field is required");
        }else{
            $("#ptotal_1").removeClass("is-invalid");
            $(".p_loss_total_error").text('');
        }

        $(".rmqty").each(function () {
            let rm_qty = Number($(this).val());
            if (rm_qty == "") {
                status = false;
                $(this).addClass("is-invalid");
                $(this).closest("tr").find(".rm_loss_qty_error").text(
                    "The Loss Quantity field is required"
                );
            }else{
                $(this).removeClass("is-invalid");
                $(this).closest("tr").find(".rm_loss_qty_error").text('');
            }
        });

        $(".rmtotal").each(function () {
            let rm_total = Number($(this).val());
            if (rm_total == "") {
                status = false;
                $(this).addClass("is-invalid");
                $(this).closest("tr").find(".rm_loss_total_error").text(
                    "The Loss Amount field is required"
                );
            }else{
                $(this).removeClass("is-invalid");
                $(this).closest("tr").find(".rm_loss_total_error").text('');
            }
        });
        
        if (status == false) {
            return false;
        }
    });
    setAttribute();
    cal_row();
});
