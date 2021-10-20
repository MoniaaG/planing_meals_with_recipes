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
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('recipe_category.store')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Kategoria przepisu </label>
                <input type="name" class="form-control" id="name" name="name" placeholder="Podaj kategorię przepisu">
            </div>
            <button type="submit" class="btn btn-primary">Dodaj kategorię przepisu</button>
            </form>
        </div>
    </div>
</div>
@endsection