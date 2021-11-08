/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************!*\
  !*** ./resources/js/likes.js ***!
  \*******************************/
$(document).ready(function () {
  $('.fa-heart').on('click', function () {
    if ($(this)[0].style['color'] == "grey") {
      $(this).css("color", "red");
      liked($(this).attr("data-url"), $(this));
    } else {
      $(this).css("color", "grey");
      liked($(this).attr("data-url"), $(this));
    }
  });
});

function liked(url, obj) {
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: url,
    type: 'POST',
    success: function success(result) {},
    error: function error(result) {
      bootbox.alert({
        title: "Uwaga",
        message: "<div class=\"modal-icon\"><span>Polubienie przepisu nie powid\u0142o si\u0119!</span></div>",
        centerVertical: true
      });
    }
  });
}
/******/ })()
;