@extends('layouts.app')


@section('content')
<div class="container">
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
        <h1>Wygeneruj listę zakupów</h1>
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

        <p class="font-weight-bold">Typ genrowanej listy zakupów</p>
        <label><input class="mr-2" type="radio" name="list_type" value="1"> Generowanie ilości produktów potrzebych do sporządzenia przepisów na wybrany(e) wyżej dzień(dni). Bez uwzględnienia tego co jest w spiżarni.</label>
        <label><input class="mr-2" type="radio" name="list_type" value="2"> Generowanie ilości produktów uwzględniając aktulany stan produktów w spiżarni.</label>
        <label><input class="mr-2" type="radio" name="list_type" value="3"> Generowanie listy uwzględniającej ilości produktów wykorzystane od dnia dzisiejszego do dnia generowania listy z zachowaniem pozostałych ilości produktów w spiżarni. Oparcie ilości na przepisach jeszcze nie ugotowanych bo do ich realizacji będzie potrzebne wykorzystanie jeszcze nie użytch produktów.</label>

        <button type="submit" class="btn btn-success col-6 my-3 offset-3">Generuj</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#start').change(function(e){
                let valueStart = $(this).val();
                console.log(valueStart);
                let today = new Date();
                valueStart = new  Date(valueStart);
                $('#afterToday').remove();
                $('#afterLabel').remove();
                if(valueStart > today) {
                    let checkbox = document.createElement("input");
                    checkbox.setAttribute("type", "checkbox");
                    checkbox.setAttribute("name", "afterToday");
                    checkbox.setAttribute("id", "afterToday");
                    checkbox.setAttribute("class", "mr-2");
                    console.log(checkbox);
                    $('#start').after(checkbox);
                    $('#afterToday').prop('checked', true);
                    $('#afterToday').after($("<label>").attr({
                        for: $('#afterToday').attr("id"),
                        id: "afterLabel",
                        }).html("Czy uwzględnić dzisiejszy dzień i pomniejszyć o niego ilości produktów w spiżarni?"));
                }
                else {
                    $('#afterToday').remove();
                    $('#afterLabel').remove();
                }
            });
        });
    </script>
@endsection