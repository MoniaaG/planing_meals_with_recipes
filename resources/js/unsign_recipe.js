$(document).ready(function() {
    $(document).on('click', "[data-unsign-href]", function () {
        var url = $(this).data("unsign-href");
        let object = $(this);
        bootbox.confirm({
            title: 'title',
            message: `<div class="modal-icon"><i class="far fa-trash-alt"></i><span>Czy chcesz zmienić przepis na nieugotowany?></span></div>`,
            buttons: {
                confirm: {
                    label: `<i class="fa fa-check mr-1"></i> Oznacz jako nieugotowany!`,
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
                        success: function(result) {
                            $(object[0].children[0]).addClass('far');
                            $(object[0].children[0]).removeClass('fas');
                            bootbox.alert({
                                title: 'Przepis oznaczono jako nieugotowany!',
                                message: `<div class="modal-icon"><i class="fa fa-check text-success"></i><span>Oznaczono</span></div>`,
                                callback: function(confirm) {
                                    $(location).attr("href", '/calendar/show');
                                },
                            });
                        },
                        error: function() {
                            bootbox.alert({
                                title: 'Nie można oznaczyć przepisu jako nieugotowany!',
                                message: `<div class="modal-icon"><i class="fa fa-times text-danger"></i><span></span></div>`,
                            });
                        },
                    });
                }
            }
        });
    })
});