
let jqry = $.noConflict();
jqry(document).ready(function(){
    "use strict";

    //use for every report view
    let today = new Date();
    let dd = today.getDate();
    let mm = today.getMonth()+1; 
    let yyyy = today.getFullYear();

    if(dd<10) {
        dd = '0'+dd
    }

    if(mm<10) {
        mm = '0'+mm
    }
    today = yyyy + '-' + mm + '-' + dd;

    //get title and datatable id name from hidden input filed that is before in the table in view page for every datatable
    let datatable_name = $(".datatable_name").attr('data-id_name');
    let title = $(".datatable_name").attr('data-title');
    let print_db = $("#print_db").val();
    let excel_db = $("#excel_db").val();
    let pdf_db = $("#pdf_db").val();
    let TITLE = title+ ", "+today;
    jqry("#" + datatable_name).DataTable({
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
});