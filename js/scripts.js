$(window).scroll(function () {
  var sticky = $(".header"),
    scroll = $(window).scrollTop();

  if (scroll >= 100) sticky.addClass("fixed");
  else sticky.removeClass("fixed");
});

$(document).ready(function () {
  // При изменении положения ползунка
  $('input[type="range"]').on("input", function () {
    var value = $(this).val();
    $(this).next(".slider-value").text(value);
  });
});
