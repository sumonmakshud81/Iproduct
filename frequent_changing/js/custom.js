"use strict";
let today = new Date();
let dd = today.getDate();
let mm = today.getMonth() + 1;
let yyyy = today.getFullYear();

if (dd < 10) {
    dd = "0" + dd;
}

if (mm < 10) {
    mm = "0" + mm;
}
today = yyyy + "-" + mm + "-" + dd;

//initial select2
$(".select2").select2();
$(".select_multiple").select2({
    multiple: true,
    placeholder: "Select",
    allowClear: true,
});
$(".select_multiple").val("placeholder").trigger("change");

$(".customDatepickerCustom").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    endDate: "+0d",
});
//Date picker
$("#date").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true,
});
$("#dates2").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
});

$(".customDatepicker").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true,
});

$(".customDatepicker1").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
});
$(".datepicker_months").datepicker({
    format: "yyyy-M",
    autoclose: true,
    viewMode: "months",
    minViewMode: "months",
});

feather.replace();
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: "icheckbox_minimal-blue",
    radioClass: "iradio_minimal-blue",
});

$(document).ready(function () {
    $(".menu-open").click(function (e) {
        // Toggle the visibility of the inner UL with animation
        $(this).children(".treeview-menu").slideToggle();
    });
});
