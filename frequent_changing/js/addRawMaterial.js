$(document).ready(function () {
    "use strict";
    $(".field_clear").change(function () {
        let is_checked = $(this).is(":checked");
        if (!is_checked) {
            $(".crempty").val("");
        }
        $(".crempty").prop("readonly", !is_checked);
    });
    $(document).on("keyup", ".consumption", function () {
        let rate_per_unit = $("#rate_per_unit").val();
        let conversion_rate = $("#conversion_rate").val();
        if (rate_per_unit == "" || rate_per_unit == 0) {
            rate_per_unit = 0;
        }
        if (conversion_rate == "" || conversion_rate == 0) {
            conversion_rate = 1;
        }
        let total_amount = rate_per_unit / conversion_rate;
        $("#rate_per_consumption_unit").val(total_amount.toFixed(2));
    });

    /**
     * @description This function is used to change the value of consumption div
     */
    function valueChanged() {
        if ($(".consumption_check").is(":checked")) {
            $("#consumption_div").removeClass("d-none");
            let selectedUnit = $("#consumption_unit")
                .children("option:selected")
                .text();
            let selectedUnitValue = $("#consumption_unit").val();
            if (selectedUnitValue) {
                $(".opening_stock_unit").text(selectedUnit);
            }
        } else {
            $("#consumption_div").addClass("d-none");
            let selectedUnit = $("#unit").children("option:selected").text();
            if (selectedUnit) {
                $(".opening_stock_unit").text(selectedUnit);
            }
        }
    }

    $(document).on("click", ".consumption_check", function () {
        valueChanged();
    });

    $(document).on("change", ".field_clear", function () {
        let is_checked = $(this).is(":checked");
        if (!is_checked) {
            $(".crempty").val("");
        }
        $(".crempty").prop("readonly", !is_checked);
    });

    $(document).on("change", "select.con_unit", function () {
        let selectedUnitStock = $(this).children("option:selected").text();
        $("#select_unit_stock").text(selectedUnitStock);
        $("#select_unit_alert").text(selectedUnitStock);
    });

    $(document).on("change", "#unit", function () {
        let selectedUnit = $(this).children("option:selected").text();
        $(".opening_stock_unit").text(selectedUnit);
    });

    $(document).on("change", "#consumption_unit", function () {
        let selectedUnit = $(this).children("option:selected").text();
        $(".opening_stock_unit").text(selectedUnit);
    });
});
