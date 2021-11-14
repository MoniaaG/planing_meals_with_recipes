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
            <form action="{{ route('product.proposition_store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-12 mr-5">
                <div class="form-group">
                    <label for="name">Nazwa produktu</label>
                    <input type="name" class="form-control" id="name" name="name" placeholder="Podaj nazwę proponowanego produktu">
                </div>
                <div class="form-group">
                    <label for="unit_id">Jednostka</label>
                    <select class="form-control" id="unit_id" name="unit_id">
                    @foreach ($units as $unit)
                        <option value="{{$unit->id}}">{{$unit->unit}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="product_category_id">Kategoria produtu</label>
                    <select class="form-control" id="product_category_id" name="product_category_id">
                    @foreach ($product_categories as $product_category)
                        <option value="{{$product_category->id}}">{{ucfirst($product_category->name)}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="image">Zdjęcie produktu</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary col-12">Dodaj produkt do proponowanych</button>
            </div>
            </form>   
        </div>
    </div>
</div>
@endsection