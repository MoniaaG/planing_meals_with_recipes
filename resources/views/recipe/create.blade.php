@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('recipe.store')}}" method="post">
            <div class="form-group">
                <label for="name">Nazwa przepisu</label>
                <input type="name" class="form-control" id="name" name="name" placeholder="Podaj nazwę przepisu">
            </div>
            <div class="form-group">
                <label for="products_id">Składniki</label>
                <select multiple class="form-control" id="products_id" name="products_id[]">
                <option value="1">Mleko</option>
                <option value="2">Jogurt</option>
                </select>
            </div>
            <div class="form-group">
                <label for="short_description">Krótki opis przepisu</label>
                <input type="short_description" class="form-control" id="short_description" name="short_description" placeholder="Podaj krótki opis przepisu">
            </div>
            <div class="form-group">
                <label for="description">Opis przepisu</label>
                <input type="description" class="form-control" id="description" name="description" placeholder="Podaj opis przepisu">
            </div>
            <div class="form-group">
                <label for="category_id">Kategoria przepisu</label>
                <select class="form-control" id="category_id" name="category_id">
                <option value="1">Obiady</option>
                <option value="2">Ciasta</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="small_image">Małe zdjęcię dania</label>
                <input type="file" class="form-control-file" id="small_image" name="small_image">
            </div>
            <div class="form-group">
                <label for="big_image">Duże zdjęcie dania</label>
                <input type="file" class="form-control-file" id="big_image" name="big_image">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                    <input class="mr-3" type="checkbox" name="share" id="share" aria-label="Czy idostępnic przepis publicznie?">
                    Czy udostępnić przepis publicznie?
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj przepis</button>
            </form>
        </div>
    </div>
</div>
@endsection