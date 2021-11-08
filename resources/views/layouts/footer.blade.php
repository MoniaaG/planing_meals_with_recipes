@extends('layouts.app')

@section('footer')
<div class="bg-dark" style="height: 100px;">
    @toastr_css
    @jquery
    @toastr_js
    @toastr_render</div>
@endsection