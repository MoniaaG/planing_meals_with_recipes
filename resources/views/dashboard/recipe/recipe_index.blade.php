@extends('dashboard.welcome')

@section('scripts-start')
<script src="{{asset('js/delete/recipe.js')}}" defer></script>
@endsection
@section('content')
<div class="container dashboard mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-sm-responsive rounded" style="border-radious: 20px">
            <thead class="thead-dark">
                <tr>
                <th scope="col">@lang('recipe.recipe_name')</th>
                <th scope="col">@lang('recipe.recipe_category')</th>
                <th scope="col">@lang('recipe.shared')</th>
                <th scope="col">@lang('product.actions')</th>
                </tr>
            </thead>
            <tbody>
            @foreach($recipes as $recipe)
                <tr>
                <th scope="row">{{ $recipe->name }}</th>
                <td>{{ $recipe->category->name}}</td>
                <td>@if($recipe->share) tak @else nie @endif</td>
                <td>
                <a class="btn btn-info" href="{{ route('recipe.show', ['recipe' => $recipe])}}" ><i class="fas fa-eye"></i></a>
                <a class="btn btn-success" href="{{route('recipe.edit', ['recipe' => $recipe])}}"><i class="fas fa-edit"></i></a>
                </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
        {{ $recipes->links() }}
    </div>
</div>
@endsection