@extends('layouts.app')

@section('content')
<div class="container mb-4">
    <div class="row m-0 p-0">
        <h1 class="col-12 bg-dark text-white rounded mb-3">Najnowsze przepisy użytkowników</h1>
        @foreach($recipes_newest as $recipe)
        <div class="card col-12 col-md-4 mb-3 d-flex p-0">
        <img class="card-img-top" height="250px" src="{{ asset($recipe->small_image)}}" alt="Card image cap">
        @auth
        @if(Auth::user()->id != $recipe->user_id)
        <i class="fas fa-heart fa-2x" style="@if(count($recipe->liked()) == 1) color:red; @else color: grey; @endif" data-recipe="{{$recipe->id}}" data-url="{{route('recipe.like', ['recipe' => $recipe])}}"></i>
        @endif
        @endauth
        <div class="card-body">
            <h5 class="card-title">{{ $recipe->name }}</h5>
            <p class="card-text">{{ $recipe->small_description }}</p>
            <a href="{{ route('recipe.show', ['recipe' => $recipe]) }}" class="btn btn-primary">Czytaj dalej</a>
        </div>
        </div>
        @endforeach
        <h1 class="col-12 bg-dark text-white rounded mt-4 mb-3">Przepisy z największą ilością polubień</h1>
        @foreach($recipes_most_liked as $recipe)
        <div class="card col-12 col-md-4 mb-3 d-flex p-0">
        <img class="card-img-top" height="250px" src="{{ asset($recipe->small_image)}}" alt="Card image cap">
        @auth
        @if(Auth::user()->id != $recipe->user_id)
        <i class="fas fa-heart fa-2x" style="@if(count($recipe->liked()) == 1) color:red; @else color: grey; @endif" data-recipe="{{$recipe->id}}" data-url="{{route('recipe.like', ['recipe' => $recipe])}}"></i>
        @endif
        @endauth
        <div class="card-body">
            <h5 class="card-title">{{ $recipe->name }}</h5>
            <p class="card-text">{{ $recipe->small_description }}</p>
            <a href="{{ route('recipe.show', ['recipe' => $recipe]) }}" class="btn btn-primary">Czytaj dalej</a>
        </div>
        </div>
        @endforeach

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/likes.js')}}" defer></script>
@endsection