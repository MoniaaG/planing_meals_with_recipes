@extends('layouts.app')

@section('scripts-start')
<script src="{{asset('js/delete/pantry_product.js')}}" defer></script>
@endsection
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-sm-responsive rounded">
            <thead class="thead-dark">
                <tr>
                <th class="col-3">Nazwa produktu</th>
                <th class="col-2">Zdjęcie produktu</th>
                <th class="col-2">Ilość w spiżarni</th>
                <th class="col-2">Jednostka</th>
                <th class="col-3">Akcje</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pantry_products as $product)
                <tr>
                <td class="col-3">{{ $product->name }}</td>
                <td class="col-2">@if($product->image == "image") <div class="img-thumbnail bg-info text-white">Brak zdjęcia</div> @else <img width="100" height="100" class="img-thumbnail rounded"  src="{{asset($product->image)}}">@endif</td>
                <td class="col-2" id="product_quantity-{{$product->pivot->id}}">{{ $product->pivot->quantity}}</td>
                <td class="col-2">{{$product->unit->unit}}</td>
                <td class="col-3 text-sm-center text-md-left ">
                    <button type="button" class="btn btn-primary edit-pantry_product" data-toggle="modal" data-target="#exampleModalQuantity" data-edit_route={{ route('pantry.product.update', ['pantry_product' => $product->pivot->id])}} data-quantity={{$product->pivot->quantity}}><i class="fas fa-edit"></i></button>
                    <a class="delete btn-danger btn" title="Usuń produkt ze spiżarni" data-toggle="tooltip" data-placement="bottom" data-delete-href="{{ route('pantry.destroy_pantry_product', ['pantry_product' => $product->pivot->id]) }}"><i class="fas fa-trash"></i></a>
                </td>
                </tr>
            @endforeach
            @if(count($pantry_products) == 0) 
              <tr>
                <td class="text-center h3" colspan="5">{{ 'Brak produktów w spiżarni' }}</td>
              </tr>
            @endif
            </tbody>
            </table>
            
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalQuantity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edytuj ilość produktu : </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="get" action="#">
            @csrf
            <div class="form-group">
                <label for="quantity">Ilość produktu :</label>
                <input class="form-control" type="text" id="quantity" name="quantity">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
        <button type="button" class="btn btn-primary save-pantry">Zapisz zmiany</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
  $('.edit-pantry_product').on('click', function() {
      $('#quantity').val($(this).data('quantity'));
      let url = $(this).data('edit_route');
      let urlId = url.substring(url.lastIndexOf('/')+1);
      let productQuantityName = '#product_quantity-' + urlId;
      if($(productQuantityName).html().trim() != $('#quantity'))
      {
        $('#quantity').val($(productQuantityName).html().trim());
        $(this).attr('data-quantity', $(productQuantityName).html().trim());
      }
      $('.save-pantry').attr('data-url', url); 
  });
  $('.save-pantry').on('click', function() {
        let quantityInput = $('#quantity').val();
        let url = $(this).attr('data-url');
        let urlId = url.substring(url.lastIndexOf('/')+1);
        let productQuantityName = '#product_quantity-' + urlId;
        save_pantry(url, quantityInput);
        $(productQuantityName).html(parseFloat(quantityInput).toFixed(2));
    });
});

function save_pantry(url,quantity){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        data: 'quantity='+ quantity,
        type: 'post',
        success: function(result) {
            bootbox.alert({
                message: `Edytowano ilość produktu w spiżarni!`,
                centerVertical: true,
            });
        },
        error: function(result) {
            bootbox.alert({
                message: `Edycja ilości produktu w spiżarni niepowiodła się!`,
                centerVertical: true,
            });
        },
    });
  }
</script>
@endsection