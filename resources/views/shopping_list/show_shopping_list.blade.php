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
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: green;
}

input:focus + .slider {
  box-shadow: 0 0 1px green;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

    <div class="col-12 justify-content-center">
    <h1 class="my-3">@if($shoppinglist->start_date == $shoppinglist->end_date) Lista zakupów na dzień {{$shoppinglist->start_date->toDateString()}} @else Lista zakupów obowiązująca od dnia {{ $shoppinglist->start_date->toDateString() }} do {{ $shoppinglist->end_date->toDateString() }} @endif</h1>
        <form action="{{ route('pantry.addProductToPantryFromList')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table">
            <thead class="thead-dark">
                <tr>
                <th >Produkt</th>
                <th >Ilość</th>
                <th >Data przydatności (opcjonalnie)</th>
                </tr>
            </thead>
            <tbody>
            @foreach($shoppinglist->products as $key => $product)
                <tr>
                <th>
                    <h6>{{$product['name']}}</h6> 
                    <input id="products[{{$key}}][id]" name="products[{{$key}}][id]" value="{{$product->id}}" type="hidden">
                </th>
                <th >    
                    <input class="form-control"  type="text" id="products[{{$key}}][quantity]" name="products[{{$key}}][quantity]" value="{{$product->pivot->quantity}}">
                </th>
                <th >    
                    <input class="form-control"  type="date" id="products[{{$key}}][expiration_date]" name="products[{{$key}}][expiration_date]" min="{{$today}}">
                </th>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>  
    @if(count($shoppinglist->products) > 0)
        <button class="btn btn-primary col-6 my-3 offset-3" type="submit">Dodaj produkty z listy zakupów do spiżarni</button>
    @endif

</form>

<table class="table">
    <thead class="thead-dark">
        <tr>
        <th >Produkt</th>
        <th >Ilość</th>
        </tr>
    </thead>
    <tbody>
    @foreach($shoppinglist->products as $key => $product)
        <tr>
        <td>
            <h6>{{$product['name']}}</h6> 
        </td>
        <td>    
            {{$product->pivot->quantity}}
        </td>
        </tr>
    @endforeach
    </tbody>
</table>
    <label class="switch">
        <input type="checkbox" checked>
        <span class="slider round"></span>
    </label>
    </div>
</div>

@endsection