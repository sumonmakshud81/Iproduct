(function ($) {
    "use strict";
    //App Url
    let app_url = $('input[name="app-url"]').attr("data-app_url");
    let auth_id = localStorage.getItem("auth_id");
    $(".select2").select2();
    $(".select_multiple").val("placeholder").trigger("change");

    $(document).on("click", ".close_common_image_modal", function (e) {
        closeModal("commonImage");
    });

    $(document).on("click", ".open_common_image_modal", function (e) {
        openModal("commonImage");
    });

    let tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    let forms = document.querySelectorAll(".needs-validation");
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener(
            "submit",
            function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add("was-validated");
            },
            false
        );
    });

    $(document).on("click", ".notification_bell_icon_", function () {
        $(".loader_notification_div").show();
        $("#all_notifications_show_div").html("");
        loadNotification(1);
    });

    $(".load-more-notification").on("click", function (e) {
        e.preventDefault();
        $(".loader_notification_div").show();
        $("#all_notifications_show_div").hide();
        let nextPage = $(".notification_element").length / 10 + 1;
        loadNotification(nextPage);
    });

    let isLoading = false;

    /**
     * @description Load Notification
     * @param {*} page
     */
    function loadNotification(page) {
        $.ajax({
            url: app_url + "/get-all-unread-notification",
            method: "GET",
            data: { page: page },
            success: function (response) {
                $(".loader_notification_div").hide();
                // Process the data
                let data = response.data;
                let div_data = "";
                if (data.length > 0) {
                    $("#all_notifications_show_div").show();
                    $(data).each(function (index, value) {
                        div_data += `<li class="list-group-item list-group-item-action dropdown-notifications-item notification_element ${value.bg_color}" id="notification-id-${value.id}">
                        <div class="d-flex">
                          <div class="flex-grow-1">
                            <h6 class="mb-1">${value.ticket_title}</h6>
                            <p class="mb-0">
                              <a class="single-notification" href="javascript:void(0)" data-link="${value.details_link}" data-id="${value.id}">${value.message}</a>
                            </p>
                            
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-archive remove-single-notification" data-id="${value.id}"
                              ><span class="fa fa-times"></span
                            ></a>
                          </div>
                        </div>
                      </li>`;
                    });
                    $("#all_notifications_show_div").append(div_data);
                } else {
                    if (
                        page == 1 &&
                        $(".remove-single-notification").length == 0
                    ) {
                        let message = $("#no_data_found").val();

                        div_data = `<li class="list-group-item list-group-item-action dropdown-notifications-item notification_element>
                            <div class="d-flex">
                              <span class="alert alert-danger">${message}</span>
                            </div>
                          </li>`;
                        $("#all_notifications_show_div").html(div_data);
                        $(".dropdown-menu-footer").addClass("d-none");
                    }
                    $("#all_notifications_show_div").show();
                }
            },
            error: function (xhr, status, error) {},
        });
    }

    // Flag to prevent multiple simultaneous requests
    $("#all_notifications_show_div").on("scroll", function (e) {
        e.preventDefault();
        let element = $(this);
        let scrollTop = element.scrollTop();
        let scrollHeight = element.prop("scrollHeight");
        let clientHeight = element.prop("clientHeight");

        if (scrollTop + clientHeight >= scrollHeight - 1 && !isLoading) {
            isLoading = true;
            let nextPage = $(".notification_element").length / 10 + 1;
            if (isLoading) {
                $(".loader_notification_div").show();
                $("#all_notifications_show_div").hide();
                loadNotification(nextPage);
            }
            isLoading = false;
        }
    });

    $(document).on("click", ".remove-single-notification", function () {
        let id = $(this).attr("data-id");
        $.ajax({
            url: app_url + "/delete-notification/" + id,
            method: "DELETE",
            success: function (response) {
                if (response.status) {
                    $("#notification-id-" + id).remove();
                    let notification_div = $(".user-notification_" + auth_id);
                    if (notification_div.text() > 0) {
                        notification_div.text(response.unread_count);
                    }
                    toastr.success($("#delete_message").val());
                }
            },
        });
    });

    $(document).on("click", ".single-notification", function () {
        let link = $(this).attr("data-link");
        let id = $(this).attr("data-id");
        $.ajax({
            url: app_url + "/mark-as-read/" + id,
            method: "GET",
            success: function (response) {
                if (response.status) {
                    if (link) {
                        location.href = link;
                    }
                }
            },
        });
        return;
    });

    $(document).on("click", ".mark-all-as-read", function () {
        $.ajax({
            url: app_url + "/mark-as-read-all",
            method: "GET",
            success: function (response) {
                if (response.status) {
                    $(".notification_element").removeClass("bg-unseen");
                    $(".user-notification_" + auth_id).text(0);
                    toastr.success($("#update_message").val());
                }
            },
        });
    });

    $(document).on("click", ".delete-all-notification", function () {
        Swal.fire({
            title: $("#are_you_sure").val(),
            text: $("#confirm_delete_all_notification").val(),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: $("#yes_delete_it").val(),
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: app_url + "/delete-all-notification",
                    method: "DELETE",
                    success: function (response) {
                        if (response.status) {
                            $("#all_notifications_show_div").empty();
                            toastr.success($("#delete_message").val());
                            $(".notification_element").removeClass("bg-unseen");
                            $(".user-notification_" + auth_id).text(0);
                        }
                    },
                });
            }
        });
    });

    $(".set_collapse iconify-icon").on("click", function () {
        let status = $(".set_collapse").attr("data-status");
        let status_tmp = "";
        if (status == 1) {
            $(".set_collapse").attr("data-status", 0);
            status_tmp = "No";
        } else {
            $(".set_collapse").attr("data-status", 1);
            status_tmp = "Yes";
        }
        console.log(status, status_tmp);
        let dashboardWrapper = $(".dashboard-wrapper");
        if (dashboardWrapper.hasClass("sidebar-collapse-dashboard")) {
            dashboardWrapper.removeClass("sidebar-collapse-dashboard");
        } else {
            dashboardWrapper.addClass("sidebar-collapse-dashboard");
        }
        let base_url = $("#hidden_base_url").val();
        $.ajax({
            url: base_url + "set-collapse",
            method: "GET",
            data: { status: status_tmp },
            success: function (response) {
                console.log(response);
            },
        });       
    });

    // Set active menu
    let url_segment = $("#segment-fetcher").attr("data-id");
    let current_url = window.location.href;

    $(".child-menu").each(function (index, value) {
        let segment_1 = value.href.split("/");
        let full_url = value.href;
        if (current_url == full_url) {
            $(this).parent().addClass("activated");
        }
        if (segment_1.includes(url_segment)) {
            let element = value.closest(".parent-menu");
            let classList = element.className;
            if (classList.indexOf("treeview") !== -1) {
                element.className = "parent-menu active treeview fetch-active";
            } else {
                element.className = "parent-menu active fetch-active";
            }
        }
    });

    $("#menu-search").keyup(function (event) {
        if (event.keyCode === 13) {
            $(".parent-menu")
                .removeClass("d-none active menu-open")
                .find(".fa")
                .removeClass("color-white");
            $(".fetch-active")
                .addClass("active menu-open")
                .find(".fa")
                .addClass("color-white");
        }
    });

    $("#menu-search").on("search", function () {
        $(".parent-menu")
            .removeClass("d-none active menu-open")
            .find(".fa")
            .removeClass("color-white");
        $(".fetch-active")
            .addClass("active menu-open")
            .find(".fa")
            .addClass("color-white");
    });

    $(document).on("keyup", "#menu-search", function () {
        let str_search = $("#menu-search").val();
        if (str_search.length > 1) {
            $(".parent-menu").each(function (i, obj) {
                let status_exist = false;
                $(this)
                    .find(".match_bold")
                    .each(function (i, obj) {
                        let child_menu = $(this).text().toLowerCase();
                        if (child_menu.includes(str_search.toLowerCase())) {
                            status_exist = true;
                        }
                    });

                if (status_exist) {
                    $(".parent-menu").addClass("d-none");
                    $(this)
                        .addClass("active menu-open")
                        .removeClass("d-none")
                        .find(".fa")
                        .addClass("color-white");
                    matchingBold(str_search);
                } else {
                    $(".parent-menu").remove("d-none");
                    $(this).removeClass("active menu-open");
                    $(this).find(".fa").removeClass("color-white");
                }
            });
        } else {
            $(".parent-menu")
                .removeClass("d-none active menu-open")
                .find(".fa")
                .removeClass("color-white");
            $(".fetch-active")
                .addClass("active menu-open")
                .find(".fa")
                .addClass("color-white");
            $(".match_bold").each(function () {
                let text = $(this).text();
                $(this).text(text);
            });
            //set old view menu
            $(".child-menu").each(function (index, value) {
                let segment_1 = value.href.split("/");
                if (segment_1.includes(url_segment)) {
                    let element = value.closest(".parent-menu");
                    let classList = element.className;
                    if (classList.indexOf("treeview") !== -1) {
                        element.className =
                            "parent-menu active treeview fetch-active";
                    } else {
                        element.className = "parent-menu active fetch-active";
                    }
                    $(".parent-menu.active")
                        .find(".fa")
                        .addClass("color-white");
                    return false;
                }
            });
        }
    });

    /**
     * @description Matching Bold Text
     * @param {*} target_string
     */
    function matchingBold(target_string) {
        $(".match_bold").each(function () {
            let text = $(this).text();
            // Replace the search term with the same term wrapped in a bold tag
            let highlightedText = text.replace(
                new RegExp(target_string, "gi"),
                "<b>$&</b>"
            );
            $(this).html(highlightedText);
        });
    }

    $(document).on("submit", "#common-form", function () {
        showSpin("spinner", "submit-btn");
    });
    

    $(document).on("click", ".treeview", function(){
        $(".treeview-menu").addCss('display', 'block');
    })

    /**
     * Logout Tigger
     */
    $(document).on("click", ".logOutTrigger", function () {
        $("#logout-form").submit();
    });
})(jQuery);
