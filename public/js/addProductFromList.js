/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************!*\
  !*** ./resources/js/addProductFromList.js ***!
  \********************************************/
$(document).ready(function () {
  $('.btn_add').on('click', function (e) {
    e.preventDefault();
    var url = $(this).data("href");
    var key = $(this)[0]['id'].slice(8);
    var keyid = "products[".concat(key, "][id]");
    var id = $("input[name='" + keyid + "']").val();
    var keyqu = "products[".concat(key, "][quantity]");
    var quantity = $("input[name='" + keyqu + "']").val();
    product = [];
    product['id'] = id;
    product['quantity'] = quantity;
    console.log(product);
    bootbox.confirm({
      title: 'title',
      message: "<div class=\"modal-icon\"><i class=\"far fa-trash-alt\"></i><span>Czy chcesz doda\u0107 ten produkt do spi\u017Carni?</span></div>",
      buttons: {
        confirm: {
          label: "<i class=\"fa fa-check mr-1\"></i> Dodaj",
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
            dataType: "json",
            data: {
              products: {
                0: {
                  id: product['id'],
                  quantity: product['quantity']
                }
              }
            },
            success: function success(result) {
              bootbox.alert({
                title: 'Produkt dodany',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-check text-success\"></i><span>Dodano</span></div>",
                callback: function callback(confirm) {
                  $(location).attr("href", '/shopping_list');
                }
              });
            },
            error: function error() {
              bootbox.alert({
                title: 'Nie mozna dodać produktu',
                message: "<div class=\"modal-icon\"><i class=\"fa fa-times text-danger\"></i><span>Produkt nie zosta\u0142 dodany do spi\u017Carni!</span></div>"
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