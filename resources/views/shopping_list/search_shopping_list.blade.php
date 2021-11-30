@extends('layouts.app')


@section('content')
<div class="container mb-4">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="col-12 justify-content-center">
    <div class="card text-center">
        <div class="card-header bg-dark text-white">
            <h2>Wygeneruj listę zakupów</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('pantry.whatNeedToBuy')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="start">Od dnia</label>
            <input class="form-control mb-2" type="date" id="start" name="start" min="{{ $today }}" value="{{ $today }}">
        </div>
        <div class="form-group">
            <label for="end">Do dnia</label>
            <input class="form-control" type="date" id="end" name="end" min="{{ $today }}">
        </div>
        <p class="font-weight-bold">Wybierz typ genrowanej listy zakupów</p>
        <div class="form-group text-left">
        <label><input class="mr-2" type="radio" name="list_type" value="1"> Generowanie ilości produktów potrzebych do sporządzenia przepisów na wybrany(e) wyżej dzień(dni). Bez uwzględnienia tego co jest w spiżarni.</label>
        <label><input class="mr-2" type="radio" name="list_type" value="2"> Generowanie ilości produktów uwzględniając aktulany stan produktów w spiżarni.</label>
        <label><input class="mr-2" type="radio" name="list_type" value="3" checked> Generowanie listy uwzględniającej ilości produktów wykorzystane od dnia dzisiejszego do dnia generowania listy z zachowaniem pozostałych ilości produktów w spiżarni. Oparcie ilości na przepisach jeszcze nie ugotowanych bo do ich realizacji będzie potrzebne wykorzystanie jeszcze nie użytch produktów.</label>
        </div>
        </div>
        <div class="card-footer text-muted bg-dark">
            <button type="submit" class="btn btn-success col-6">Generuj</button>
        </form>
        </div>
    </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#start').change(function(e){
            let valueStart = $('#start').val();
            console.log(valueStart);
            $('#end').prop("min", valueStart);
        });
    });
</script>
@endsection