@extends('dashboard.welcome')

@section('content')
<div class="container dashboard mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="@if(isset($edit)) {{route('product_category.update', ['product_category' => $product_category]) }}@else  {{route('product_category.store')}} @endif" method="post">
            @csrf
            @if(isset($edit)) @method('put') @endif
            <div class="form-group">
                <label for="name">@lang('product.product_category')</label>
                <input type="name" class="form-control" id="name" name="name" placeholder="Podaj kategorię produktu" @if(isset($edit)) value="{{$product_category->name}}" @endif >
            </div>
            <button type="submit" class="btn btn-primary">@if(isset($edit)) @lang('product.edit_product_category') @else @lang('product.add_product_category') @endif</button>
            </form>
        </div>
    </div>
</div>
@endsection