$(document).ready(function() {
    $(document).on('click', "[data-assign-href]", function () {
        var url = $(this).data("assign-href");
        let object = $(this);
        bootbox.confirm({
            title: 'title',
            message: `<div class="modal-icon"><i class="far fa-trash-alt"></i><span>Czy chcesz oznaczyć przpis jako ugotowany></span></div>`,
            buttons: {
                confirm: {
                    label: `<i class="fa fa-check mr-1"></i> Oznacz jako ugotowany`,
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
                            $(object[0].children[0]).addClass('fas');
                            $(object[0].children[0]).removeClass('far');
                            bootbox.alert({
                                title: 'Przepis oznaczono jako ugotowany!',
                                message: `<div class="modal-icon"><i class="fa fa-check text-success"></i><span>Oznaczono</span></div>`,
                                callback: function(confirm) {
                                    $(location).attr("href", '/calendar/show');
                                },
                            });
                        },
                        error: function() {
                            bootbox.alert({
                                title: 'Nie można oznaczyć przepisu jako ugotowany!',
                                message: `<div class="modal-icon"><i class="fa fa-times text-danger"></i><span>W spiżarni brakuje ilości pewnych produktów do wykonania przepisu! Uzupełnij je w celu oznaczenia przepisu!</span></div>`,
                            });
                        },
                    });
                }
            }
        });
    })
});