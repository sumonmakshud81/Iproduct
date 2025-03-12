$(document).ready(function () {
    "use strict";
    let default_currency = $("#default_currency").val();
    let base_url = $("#hidden_base_url").val();
    $(document).on("click", ".print_invoice", function () {
        viewChallan($(this).attr("data-id"));
    });

    function viewChallan(id) {
        open(
            base_url + "customer-order-print/" + id,
            "Print Customer Order",
            "width=1600,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
    }
    modalIssueFix();
    function modalIssueFix() {
        $("#product_id").select2({
            dropdownParent: $("#deliveryModal"),
        });
        $("#delivery_status").select2({
            dropdownParent: $("#deliveryModal"),
        });

        $("#invoice_type").select2({
            dropdownParent: $("#invoiceModal"),
        });
    }

    /**
     * @description This function is used to Calculate the Row
     */
    function cal_row() {
        let i = 1;
        let total_sub_tatal = 0;
        let total_cost = 0;
        let total_profit = 0;

        i = 1;

        $(".sub_total_c").each(function () {
            let sub_total = Number($("#sub_total_" + i).val());
            total_sub_tatal += sub_total;
            i++;
        });

        i = 1;
        $(".cost_c").each(function () {
            let cost = Number($("#cost_" + i).val());
            let quantity = Number($("#quantity_" + i).val());
            total_cost += cost * quantity;
            i++;
        });

        i = 1;
        $(".profit_c").each(function () {
            let profit = Number($("#profit_" + i).val());
            total_profit += profit;
            i++;
        });

        $("#total_subtotal").val(total_sub_tatal.toFixed(2));
        $("#total_cost").val(total_cost.toFixed(2));
        $("#total_profit").val(total_profit.toFixed(2));

        $(".invoice_amount").val(total_sub_tatal.toFixed(2));
        $(".invoice_due").val(total_sub_tatal.toFixed(2));
        $(".invoice_order_due").val(total_sub_tatal.toFixed(2));
        modalIssueFix();
    }

    let i = 0;

    $(document).on("click", "#finishProduct", function (e) {
        ++i;
        let hidden_product = $("#hidden_product").html();
        $(".errProduct").remove();
        $(".add_trm").append(
            "<tr>" +
                '<td class="ir_txt_center"><p class="set_sn rowCount">' +
                i +
                "</p></td>" +
                '<td><select class="form-control fproduct_id select2" name="product[]" id="fproduct_id_' +
                i +
                '"><option value="">Please Select</option>\n' +
                hidden_product +
                "</select></td>" +
                '<td><input type="number" name="quantity[]" onfocus="this.select();" class="check_required form-control integerchk quantity_c" placeholder="Quantity" id="quantity_' +
                i +
                '"></td>' +
                '<td><div class="input-group"><input type="text" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk unit_price_c" placeholder="Unit Price" id="unit_price_' +
                i +
                '"><span class="input-group-text rmcurrency">' +
                default_currency +
                "</span></div></td>" +
                '<td><div class="input-group"><input type="text" name="discount_percent[]" onfocus="this.select();" class="check_required form-control integerchk discount_percent_c" placeholder="Discount" id="discount_percent_' +
                i +
                '"><span class="input-group-text">%</span></div></td>' +
                '<td><div class="input-group"><input type="number" id="sub_total_' +
                i +
                '" name="sub_total[]" class="form-control sub_total_c" placeholder="Subtotal" readonly=""><span class="input-group-text rmcurrency">' +
                default_currency +
                "</span></div></td>" +
                '<td><div class="input-group"><input type="number" id="cost_' +
                i +
                '" name="cost[]" class="form-control cost_c" placeholder="Cost" readonly=""><span class="input-group-text rmcurrency">' +
                default_currency +
                "</span></div></td>" +
                '<td><div class="input-group"><input type="number" id="profit_' +
                i +
                '" name="profit[]" class="form-control profit_c" placeholder="Profit" readonly=""><span class="input-group-text rmcurrency">' +
                default_currency +
                "</span></div></td>" +
                '<td><input type="text" id="delivery_date_' +
                i +
                '" name="delivery_date_product[]" class="form-control customDatepicker" placeholder="Delivery Date"></td>' +
                '<td class="text-center align-middle"><span id="production_status_' +
                i +
                '">N/A</span></td>' +
                '<td class="align-middle"><span id="deliveries_qty_' +
                i +
                '">0</span></td>' +
                '<td class="ir_txt_center"><a class="btn btn-xs del_row remove-tr dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>' +
                "</tr>"
        );

        $(".select2").select2();

        $(".customDatepicker").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
        });
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": jQuery(`meta[name="csrf-token"]`).attr("content"),
        },
    });

    $(document).on("change", ".fproduct_id", function (e) {
        let fproduct_id = $(this).find(":selected").val();
        let hidden_base_url = $("#hidden_base_url").val();

        let product_field_id = this.id;

        let product_field_array = product_field_id.split("_");

        let c_id = product_field_array[2];

        let default_currency = $("#default_currency").val();

        $.ajax({
            type: "POST",
            url: hidden_base_url + "getFinishProductDetails",
            data: { id: fproduct_id },
            dataType: "json",
            success: function (data) {
                $("#unit_price_" + c_id).val(data.sale_price);
                $("#cost_" + c_id).val(data.total_cost);

                $(".rmcurrency").html(default_currency);
            },
            error: function () {},
        });
    });

    $(document).on("keydown", ".quantity_c", function (e) {
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

    $(document).on("keyup", ".quantity_c", function (e) {
        let quantity = Number($(this).val());

        let product_field_id = this.id;
        let product_field_array = product_field_id.split("_");
        let c_id = product_field_array[1];

        let unit_price = Number($("#unit_price_" + c_id).val());
        let cost = Number($("#cost_" + c_id).val());

        let sub_total = quantity * unit_price;

        let total_cost = quantity * cost;

        let profit = sub_total - total_cost;
        console.log("profit = " + profit);

        $("#sub_total_" + c_id).val(sub_total);
        $("#profit_" + c_id).val(profit);

        cal_row();
    });

    $(document).on("keyup", ".discount_percent_c", function (e) {
        let discount_percent = Number($(this).val());

        let product_field_id = this.id;
        let product_field_array = product_field_id.split("_");
        let c_id = product_field_array[2];

        let quantity = Number($("#quantity_" + c_id).val());
        let cost = Number($("#cost_" + c_id).val());

        let sub_total = Number($("#sub_total_" + c_id).val());
        let total_cost = quantity * cost;

        let productDiscountAmount =
            sub_total * (parseFloat($.trim(discount_percent)) / 100);

        let profit = sub_total - total_cost - productDiscountAmount;

        $("#profit_" + c_id).val(profit);

        cal_row();
    });

    $(document).on("click", ".del_row", function (e) {
        $(this).parent().parent().remove();
        setAttribute();
        cal_row();
    });

    $(document).on("change", "#order_type", function () {
        let order_type = $(this).val();
        if (order_type == "Work Order") {
            $("#deliveries_section").removeClass("d-none");
            $("#invoice_quotations_sections").removeClass("d-none");
            addDefaultQuotations();
            modalIssueFix();
        } else {
            $("#deliveries_section").addClass("d-none");
            $("#invoice_quotations_sections").addClass("d-none");
        }
    });
    let today = new Date().toISOString().slice(0, 10);
    /**
     * @description This function is used to add default Quotations
     */
    function addDefaultQuotations() {
        console.log(today);
        $(".add_order_inv").html(
            '<tr class="rowCount"><td class="width_1_p ir_txt_center">1</td><td><input type="text" name="invoice_type[]" class="form-control" value="Quotation" readonly></td><td><input type="text" name="invoice_date[]" class="form-control" value="' +
                today +
                '" readonly></td> <td><input type="text" name="invoice_amount[]" class="form-control invoice_amount" value="0" readonly></td><td><input type="text" name="invoice_paid[]" class="form-control" value="0" readonly></td><td><input type="text" name="invoice_due[]" class="form-control invoice_due" value="0" readonly></td><td><input type="text" name="invoice_order_due[]" class="form-control invoice_order_due" value="0" readonly></td><td class="ir_txt_center"><a class="btn btn-xs del_inv_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td></tr>'
        );
    }

    /**
     * Invoice Add Row
     */
    $(document).on("click", ".invoice_submit", function (e) {
        e.preventDefault();
        let invoice_type = $("#invoice_type").val();
        let invoice_date = today;
        let invoice_amount = $("#paid_amount").val();
        let invoice_due = $("#due_amount").val();
        let invoice_order_due = $("#order_due_amount").val();
        let rowCount = $(".add_order_inv tr").length;
        rowCount = rowCount + 1;

        if (invoice_amount == "") {
            $("#paid_amount").addClass("is-invalid");
            $("#paid_amount").focus();
            $(".paid_amount_err_msg")
                .html("Please enter paid amount")
                .fadeOut(5000);
            return false;
        }

        $(".add_order_inv").append(
            '<tr class="rowCount"><td class="width_1_p ir_txt_center">1</td><td><input type="text" name="invoice_type[]" class="form-control" value="' +
                invoice_type +
                '" readonly></td><td><input type="text" name="invoice_date[]" class="form-control" value="' +
                invoice_date +
                '" readonly></td> <td><input type="text" name="invoice_amount[]" class="form-control invoice_amount" value="' +
                invoice_amount +
                '" readonly></td><td><input type="text" name="invoice_paid[]" class="form-control" value="' +
                invoice_amount +
                '" readonly></td><td><input type="text" name="invoice_due[]" class="form-control invoice_due" value="' +
                invoice_due +
                '" readonly></td><td><input type="text" name="invoice_order_due[]" class="form-control invoice_order_due" value="' +
                invoice_order_due +
                '" readonly></td><td class="ir_txt_center"><a class="btn btn-xs del_inv_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td></tr>'
        );
        $("#invoiceModal").modal("hide");
    });

    /**
     * Delileties Add Row
     */

    $(document).on("click", ".delivaries_button", function (e) {
        e.preventDefault();
        $(".table-responsive").removeClass("d-none");
        let product = $("#product_id").val();
        let quantity = $("#delivary_quantity").val();
        let delivery_date = $("#ddelivery_date").val();
        let delivery_status = $("#delivery_status").val();
        let delivery_note = $("#delivery_note").val() ?? "";
        let rowCount = $(".add_deliveries tr").length;
        rowCount = rowCount + 1;

        if (product == "") {
            $("#product_id").addClass("is-invalid");
            $("#product_id").focus();
            $(".product_error").html("Please select product").fadeOut(5000);
            return false;
        }

        if (quantity == "") {
            $("#delivary_quantity").addClass("is-invalid");
            $("#delivary_quantity").focus();
            $(".quantity_error").html("Please enter quantity").fadeOut(5000);
            return false;
        }

        if (delivery_date == "") {
            $("#ddelivery_date").addClass("is-invalid");
            $("#ddelivery_date").focus();
            $(".delivery_date_error")
                .html("Please select delivery date")
                .fadeOut(5000);
            return false;
        }

        if (delivery_status == "") {
            $("#delivery_status").addClass("is-invalid");
            $("#delivery_status").focus();
            $(".delivery_status_error")
                .html("Please select delivery status")
                .fadeOut(5000);
            return false;
        }

        $.ajax({
            type: "POST",
            url: $("#hidden_base_url").val() + "getFinishProductDetails",
            data: { id: product },
            dataType: "json",
            success: function (data) {
                $(".add_deliveries").append(
                    '<tr class="rowCount"><td class="width_1_p ir_txt_center">' +
                        rowCount +
                        '</td><td><input type="hidden" name="delivaries_product[]" value="' +
                        product +
                        '" /><input type="text" class="form-control" value="' +
                        data.name +
                        '" readonly></td><td><input type="text" name="delivaries_quantity[]" class="form-control" value="' +
                        quantity +
                        '"></td><td><input type="text" name="delivaries_date[]" class="form-control customDatepicker" value="' +
                        delivery_date +
                        '"></td><td><input type="text" name="delivaries_status[]" class="form-control" value="' +
                        delivery_status +
                        '"></td><td><input type="text" name="delivaries_note[]" class="form-control" value="' +
                        delivery_note +
                        '"></td><td class="ir_txt_center"><a class="btn btn-xs del_del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td></tr>'
                );
                $("#deliveryModal").modal("hide");
            },
        });
    });

    /**
     * Check Stock
     */

    $(document).on("click", ".stockCheck", function (e) {
        e.preventDefault();
        let totalProducts = $(".fproduct_id").length;
        $(".download_button").removeClass("disabled");
        if (totalProducts == 0) {
            let message =
                "<p class='text-center my-2 text-danger'>Please add products first</p>";
            $(".stock_check_table").html(message);
            $(".download_button").addClass("disabled");
            return false;
        }
        let sn = 1;
        let productName = [];
        let productCode = [];
        let currentStock = [];
        let needed = [];
        let completedRequests = 0;

        $(".fproduct_id").each(function () {
            let id = $(this).val();
            let product_name = $(this).find("option:selected").text();
            let quantity = $(this).closest("tr").find(".quantity_c").val();

            $.ajax({
                type: "POST",
                url: $("#hidden_base_url").val() + "getFinishProductDetails",
                data: { id: id },
                dataType: "json",
                success: function (data) {
                    let stock = data.current_total_stock ?? 0;
                    let code = data.code;
                    let neededForManufacture = quantity - stock;
                    neededForManufacture =
                        neededForManufacture < 0 ? 0 : neededForManufacture;

                    // Push the data into arrays inside the success callback
                    productName.push(product_name);
                    productCode.push(code);
                    currentStock.push(stock);
                    needed.push(neededForManufacture);

                    // Increment the completed requests counter
                    completedRequests++;

                    // Check if all requests are completed
                    if (completedRequests === totalProducts) {
                        generateTable(
                            productName,
                            productCode,
                            currentStock,
                            needed
                        );
                    }
                },
                error: function () {
                    completedRequests++;
                    if (completedRequests === totalProducts) {
                        generateTable(
                            productName,
                            productCode,
                            currentStock,
                            needed
                        );
                    }
                },
            });
        });
    });

    /**
     * @description This function is used to generate the table
     * @param {Array} productName
     * @param {Array} productCode
     * @param {Array} currentStock
     * @param {Array} needed
     */
    function generateTable(productName, productCode, currentStock, needed) {
        let sn = 1;
        let table = "";
        for (let i = 0; i < productName.length; i++) {
            table +=
                "<tr><td>" +
                sn +
                "</td><td>" +
                productName[i] +
                "(" +
                productCode[i] +
                ")</td><td>" +
                currentStock[i] +
                "</td><td>" +
                needed[i] +
                "</td></tr>";
            sn++;
        }

        $(".stock_check_table").html(table);
    }

    /**
     * Estimated Cost
     */

    $(document).on("click", ".estimateCost", function (e) {
        e.preventDefault();
        let totalProducts = $(".fproduct_id").length;
        $(".download_button_cost").removeClass("disabled");
        if (totalProducts == 0) {
            let message =
                "<p class='text-center my-2 text-danger'>Please add products first</p>";
            $(".estimate_cost_table").html(message);
            $(".download_button_cost").addClass("disabled");
            return false;
        }

        let productName = [];
        let productCode = [];
        let quantity = [];
        let rawMaterialCost = [];
        let nonInventoryCost = [];
        let totalRawMaterialCost = [];
        let totalNonInventoryCost = [];
        let grandTotal = [];

        let requiredTime = [];

        $(".fproduct_id").each(function () {
            let id = $(this).val();
            let product_name = $(this).find("option:selected").text();
            let quantity_p = $(this).closest("tr").find(".quantity_c").val();

            $.ajax({
                type: "POST",
                url: $("#hidden_base_url").val() + "getFinishProductDetails",
                data: { id: id },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    let code = data.code;

                    // Push the data into arrays inside the success callback
                    productName.push(product_name);
                    productCode.push(code);
                    quantity.push(quantity_p);
                    rawMaterialCost.push(data.rmaterials);
                    nonInventoryCost.push(data.non_inventory);
                    totalRawMaterialCost.push(data.rmcost_total);
                    totalNonInventoryCost.push(data.noninitem_total);
                    requiredTime.push(data.required_time);

                    grandTotal.push(data.total_cost);

                    generateCostTable(
                        productName,
                        productCode,
                        quantity,
                        rawMaterialCost,
                        nonInventoryCost,
                        totalRawMaterialCost,
                        totalNonInventoryCost,
                        grandTotal,
                        requiredTime
                    );
                },
            });
        });
    });

    /**
     * @description This function is used to generate the cost table
     * @param {*} productName
     * @param {*} productCode
     * @param {*} quantity
     * @param {*} rawMaterialCost
     * @param {*} nonInventoryCost
     * @param {*} totalRawMaterialCost
     * @param {*} totalNonInventoryCost
     * @param {*} grandTotal
     * @param {*} requiredTime
     */
    function generateCostTable(
        productName,
        productCode,
        quantity,
        rawMaterialCost,
        nonInventoryCost,
        totalRawMaterialCost,
        totalNonInventoryCost,
        grandTotal,
        requiredTime
    ) {
        let sn = 1;
        let table = "";
        console.log(nonInventoryCost);
        for (let i = 0; i < productName.length; i++) {
            let rawMaterials = `<div id="stockInnerTable"><ul><li><div>Raw Materials</div></li><li><div class="w-40">Name</div><div class="w-40">Value x Quantity</div><div class="w-20 text-end">Total</div></li>`;

            for (let j = 0; j < rawMaterialCost[i].length; j++) {
                rawMaterials += `<li>
                    <div class="w-40">${rawMaterialCost[i][j].raw_materials.name}</div>
                    <div class="w-40">
                        ${default_currency}${rawMaterialCost[i][j].unit_price} x ${rawMaterialCost[i][j].consumption}
                    </div>
                    <div class="w-20 text-end">${default_currency}${rawMaterialCost[i][j].total_cost}</div>
                </li>`;
            }

            rawMaterials += `<li><div class="fw-medium">Total</div><div>${default_currency}${totalRawMaterialCost[i]}</div></li>`;

            rawMaterials += `<li><div class="fw-bold">Non Inventory Cost</div></li><li><div class="w-50">Name</div><div class="w-50 text-end">Total</div></li>`;

            for (let j = 0; j < nonInventoryCost[i].length; j++) {
                rawMaterials += `<li>
                    <div class="w-50">${nonInventoryCost[i][j].non_inventory_item.name}</div>
                    <div class="w-50 text-end">${default_currency}${nonInventoryCost[i][j].nin_cost}</div>
                </li>`;
            }
            rawMaterials += `<li><div class="fw-medium">Total</div><div>${default_currency}${totalNonInventoryCost[i]}</div></li>`;
            rawMaterials += `<li><div class="fw-bold">Grand Total</div><div>${default_currency}${grandTotal[i]}</div></li>`;

            rawMaterials += "</ul></div>";

            let requiredTimes =
                requiredTime[i] + " x " + quantity[i] + " =<br>";
            requiredTimes += calculateTotalRequiredTime(
                requiredTime[i],
                quantity[i]
            );

            table +=
                "<tr><td>" +
                sn +
                "</td><td>" +
                productName[i] +
                "(" +
                productCode[i] +
                ")</td><td>" +
                quantity[i] +
                "</td><td>" +
                rawMaterials +
                "</td><td>" +
                requiredTimes +
                "</td></tr>";
            sn++;
        }

        $(".estimate_cost_table").html(table);
    }

    /**
     * @description This function is used to calculate the total required time
     * @param {*} timeString
     * @param {*} quantity
     * @returns
     */
    function calculateTotalRequiredTime(timeString, quantity) {
        const timeRegex =
            /(\d+)\s*month[s]?\s*(\d+)\s*day[s]?\s*(\d+)\s*hour[s]?\s*(\d+)\s*minute[s]?/;
        const timeMatch = timeString.match(timeRegex);

        const timeForOneProduct = {
            months: parseInt(timeMatch[1]),
            days: parseInt(timeMatch[2]),
            hours: parseInt(timeMatch[3]),
            minutes: parseInt(timeMatch[4]),
        };

        const daysInMonth = 30; // Assuming 30 days in a month for simplicity
        const hoursInDay = 24;
        const minutesInHour = 60;

        // Calculate total time for one product in hours
        let totalHoursOneProduct =
            timeForOneProduct.months * daysInMonth * hoursInDay +
            timeForOneProduct.days * hoursInDay +
            timeForOneProduct.hours +
            timeForOneProduct.minutes / minutesInHour;

        let totalHoursAllProducts = totalHoursOneProduct * quantity;

        // Convert total hours back into months, days, hours, and minutes
        let totalMonths = Math.floor(
            totalHoursAllProducts / (daysInMonth * hoursInDay)
        );
        totalHoursAllProducts %= daysInMonth * hoursInDay;

        let totalDays = Math.floor(totalHoursAllProducts / hoursInDay);
        totalHoursAllProducts %= hoursInDay;

        let totalHours = Math.floor(totalHoursAllProducts);
        let totalMinutes = Math.floor(
            (totalHoursAllProducts - totalHours) * minutesInHour
        );

        return `${totalMonths} months, ${totalDays} days, ${totalHours} hours, ${totalMinutes} minutes`;
    }
    $("#invoiceModal").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
    });

    $("#deliveryModal").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
    });

    $(document).on("click", ".del_inv_row", function (e) {
        $(this).parent().parent().remove();
    });

    $(document).on("click", ".del_del_row", function (e) {
        $(this).parent().parent().remove();
    });

    /**
     * download_button
     */

    $(document).on("click", ".download_button", function (e) {
        e.preventDefault();
        let reference_no = $("#code").val();
        let order_type = $("#order_type").val();
        let customer = $("#customer_id").val();
        let productData = [];
        $(".stock_check_table tr").each(function () {
            let row = $(this)
                .find("td")
                .map(function () {
                    return $(this).text();
                })
                .get();
            productData.push({
                id: row[0],
                name: row[1],
                quantity: row[2],
                price: row[3],
            });
        });
        if (reference_no == "") {
            let message =
                "<p class='text-center my-2 text-danger'>Please enter reference number</p>";
            $(".stock_check_table").html(message);
            $(".download_button").addClass("disabled");
            return false;
        } else {
            $(".download_button").removeClass("disabled");
        }
        if (order_type == "") {
            let message =
                "<p class='text-center my-2 text-danger'>Please select order type</p>";
            $(".stock_check_table").html(message);
            $(".download_button").addClass("disabled");
            return false;
        } else {
            $(".download_button").removeClass("disabled");
        }
        if (customer == "") {
            let message =
                "<p class='text-center my-2 text-danger'>Please select customer</p>";
            $(".stock_check_table").html(message);
            $(".download_button").addClass("disabled");
            return false;
        } else {
            $(".download_button").removeClass("disabled");
        }

        $.ajax({
            type: "POST",
            url: $("#hidden_base_url").val() + "downloadStockCheck",
            data: {
                reference_no: reference_no,
                order_type: order_type,
                customer: customer,
                productData: productData,
            },
            xhrFields: {
                responseType: "blob",
            },
            success: function (blob) {
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "stock.pdf";
                link.click();

                $("#stockCheck").modal("hide");
            },
            error: function () {
                console.log("error");
            },
        });
    });

    /**
     * Estimate Cost
     */
    $(document).on("click", ".download_button_cost", function (e) {
        e.preventDefault();
        let reference_no = $("#code").val();
        let order_type = $("#order_type").val();
        let customer = $("#customer_id").val();
        let productData = [];
        $(".estimate_cost_table tr").each(function () {
            let row = $(this)
                .find("td")
                .map(function () {
                    return $(this).html();
                })
                .get();
            productData.push({
                id: row[0],
                name: row[1],
                quantity: row[2],
                cost: encodeURIComponent(row[3]),
                required_time: row[4],
            });
        });
        console.log(productData);
        if (reference_no == "") {
            let message =
                "<p class='text-center my-2 text-danger'>Please enter reference number</p>";
            $(".estimate_cost_table").html(message);
            $(".download_button_cost").addClass("disabled");
            return false;
        } else {
            $(".download_button_cost").removeClass("disabled");
        }
        if (order_type == "") {
            let message =
                "<p class='text-center my-2 text-danger'>Please select order type</p>";
            $(".estimate_cost_table").html(message);
            $(".download_button_cost").addClass("disabled");
            return false;
        } else {
            $(".download_button_cost").removeClass("disabled");
        }
        if (customer == "") {
            let message =
                "<p class='text-center my-2 text-danger'>Please select customer</p>";
            $(".estimate_cost_table").html(message);
            $(".download_button_cost").addClass("disabled");
            return false;
        } else {
            $(".download_button_cost").removeClass("disabled");
        }

        $.ajax({
            type: "POST",
            url: $("#hidden_base_url").val() + "downloadEstimateCost",
            data: {
                reference_no: reference_no,
                order_type: order_type,
                customer: customer,
                productData: productData,
            },
            xhrFields: {
                responseType: "blob",
            },
            success: function (blob) {
                // console.log(blob);
                // return;
                if (blob.size > 0) {
                    // Check if the blob is not empty
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "estimate_cost.pdf";
                    link.click();

                    $("#estimateCost").modal("hide");
                } else {
                    console.error("The blob is empty");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error: ", error);
                console.log(xhr.responseText);
            },
        });
    });

    $(document).on("click", ".order_submit_button", function () {
        let status = true;

        let code = $("#code").val();
        let customer_id = $("#customer_id").val();
        let order_type = $("#order_type").val();
        let delivery_date = $("#delivery_date").val();
        let delivery_address = $("#delivery_address").val();

        if (code == "") {
            showErrorMessage("code", "The code field is required");
            status = false;
        } else {
            $("#code").removeClass("is-invalid");
            $("#code").closest("div").find(".text-danger").addClass("d-none");
        }

        if (customer_id == "") {
            showErrorMessage("customer_id", "The customer field is required");
            status = false;
        } else {
            $("#customer_id").removeClass("is-invalid");
            $("#customer_id")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (order_type == "") {
            showErrorMessage("order_type", "The order type field is required");
            status = false;
        } else {
            $("#order_type").removeClass("is-invalid");
            $("#order_type")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (order_type == "Work Order" && delivery_date == "") {
            showErrorMessage(
                "delivery_date",
                "The delivery date field is required"
            );
            status = false;
        } else {
            $("#delivery_date").removeClass("is-invalid");
            $("#delivery_date")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        let rowCount = $(".rowCount").length;
        if (!Number(rowCount)) {
            status = false;
            $("#fprm .add_trm").html(
                '<tr><td colspan="6" class="text-danger errProduct">Please add minimum one product</td></tr>'
            );
        } else {
            $(".errProduct").remove();
        }

        if (status == true) {
            return true;
        } else {
            $("html, body").animate({ scrollTop: 0 }, "slow");
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
});
