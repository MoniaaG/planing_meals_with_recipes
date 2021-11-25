@extends('dashboard.welcome')

@section('scripts-start')
<script src="{{asset('js/delete/product_category.js')}}" defer></script>
@endsection
@section('content')
<div class="container dashboard mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table col-md-8 offset-md-3 col-lg-12 offset-lg-1">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Kategoria produktu</th>
                <th scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
            @foreach($product_categories as $product_category)
                <tr>
                <th scope="row">{{ $product_category->name }}</th>
                <td>
                <a class="btn btn-success" href="{{route('product_category.edit', ['product_category' => $product_category])}}"><i class="fas fa-edit"></i></a>
                <a class="delete btn-danger btn" title="Usuń kategorię produktu" data-toggle="tooltip" data-placement="bottom" data-delete-href="{{ route('product_category.destroy', ['product_category' => $product_category]) }}">
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