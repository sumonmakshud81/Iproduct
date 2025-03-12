$(function () {
    "use strict";
    function checkParentActive() {
        $(".menu_class").each(function () {
            let this_parent_name = $(this).attr("data-name");
            if (
                $(".menu_activities_" + this_parent_name).length ==
                $(".menu_activities_" + this_parent_name + ":checked").length
            ) {
                $("#menu_" + this_parent_name).prop("checked", true);
            } else {
                $("#menu_" + this_parent_name).prop("checked", false);
            }
        });
    }
    $(document).on("click", "#select_all_role", function () {
        if ($(this).is(":checked")) {
            $(".menu_class").prop("checked", true);
            $(".activity_class").prop("checked", true);
        } else {
            $(".menu_class").prop("checked", false);
            $(".activity_class").prop("checked", false);
        }
        checkParentActive();
    });
    $(document).on("click", ".menu_class", function () {
        let data_id = $(this).attr("data-id");
        if ($(this).is(":checked")) {
            $(".menu_activities_" + data_id).prop("checked", true);
        } else {
            $(".menu_activities_" + data_id).prop("checked", false);
        }
        checkParentActive();
    });

    $(document).on("click", ".activity_class", function () {
        let menu_key = $(this).attr("data-id");
        if ($(this).is(":checked")) {
            $("#menu_" + menu_key).prop("checked", true);
        }
        checkParentActive();
    });
    checkParentActive();
});
