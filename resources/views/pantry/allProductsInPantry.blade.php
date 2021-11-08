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
                <td>{{ $product->pivot->quantity}}</td>
                <td>{{$product->unit->unit}}</td>
                <td class="@if(isset($product->pivot->expiration_date) &&$product->pivot->expiration_date < $today) bg-danger @endif">@if(isset($product->pivot->expiration_date)) {{$product->pivot->expiration_date}} @else {{"Nie podano"}}@endif</td>
                <td>
                    <i class="fas fa-edit"></i>
                    <a class="delete btn-danger btn" title="Usuń produkt ze spiżarni" data-toggle="tooltip" data-placement="bottom" data-delete-href="{{ route('pantry.destroy_pantry_product', ['pantry_product' => $product->id]) }}"><i class="fas fa-trash"></i></a>
                </td>
                </tr>
            @endforeach
            </tbody>
            </table>
            {{ $pantry_products->links() }}
        </div>
    </div>
</div>
@endsection