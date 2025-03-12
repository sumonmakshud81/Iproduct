$(function () {
    $("#datatable").DataTable({
        autoWidth: true,
        ordering: true,
        order: [[0, "desc"]],
        dom: '<"top-left-item col-sm-12 col-md-6"lf> <"top-right-item col-sm-12 col-md-6"B> t <"bottom-left-item col-12 col-lg-6 "i><"bottom-right-item col-12 col-lg-6 "p>',
        buttons: [
            {
                extend: "print",
                text: '<i class="fa fa-print"></i> Print',
                titleAttr: "print",
            },
            {
                extend: "copyHtml5",
                text: '<i class="fa fa-files-o"></i> Copy',
                titleAttr: "Copy",
            },
            {
                extend: "excelHtml5",
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                titleAttr: "Excel",
            },
            {
                extend: "csvHtml5",
                text: '<i class="fa fa-file-text-o"></i> CSV',
                titleAttr: "CSV",
            },
            {
                extend: "pdfHtml5",
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                titleAttr: "PDF",
            },
        ],
        language: {
            paginate: {
                previous: "<i class='fa fa-chevron-left'></i>",
                next: "<i class='fa fa-chevron-right'></i>",
            },
        },
    });

    $(".select2").select2();

    
});
