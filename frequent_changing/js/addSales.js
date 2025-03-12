$(document).ready(function () {
    "use strict";
    let hidden_alert = $("#hidden_alert").val();
    let hidden_cancel = $("#hidden_cancel").val();
    let hidden_ok = $("#hidden_ok").val();
    $(document).on("keydown", ".discount", function (e) {
        let keys = e.charCode || e.keyCode || 0;
        return (
            keys == 8 ||
            keys == 9 ||
            keys == 13 ||
            keys == 46 ||
            keys == 110 ||
            keys == 86 ||
            keys == 190 ||
            (keys >= 35 && keys <= 40) ||
            (keys >= 48 && keys <= 57) ||
            (keys >= 96 && keys <= 105)
        );
    });

    $(document).on("keyup", ".discount", function (e) {
        let input = $(this).val();
        let ponto = input.split(".").length;
        let slash = input.split("-").length;
        if (ponto > 2) $(this).val(input.substr(0, input.length - 1));
        $(this).val(input.replace(/[^0-9.%]/, ""));
        if (slash > 2) $(this).val(input.substr(0, input.length - 1));
        if (ponto == 2) $(this).val(input.substr(0, input.indexOf(".") + 4));
        if (input == ".") $(this).val("");
    });

    $("#customerModal").on("hidden.bs.modal", function () {
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

        let paid = Number($("#paid").val());
        let other_amount = Number($("#other").val());

        //foraddDiscount
        let disc = $("#discount").val();
        let totalDiscount = 0;
        if (
            $.trim(disc) == "" ||
            $.trim(disc) == "%" ||
            $.trim(disc) == "%%" ||
            $.trim(disc) == "%%%" ||
            $.trim(disc) == "%%%%"
        ) {
            totalDiscount = 0;
        } else {
            let Disc_fields = disc.split("%");
            let discAmount = Disc_fields[0];
            let discP = Disc_fields[1];

            if (discP == "") {
                totalDiscount =
                    row_tatal_total * (parseFloat($.trim(discAmount)) / 100);
            } else {
                if (!disc) {
                    discAmount = 0;
                }
                totalDiscount = parseFloat(discAmount);
            }
        }
        $("#subtotal").val(row_tatal_total.toFixed(2));
        $("#grand_total").val(
            (row_tatal_total + other_amount - totalDiscount).toFixed(2)
        );

        let due = row_tatal_total - paid + other_amount - totalDiscount;
        $("#due").val(due.toFixed(2));
    }

    /**
     * @description This function is used to invoice print
     */
    function invoicePrint() {
        window.print();
    }

    $(document).on("keyup", ".invoicePrint", function (e) {
        invoicePrint();
    });

    $(document).on("click", ".invoicePrint", function (e) {
        invoicePrint();
    });

    $(document).on("focus", ".invoicePrint", function (e) {
        invoicePrint();
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": jQuery(`meta[name="csrf-token"]`).attr("content"),
        },
    });

    $(document).on("change", "#product_id", function (e) {
        let params = $(this).find(":selected").val();
        if (params != "") {
            let separate_params = params.split("|");
            let hidden_base_url = $("#hidden_base_url").val();
            let st_method = separate_params[6];
            $("#batch_sec").addClass("d-none");
            $("#qty_modal").val("");
            $("#batch_no").val("");
            let item_details_array = params.split("|");
            $("#item_id_modal").val(item_details_array[0]);
            let stock_with_unit =
                item_details_array[7] + " " + item_details_array[4];
            $("#item_stock_modal").text(stock_with_unit);
            $("#item_name_modal").text(item_details_array[1]);
            $("#item_currency_modal").val(item_details_array[5]);
            $("#item_unit_modal").val(item_details_array[4]);
            $(".modal_unit_name").html(item_details_array[4]);
            $(".unit_price_modal").val(item_details_array[3]);
            $("#item_st_method").val(st_method);
            $("#item_params").val(params);
            if (st_method == "batchcontrol") {
                $("#batch_sec").removeClass("d-none");
                $.ajax({
                    url: hidden_base_url + "getBatchControlProduct",
                    method: "GET",
                    data: {
                        product_id: item_details_array[0],
                    },
                    success: function (response) {
                        let data = JSON.parse(response);
                        let html = "";
                        for (let i = 0; i < data.length; i++) {
                            html += `<tr>
                                <td>${data[i].batch_no} </td>
                                <td>${data[i].product_quantity} ${item_details_array[4]}</td>
                                <input type="hidden" class="current_stock" value="${data[i].product_quantity}">
                                <input type="hidden" class="batch_no" value="${data[i].batch_no}">
                                <input type="hidden" class="manufacture_id" value="${data[i].id}">
                                <td>
                                    <div class="input-group">
                                        <input type="number" class="form-control sale_qty" name="sale_qty[]" placeholder="Sale Qty">
                                        <span class="input-group-text">${item_details_array[4]}</span>
                                    </div>
                                </td>
                            </tr>`;
                        }
                        $("#batch_table_body").html(html);
                    },
                });
            }
            $("#cartPreviewModal").modal("show");
        }
    });

    // Update qty_modal when sale_qty inputs change
    $(document).on("input", ".sale_qty", function () {
        let totalQty = 0;
        $(".sale_qty").each(function () {
            let qty = parseFloat($(this).val()) || 0;
            let currentStock = parseFloat(
                $(this).closest("tr").find(".current_stock").val()
            );

            // Ensure qty doesn't exceed current stock
            if (qty > currentStock) {
                qty = currentStock;
                $(this).val(currentStock);
            }

            totalQty += qty;
        });

        // Update qty_modal with the total quantity
        $("#qty_modal").val(totalQty);
    });

    // Prevent manual editing of qty_modal when using batch control
    $("#qty_modal").on("focus", function () {
        if ($("#item_st_method").val() === "batchcontrol") {
            $(this).blur();
        }
    });

    $(document).on("click", "#addToCart", function (e) {
        e.preventDefault();
        let unit_price = $(".unit_price_modal").val();
        let qty_modal = $("#qty_modal").val();
        let item_unit_modal = $("#item_unit_modal").val();
        let item_id_modal = $("#item_id_modal").val();
        let item_currency_modal = $("#item_currency_modal").val();
        let params = $("#item_params").val();

        let st_method = $("#item_st_method").val();

        let item_details_array = params.split("|");
        let product_id = item_details_array[0];
        let current_stock = item_details_array[7];
        let item_name_modal = item_details_array[1];
        let batch_number = [];
        let batch_qty = [];
        let manufacture_id = [];
        if (st_method == "batchcontrol") {
            $(".batch_no").each(function () {
                batch_number.push($(this).val());
            });
            $(".sale_qty").each(function () {
                batch_qty.push($(this).val());
            });
            $(".manufacture_id").each(function () {
                manufacture_id.push($(this).val());
            });
        } else {
            batch_number = "";
        }
        if (st_method == "fifo" || st_method == "fefo") {
            let q = Number(qty_modal);
            let s = Number(current_stock);
            if (q > s) {
                let hidden_alert = $("#hidden_alert").val();
                let hidden_cancel = $("#hidden_cancel").val();
                let hidden_ok = $("#hidden_ok").val();
                swal({
                    title: hidden_alert + "!",
                    text:
                        "Quantity Not grater than Stock. This product current stock is " +
                        current_stock,
                    cancelButtonText: hidden_cancel,
                    confirmButtonText: hidden_ok,
                    confirmButtonColor: "#3c8dbc",
                });
                $("#product_id").val("").change();
                $("#cartPreviewModal").modal("hide");
                return;
            }

            let hidden_base_url = $("#hidden_base_url").val();

            let method_base_url = "";

            if (st_method == "fifo")
                method_base_url = hidden_base_url + "getFifoFProduct";

            if (st_method == "fefo")
                method_base_url = hidden_base_url + "getFefoFProduct";

            $.ajax({
                type: "POST",
                url: method_base_url,
                data: {
                    id: product_id,
                    unit_price: unit_price,
                    quantity: qty_modal,
                    item_id_modal: item_id_modal,
                    item_currency_modal: item_currency_modal,
                    item_unit_modal: item_unit_modal,
                },
                success: function (html) {
                    console.log(html);

                    let check_exist = true;

                    $(".rowCount").each(function () {
                        let id = $(this).attr("data-id");
                        if (Number(id) == Number(item_id_modal)) {
                            check_exist = false;
                        }
                    });

                    if (check_exist == true) {
                        if (item_id_modal) {
                            $(".add_tr").append(html);
                            setAttribute();
                            cal_row();
                            $("#product_id").val("").change();
                            $("#cartPreviewModal").modal("hide");
                            $("#fifoPreviewModal").modal("hide");
                            return false;
                        }
                    } else {
                        let hidden_alert = $("#hidden_alert").val();
                        let hidden_cancel = $("#hidden_cancel").val();
                        let hidden_ok = $("#hidden_ok").val();
                        swal({
                            title: hidden_alert + "!",
                            text: "This Finish Product already added",
                            cancelButtonText: hidden_cancel,
                            confirmButtonText: hidden_ok,
                            confirmButtonColor: "#3c8dbc",
                        });
                        $("#product_id").val("").change();
                        return false;
                    }
                },
                error: function () {},
            });
        } else {
            if (st_method == "batchcontrol") {
                batch_number.forEach((batch, index) => {
                    if (
                        batch_qty[index] != "" &&
                        !isNaN(batch_qty[index]) &&
                        batch_qty[index] != 0
                    ) {
                        appendCart(
                            item_id_modal,
                            item_name_modal,
                            item_currency_modal,
                            item_unit_modal,
                            unit_price,
                            batch_qty[index],
                            product_id,
                            current_stock,
                            batch,
                            st_method,
                            manufacture_id[index]
                        );
                    }
                });
            } else {
                appendCart(
                    item_id_modal,
                    item_name_modal,
                    item_currency_modal,
                    item_unit_modal,
                    unit_price,
                    qty_modal,
                    product_id,
                    current_stock,
                    batch_number,
                    st_method,
                );
            }
        }
    });
    /**
     * @description This function is used to append the cart
     * @param {*} item_id_modal
     * @param {*} item_name_modal
     * @param {*} item_currency_modal
     * @param {*} item_unit_modal
     * @param {*} unit_price
     * @param {*} qty_modal
     * @param {*} product_id
     * @returns
     */
    function appendCart(
        item_id_modal,
        item_name_modal,
        item_currency_modal,
        item_unit_modal,
        unit_price,
        qty_modal,
        product_id,
        current_stock,
        batch_no,
        st_method,
        manufacture_id = null
    ) {
        let q = Number(qty_modal);
        let s = Number(current_stock);
        if (q > s) {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text:
                    "Quantity Not grater than Stock. This product current stock is " +
                    current_stock,
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
            $("#product_id").val("").change();
            $("#cartPreviewModal").modal("hide");
            return;
        }
        let html = `<tr class="rowCount" data-id="${item_id_modal}" data-batch="${batch_no}">
                <td class="width_1_p text-start"><p class="set_sn">1</p></td>
                <td>
                    <input type="hidden" class="current_stock" value="${current_stock}" name="current_stock[]">
                    <input type="hidden" value="${product_id}" name="selected_product_id[]">
                    <input type="hidden" value="${manufacture_id}" name="rm_id[]">
                    <input type="hidden" value="${manufacture_id}" name="manufacture_id[]">
                    <span>${item_name_modal}${
            batch_no ? `<br><small>Batch No: ${batch_no}</small>` : ""
        }</span>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" tabindex="5" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning unit_price_c cal_row" placeholder="Unit Price" value="${unit_price}">
                        <span class="input-group-text">${item_currency_modal}</span>
                    </div>
                    <span class='text-danger'></span>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" data-countid="1" tabindex="51" id="quantity_amount_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning qty_c cal_row" value="${qty_modal}" placeholder="Qty/Amount">
                        <span class="input-group-text">${item_unit_modal}</span>
                    </div>
                    <span class='text-danger'></span>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" id="total_1" name="total[]" class="form-control input_aligning total_c" placeholder="Total" readonly="">
                        <span class="input-group-text">${item_currency_modal}</span>
                    </div>
                </td>
                <td class="ir_txt_center">
                    <a class="btn btn-xs del_row dlt_button">
                        <iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon>
                    </a>
                </td>
            </tr>`;

        let check_exist = true;

        $(".rowCount").each(function () {
            let id = $(this).attr("data-id");
            let batch = $(this).attr("data-batch");
            if (st_method == "batchcontrol") {
                if (Number(id) == Number(item_id_modal) && batch == batch_no) {
                    check_exist = false;
                }
            } else {
                if (Number(id) == Number(item_id_modal)) {
                    check_exist = false;
                }
            }
        });

        if (check_exist == true) {
            if (item_id_modal) {
                $(".add_tr").append(html);
                setAttribute();
                cal_row();
                $("#product_id").val("").change();
                $("#cartPreviewModal").modal("hide");
                $("#fifoPreviewModal").modal("hide");
                return false;
            }
        } else {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text: "This Finish Product already added",
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
            $("#product_id").val("").change();
            return false;
        }
    }

    $(document).on("keyup", ".qty_c", function () {
        let current_stock = Number(
            $(this).closest("tr").find(".current_stock").val()
        );
        let qty = Number($(this).val());
        console.log(current_stock, qty);
        if (qty > current_stock) {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text:
                    "Quantity Not grater than Stock. This product current stock is " +
                    current_stock,
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
            $(this).val(current_stock);
        }
        cal_row();
    });

    $(document).on("click", ".del_row", function (e) {
        $(this).parent().parent().remove();

        setAttribute();
        cal_row();
    });

    $(document).on("keyup", ".cal_row", function (e) {
        cal_row();
    });

    $(document).on("click", ".cal_row", function (e) {
        cal_row();
    });

    $(document).on("focus", ".cal_row", function (e) {
        cal_row();
    });

    $("#sale_form").submit(function (e) {
        let status = true;
        let focus = 1;
        let code = $("#code").val();
        let customer_id = $("#customer_id").val();
        let customDatepicker = $(".customDatepicker").val();
        let statusField = $("#status").val();
        let product_id = $("#product_id").val();
        let paid = $("#paid").val();
        let accounts = $("#accounts").val();
        let date = $("#date").val();

        if (date == "") {
            status = false;
            showErrorMessage("date", "The Date field is required");
        } else {
            $("#date").removeClass("is-invalid");
            $("#date").closest("div").find(".text-danger").addClass("d-none");
        }

        if (code == "") {
            status = false;
            showErrorMessage("code", "The Code field is required");
        } else {
            $("#code").removeClass("is-invalid");
            $("#code").closest("div").find(".text-danger").addClass("d-none");
        }

        if (customer_id == "") {
            status = false;
            $("#customer_id").addClass("is-invalid");
            $(".customerErr").text("The Customer field is required");
            $(".customerErr").removeClass("d-none");
        } else {
            $("#customer_id").removeClass("is-invalid");
            $(".customerErr").addClass("d-none");
        }

        if (customDatepicker == "") {
            status = false;
            showErrorMessage("customDatepicker", "The Date field is required");
        } else {
            $("#customDatepicker").removeClass("is-invalid");
            $("#customDatepicker")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (statusField == "") {
            status = false;
            showErrorMessage("status", "The Status field is required");
        } else {
            $("#status").removeClass("is-invalid");
            $("#status").closest("div").find(".text-danger").addClass("d-none");
        }

        if (accounts == "") {
            status = false;
            showErrorMessage("accounts", "The Account field is required");
        } else {
            $("#accounts").removeClass("is-invalid");
            $("#accounts")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (paid == "") {
            status = false;
            $("#paid").addClass("is-invalid");
            let closestDiv = $(".paidErr");
            closestDiv.text("The Paid field is required");
            closestDiv.removeClass("d-none");
        } else {
            $("#paid").removeClass("is-invalid");
            $(".paidErr").addClass("d-none");
        }

        // Check if unit price fields are empty
        $(".unit_price_c").each(function () {
            let unitPrice = $(this).val();
            if (unitPrice === "") {
                status = false;
                $(this).addClass("is-invalid");
                $(this)
                    .closest("td")
                    .find(".text-danger")
                    .text("Unit price is required")
                    .removeClass("d-none");
            } else {
                $(this).removeClass("is-invalid");
                $(this).closest("td").find(".text-danger").addClass("d-none");
            }
        });

        // Check if quantity fields are empty
        $(".qty_c").each(function () {
            let quantity = $(this).val();
            if (quantity === "") {
                status = false;
                $(this).addClass("is-invalid");
                $(this)
                    .closest("td")
                    .find(".text-danger")
                    .text("Quantity is required")
                    .removeClass("d-none");
            } else {
                $(this).removeClass("is-invalid");
                $(this).closest("td").find(".text-danger").addClass("d-none");
            }
        });

        let rowCount = $(".rowCount").length;
        if (!Number(rowCount)) {
            status = false;
            $("#purchase_cart .add_tr").html(
                '<tr><td colspan="6" class="text-danger errProduct">Add minimum one product</td></tr>'
            );
        } else {
            $("#purchase_cart .add_tr").removeClass("errProduct");
        }

        if (status == true) {
            let customer_credit_limit = $(".customer_credit_limit").val();
            let customer_prevoius_due = $(".customer_previous_due").val();
            let customer_current_due = $(".customer_current_due").val();
            let total_due =
                Number(customer_prevoius_due) + Number(customer_current_due);
            if(isNaN(total_due)){
                total_due = 0;
            }
            console.log(customer_current_due,customer_credit_limit,customer_prevoius_due,total_due);
            let quotation_page = $("#quotation_page").val();
            if(quotation_page == 0){
                if (total_due <= customer_credit_limit) {
                    return true;
                } else {
                    swal({
                        title: hidden_alert + "!",
                        text: "Customer Credit Limit Exceeds",
                        cancelButtonText: hidden_cancel,
                        confirmButtonText: hidden_ok,
                        confirmButtonColor: "#3c8dbc",
                    });
                    return false;
                }
            }else{
                return true;
            }
        } else {
            return false;
        }
    });

    function showErrorMessage(id, message) {
        $("#" + id + "").addClass("is-invalid");
        let closestDiv = $("#" + id + "")
            .closest("div")
            .find(".text-danger");
        closestDiv.text(message);
        closestDiv.removeClass("d-none");
    }

    setAttribute();
    cal_row();

    $("#pull_low_stock_products").on("click", function () {
        let hidden_base_url = $("#hidden_base_url").val();
        $.ajax({
            url: hidden_base_url + "getLowRMStock",
            method: "GET",
            success: function (response) {
                $(".add_tr").empty();
                $(".add_tr").append(response);
                $("#populate_click").val("clicked");
                setAttribute();
                cal_row();
            },
            error: function () {
                alert("error");
            },
        });
    });

    $("#customer_id").on("change", function () {
        let hidden_base_url = $("#hidden_base_url").val();
        let customer_id = $("#customer_id").val();
        $.ajax({
            type: "GET",
            dataType: "json",
            url: hidden_base_url + "getCustomerDue",
            data: {
                customer_id: customer_id,
            },
            success: function (data) {
                console.log(data);
                $(".customer_previous_due").val(data.supplier_total_due);
                $(".customer_credit_limit").val(data.credit_limit);
            },
        });
    });

     // Currency Change
    $(document).on("change", "#change_currency", function () {
        if ($(this).is(":checked")) {
            $("#currency_section").removeClass("d-none");
        } else {
            $("#currency_section").addClass("d-none");
        }

        $(".select2-container").css('width', '100%');
    });

    $(document).on("change", "#currency", function(){
        let data = $(this).val();
        let conversion_rate = data.split("|")[1];
        let amount = $("#paid").val();
        $("#converted_amount").val(currencyConversion(amount, conversion_rate));
        $(".converted_amount_currency").text(data.split("|")[2]);
        $("#currency_id").val(data.split("|")[0]);
    })

    function currencyConversion(amount, conversion_rate) {
        const convertedAmount = amount * conversion_rate;
    return convertedAmount.toFixed(2);
    }
});
