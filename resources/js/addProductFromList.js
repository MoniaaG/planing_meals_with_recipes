$(document).ready(function() {
    $('.btn_add').on('click', function (e) {
        e.preventDefault();
        var url = $(this).data("href");

        let key = $(this)[0]['id'].slice(8);
        let keyid = `products[${key}][id]`;
        let id = ($("input[name='"+keyid+"']").val());
        let keyqu = `products[${key}][quantity]`;
        let quantity = ($("input[name='"+keyqu+"']").val());
        product = [];
        product['id'] = id;
        product['quantity'] = quantity;
        console.log(product);
        bootbox.confirm({
            title: 'title',
            message: `<div class="modal-icon"><i class="far fa-trash-alt"></i><span>Czy chcesz dodać ten produkt do spiżarni?</span></div>`,
            buttons: {
                confirm: {
                    label: `<i class="fa fa-check mr-1"></i> Dodaj`,
                    className: 'btn-danger',
                },
                cancel: {
                    label: `<i class="fa fa-times mr-1"></i> Zamknij`,
                    className: 'btn-success',
                },
            },
            callback: function(confirm) {
                if( confirm ) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: "json",
                        data: {products: {0:{id: product['id'], quantity: product['quantity']}}},
                        success: function(result) {
                            bootbox.alert({
                                title: 'Produkt dodany',
                                message: `<div class="modal-icon"><i class="fa fa-check text-success"></i><span>Dodano</span></div>`,
                                callback: function(confirm) {
                                    $(location).attr("href", '/shopping_list');
                                },
                            });
                        },
                        error: function() {
                            bootbox.alert({
                                title: 'Nie mozna dodać produktu',
                                message: `<div class="modal-icon"><i class="fa fa-times text-danger"></i><span>Produkt nie został dodany do spiżarni!</span></div>`,
                            });
                        },
                    });
                }
            }
        });
    })
});