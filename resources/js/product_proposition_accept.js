$(document).ready(function() {
    $(document).on('click', "[data-accept-href]", function () {
        var url = $(this).data("accept-href");
        console.log(url);
        bootbox.confirm({
            title: 'title',
            message: `<div class="modal-icon"><i class="far fa-trash-alt"></i><span>Czy chcesz zaakceptować proponowany produkt?</span></div>`,
            buttons: {
                confirm: {
                    label: `<i class="fa fa-check mr-1"></i> Akceptuj`,
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
                        type: 'POST',
                        success: function(result) {
                            bootbox.alert({
                                title: 'Produkt został zaakceptowany!',
                                message: `<div class="modal-icon"><i class="fa fa-check text-success"></i><span>Zaakceptowano</span></div>`,
                                callback: function(confirm) {
                                    $(location).attr("href", '/dashboard/product_proposition');
                                },
                            });
                        },
                        error: function() {
                            bootbox.alert({
                                title: 'Nie mozna zaakceptować proponowanego produktu!',
                                message: `<div class="modal-icon"><i class="fa fa-times text-danger"></i><span></span></div>`,
                            });
                        },
                    });
                }
            }
        });
    })
});