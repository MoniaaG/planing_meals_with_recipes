$(document).ready(function() {
    $('.fa-heart').on('click', function() {
        if($(this)[0].style['color'] == "grey"){
            $(this).css("color", "red");
            liked($(this).attr("data-url"), $(this))
        }else {
            $(this).css("color", "grey");
            liked($(this).attr("data-url"), $(this))
        }
    })
});

function liked(url, obj) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        type: 'POST',
        success: function(result) {
        },
        error: function(result) {
            bootbox.alert({
                title: `Uwaga`,
                message: `<div class="modal-icon"><span>Polubienie przepisu nie powidło się!</span></div>`,
                centerVertical: true,
            });
        },
    });
}