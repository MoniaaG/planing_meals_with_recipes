@extends('layouts.app')

@section('scripts-start')
<script src="{{asset('js/delete/pantry_product.js')}}" defer></script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Nazwa produktu</th>
                <th scope="col">Ilość w spiżarni</th>
                <th scope="col">Jednostka</th>
                <th scope="col">Data przydatności</th>
                <th scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pantry_products as $product)
                <tr>
                <th scope="row">{{ $product->name }}</th>
                <td id="product_quantity-{{$product->pivot->id}}">{{ $product->pivot->quantity}}</td>
                <td>{{$product->unit->unit}}</td>
                <td class="@if(isset($product->pivot->expiration_date) &&$product->pivot->expiration_date < $today) bg-danger @endif">@if(isset($product->pivot->expiration_date)) {{$product->pivot->expiration_date}} @else {{"Nie podano"}}@endif</td>
                <td>
                    <button type="button" class="btn btn-primary edit-pantry_product" data-toggle="modal" data-target="#exampleModal" data-edit_route={{ route('pantry.product.update', ['pantry_product' => $product->pivot->id])}} data-quantity={{$product->pivot->quantity}}><i class="fas fa-edit"></i></button>
                    <a class="delete btn-danger btn" title="Usuń produkt ze spiżarni" data-toggle="tooltip" data-placement="bottom" data-delete-href="{{ route('pantry.destroy_pantry_product', ['pantry_product' => $product->pivot->id]) }}"><i class="fas fa-trash"></i></a>
                </td>
                </tr>
            @endforeach
            </tbody>
            </table>
            
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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