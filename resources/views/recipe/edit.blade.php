@extends('layouts.app')


@section('content')
<div class="container mb-5">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="row justify-content-center col-12 mb-4">
        <div class="col-12">
          <div class="card text-center">
            <div class="card-header bg-dark text-white">
              <h2>Edytuj przepis</h2>
            </div>
            <div class="card-body">
              <form action="{{ route('recipe.update', ['recipe' => $recipe])}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="col-12 mr-5">
                <div class="form-group">
                    <label for="name">Nazwa przepisu</label>
                    <input type="name" class="form-control" id="name" name="name" placeholder="Podaj nazwę przepisu" value="{{$recipe->name}}">
                </div>

                <div class="form-group">
                    <label for="short_description">Krótki opis przepisu</label>
                    <input type="short_description" class="form-control" id="short_description" name="short_description" placeholder="Podaj krótki opis przepisu" value="{{$recipe->short_description}}">
                </div>
                <div class="form-group">
                    <label for="description">Opis przepisu</label>
                    <input type="description" class="form-control" id="description" name="description" placeholder="Podaj opis przepisu" value="{{$recipe->description}}">
                </div>
                <div class="form-group">
                    <label for="category_id">Kategoria przepisu</label>
                    <select class="form-control" id="category_id" name="category_id">
                    @foreach ($categories as $category)
                        <option @if($recipe->category_id == $category->id) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group text-left">
                    <label for="small_image">Małe zdjęcię dania</label>
                    <input type="file" class="form-control-file" id="small_image" name="small_image">
                </div>
                <div class="form-group text-left">
                    <label for="big_image">Duże zdjęcie dania</label>
                    <input type="file" class="form-control-file" id="big_image" name="big_image">
                </div>
                @if(!$recipe->share)
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                        <input class="mr-3" type="checkbox" name="share" id="share" aria-label="Czy udostępnić przepis publicznie?" @if($recipe->share) checked @endif>
                        Czy udostępnić przepis publicznie? <b> UWAGA! Ta operacja jest nieodwracalna.</b>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @if(!$recipe->share)
            <div class="col-12 my-3">
            <h2>Szukaj składnika</h2>
                <select class="js-data-example-ajax col-12" data-route="{{ route('searchProduct')}}" aria-label="Default select example" >
                    <option disabled>Wybierz produkty</option>
                </select>
                <a class="btn btn-success mt-2 text-white" id="add">Dodaj składnik</a>
                <div id="quantitySection">
                    <h1 class="my-3">Składniki</h1>
                    <h5>W tym miejscu wymagane jest podanie ilości wybranych składników</h5>
                    @foreach($recipe->products as $key => $product)
                    <div class="d-flex my-2" style="flex-direction: columns;">
                    <h5 id="product{{$key}}" class="col-5">{{$product->name}}</h1>
                    <input class="form-control col-4 mr-2" id="products[{{$key}}][name]" name="products[{{$key}}][name]" type="hidden" value="{{$product->name}}">
                    <input class="form-control col-4 mr-2" id="products[{{$key}}][barcode]" name="products[{{$key}}][barcode]" value="{{$product->barcode}}" type="hidden">
                    <input class="form-control col-4 mr-2" id="products[{{$key}}][id]" name="products[{{$key}}][id]" value="{{$product->id}}" type="hidden">
                    <input class="form-control col-4" id="products[{{$key}}][quantity]" name="products[{{$key}}][quantity]" value="{{$product->pivot->quantity}}">
                    <input class="form-control col-4" id="products[{{$key}}][unit_name]" name="products[{{$key}}][unit_name]" value="{{$product->unit->unit}}" type="hidden">
                    <h5 id="productunit{{$key}}" class="col-1">{{$product->unit->unit}}</h5>
                    <input class="form-control col-3 ml-2" id="products[{{$key}}][image]" name="products[{{$key}}][image]" value="{{$product->image}}" type="hidden">
                    <a id="btn_del{{$key}}" class="btn btn-danger col-1 btn_del">USUŃ</a>
                  </div>
                @endforeach
                </div>
            </div>
            @endif
            </div>
            <div class="card-footer text-muted bg-dark">
              <button type="submit" class="col-sm-12 col-md-6   btn btn-primary">Edytuj przepis</button>
            </form> 
            </div>
          </div>
              
        </div>
    </div>


