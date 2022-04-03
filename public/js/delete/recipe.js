/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/delete/recipe.js ***!
  \***************************************/
$(document).ready(function () {
  $(document).on('click', "[data-delete-href]", function () {
    var url = $(this).data("delete-href");
    console.log(url);
    bootbox.confirm({
      title: 'title',
      message: "<div class=\"modal-icon\"><i class=\"far fa-trash-alt\"></i><span>Czy chcesz usun\u0105\u0107 ten przepis? To dzia\u0142anie trwale usunie nawet zaplanowane w Twoim kalendarzu pomys\u0142y na dania! </span></div>",
      buttons: {
        confirm: {
          label: "<i class=\"fa fa-check mr-1\"></i> Usu\u0144",
          className: 'btn-danger'
        },
        cancel: {
          label: "<i class=\"fa fa-times mr-1\"></i> Zamknij",
          className: 'btn-success'
        }
      },
      callback: function callback(confirm) {
        if (confirm) {
          $.ajax({
            url: url,
            type: 'DELETE',
            success: function success(result) {
              bootbox.alert({
                title: 'Przepis został usunięty!',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-check text-success\"></i><span>Usuni\u0119to</span></div>",
                callback: function callback(confirm) {
                  $(location).attr("href", '/recipe/all');
                }
              });
            },
            error: function error() {
              bootbox.alert({
                title: 'Nie można usunąć przepisu!',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-times text-danger\"></i><span></span></div>"
              });
            }
          });
        }
      }
    });
  });
});
/******/ })()
;