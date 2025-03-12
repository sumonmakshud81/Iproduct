$(function () {
    "use strict";
    //BAR CHART
    let bar = new Morris.Bar({
        element: 'operational_comparision',
        resize: true,
        data: [
    {y: delivered_order, a: Number(delivered_order_value)},
    {y: purchase_, a: Number(purchase_value)},
    {y: salary_, a: Number(salary_value)},
    {y: Deposit_, a: Number(Deposit_value)},
    {y: Withdraw_, a: Number(Withdraw_value)},
    {y: damage_, a: Number(damage_value)},
    {y: expense_, a: Number(expense_value)},
    {y: supp_pay_, a: Number(supp_pay_value)}
],
    barColors: [base_color, base_color],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Amount'],
        hideHover: 'auto'
});

    $('#low_stock_products, #top_ten_food_menu, #top_ten_customer, #customer_receivable, #supplier_payable').slimscroll({
        height: '220px'
    });

    let pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    let pieChart       = new Chart(pieChartCanvas);
    let PieData        = [
        {
            value    : delivered_order_value,
    color    : '#0fe300',
        highlight: base_color,
        label    : delivered_order
},
    {
        value    : new_order_value,
        color    : base_color,
            highlight: '#cdd2d2',
        label    : new_order_value
    },
    {
        value    : cancelled_order_value,
        color    : base_color,
            highlight: '#38d1ff',
        label    : cancelled_order
    }
];

    let pieOptions     = {
        // Boolean - Whether we should show a stroke on each segment
        segmentShowStroke    : true,
        // String - The colour of each segment stroke
        segmentStrokeColor   : '#fff',
        // Number - The width of each segment stroke
        segmentStrokeWidth   : 1,
        // Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        // Number - Amount of animation steps
        animationSteps       : 100,
        // String - Animation easing effect
        animationEasing      : 'easeOutBounce',
        // Boolean - Whether we animate the rotation of the Doughnut
        animateRotate        : true,
        // Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale         : false,
        // Boolean - whether to make the chart responsive to window resizing
        responsive           : true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio  : false,
        // String - A legend template
        legendTemplate       : '<ul class=\'<\x25=name.toLowerCase()\x25>-legend\'><\x25 for (let i=0; i<segments.length; i++){\x25><li><span style=\'background-color:<\x25=segments[i].fillColor\x25>\'></span><\x25if(segments[i].label){\x25><\x25=segments[i].label\x25><\x25}\x25></li><\x25}\x25></ul>',
        // String - A tooltip template
        tooltipTemplate      : '<\x25=value \x25> <\x25=label\x25>'
    };
    // Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
    // -----------------
    // - END PIE CHART -
    // -----------------



//call default 12 month
    selectMonth(12);
    $('#operational_coparision_range').on('click',function(){
        $('#operation_comparision_range_fields').show();
    });
    $('#operation_comparision_cancel').on('click',function(){
        $('#operation_comparision_range_fields').hide();
    })
    $('#operation_comparision_input').daterangepicker({
        opens: 'left',
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    /**
     * @description This function is used to select the month
     * @param {*} value 
     */
    function  selectMonth(value) {
        $.ajax({
            url: base_url+'Ajax/comparison_sale_report_ajax_get',
            type: 'get',
            datatype: 'json',
            data: {months: value, get_csrf_token_name: get_csrf_hash},
            success: function (response) {
                let json = $.parseJSON(response);
                google.charts.load('current', {'packages':['corechart', 'bar']});
                google.charts.setOnLoadCallback(drawStuff);
                function drawStuff() {
                    let chartDiv = document.getElementById('chart_div');

                    let data = '';
                    let dataArray = [];
                    let dataArrayValue = [];
                    dataArrayValue = [];
                    dataArrayValue.push('');
                    dataArrayValue.push('');
                    dataArray.push(dataArrayValue);

                    $.each(json, function (i, v) {
                        window['monthName'+i]=v.month;
                        window['collection'+i]=v.saleAmount;
                        dataArrayValue = [];
                        dataArrayValue.push(v.month);
                        dataArrayValue.push(v.saleAmount);
                        dataArray.push(dataArrayValue);
                    });
                    data = google.visualization.arrayToDataTable(dataArray);
                    let options = {
                        legend: { position: "none" },
                        colors: ['#00a65a', '#00a65a', '#00a65a'],
                        axes: {
                            y: {
                                all: {
                                    format: {
                                        pattern: 'decimal'
                                    }
                                }
                            }
                        },
                        series: {
                            0: { axis: '0' }
                        }
                    };

                    function drawMaterialChart() {
                        let materialChart = new google.charts.Bar(chartDiv);
                        materialChart.draw(data,options);
                    }
                    function drawClassicChart() {
                        let classicChart = new google.visualization.ColumnChart(chartDiv);
                        classicChart.draw(data, classicOptions);

                    }
                    drawMaterialChart();
                }
            }
        });

    }
});
