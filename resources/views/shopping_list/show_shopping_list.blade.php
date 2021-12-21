@extends('layouts.app')


@section('content')
<div class="container mb-4">
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

    <div class="card text-center">
    <div class="card-header bg-dark text-white d-md-flex justify-content-between">
        <h4 class="my-3">@if($shoppinglist->start_date == $shoppinglist->end_date) Lista zakupów na dzień {{$shoppinglist->start_date->toDateString()}} @else Lista zakupów obowiązująca od dnia {{ $shoppinglist->start_date->toDateString() }} do {{ $shoppinglist->end_date->toDateString() }} @endif</h2>
        <input class="decision align-item-center" type="checkbox" checked data-toggle="toggle" data-onstyle="success" data-on="Zmień na listę" data-off="Zmień na formularz" data-offstyle="danger" data-size="small">
    </div>
    <div class="card-body">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table formShoppinglist">
            <thead class="thead-dark">
                <tr>
                    <th class="col-5">@lang('shopping_list.product')</th>
                    <th class="col-4">@lang('shopping_list.quantity')</th>
                    <th class="col-1">@lang('shopping_list.unit')</th>
                    <th class="col-2">@lang('shopping_list.added_to_pantry')</th>
                </tr>
            </thead>
            <tbody>
    @foreach($shoppinglist->products as $key => $product)
        <form action="{{ route('pantry.addProductToPantryFromList')}}" method="post" enctype="multipart/form-data" style="display: none;">
        @csrf
                <tr>
                <td class="col-5">
                    <h6>{{$product['name']}}</h6> 
                    <input id="products[{{$key}}][id]" name="products[{{$key}}][id]" value="{{$product->id}}" type="hidden">
                </td>
                <td class="col-4">    
                    <input class="form-control"  type="text" id="products[{{$key}}][quantity]" name="products[{{$key}}][quantity]" value="{{$product->pivot->quantity}}">
                </td>
                <td class="col-1 text-center">
                    <h6>{{$product->unit->unit}}</h6>
                </td>
                <td class="col-2 text-center">
                    @if(!$product->pivot['added_to_pantry']) <button type="submit" class="btn btn-success btn_add" id="btn_add-{{$key}}" data-href="{{route('pantry.addProductToPantryFromList')}}">@lang('shopping_list.add')</button> @else <button class="btn btn-success" disabled>Dodaj</button> @endif
                </td>
                </tr>
        </div>
    </div>  
</form>
@endforeach
@if(count($shoppinglist->products) == 0) 
<tr>
    <td colspan="4"><h4>Brak produktów na liście</h4></td>
</tr>
@endif
</tbody>
</table>

<table id="shoppinglist" class="table">
    <thead class="thead-dark">
        <tr>
            <th class="col-5">@lang('shopping_list.product')</th>
            <th class="col-4">@lang('shopping_list.quantity')</th>
            <th class="col-1">@lang('shopping_list.unit')</th>
            <th class="col-2">@lang('shopping_list.added_to_pantry')</th>
        </tr>
    </thead>
    <tbody>
    @foreach($shoppinglist->products as $key => $product)
        <tr>
            <td class="col-5">
                <h6>{{$product['name']}}</h6> 
            </td>
            <td class="col-4">    
                {{$product->pivot->quantity}}
            </td>
            <td class="col-1 text-center">
                <h6>{{$product->unit->unit}}</h6>
            </td>
            <td class="col-2 text-center">
                <h6>@if($product->pivot->added_to_pantry) @lang('shopping_list.added') @else @lang('shopping_list.not_added') @endif</h6>
            </td>
        </tr>
    @endforeach
    @if(count($shoppinglist->products) == 0) 
        <tr>
            <td colspan="4"><h4>Brak produktów na liście zakupów</h4></td>
        </tr>
    @endif
    </tbody>
</table> 
    </div>
    </div>
    
</div>

@endsection

@section('scripts')

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
    $(document).ready(function() {
        
        if($($(".decision")[0].parentNode).hasClass('off')){
            $(".formShoppinglist").css("display", "none");
            $("#shoppinglist").css("display", "");
        }else {
            $(".formShoppinglist").css("display", "");
            $("#shoppinglist").css("display", "none");
        }

        $($($(".decision")[0].parentNode)).click(function(e){
            if($($(".decision")[0].parentNode).hasClass('off')){
                $(".formShoppinglist").css("display", "");
                $("#shoppinglist").css("display", "none");
            }else {
                $(".formShoppinglist").css("display", "none");
                $("#shoppinglist").css("display", "");
            }
        });
    });
</script>
@endsection