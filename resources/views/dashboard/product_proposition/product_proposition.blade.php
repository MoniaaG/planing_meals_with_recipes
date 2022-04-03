@extends('dashboard.welcome')

@section('scripts-start')
<script src="{{asset('js/delete/product_proposition_reject.js')}}" defer></script>
<script src="{{asset('js/product_proposition_accept.js')}}" defer></script>
@endsection
@section('content')
<div class="container dashboard mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-sm-responsive table-md-responsive col-md-8 offset-md-4 col-lg-11 offset-lg-1">
            <thead class="thead-dark">
                <tr>
                <th scope="col">@lang('product.proposition_product_name')</th>
                <th scope="col">@lang('product.product_category')</th>
                <th scope="col">@lang('product.unit')</th>
                <th scope="col">@lang('product.product_photo')</th>
                <th scope="col">@lang('product.actions')</th>
                </tr>
            </thead>
            <tbody>
            @foreach($product_propositions as $product_proposition)
                <tr>
                <td scope="col">{{ $product_proposition->name }}</td>
                <th scope="col">{{$product_proposition->product_category->name}}</th>
                <th scope="col">{{$product_proposition->unit->unit}}</th>
                <th scope="col">@if($product_proposition->image == "image") <div class="img-thumbnail bg-info text-white">@lang('product.no_photo')</div> @else <img class="img-thumbnail" width="100px" height="80px" src="{{asset($product_proposition->image)}}">@endif</th>
                <td scope="col">
                    <a class="btn btn-success" data-accept-href="{{route('dashboard.product_proposition.accept', ['product' => $product_proposition])}}" title="Akceptuj proponowany produkt"><i class="fas fa-check-circle"></i> @lang('product.accept')</a>
                    @if(Auth::user()->hasRole('admin'))
                    <a class="delete btn-danger btn" title="Odrzuć proponowany produkt" data-toggle="tooltip" data-placement="bottom" data-delete-href="{{ route('dashboard.product_proposition.reject', ['product' => $product_proposition]) }}">
                    <i class="far fa-window-close"></i> Odrzuć</a>
                    @endif
                </td>
                </tr>
            @endforeach
            @if($product_propositions->count() == 0)
            <tr>
                <td colspan="5" class="text-center font-weight-bold" scope="col"><h2>@lang('product.no_prop_products')</h2></td>
            </tr>
            @endif
            </tbody>
            </table>
        </div>
        {{ $product_propositions->links() }}
    </div>
</div>
@endsection