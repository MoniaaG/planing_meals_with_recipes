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

<div class="col-12">
            <form action="{{ route('pantry.whatNeedToBuy')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="date" name="start">
            <input type="date" name="end">

            <button type="submit" class="btn btn-primary col-12 mx-4 my-3">What to buy</button>
            </form>
        </div>
    <div class="row justify-content-center col-12">
        <div class="col-12">
            <form action="{{ route('pantry.storeProduct')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-12 my-3">
                <div class="form-group ingredients">
                    <label for="searchProduct">Produkty</label>
                    <input class="form-control" id="searchProductText" placeholder="Podaj nazwę szukanego składnika/produktu">
                    <span class="btn btn-info mt-2" id="findProduct" data-route="{{ route('searchProduct')}}">Szukaj produktu</span>
                </div>

                <select class="custom-select" id="products" aria-label="Default select example">
                    <option disabled>Wybierz produkty</option>
                </select>
                <a class="btn btn-warning mt-2" id="add">Dodaj składnik</a>

                <div id="quantitySection">
                    <h1 class="my-3">Produkty</h1>
                    <h5>W tym miejscu wymagane jest podanie ilości wybranych produktów, które chcesz dodać do spiżarni</h5>
                </div>
            </div>

            <button type="submit" class="btn btn-primary col-12 mx-4 my-3">Dodaj produkty do spiżarni</button>
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
        <div class="d-flex my-2" style="flex-direction: columns;">
            <input class="form-control col-4 mr-2" id="products[${i}][name]" name="products[${i}][name]" value="${select.children('option:selected')[0]['text']}">
            <input class="form-control col-4 mr-2" id="products[${i}][barcode]" name="products[${i}][barcode]" value="${select.children('option:selected')[0]['dataset']['barcode']}" type="hidden">
            <input class="form-control col-4 mr-2" id="products[${i}][id]" name="products[${i}][id]" value="${select.children('option:selected')[0]['value']}" type="hidden">
            <input class="form-control col-4" id="products[${i}][quantity]" name="products[${i}][quantity]"> ${select.children('option:selected')[0]['dataset']['unit_name']}
            <input class="form-control col-4" id="products[${i}][unit_name]" name="products[${i}][unit_name]" value="${select.children('option:selected')[0]['dataset']['unit_name']}" type="hidden">
            <input class="form-control col-3 ml-2" type="date" id="products[${i}][expiration_date]" name="products[${i}][expiration_date]">
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
              console.log(data);
              if(data.productsFromDB != null){
                  for(let j = 0; j < data.productsFromDB.length; j++)
                {
                    select.append(`<option data-barcode="${data.productsFromDB[j]['barcode']}" value="${data.productsFromDB[j]['id']}" data-unit="${data.productsFromDB[j]['unit_id']}" data-unit_name="${data.productsFromDB[j]['unit']['unit']}">${data.productsFromDB[j]['name']} </option>`);
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
