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
    <div class="row justify-content-center">
        <div class="col-12">
        <div class="card text-center">
            <div class="card-header bg-dark card-title text-white font-weight-bold">
                <h2>@lang('product.add_product_proposition')</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('product.proposition_store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-12 mr-5">
                <div class="form-group">
                    <label for="name">@lang('product.product_name')</label>
                    <input type="name" class="form-control" id="name" name="name" placeholder="Podaj nazwę proponowanego produktu">
                </div>
                <div class="form-group">
                    <label for="unit_id">@lang('product.unit')</label>
                    <select class="form-control" id="unit_id" name="unit_id">
                    @foreach ($units as $unit)
                        <option value="{{$unit->id}}">{{$unit->unit}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="product_category_id">@lang('product.product_category')</label>
                    <select class="form-control" id="product_category_id" name="product_category_id">
                    @foreach ($product_categories as $product_category)
                        <option value="{{$product_category->id}}">{{ucfirst($product_category->name)}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="image">@lang('product.product_photo')</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                
            </div>
              
            </div>
            <div class="card-footer text-muted bg-dark">
               <button type="submit" class="btn btn-primary col-sm-12 col-md-6">@lang('product.add_product_proposition')</button>
               </form> 
            </div>
        </div>
        </div>
    </div>
</div>
@endsection