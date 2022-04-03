/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/assign_recipe.js ***!
  \***************************************/
$(document).ready(function () {
  $(document).on('click', "[data-assign-href]", function () {
    var url = $(this).data("assign-href");
    var object = $(this);
    bootbox.confirm({
      title: 'title',
      message: "<div class=\"modal-icon\"><i class=\"far fa-trash-alt\"></i><span>Czy chcesz oznaczy\u0107 przepis jako ugotowany?</span></div>",
      buttons: {
        confirm: {
          label: "<i class=\"fa fa-check mr-1\"></i> Oznacz jako ugotowany",
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
            type: 'POST',
            success: function success(result) {
              $(object[0].children[0]).addClass('fas');
              $(object[0].children[0]).removeClass('far');
              bootbox.alert({
                title: 'Przepis oznaczono jako ugotowany!',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-check text-success\"></i><span>Oznaczono jako ugotowany!</span></div>",
                callback: function callback(confirm) {
                  $(location).attr("href", '/calendar/show');
                }
              });
            },
            error: function error() {
              bootbox.alert({
                title: 'Nie można oznaczyć przepisu jako ugotowany!',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-times text-danger\"></i><span>W spi\u017Carni brakuje ilo\u015Bci pewnych produkt\xF3w do wykonania przepisu! Uzupe\u0142nij je w celu oznaczenia przepisu!</span></div>"
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