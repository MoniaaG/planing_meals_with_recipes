/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************************!*\
  !*** ./resources/js/delete/calendar_recipe.js ***!
  \************************************************/
$(document).ready(function () {
  $(document).on('click', "[data-delete-href]", function () {
    var url = $(this).data("delete-href");
    bootbox.confirm({
      title: 'title',
      message: "<div class=\"modal-icon\"><i class=\"far fa-trash-alt\"></i><span>confitm text</span></div>",
      buttons: {
        confirm: {
          label: "<i class=\"fa fa-check mr-1\"></i> usu\u0144",
          className: 'btn-danger'
        },
        cancel: {
          label: "<i class=\"fa fa-times mr-1\"></i> zamknij",
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
                title: 'przepis usuniety',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-check text-success\"></i><span>usunieto</span></div>",
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