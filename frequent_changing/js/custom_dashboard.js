(function ($) {
    "use strict";
    let base_url = $('input[name="hidden_base_url"]').val();
    let url_prefix = $("#url_prefix").val();
    let six_month_text = $("#six_month_value").val();
    let one_year_text = $("#one_year_value").val();
    let purchase_text = $("#purchase_text_value").val();
    let supplier_due_payment_text = $("#supplier_due_payment_text_value").val();
    let non_inventory_cost_text = $("#non_inventory_cost_text_value").val();
    let sale_text = $("#sale_text_value").val();
    let customer_due_received_text = $(
        "#customer_due_received_text_value"
    ).val();
    let expense_text = $("#expense_text_value").val();
    let payroll_text = $("#payroll_text_value").val();
    let moneyFlowChart = null;

    $(document).on("change", "#filter_chart_month", function () {
        let month = $(this).val();
        if (moneyFlowChart != null) {
            moneyFlowChart.destroy();
        }
        moneyFlowData(month);

        if (month == 6) {
            $("#month_span").html(six_month_text);
        } else {
            $("#month_span").html(one_year_text);
        }
    });
    moneyFlowData();
    /**
     * @description This function is used to get the money flow data
     * @param {*} month
     */
    function moneyFlowData(month = 6) {
        $(document).ready(function () {
            $.ajax({
                method: "GET",
                async: false,
                url: base_url + "money-flow",
                data: {
                    month: month,
                },
                success: function (response) {
                    const labels = response.months;
                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: purchase_text,
                                data: response.purchase,
                                borderColor: "#8b5cf6",
                                backgroundColor: "#8b5cf6",
                            },
                            {
                                label: supplier_due_payment_text,
                                data: response.supplierDuePayment,
                                borderColor: "#976cf7",
                                backgroundColor: "#976cf7",
                            },
                            {
                                label: non_inventory_cost_text,
                                data: response.nonInventoryCost,
                                borderColor: "#a27df8",
                                backgroundColor: "#a27df8",
                            },
                            {
                                label: sale_text,
                                data: response.sale,
                                borderColor: "#ae8df9",
                                backgroundColor: "#ae8df9",
                            },
                            {
                                label: customer_due_received_text,
                                data: response.customerDueReceive,
                                borderColor: "#b99dfa",
                                backgroundColor: "#b99dfa",
                            },
                            {
                                label: expense_text,
                                data: response.expense,
                                borderColor: "#c5aefb",
                                backgroundColor: "#c5aefb",
                            },
                            {
                                label: payroll_text,
                                data: response.payroll,
                                borderColor: "#d1befb",
                                backgroundColor: "#d1befb",
                            },
                        ],
                    };
                    const config = {
                        type: "bar",
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: "top",
                                },
                                title: {
                                    display: false,
                                    text: "Money Flow Comparison",
                                },
                            },
                        },
                    };
                    moneyFlowChart = new Chart(
                        document.getElementById("dashboardGraph"),
                        config
                    );
                },
            });
            let chartHeight = $(".graph_card").height();
            chartHeight = chartHeight - 60;
            $("#balanceGraph").css("height", chartHeight);
        });
    }

    window.onload = function () {
        let chartHeight = $(".graph_card").height();
        let graphHeight =
            chartHeight > 500 ? chartHeight - 200 : chartHeight - 100;
        $.ajax({
            method: "GET",
            async: false,
            url: base_url + "balance-by-account",
            success: function (response) {
                const result = response.accounts.map((account, index) => {
                    return {
                        y: response.balance[index],
                        label: account,
                    };
                });
                console.log(result);
                let chart = new CanvasJS.Chart("balanceGraph", {
                    theme: "light2",
                    exportEnabled: false,
                    animationEnabled: true,
                    height: graphHeight,
                    data: [
                        {
                            type: "pie",
                            startAngle: 25,
                            toolTipContent: "<b>{label}</b>: {y}$",
                            showInLegend: "false",
                            legendText: "{label}",
                            indexLabelFontSize: 14,
                            indexLabel: "{label}",
                            dataPoints: result,
                        },
                    ],
                });
                chart.render();
            },
        });
    };

    /**
     * @description This function is used to convert object to array
     * @param {*} obj
     * @returns
     */
    function convertToArray(obj) {
        return Object.keys(obj).map(function (key) {
            return obj[key] == null ? "Data" : obj[key];
        });
    }
    let print_db = $("#print_db").val();
    let excel_db = $("#excel_db").val();
    let pdf_db = $("#pdf_db").val();
    let title = $(".datatable_name").attr("data-title");
    let TITLE = title + ", " + today;
    $(".datatable_dashboard").DataTable({
        autoWidth: false,
        ordering: false,
        dom: "Bfrtip",
        buttons: [
            {
                extend: "print",
                text:
                    '<span style="display: flex; align-items-center; gap: 8px;"><iconify-icon icon="solar:printer-broken" width="16"></iconify-icon> ' +
                    print_db +
                    "</span>",
                title: TITLE,
                exportOptions: {
                    columns: ":visible:not(.not-export-col)",
                },
            },
            {
                extend: "excelHtml5",
                text:
                    '<span style="display: flex; align-items-center; gap: 8px;"><iconify-icon icon="icon-park-solid:excel" width="16"></iconify-icon> ' +
                    excel_db +
                    "</span>",
                title: TITLE,
                exportOptions: {
                    columns: ":visible:not(.not-export-col)",
                },
            },
            {
                extend: "pdfHtml5",
                text:
                    '<span style="display: flex; align-items-center; gap: 8px;"><iconify-icon icon="teenyicons:pdf-outline" width="16"></iconify-icon> ' +
                    pdf_db +
                    "</span>",
                title: TITLE,
                exportOptions: {
                    columns: ":visible:not(.not-export-col)",
                },
            },
        ],
    });
})(jQuery);
