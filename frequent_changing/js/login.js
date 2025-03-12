

jQuery(document).ready(function($) {
    "use strict";
    $(".login_btn_click").on("click", function () {
        $("#email").val("admin@doorsoft.co");
        $("#password").val("123456");
        $(".login-button").click();
    });
});