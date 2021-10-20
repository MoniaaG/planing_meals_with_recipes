@extends('layouts.app')

@section('scripts-start')
<script src="{{asset('js/delete/recipe_category.js')}}" defer></script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Kategoria przepisu</th>
                <th scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
            @foreach($recipe_categories as $recipe_category)
                <tr>
                <th scope="row">{{ $recipe_category->name }}</th>
                <td>
                <i class="fas fa-edit"></i>
                <a class="delete btn-danger" class=""title="Usuń kategorię przepisu" data-toggle="tooltip" data-placement="bottom" data-delete-href="{{ route('recipe_category.destroy', ['recipe_category' => $recipe_category]) }}">
                <i class="fas fa-trash"></i></a>
                </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection