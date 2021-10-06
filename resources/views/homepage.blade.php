@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row m-0 p-0 justify-content-between">
        @foreach($recipes as $recipe)
        <div class="card col-12 col-md-4 mb-3 d-flex p-0">
        <img class="card-img-top" src="{{ asset($recipe->small_image)}}" alt="Card image cap">
        <i class="far fa-heart fa-2x"></i>
        <div class="card-body">
            <h5 class="card-title">{{ $recipe->name }}</h5>
            <p class="card-text">{{ $recipe->small_description }}</p>
            <a href="#" class="btn btn-primary">Czytaj dalej</a>
        </div>
        </div>
        @endforeach
    </div>
</div>
@endsection