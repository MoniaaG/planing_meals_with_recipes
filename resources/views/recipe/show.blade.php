@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-12">
        <img class="image col-md-12 img-fluid float-left"  src="{{ asset($recipe->big_image) }}">
        <i class="fas fa-heart fa-2x" style="@if(count($recipe->liked()) == 1) color:red; @else color: grey; @endif" data-recipe="{{$recipe->id}}" data-url="{{route('recipe.like', ['recipe' => $recipe])}}"></i>
            <!-- add like option if auth-->
            <div class="col-md-3"></div><!-- with units and quantity-->
            <div class="starts col-md-9"></div><div class="col-3"></div>
            <div class="ingredients col-12">
            @foreach ($recipe->products as $recipe_product)
                <p>{{ ucfirst($recipe_product->name) }}  :  {{ $recipe_product->pivot->quantity }} {{ $recipe_product->unit->unit}}</p>
            @endforeach
            </div>
            <div class="description col-12">{{ $recipe->description }}jhmghmghjmgj,mgj,tgjh,tgju,tgujh,tuj,tyu,dtyuj,u,dtyu,y,y,yu,yru,yu,yru,yu,yju,y</div>
            <div class="comments col-md-12"></div> 
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/likes.js')}}" defer></script>
@endsection