@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Nazwa przepisu</th>
                <th scope="col">Kategoria przepisu</th>
                <th scope="col">Udostepniony</th>
                <th scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
            @foreach($recipes as $recipe)
                <tr>
                <th scope="row">{{ $recipe->name }}</th>
                <td>{{ $recipe->category->name}}</td>
                <td>@if($recipe->share) tak @else nie @endif</td>
                <td>
                <i class="fas fa-eye"></i>
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash"></i>
                </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection