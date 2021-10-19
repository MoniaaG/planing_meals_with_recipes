@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('product_category.store')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Kategoria produktu</label>
                <input type="name" class="form-control" id="name" name="name" placeholder="Podaj kategorię produktu">
            </div>
            <button type="submit" class="btn btn-primary">Dodaj kategorię produktu</button>
            </form>
        </div>
    </div>
</div>
@endsection