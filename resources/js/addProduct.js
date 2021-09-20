$(document).ready(function() {
    $('#findProduct').click(function(){
        let searchProductText = $('#searchProductText');
        let url = $(this).attr('data-route');
        console.log(searchProductText.val());
        searchProduct(searchProductText);
    });

    /*$('.plus').click(function(){
        let id = $(this).attr('data-product-plus');
        let route = $(this).attr('data-route');
        let value = 1;
        editProduct(id, route, value);
    });*/
    function searchProduct(searchText, route)
    {
        $.ajax({
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route,
            type: 'POST',
            data: 'value=' + searchText,
            success: function(result) {
              console.log('udalo sie');
                /*$('.total1').html(el + result.total.toFixed(2)+"zł");
                $('.total').html(result.total.toFixed(2)+"zł");
                $(`#quantity${id}`).html(result.quantity);
                $(`#sell${id}`).html(result.sell.toFixed(2)+"zł");
                if(result.quantity > 1)$(`[data-product-minus=${id}]`).removeAttr('disabled');
                else $(`[data-product-minus=${id}]`).prop('disabled', 'disabled');*/
            },
            error: function(result) {
                /*bootbox.alert({
                    title: `Uwaga`,
                    message: `<div class="modal-icon"><span>Brak produktu na magazynie</span></div>`,
                    centerVertical: true,
                });*/
                console.log('error');
            },
        });
    }  
});





/*$(document).ready(function () {
let i = 0;
let ingredients = $('.ingredients');
console.log(ingredients);
const button = $('#addProduct');
console.log(button);
button.click(function(){
    addNewProduct();
  });

function addNewProduct()
{
    ingredients.append(`<div class="form-group">
    <select class="form-control" name="product[${i}][0]">
    </select>
    <label for="product[${i}][1]">Ilość powyższego produktu</label>
    <input class="form-control" id="product[${i}][1]" name="product[${i}][1]">
    <hr>
    </div>`);
    i++;
}
});*/