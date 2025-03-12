$(document).ready(function () {
  $(".right__sidebar__toggle").on("click", function () {
    $(".main-header").toggleClass("active_sidebar");
    $(".main-sidebar2").toggleClass("active_sidebar");
    $(".sidebar2_logo").find(".logo__mini").toggle(0);
    $(".sidebar2_logo").find(".logo__lg").toggle(0);
  });

  const thisForArabic = () => {
    if ($(".content-header").hasClass("dashboard_content_header")) {
      console.log("find it");
    } else {
      $(".content-header").addClass("lang_arabic");
    }
  };
 /*  thisForArabic();*/
});
