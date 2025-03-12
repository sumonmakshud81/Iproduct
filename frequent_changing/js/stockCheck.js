$(document).ready(function() {
            "use strict";
            $(".productModal").select2({
                dropdownParent: $("#multipleProductModal"),
            });
            $("#finish_p_id").select2({
                dropdownParent: $("#filterModal"),
            });
            $("#manufacture_id").select2({
                dropdownParent: $("#filterModal"),
            });
        });