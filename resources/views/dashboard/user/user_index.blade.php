@extends('dashboard.welcome')

@section('scripts-start')
<script src="{{asset('js/delete/user.js')}}" defer></script>
@endsection
@section('content')
<div class="container dashboard mt-5">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm-responsive table-md-responsive">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Nazwa użytkownika</th>
                <th scope="col">Email</th>
                <th scope="col">Rola</th>
                <th scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                <td scope="row">{{ $user->name }}</td>
                <td scope="row">{{ $user->email }}</td>
                <td scope="row">{{ $user->role }}</td>
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