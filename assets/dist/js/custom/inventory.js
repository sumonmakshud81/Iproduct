$(function () {
  var TITLE = "Inventory Report " + today;

  $("#datatable").DataTable({
    autoWidth: false,
    ordering: false,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "print",
        title: TITLE,
      },
      {
        extend: "excelHtml5",
        title: TITLE,
      },
      {
        extend: "pdfHtml5",
        title: TITLE,
      },
    ],
  });
});
