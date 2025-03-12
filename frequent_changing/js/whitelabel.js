$(document).ready(function () {
    "use strict";
    let input = document.getElementById('logo');
    let hidden_alert = $("#hidden_alert").val();
    let hidden_ok = $("#hidden_ok").val();
    input.addEventListener("change", function () {
        let file = this.files[0];
        let img = new Image();
        //call on load
        img.onload = function () {
            URL.revokeObjectURL(this.src);
            //calculate image size
            let size = Math.round(Number(file.size) / 1024);
            //get width
            let width = Number(this.width);
            //get height
            let height = Number(this.height);
            if (width == 230 && height == 50) {
                if (Number(size) > 1024) {
                    $("#logo").val('');
                    swal({
                        title: hidden_alert,
                        text: "Please select max size: 1MB",
                        confirmButtonText: hidden_ok,
                    });
                }
            } else {
                $("#logo").val('');
                swal({
                    title: hidden_alert,
                    text: "Please select (230px X 50px, jpeg,jpg,png, 1MB) logo",
                    confirmButtonText: hidden_ok,
                });
            }
        }

        let objectURL = URL.createObjectURL(file);
        img.src = objectURL;
    });

    //preview the selected images
    $(document).on('click', '.show_preview', function (e) {
        let file_path = $(this).attr('data-file_path');
        e.preventDefault();
        $("#show_id").attr('src', file_path);
        $("#show_id").css('width', "unset");
        $("#logo_preview").modal("show");

    });
});