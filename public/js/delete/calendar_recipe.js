/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************************!*\
  !*** ./resources/js/delete/calendar_recipe.js ***!
  \************************************************/
$(document).ready(function () {
  $(document).on('click', "[data-delete-href]", function () {
    var url = $(this).data("delete-href");
    console.log(url);
    bootbox.confirm({
      title: 'Czy chcesz usunąć ten przepis?',
      message: "<div class=\"modal-icon\"><i class=\"far fa-trash-alt\"></i><span>Czy jesteś pewnien/na, że chcesz usunąć ten przepis?</span></div>",
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
                title: 'Przepis został usunięty',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-check text-success\"></i><span>Przepis został pomyślnie usunięty!</span></div>",
                callback: function callback(confirm) {
                  $(location).attr("href", '/calendar/show');
                }
              });
            },
            error: function error() {
              bootbox.alert({
                title: 'nie mozna usunac',
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