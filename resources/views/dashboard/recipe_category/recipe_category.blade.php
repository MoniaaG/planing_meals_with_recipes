@extends('dashboard.welcome')

@section('scripts-start')
<script src="{{asset('js/delete/recipe_category.js')}}" defer></script>
@endsection
@section('content')
<div class="container dashboard mt-5">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm-responsive table-md-responsive col-md-8 offset-md-4 col-lg-11 offset-lg-1">
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
                <a href="{{ route('recipe_category.edit', ['recipe_category' => $recipe_category])}}" class="btn btn-success"><i class="fas fa-edit"></i></a>
                <a class="delete btn-danger btn" title="Usuń kategorię przepisu" data-toggle="tooltip" data-placement="bottom" data-delete-href="{{ route('recipe_category.destroy', ['recipe_category' => $recipe_category]) }}">
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