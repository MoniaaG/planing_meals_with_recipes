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
  <div class="row justify-content-center col-12">
      <div class="col-12">
      <div class="card text-center">
        <div class="card-header bg-dark text-white">
          <h2>Dodaj produkty do spiżarni</h2>
        </div>
        <div class="card-body">
          <form action="{{ route('pantry.storeProduct')}}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="col-12 my-3">
          <h2>Szukaj składnika</h2>
              <select class="js-data-example-ajax col-12" data-route="{{ route('searchProduct')}}" aria-label="Default select example" >
                  <option disabled>Wybierz produkty</option>
              </select>
              <a class="btn btn-success mt-2" id="add">Dodaj składnik</a>
              <div id="quantitySection">
                  <h1 class="my-3">Produkty</h1>
                  <h5>W tym miejscu wymagane jest podanie ilości wybranych produktów, które chcesz dodać do spiżarni</h5>
              </div>
          </div>
        </div>
        <div class="card-footer text-muted bg-dark">
          <button type="submit" class="btn btn-primary col-12 col-md-6">Dodaj produkty do spiżarni</button>
          </form>
        </div>
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
