@extends('layouts.app')


@section('content')
<div class="container">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="row justify-content-center col-6">
        <div class="col-12">
            <form class="d-flex" style="flex-direction: columns;" action="{{ route('recipe.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-6 mr-5">
                <div class="form-group">
                    <label for="name">Nazwa przepisu</label>
                    <input type="name" class="form-control" id="name" name="name" placeholder="Podaj nazwę przepisu">
                </div>

                <div class="form-group">
                    <label for="short_description">Krótki opis przepisu</label>
                    <input type="short_description" class="form-control" id="short_description" name="short_description" placeholder="Podaj krótki opis przepisu">
                </div>
                <div class="form-group">
                    <label for="description">Opis przepisu</label>
                    <input type="description" class="form-control" id="description" name="description" placeholder="Podaj opis przepisu">
                </div>
                <div class="form-group">
                    <label for="category_id">Kategoria przepisu</label>
                    <select class="form-control" id="category_id" name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="small_image">Małe zdjęcię dania</label>
                    <input type="file" class="form-control-file" id="small_image" name="small_image">
                </div>
                <div class="form-group">
                    <label for="big_image">Duże zdjęcie dania</label>
                    <input type="file" class="form-control-file" id="big_image" name="big_image">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                        <input class="mr-3" type="checkbox" name="share" id="share" aria-label="Czy idostępnic przepis publicznie?">
                        Czy udostępnić przepis publicznie?
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Dodaj przepis</button>
            </div>
            <div class="col-6">
                <div class="form-group ingredients">
                    <label for="searchProduct">Składniki</label>
                    <input class="form-control" id="searchProductText">
                    <span class="btn btn-info mt-2" id="findProduct" data-route="{{ route('searchProduct')}}">Szukaj składnika</span>
                </div>

                <select class="custom-select" id="products" aria-label="Default select example">
                    <option disabled>Wybierz produkty</option>
                </select>
                <a class="btn btn-warning mt-2" id="add">Dodaj</a>

                <div id="quantitySection">
                    <h1>Składniki</h1>
                </div>
            </div>
            </form>

            
        </div>
       
    </div>
    
</div>

<!--<div id="aapp">
   <example-component></example-component>
</div>-->
@section('scripts')
<!--<script src="{{ mix('js/app.js') }}"></script>-->
<script>
$(document).ready(function() {
    $('#findProduct').click(function(e){
        e.preventDefault();
        let searchProductText = $('#searchProductText');
        let url = $(this).attr('data-route');
        console.log(url);
        console.log(searchProductText.val());
        searchProduct(searchProductText.val(), url);
    });
    let i = 0;
    $('#add').click(function(e){
        e.preventDefault();
        let select = $('#products');
        let sectionWithProductQuantity = $('#quantitySection');
        console.log();
        sectionWithProductQuantity.append(`
        <div class="d-flex" style="flex-direction: columns;">
            <input class="form-control col-12 mr-2" id="product[${i}][name]" name="product[${i}][name]" value="${select.children('option:selected')[0]['text']}">
            <input class="form-control col-12 mr-2" id="product[${i}][barcode]" name="product[${i}][barcode]" value="${select.children('option:selected')[0]['dataset']['barcode']}" type="hidden">
            <input class="form-control col-12 mr-2" id="product[${i}][id]" name="product[${i}][id]" value="${select.children('option:selected')[0]['value']}" type="hidden">
            <input class="form-control col-12" id="product[${i}][quantity]" name="product[${i}][quantity]"> ${select.children('option:selected')[0]['dataset']['unit_name']}
            <input class="form-control col-12" id="product[${i}][unit_name]" name="product[${i}][unit_name]" value="${select.children('option:selected')[0]['dataset']['unit_name']}" type="hidden">
        </div>`)
        console.log(select.children('option:selected')[0]['text']);
        i++;
    });



    function searchProduct(searchText, route)
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route,
            type: 'POST',
            data: 'value=' + searchText,
            success: function(data) {
              let select = $('#products');
              select.children().remove();
              if(data.productsFromDB != null){
                  for(let j = 0; j < data.productsFromDB.length; j++)
                {
                    console.log(data.productsFromDB[i]['id']);
                    console.log(data.productsFromDB[i]['name']);
                    console.log(data.productsFromDB[i]['barcode']);
                    console.log(data.productsFromDB[i]['unit']['unit'] + 'unit')
                    select.append(`<option data-barcode="${data.productsFromDB[i]['barcode']}" value="${data.productsFromDB[i]['id']}" data-unit="${data.productsFromDB[i]['unit_id']}" data-unit_name="${data.productsFromDB[i]['unit']['unit']}">${data.productsFromDB[i]['name']} </option>`);
                }
              }
              if(data.productsFromAPI != null){
                for(let i = 0; i < data.productsFromAPI.length; i++)
                {
                    let unit = 'g';
                    data.productsFromAPI[i]['quantity'] != null ? unit = data.productsFromAPI[i]['quantity'] : unit = 'g';
                    if(unit.includes('kg') || unit.includes('g')) { unit = 'g' }
                    else if(unit.includes('l') || unit.includes('ml')) { unit = 'ml' }
                    else if(unit.includes('g') && unit.includes('ml')) { unit = 'ml' }
                    else if(unit.includes('szt')) { unit = 'szt'}
                    console.log(unit+" każda jednostka xd " + i );
                    console.log(data.productsFromAPI[i]['_id']);
                    console.log(data.productsFromAPI[i]['product_name']);
                    select.append(`<option data-barcode="${data.productsFromAPI[i]['_id']}" value="${data.productsFromAPI[i]['_id']}" data-unit_name="${unit}">${data.productsFromAPI[i]['product_name']}</option>`);
                }
              }
              /*if(data.productsFromDB != null){
                  console.log('xd'+ data.productsFromDB);
              }
              
              /*data.productsFromDB.forEach(group => {
                    console.log(group);
                    });
              }
              console.log(data.status);
              console.log(data.productsFromAPI);*/
                /*$('.total1').html(el + resu
                lt.total.toFixed(2)+"zł");
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
</script>
@endsection
@endsection
