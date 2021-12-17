/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************************!*\
  !*** ./resources/js/delete/recipe_category.js ***!
  \************************************************/
$(document).ready(function () {
  $(document).on('click', "[data-delete-href]", function () {
    var url = $(this).data("delete-href");
    console.log(url);
    bootbox.confirm({
      title: 'title',
      message: "<div class=\"modal-icon\"><i class=\"far fa-trash-alt\"></i><span>Czy chcesz usun\u0105\u0107 kategori\u0119 przepisu?</span></div>",
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
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            type: 'DELETE',
            success: function success(result) {
              bootbox.alert({
                title: 'Kategoria przepisu usunieta',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-check text-success\"></i><span>Usunieto</span></div>",
                callback: function callback(confirm) {
                  $(location).attr("href", '/dashboard/recipe_category/index');
                }
              });
            },
            error: function error() {
              bootbox.alert({
                title: 'Nie mozna usunac',
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