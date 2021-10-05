@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('product.store')}}" method="post">
            <div class="form-group">
                <label for="name">Nazwa produktu </label>
                <input type="name" class="form-control" id="name" name="name" placeholder="Podaj nazwę produktu">
            </div>
            <div class="form-group">
                <label for="unit_id">Jednostka</label>
                <select class="form-control" id="unit_id" name="unit_id">
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="image">Zdjęcie produktu</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Dodaj produkt do proponowanych</button>
            </form>
        </div>
    </div>
</div>
@endsection