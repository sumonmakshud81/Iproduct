(function ($) {
    "use strict";
    const bodyElement = $("body");
    /**
     * Get All Export Button And Append Another Location
     */
    setTimeout(() => {
        bodyElement
            .find(".dt-buttons")
            .wrap(
                '<div class="left-btn-box"><div class="exportDropdown"></div></div>'
            );
        $(document)
            .find(".dataTables_filter")
            .wrap('<div class="right-btn-box"></div>');
        /**
         * Append Export Dropdown Btn And Delete Button
         */
        $(document)
            .find(".exportDropdown")
            .prepend(
                `<a href="javascript:void(0)" type="button" class="btn toggleBtn parent_btn">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share font-small-4 me-50"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg> Export
             <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="6 9 12 15 18 9"></polyline></svg>
         </a>`
            );

        /**
         * Append Add New Button And Filter by Button
         */
        let filter_status = bodyElement
            .find(".datatable_name")
            .attr("data-filter");
        if (filter_status === "yes") {
            $(document).find(".left-btn-box").prepend(`
        <a href="javascript:void(0)" type="button" data-bs-toggle="modal" data-bs-target="#filterModal" class="m-right btn toggleBtn parent_btn dataFilterBy">
        <svg class="m-right" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg> Filter By
          </a>
        `);
        }

        justWorkForTable();
    }, 300);

    function justWorkForTable() {
        /**
         * Click to show Export Dropdown
         */
        bodyElement.on("click", ".toggleBtn", function () {
            $(this).next(".dt-buttons").toggleClass("active");
        });

        $(window).on("click", function (e) {
            if ($(e.target).closest(".exportDropdown").length === 0) {
                $(document).find(".dt-buttons").removeClass("active");
            }
        });
        /**
         * Append search icon on search field
         */

        bodyElement
            .find(".dataTables_filter label")
            .prepend(
                `<svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>`
            );
        bodyElement
            .find(".dataTables_filter label")
            .find("input")
            .attr("placeholder", "Search Here");
    }

    /**
     * Filter Modal Open and CLose
     */
    $(document).on("click", ".dataFilterBy", function () {
        bodyElement.find(".filter-modal").addClass("active");
        bodyElement.find(".filter-overlay").fadeIn();
    });
    bodyElement.on("click", ".close-filter-modal", function () {
        $(this).parent().parent().parent().removeClass("active");
        bodyElement.find(".filter-overlay").fadeOut();
    });
    bodyElement.on("click", ".filter-overlay", function () {
        bodyElement.find(".filter-modal").removeClass("active");
        $(this).fadeOut();
    });

    /*get button html*/
    let btn_list = $(".btn_list");
    setTimeout(() => {
        /**
         * Append Add New Button And Filter by Button
         */
        $(document).find(".top-right-item").prepend(btn_list);
        // bodyElement.find(".dataTables_length label").find("select").niceSelect();

        bodyElement
            .find("#datatable_filter")
            .find("input")
            .on("focus", function () {
                $(this).parent().addClass("active");
            })
            .on("blur", function () {
                $(this).parent().removeClass("active");
            });
    }, 500);

    /**
     * Nice Select
     */
})(jQuery);
