$(window).scroll(function () {
  var sticky = $(".header"),
    scroll = $(window).scrollTop();

  if (scroll >= 100) sticky.addClass("fixed");
  else sticky.removeClass("fixed");
});
