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
            <form action="@if(isset($edit)){{ route('recipe_category.update', ['recipe_category' => $recipe_category])}} @else {{ route('recipe_category.store') }} @endif" method="post">
            @csrf
            @if(isset($edit)) @method('put') @endif
            <div class="form-group">
                <label for="name">Kategoria przepisu </label>
                <input type="name" class="form-control" id="name" name="name" placeholder="Podaj kategorię przepisu" @if(isset($edit)) value="{{ $recipe_category->name }}" @endif>
            </div>
            <button type="submit" class="btn btn-primary">@if(isset($edit)) Edytuj @else Dodaj @endif kategorię przepisu</button>
            </form>
        </div>
    </div>
</div>
@endsection