@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
  let i = 0;
  try{
  if($('#quantitySection')[0]['lastElementChild'].children[0]['id'].slice(7)) {
    i = new Number($('#quantitySection')[0]['lastElementChild'].children[0]['id'].slice(7))+1;
  }
  }catch(e) {
    
  }
  $('#add').click(function(e){
    e.preventDefault();
    let select = $('.js-data-example-ajax');
    let sectionWithProductQuantity = $('#quantitySection');

    let unit = 'g';
    select.children('option:selected')[0]['dataset']['unit_name'] != null ? unit = select.children('option:selected')[0]['dataset']['unit_name'] : unit = 'g';
    if(unit.includes('kg') || unit.includes('g')) { unit = 'g' }
    else if(unit.includes('l') || unit.includes('ml')) { unit = 'ml' }
    else if(unit.includes('g') && unit.includes('ml')) { unit = 'ml' }
    else if(unit.includes('szt')) { unit = 'szt'}
    
    let barcode = null;
    if(select.children('option:selected')[0]['dataset']['barcode'] != undefined)
      barcode = select.children('option:selected')[0]['dataset']['barcode'];

    let image = 'image';
    if(select.children('option:selected')[0]['dataset']['image'] != undefined)
      image = select.children('option:selected')[0]['dataset']['image'];

    sectionWithProductQuantity.append(`
     <div class="d-flex my-2" style="flex-direction: columns;">
        <h5 id="product${i}" class="col-5">${select.children('option:selected')[0]['text']}</h1>
        <input class="form-control col-4 mr-2" id="products[${i}][name]" name="products[${i}][name]" type="hidden" value="${select.children('option:selected')[0]['text']}">
        <input class="form-control col-4 mr-2" id="products[${i}][barcode]" name="products[${i}][barcode]" value="${barcode}" type="hidden">
        <input class="form-control col-4 mr-2" id="products[${i}][id]" name="products[${i}][id]" value="${select.children('option:selected')[0]['value']}" type="hidden">
        <input class="form-control col-4" id="products[${i}][quantity]" name="products[${i}][quantity]">
        <input class="form-control col-4" id="products[${i}][unit_name]" name="products[${i}][unit_name]" value="${unit}" type="hidden">
        <h5 id="productunit${i}" class="col-1">${unit}</h5>
        <input class="form-control col-3 ml-2" id="products[${i}][image]" name="products[${i}][image]" value="${image}" type="hidden">
        <a id="btn_del${i}" class="btn btn-danger col-1 btn_del">USUŃ</a>
    </div>`)
    i++;
  });

  $("body").on("click", ".btn_del", function(){
    console.log($(this));
    let id = $(this)[0]['id'].slice(7);
    console.log(id);
    let productId = `products[${id}][id]`;
    $("input[name='"+productId+"']").attr('disabled', 'true');
    let productName = `products[${id}][name]`;
    $("input[name='"+productName+"']").attr('disabled', 'true');
    let productBarcode = `products[${id}][barcode]`;
    $("input[name='"+productBarcode+"']").attr('disabled', 'true');
    let productQuantity = `products[${id}][quantity]`;
    $("input[name='"+productQuantity+"']").attr('disabled', 'true');
    $("input[name='"+productQuantity+"']").css('display', 'none');
    let productUnit = `products[${id}][unit_name]`;
    $("input[name='"+productUnit+"']").attr('disabled', 'true');
    let productImage = `products[${id}][image]`;
    $("input[name='"+productImage+"']").attr('disabled', 'true');
    let productNameshown = `product${id}`;
    $("h5[id='"+productNameshown+"']").css('display', 'none');
    let productUnitName = `productunit${id}`;
    $("h5[id='"+productUnitName+"']").css('display', 'none');
    $(this).css('display', 'none');
  });
});

$('.js-data-example-ajax').select2({
    ajax: {
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "{{ route('searchProduct') }}",
    type: 'post',
    dataType: 'json',
    delay: 300,
    data: function (params) {
      console.log($(this).attr('data-route'));
      return {
        search: params.term,
      }
    },
    processResults: function (response) {
      console.log(response);
      return {
        results: response
      }
    },
    cache: true,
  }
}).on('select2:select', function (e) {
    console.log(e);
    var data = e.params.data;  

    $(this).children('[value="'+data['id']+'"]').attr({
      'data-barcode':data["data-barcode"],
      'data-unit_name': data['data-unit'],
      'data-image': data['data-image'],
    })
  });
</script>
@endsection
@endsection