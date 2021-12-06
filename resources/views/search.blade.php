@extends('layouts.app')

@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-stripped table-bordered table-hover table-sm-responsive rounded">
                <thead class="thead-dark">
                <tr>
                    <th class="col-2">@lang('recipe.recipe_photo')</th>
                    <th class="col-3">@lang('recipe.recipe_name')</th>
                    <th class="col-7">@lang('recipe.recipe_description')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($newArray as $recipe)
                
                <tr>
                    <td colspan="3">
                        <a class="d-inline-block w-100 text-dark text-decoration-none" href="{{route('recipe.show', ['recipe' => $recipe])}}">
                            <img width="100" height="100" class="img-thumbnail col-2 p-0 float-left" src="{{asset($recipe->small_image)}}">
                            <h6 class="col-3 d-inline-block">{{$recipe->name}}</h6>  {{substr(strip_tags($recipe->description),0,500)}}
                        </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
                 @if(count($newArray) == 0)
                <tr>
                    <td colspan="3" class="text-center"><h3>@lang('recipe.no_results')</h3></td>
                </tr> 
                @endif
            </table>
        </div>
    </div>
</div>
<div class="pagination-items container mb-3">
    {{ $newArray->appends(['search' => request()->search, 'per_page' => request()->per_page])->links()  }}
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/likes.js')}}" defer></script>
@endsection