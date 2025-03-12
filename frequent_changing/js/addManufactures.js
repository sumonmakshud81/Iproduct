$(document).ready(function () {
    "use strict";
    let tax_type = $(".tax_type").val();
    let input = document.getElementById("file_button");
    let default_currency = $("#default_currency").val();
    let target = $(".sort_menu");
    target.sortable({
        handle: ".handle",
        placeholder: "highlight",
        axis: "y",
        update: function (e, ui) {
            reorderSerial();
        },
    });
    function reorderSerial() {
        let i = 1;
        $(".set_sn4").each(function () {
            $(this).html(i);
            i++;
        });
    }
    input.addEventListener("change", function () {
        let file = this.files[0];
        let img = new Image();
        //extension check jpg/jpeg/png/pdf/doc/docx only
        let ext = file.name.split(".").pop().toLowerCase();
        if (
            $.inArray(ext, ["jpg", "jpeg", "png", "pdf", "doc", "docx"]) == -1
        ) {
            $("#file_button").val("");
            $("#file_button").addClass("is-invalid");
            $(".errorFile").text(
                "Invalid file type. File type must be jpg, jpeg, png, pdf, doc or docx."
            );
        }

        //calculate image size
        let size = Math.round(Number(file.size) / 1024);
        //get width
        let width = Number(this.width);
        //get height
        let height = Number(this.height);
        if (Number(size) > 5120) {
            $("#file_button").val("");
            $("#file_button").addClass("is-invalid");
            $(".errorFile").text(
                "File size is too large. File size must be less than 5 MB."
            );
        }
        //call on load
        img.onload = function () {
            URL.revokeObjectURL(this.src);
            //calculate image size
            let size = Math.round(Number(file.size) / 1024);
            //get width
            let width = Number(this.width);
            //get height
            let height = Number(this.height);
            if (Number(size) > 5120) {
                $("#file_button").val("");
                $("#file_button").addClass("is-invalid");
                $(".errorFile").text(
                    "File size is too large. File size must be less than 5 MB."
                );
            }
        };

        let objectURL = URL.createObjectURL(file);
        img.src = objectURL;
    });
    checkBoxSingle();
    function checkBoxSingle() {
        $(".custom_checkbox").change(function (e) {
            e.preventDefault();
            $(".custom_checkbox").not(this).prop("checked", false);
            $(this).prop("checked", true);
        });
    }

    $("#productionstage_id").select2({
        dropdownParent: $("#productScheduling"),
    });
    $("#productScheduling").on("shown.bs.modal", function (e) {
        $(".daterangepicker").css("z-index", "1600");
    });
    $("#supplierModal").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
    });

    /**
     * @description: Set Attribute
     * @returns: {void}
     */
    function setAttribute() {
        let i = 1;
        $(".set_sn").each(function () {
            $(this).html(i);
            i++;
        });
        i = 1;
        $(".set_sn").click(function () {
            $(this).html(i);
            i++;
        });
        i = 1;
        $(".set_sn1").each(function () {
            $(this).html(i);
            i++;
        });
        i = 1;
        $(".set_sn1").click(function () {
            $(this).html(i);
            i++;
        });
        i = 1;
        $(".set_sn2").each(function () {
            $(this).html(i);
            i++;
        });
        i = 1;
        $(".set_sn2").click(function () {
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
        i = 1;
        $(".total_c1").each(function () {
            $(this).attr("id", "total_1" + i);
            i++;
        });
        i = 1;
        $(".account_id_c1").each(function () {
            $(this).attr("id", "account_id" + i);
            i++;
        });
    }
    /**
     * @description Calculate Percentage
     * @param {*} total_amount
     * @param {*} percentage
     * @returns
     */
    function calPercentage(total_amount, percentage) {
        let plan_amount = (total_amount * percentage) / 100;
        return plan_amount;
    }

    /**
     * @description Calculate Row
     */
    function cal_row() {
        let i = 1;
        let row_tatal = 0;
        let row_tatal_total = 0;
        let noniitem = 0;
        let total_percentage = 0;
        let profit_total = 0;
        $(".unit_price_c").each(function () {
            let unit_price = Number($("#unit_price_" + i).val());
            let qty = Number($("#qty_" + i).val());
            row_tatal = unit_price * qty;
            row_tatal_total += row_tatal;
            $("#total_" + i).val(row_tatal.toFixed(2));
            i++;
        });

        if (tax_type == "Exclusive") {
            $(".get_percentage").each(function () {
                let tmp_value = Number($(this).val());
                total_percentage += tmp_value;
            });
        }
        i = 1;

        $(".total_c1").each(function () {
            let total = Number($("#total_1" + i).val());
            noniitem += total;
            i++;
        });
        $(".total_month").each(function () {
            let total = Number($("#total_1" + i).val());
            noniitem += total;
            i++;
        });

        $("#rmcost_total").val(row_tatal_total.toFixed(2));
        $("#noninitem_total").val(noniitem.toFixed(2));
        $("#total_cost").val((noniitem + row_tatal_total).toFixed(2));

        $(".margin_cal").each(function () {
            let profit_margin = Number($(".profit_margin").val());
            let total_cos = Number($("#total_cost").val());
            profit_total = (total_cos * profit_margin) / 100;
        });

        let getActual_sale_price = calPercentage(
            noniitem + row_tatal_total + profit_total,
            total_percentage
        );

        $("#sale_price").val(
            (
                getActual_sale_price +
                noniitem +
                row_tatal_total +
                profit_total
            ).toFixed(2)
        );
    }

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": jQuery(`meta[name="csrf-token"]`).attr("content"),
        },
    });
    $(document).on("click", "#pr_go", function (e) {
        e.preventDefault();
        $(".submit_btn").removeClass("disabled");
        $("#checkStockButton").addClass("d-none");
        if (!Number($(".product_quantity").val())) {
            let hidden_alert = $("#hidden_alert").val();
            let hidden_cancel = $("#hidden_cancel").val();
            let hidden_ok = $("#hidden_ok").val();
            swal({
                title: hidden_alert + "!",
                text: "Quantity Field is Empty",
                cancelButtonText: hidden_cancel,
                confirmButtonText: hidden_ok,
                confirmButtonColor: "#3c8dbc",
            });
        } else {
            let hidden_base_url = $("#hidden_base_url").val();
            let product_quantity = $(".product_quantity").val();
            let params = $(".fproduct_id").val();
            let separate_params = params.split("|");
            let fproduct_id = separate_params[0];
            $(".hidden_sec").removeClass("hidden_sec");
            $.ajax({
                type: "POST",
                url: hidden_base_url + "getFinishProductRManufacture",
                data: { id: fproduct_id, value: product_quantity },
                dataType: "json",
                success: function (data) {
                    $(".add_trm").html(data);
                    setAttribute();
                    cal_row();
                    rawMaterialStockCheck(product_quantity, fproduct_id);
                },
                error: function () {},
            });
            $.ajax({
                type: "POST",
                url: hidden_base_url + "getFinishProductNONI",
                data: { id: fproduct_id, value: product_quantity },
                dataType: "json",
                success: function (data) {
                    $(".add_tnoni").html(data);
                    setAttribute();
                    cal_row();
                    $(".select2").select2();
                    $("#productionstage_id").select2({
                        dropdownParent: $("#productScheduling"),
                    });
                },
                error: function () {},
            });
            $.ajax({
                type: "POST",
                url: hidden_base_url + "getFinishProductStages",
                data: { id: fproduct_id, value: product_quantity },
                dataType: "json",
                success: function (data) {
                    $(".add_tstage").html(data.html);
                    $("#t_month").val(data.total_month);
                    $("#t_day").val(data.total_day);
                    $("#t_hours").val(data.total_hour);
                    $("#t_minute").val(data.total_minute);
                    setAttribute();
                    cal_row();
                    checkBoxSingle();
                },
                error: function () {},
            });
        }
    });
    /**
     * @description Raw Material Stock Check
     * @param {*} product_quantity
     * @param {*} fproduct_id
     */
    function rawMaterialStockCheck(product_quantity, fproduct_id) {
        let hidden_base_url = $("#hidden_base_url").val();
        $.ajax({
            type: "POST",
            url: hidden_base_url + "rawMaterialStockCheck",
            data: { product_id: fproduct_id, quantity: product_quantity },
            dataType: "json",
            success: function (data) {
                let need_purchase = false;
                for (let i = 0; i < data.length; i++) {
                    if (data[i].status == "need_purchase") {
                        need_purchase = true;
                        break;
                    }
                }
                if (need_purchase == true) {
                    let hidden_alert =
                        "Your Stock is not enough. Check the stock and purchase the raw materials.";
                    let hidden_cancel = $("#hidden_cancel").val();
                    let hidden_ok = $("#hidden_ok").val();
                    swal({
                        title: hidden_alert + "!",
                        text: data.message,
                        cancelButtonText: hidden_cancel,
                        confirmButtonText: hidden_ok,
                        confirmButtonColor: "#3c8dbc",
                    });

                    $(".submit_btn").addClass("disabled");
                    $("#checkStockButton").removeClass("d-none");
                }
            },
            error: function () {},
        });
    }

    $(document).on("click", "#checkStockButton", function (e) {
        e.preventDefault();
        let hidden_base_url = $("#hidden_base_url").val();
        //all class rm id
        let rm_id = $(".rm_id")
            .map(function () {
                return $(this).val();
            })
            .get();
        //all class qty
        let quantity = $(".qty_c")
            .map(function () {
                return $(this).val();
            })
            .get();
        $.ajax({
            type: "POST",
            url: hidden_base_url + "rawMaterialStockCheckByMaterial",
            data: { rm_id: rm_id, quantity: quantity },
            dataType: "json",
            success: function (data) {
                console.log(data);
                let table =
                    '<table class="table table-bordered table-striped"><thead><tr><th>Raw Material</th><th>Stock</th><th>Required Quantity</th><th>Shortage</th></tr></thead><tbody>';
                for (let i = 0; i < data.length; i++) {
                    let shortage = data[i].required - data[i].stock;
                    shortage = shortage < 0 ? 0 : shortage;
                    table +=
                        "<tr><td>" +
                        data[i].name +
                        "</td><td>" +
                        data[i].stock_final +
                        "</td><td>" +
                        data[i].required +
                        data[i].unit +
                        "</td><td>" +
                        shortage +
                        data[i].unit +
                        "</td></tr>";

                    //Hidden Field Form for Purchase
                    table +=
                        '<input type="hidden" name="rm_id[]" value="' +
                        data[i].id +
                        '">';
                    table +=
                        '<input type="hidden" name="shortage[]" value="' +
                        shortage +
                        '">';
                    table +=
                        '<input type="hidden" name="status[]" value="' +
                        data[i].status +
                        '">';
                }
                table += "</tbody></table>";

                $("#check_stock_modal_body").html(table);
            },
            error: function () {},
        });
    });

    let i = 0;
    $(document).on("change", ".rmaterials_id", function (e) {
        let params = $(this).find(":selected").val();
        let separate_params = params.split("|");
        //cloasest parent td rm_id value set not parent use
        $(this).closest("td").find(".rm_id").val(separate_params[0]);
        $(this)
            .parent()
            .parent()
            .parent()
            .find(".pfrmup")
            .val(separate_params[5]);
        $(this)
            .parent()
            .parent()
            .parent()
            .find(".rmhunit")
            .html(separate_params[6]);
        $(this)
            .parent()
            .parent()
            .parent()
            .find(".rmcurrency")
            .html(separate_params[3]);
    });

    $(document).on("click", "#fprmaterial", function (e) {
        ++i;
        let ram_hidden = $("#ram_hidden").html();
        console.log(ram_hidden);
        $(".add_trm").append(
            "<tr>" +
                ' <td class="width_1_p text-start"><p class="set_sn rowCount"></p></td>' +
                '<td><input type="hidden" class="rm_id" /><select class="form-control rmaterials_id" name="rm_id[]">\n' +
                ram_hidden +
                "</select></td>" +
                '<td><div class="input-group"><input type="text" tabindex="5" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning unit_price_c cal_row pfrmup" placeholder="Unit Price" value="" id="unit_price_1"><span class="input-group-text rmcurrency">$</span></div></td>' +
                '<td><div class="input-group"><input type="text" data-countid="1" tabindex="51" id="qty_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning qty_c cal_row" value="" placeholder="Consumption"><span class="input-group-text rmhunit">Piece</span></div></td>' +
                '<td><div class="input-group"><input type="text" id="total_1" name="total[]" class="form-control input_aligning total_c" value="" placeholder="Total" readonly=""><span class="input-group-text rmcurrency">$</span></div></td>' +
                '<td class="text-end"><a class="btn btn-xs del_row remove-tr dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>' +
                "</tr>"
        );
        $(".select2").select2();
        $("#productionstage_id").select2({
            dropdownParent: $("#productScheduling"),
        });
        setAttribute();
        cal_row();
    });

    $(document).on("change", ".noninvemtory_id", function (e) {
        let params = $(this).find(":selected").val();
        let separate_params = params.split("|");
        $(this)
            .parent()
            .parent()
            .parent()
            .find(".nicurrency")
            .html(separate_params[2]);
    });

    i = 0;
    $(document).on("click", "#fpnonitem", function (e) {
        ++i;
        let noni_hidden = $("#noni_hidden").html();
        let account_hidden = $("#account_hidden").html();
        $(".add_tnoni").append(
            "<tr>" +
                ' <td class="width_1_p"><p class="set_sn1 rowCount1"></p></td>' +
                '<td><select class="form-control noninvemtory_id" name="noniitem_id[]" id="noninvemtory_id">\n' +
                noni_hidden +
                "</select></td><td></td>" +
                '<td><div class="input-group"><input type="text" id="total_1" name="total_1[]" class="cal_row check_required  form-control aligning total_c1" onfocus="select();" value="" placeholder="Non Inventory Cost"><span class="input-group-text nicurrency">$</span></div></td>' +
                '<td><div><select class="form-control account_id_c1" name="account_id[]" id="account_id">\n' +
                account_hidden +
                "</select></td></div>" +
                '<td class="text-end"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>' +
                "</tr>"
        );
        $(".select2").select2();
        $("#productionstage_id").select2({
            dropdownParent: $("#productScheduling"),
        });
        setAttribute();
        cal_row();
    });

    $(document).on("click", ".del_row", function (e) {
        $(this).parent().parent().remove();
        setAttribute();
        cal_row();
    });

    $(document).on("click", ".del_row_noni", function (e) {
        $(this).parent().parent().remove();
        setAttribute();
        cal_row();
    });

    $(document).on("click", ".del_row_prostage", function (e) {
        $(this).parent().parent().remove();
        setAttribute();
        cal_row();
    });

    $(document).on("keyup", ".margin_cal", function (e) {
        cal_row();
    });

    $(document).on("keyup", ".cal_row", function (e) {
        cal_row();
        let material_id = $(this)
            .parent()
            .parent()
            .parent()
            .find(".rm_id")
            .val();
        let quantity = $(this).val();
        checkSingleMaterialStock(material_id, quantity);
    });

    $(document).on("keyup", ".qty_c", function (e) {
        let quantity = $(this).val();
        let material_id = $(this)
            .parent()
            .parent()
            .parent()
            .find(".rm_id")
            .val();
        checkSingleMaterialStock(material_id, quantity);
    });

    /**
     * @description Check Single Material Stock
     * @param {*} material_id
     * @param {*} quantity
     * @returns
     */
    function checkSingleMaterialStock(material_id, quantity) {
        let hidden_base_url = $("#hidden_base_url").val();
        let status = false;
        $.ajax({
            type: "POST",
            url: hidden_base_url + "checkSingleMaterialStock",
            data: { material_id: material_id, quantity: quantity },
            dataType: "json",
            async: false,
            success: function (data) {
                if (data.status == "need_purchase") {
                    status = true;
                }

                if (status == true) {
                    let hidden_alert =
                        "Your Stock is not enough. Check the stock and purchase the raw materials.";
                    let hidden_cancel = $("#hidden_cancel").val();
                    let hidden_ok = $("#hidden_ok").val();
                    swal({
                        title: hidden_alert + "!",
                        text: data.message,
                        cancelButtonText: hidden_cancel,
                        confirmButtonText: hidden_ok,
                        confirmButtonColor: "#3c8dbc",
                    });

                    $(".submit_btn").addClass("disabled");
                    $("#checkStockButton").removeClass("d-none");
                } else {
                    $(".submit_btn").removeClass("disabled");
                    $("#checkStockButton").addClass("d-none");
                }
            },
            error: function () {},
        });
        return status;
    }

    $(document).on("click", ".set_class", function (e) {
        let this_value = $(this).val();
        let stage_name = $(this).attr("data-stage_name");
        console.log("counter => " + this_value);
        console.log("stage_name =>" + stage_name);
        $("#stage_counter").val(this_value);
        $("#stage_name").val(stage_name);
    });

    $(document).on("submit", "#manufacture_form", function (e) {
        let status = true;
        let focus = 1;
        let ref_no = $("#code").val();
        let manufacture_type = $("#manufactures").val();
        let statusField = $("#m_status").val();
        let start_date = $("#start_date").val();
        let completeDate = $("#complete_date").val();
        let quantity = $("#product_quantity").val();
        let batch_no = $("#batch_no").val();
        let expiry_days = $("#expiry_days").val();
        let st_method = $("#st_method").val();
        let checkedRadio = $("input[name='stage_check']:checked");
        if (ref_no == "") {
            status = false;
            showErrorMessage("code", "The Reference No field is Required");
        } else {
            $("#code").removeClass("is-invalid");
            $("#code").closest("div").find(".text-danger").addClass("d-none");
        }

        if (manufacture_type == "") {
            status = false;
            showErrorMessage(
                "manufactures",
                "The Production Type field is Required"
            );
        } else {
            $("#manufactures").removeClass("is-invalid");
            $("#manufactures")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (statusField == "") {
            status = false;
            showErrorMessage("m_status", "The Status field is Required");
        } else {
            $("#m_status").removeClass("is-invalid");
            $("#m_status")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (start_date == "") {
            status = false;
            showErrorMessage("start_date", "The Start Date field is Required");
        } else {
            $("#start_date").removeClass("is-invalid");
            $("#start_date")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (quantity == "") {
            status = false;
            showErrorMessage(
                "product_quantity",
                "The Quantity field is Required"
            );
        } else {
            $("#product_quantity").removeClass("is-invalid");
            $("#product_quantity")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (st_method == "fefo" && expiry_days == "") {
            status = false;
            showErrorMessage(
                "expiry_days",
                "The Expiry Days field is Required"
            );
        } else {
            $("#expiry_days").removeClass("is-invalid");
            $("#expiry_days")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (statusField == "done" && completeDate == "") {
            status = false;
            showErrorMessage(
                "complete_date",
                "The Complete Date field is Required"
            );
        } else {
            $("#complete_date").removeClass("is-invalid");
            $("#complete_date")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }
    
        console.log(checkedRadio.length, statusField);
        if (statusField == "inProgress" && checkedRadio.length <= 0) {
            status = false;
            $(".stage_check_error").removeClass("d-none");
            $(".stage_check_error").text("Please select minimum one stage");
        } else {
            $(".stage_check_error").addClass("d-none");
        }

        // check complete date not less than start date
        if (start_date != "" && completeDate != "") {
            let sd = new Date(start_date);
            let cd = new Date(completeDate);
            if (cd < sd) {
                status = false;
                showErrorMessage(
                    "complete_date",
                    "Complete Date can't be before than Start Date"
                );
            } else {
                $("#complete_date").removeClass("is-invalid");
                $("#complete_date")
                    .closest("div")
                    .find(".text-danger")
                    .addClass("d-none");
            }
        }

        // every unit price must be greater than 0
        $(".unit_price_c").each(function () {
            let unit_price = Number($(this).val());
            if (unit_price == "") {
                status = false;
                let message = "Unit Price field is Required";
                $("#unit_price_1").addClass("is-invalid");
                let closestDiv = $(".unitPriceErr").text(message);
                closestDiv.removeClass("d-none");
            }else{
                $("#unit_price_1").removeClass("is-invalid");
                $(".unitPriceErr").addClass("d-none");
            }
            if (unit_price <= 0) {
                status = false;
                let message = "Unit Price must be greater than 0";
                $("#unit_price_1").addClass("is-invalid");
                let closestDiv = $(".unitPriceErr").text(message);
                closestDiv.removeClass("d-none");
            }else{
                $("#unit_price_1").removeClass("is-invalid");
                $(".unitPriceErr")
                    .addClass("d-none");
            }
        });

        // every quantity must be greater than 0
        $(".qty_c").each(function () {
            let quantity = Number($(this).val());
            if (quantity == "") {
                status = false;
                let message = "Quantity field is Required";
                $("#qty_1").addClass("is-invalid");
                let closestDiv = $(".quantityErr").text(message);
                closestDiv.removeClass("d-none");
            }else{
                $("#qty_1").removeClass("is-invalid");
                $(".quantityErr").addClass("d-none");
            }
            if (quantity <= 0) {
                status = false;
                let message = "Quantity must be greater than 0";
                $("#qty_1").addClass("is-invalid");
                let closestDiv = $(".quantityErr").text(message);
                closestDiv.removeClass("d-none");
            }else{
                $("#qty_1").removeClass("is-invalid");
                $(".quantityErr")
                    .addClass("d-none");
            }
        });

        //every non inventory cost must be greater than 0
        $(".total_c1").each(function () {
            let total = Number($(this).val());
            if (total == "") {
                status = false;
                let message = "Non Inventory Cost field is Required";
                $(".noi_cost").addClass("is-invalid");
                let closestDiv = $(".nonInventoryCostErr").text(message);
                closestDiv.removeClass("d-none");
            }else{
                $(".noi_cost").removeClass("is-invalid");
                $(".nonInventoryCostErr")
                    .addClass("d-none");
            }
            if (total <= 0) {
                status = false;
                let message = "Non Inventory Cost must be greater than 0";
                $(".noi_cost").addClass("is-invalid");
                let closestDiv = $(".nonInventoryCostErr").text(message);
                closestDiv.removeClass("d-none");
            }else{
                $(".noi_cost").removeClass("is-invalid");
                $(".nonInventoryCostErr")
                    .addClass("d-none");
            }
        });

        // every non inventory account must be selected
        $(".account_id_c1").each(function () {
            let account_id = $(this).val();
            if (account_id == "") {
                status = false;
                let message = "Account field is Required";
                $(".account_id_c1").addClass("is-invalid");
                let closestDiv = $(".accountErr").text(message);
                closestDiv.removeClass("d-none");
            }else{
                $(".account_id_c1").removeClass("is-invalid");
                $(".accountErr")
                    .addClass("d-none");
            }
        });

        let rowCount = $(".rowCount").length;

        if (!Number(rowCount)) {
            status = false;
            showErrorMessage(
                "fproduct_id",
                "Please add minimum one Raw Material"
            );
        } else {
            $("#fproduct_id").removeClass("is-invalid");
            $("#fproduct_id")
                .closest("div")
                .find(".text-danger")
                .addClass("d-none");
        }

        if (status == false) {
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

    $(document).on("change", ".fproduct_id", function (e) {
        let params = $(this).find(":selected").val();
        let separate_params = params.split("|");
        let st_method = separate_params[1];
        $("#st_method").val(st_method);
        if (st_method === "none") {
            $(".none_method").hide();
        } else {
            $(".none_method").show();
        }
        if (st_method === "fifo") {
            $(".none_method").hide();
        }
        if (st_method === "fefo") {
            $(".fefo_method").hide();
        }
        if (st_method === "batchcontrol") {
            $(".batch_method").hide();
        }
    });

    $(document).on("change", "#manufactures", function (e) {
        let manufacture_type = $(this).find(":selected").val();

        if (manufacture_type == "fco") {
            let customers_hidden = $("#customers_hidden").html();

            $("#customer_order_area").html(
                '<div class="col-sm-12 col-md-6 mb-2 col-lg-4">' +
                    '<div class="form-group"><label>Customer <span class="required_star">*</span></label>' +
                    '<select class="form-control customer_id_c1 select2" name="customer_id" id="customer_id">\n' +
                    customers_hidden +
                    "</select></div></div>" +
                    '<div class="col-sm-12 col-md-6 mb-2 col-lg-4"><div class="form-group"><div id="customer_order_list"></div></div></div>'
            );

            $(".select2").select2();
            $("#productionstage_id").select2({
                dropdownParent: $("#productScheduling"),
            });
        } else {
            $("#customer_order_area").html("");
        }
    });

    $(document).on("change", "#customer_id", function (e) {
        let customer_id = $(this).find(":selected").val();
        let hidden_base_url = $("#hidden_base_url").val();

        $.ajax({
            type: "POST",
            url: hidden_base_url + "getCustomerOrderList",
            data: { id: customer_id },
            success: function (data) {
                $("#customer_order_list").html(data);
                $(".select2").select2();
                $("#productionstage_id").select2({
                    dropdownParent: $("#productScheduling"),
                });
            },
            error: function () {},
        });
    });

    $(document).on("change", "#customer_order_id", function (e) {
        let customer_order_id = $(this).find(":selected").val();
        let hidden_base_url = $("#hidden_base_url").val();

        $.ajax({
            type: "POST",
            url: hidden_base_url + "getCustomerOrderProducts",
            data: { id: customer_order_id },
            success: function (data) {
                $("#fproduct_id option").remove();
                $("#fproduct_id").append(data);
                $(".select2").select2();
                $("#productionstage_id").select2({
                    dropdownParent: $("#productScheduling"),
                });
            },
            error: function () {},
        });
    });

    setAttribute();
    cal_row();
    $(document).on("click", ".print_class", function (e) {
        window.print();
    });


    // Date Month Calculate
    $(document).on("change", "#month_limit", function (e) {
        let max = parseInt($(this).attr("max"));
        let min = parseInt($(this).attr("min"));
        if ($(this).val() > max) {
            $(this).val(max);
        } else if ($(this).val() < min) {
            $(this).val(min);
        }
        totalMonthDaysHourMinuteCalculate();
    });
    $(document).on("change", "#day_limit", function (e) {
        let max = parseInt($(this).attr("max"));
        let min = parseInt($(this).attr("min"));
        if ($(this).val() > max) {
            let month = $(this).closest("tr").find("#month_limit").val();
            month = month == "" ? 0 : month;
            let countedValue = parseInt($(this).val() / 30);
            let countedValue1 = parseInt($(this).val()) % 30;
            month = parseInt(month) + countedValue;
            $(this).closest("tr").find("#month_limit").val(month);
            $(this).val(countedValue1);
        } else if ($(this).val() < min) {
            $(this).val(min);
        }
        totalMonthDaysHourMinuteCalculate();
    });
    $(document).on("change", "#hours_limit", function (e) {
        let max = parseInt($(this).attr("max"));
        let min = parseInt($(this).attr("min"));
        if ($(this).val() > max) {
            let day = $(this).closest("tr").find("#day_limit").val();
            day = day == "" ? 0 : day;
            let countedValue = parseInt($(this).val() / 24);
            let countedValue1 = parseInt($(this).val()) % 24;
            day = parseInt(day) + countedValue;
            $(this).closest("tr").find("#day_limit").val(day);
            $(this).val(countedValue1);
        } else if ($(this).val() < min) {
            $(this).val(min);
        }
        totalMonthDaysHourMinuteCalculate();
    });
    $(document).on("change", "#minute_limit", function (e) {
        let max = parseInt($(this).attr("max"));
        let min = parseInt($(this).attr("min"));
        if ($(this).val() > max) {
            let hours = $(this).closest("tr").find("#hours_limit").val();
            hours = hours == "" ? 0 : hours;
            let countedValue = parseInt($(this).val() / 60);
            let countedValue1 = parseInt($(this).val()) % 60;
            hours = parseInt(hours) + countedValue;
            $(this).closest("tr").find("#hours_limit").val(hours);
            $(this).val(countedValue1);
        } else if ($(this).val() < min) {
            $(this).val(min);
        }

        totalMonthDaysHourMinuteCalculate();
    });

    function totalMonthDaysHourMinuteCalculate() {
        let totalMonth = 0;
        let totalDays = 0;
        let totalHours = 0;
        let totalMinutes = 0;

        $(".stage_name").each(function () {
            let month =
                parseInt($(this).closest("tr").find("#month_limit").val()) || 0;
            let day =
                parseInt($(this).closest("tr").find("#day_limit").val()) || 0;
            let hour =
                parseInt($(this).closest("tr").find("#hours_limit").val()) || 0;
            let minute =
                parseInt($(this).closest("tr").find("#minute_limit").val()) ||
                0;

            totalMonth += month;
            totalDays += day;
            totalHours += hour;
            totalMinutes += minute;
        });

        console.log(totalMonth, totalDays, totalHours, totalMinutes);

        $("#t_month").val(totalMonth);
        $("#t_day").val(totalDays);
        $("#t_hours").val(totalHours);
        $("#t_minute").val(totalMinutes);
    }

});
