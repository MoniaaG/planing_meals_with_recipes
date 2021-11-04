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

    <div class="col-12 justify-content-center">
    <h1 class="my-3">@if($start == $end) Lista zakupów na dzień {{$start}} @else Lista zakupów obowiązująca od dnia {{ $start }} do {{ $end }} @endif</h1>
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
            @foreach($productToBuy as $key => $product)
                <tr>
                <th >
                    <h6>{{$product['name']}}</h6> 
                    <input id="products[{{$key}}][id]" name="products[{{$key}}][id]" value="{{$product['product_id']}}" type="hidden">
                </th>
                <th >    
                    <input class="form-control"  type="text" id="products[{{$key}}][quantity]" name="products[{{$key}}][quantity]" value="{{$product['quantity']}}">
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
    @if(count($productToBuy) > 0)
        <button class="btn btn-primary col-6 my-3 offset-3" type="submit">Dodaj produkty z listy zakupów do spiżarni</button>
    @endif
</form>
    </div>
</div>

@endsection