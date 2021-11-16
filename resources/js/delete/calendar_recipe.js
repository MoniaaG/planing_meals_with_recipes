$(document).ready(function() {
    $(document).on('click', "[data-delete-href]", function () {
        var url = $(this).data("delete-href");
        console.log(url);
        bootbox.confirm({
            title: 'title',
            message: `<div class="modal-icon"><i class="far fa-trash-alt"></i><span>Czy chcesz usunąć przepis z kalendarza?</span></div>`,
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
                        url: url,
                        type: 'DELETE',
                        success: function(result) {
                            bootbox.alert({
                                title: 'przepis usuniety',
                                message: `<div class="modal-icon"><i class="fa fa-check text-success"></i><span>usunieto</span></div>`,
                                callback: function(confirm) {
                                    $(location).attr("href", '/calendar/show');
                                },
                            });
                        },
                        error: function() {
                            bootbox.alert({
                                title: 'Nie mozna usunac',
                                message: `<div class="modal-icon"><i class="fa fa-times text-danger"></i><span>Przepis nie został usuniety!</span></div>`,
                            });
                        },
                    });
                }
            }
        });
    })
});