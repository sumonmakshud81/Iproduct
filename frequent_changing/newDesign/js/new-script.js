(function ($) {
  "use strict";
  const win = $(window);
  const bodyElement = $("body");
  /**
   * Perfect Scrollbar
   */
  const ps = new PerfectScrollbar(".sidebar-menu", {
    wheelSpeed: 2,
    wheelPropagation: true,
    minScrollbarLength: 20,
  });

  ps.update();

  /**
   * Active ToolTips
   */
  let tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  /***
   * Hide Preloader
   */

  win.on("load", () => {
    setTimeout(() => {
      $(".main-preloader").fadeOut(500);
    }, 500);
  });

  /**
   * Click to show Menus for mobile devices
   */
  bodyElement.on("click", ".om", function () {
    bodyElement.find(".screen-list").addClass("active");
  });
  $(document).on("click", function (event) {
    if ($(event.target).closest(".om").length === 0) {
      bodyElement.find(".screen-list").removeClass("active");
    }
  });
})(jQuery);
