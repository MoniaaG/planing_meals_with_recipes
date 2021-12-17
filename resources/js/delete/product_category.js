$(document).ready(function() {
    $(document).on('click', "[data-delete-href]", function () {
        var url = $(this).data("delete-href");
        console.log(url);
        bootbox.confirm({
            title: 'title',
            message: `<div class="modal-icon"><i class="far fa-trash-alt"></i><span>Czy chcesz usunąć kategorię produktu?</span></div>`,
            buttons: {
                confirm: {
                    label: `<i class="fa fa-check mr-1"></i> Usuń`,
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
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        type: 'DELETE',
                        success: function(result) {
                            bootbox.alert({
                                title: 'Kategoria przepisu usunieta',
                                message: `<div class="modal-icon"><i class="fa fa-check text-success"></i><span>Usunieto</span></div>`,
                                callback: function(confirm) {
                                    $(location).attr("href", '/dashboard/product_category/index');
                                },
                            });
                        },
                        error: function() {
                            bootbox.alert({
                                title: 'Nie mozna usunac',
                                message: `<div class="modal-icon"><i class="fa fa-times text-danger"></i><span>Nie można usunąć kategorii produktu ponieważ istnieją produkty do niej przypisane!</span></div>`,
                            });
                        },
                    });
                }
            }
        });
    })
    
});