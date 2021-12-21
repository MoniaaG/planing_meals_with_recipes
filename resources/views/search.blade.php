@extends('layouts.app')

@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-stripped table-bordered table-hover table-sm-responsive rounded">
                <thead class="thead-dark">
                <tr>
                    <th class="col-12"><h4>Wyszukane przepisy</h4></th>
                </tr>
                </thead>
                <tbody>
                @foreach($newArray as $recipe)
                
                <tr>
                    <td>
                        <a class="d-inline text-white text-decoration-none" href="{{route('recipe.show', ['recipe' => $recipe])}}">
                            <img width="100" height="100" class="img-thumbnail col-12 mb-3 mb-md-0 col-md-2 p-0 float-left" src="{{asset($recipe->small_image)}}">
                            <h5 class="col-12 p-0 pl-md-4 col-md-3 d-inline-block font-weight-bold">{{$recipe->name}}</h5>  
                            <h6 class="col-12 col-md-7 p-0 pl-md-4 d-inline-block">{{substr(strip_tags($recipe->description),0,500)}}</h6>
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