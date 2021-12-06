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
            <h2>@lang('shopping_list.generate_shopping_list')</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('pantry.whatNeedToBuy')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="start">@lang('shopping_list.start_at')</label>
            <input class="form-control mb-2" type="date" id="start" name="start" min="{{ $today }}" value="{{ $today }}">
        </div>
        <div class="form-group">
            <label for="end">@lang('shopping_list.end_at')</label>
            <input class="form-control" type="date" id="end" name="end" min="{{ $today }}">
        </div>
        <p class="font-weight-bold">@lang('shopping_list.type_of_list')</p>
        <div class="form-group text-left">
        <label><input class="mr-2" type="radio" name="list_type" value="1">@lang('shopping_list.type1')</label>
        <label><input class="mr-2" type="radio" name="list_type" value="2">@lang('shopping_list.type2')</label>
        <label><input class="mr-2" type="radio" name="list_type" value="3" checked>@lang('shopping_list.type3')</label>
        </div>
        </div>
        <div class="card-footer text-muted bg-dark">
            <button type="submit" class="btn btn-success col-6">@lang('shopping_list.generate')</button>
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