@extends('layouts.app')

@section('scripts-start')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.3.0/main.min.js"></script>
<link href="{{asset('fullcalendar/main.css')}}" rel='stylesheet' />
<script src="{{asset('fullcalendar/main.js')}}"></script>
<script src="{{asset('js/delete/calendar_recipe.js')}}" defer></script>
<script src="{{asset('js/assign_recipe.js')}}" defer></script>
<script src="{{asset('js/unsign_recipe.js')}}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var calendar = new FullCalendar.Calendar(calendarEl, {
        editable: false,
        selectable: true,
        headerToolbar: {
            left: 'prev,next,today',
            center: 'title',
            right: 'dayGridMonth' // buttons for switching between views
        },
        views: {
            today: {
                buttonText: 'dziś'
            },
            dayGridMonth: {
                buttonText: 'miesiąc'
            },
        },
        events: "{{ route('calendar.index') }}",
        dateClick:function(date,event,view){
            $('#start_at').val(date.dateStr + " 00:00:00");
            $('.modal-title').html('Lista przepisów ' + date.dateStr);
            $('#modal_title').html('Dodaj danie w dniu: ' + date.dateStr);
            
            $.ajax({
                url: "{{ route('calendar_day')}}",
                data: 'start=' + date.dateStr,
                type: "POST",
                success: function(events) {
                    $('#recipes').remove();
                    $('#table').append(`<tbody id="recipes"></tbody>`);
                    let tbody = $('#recipes');
                    if(events.length == 0){
                        tbody.append(`<tr class="text-center text-white bg-danger font-weight-bold"><td colspan="2">Brak zaplanowanych przepisów na wybrany dzień!</td></tr>`);
                    }
                    else {
                        events.forEach(recipe => {
                            let el = `<tr><td class="col-8 my-1" style="background-color: ${recipe.color}; color: ${recipe.textColor};">${recipe.title}</td>`;
                            el += `<td class="d-flex col-4">
                            <a class="btn-info btn text-white" data-toggle="tooltip" href="{{URL::to('recipe/show/${recipe.recipe_id}')}}/"><i class="fas fa-eye"></i></a>
                            <button class="btn-white btn text-success border-success" data-toggle="tooltip"`;
                            if(recipe.cooked) el += `data-unsign-href="{{URL::to('calendar/unsign/${recipe.id}')}}/">`; else el+= `data-assign-href="{{URL::to('calendar/assign/${recipe.id}')}}/">`; 
                            el += `<i class="`;
                            if(recipe.cooked) el += `fas `; else el += `far `;
                            el += `fa-check-square"></i></button>
                            <button class="btn-danger btn" data-toggle="tooltip" data-delete-href="{{URL::to('calendar/destory/${recipe.id}')}}/"><i class="fas fa-trash"></i></button>
                            </td></tr>`;
                            tbody.append(el);
                        })
                    }
                }
            });
            $("#exampleModal").modal("show");
        },
        locale: 'pl',
    });
    calendar.render();
});
</script>
@endsection

@section('content')
    <div class="container mb-4">
        <div class="mb-4" id="calendar"></div>
    </div>
<div class="modal fade show" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lista przepisów</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="table" class="table">
            <thead>
                <tr>
                    <th class="col-8">Nazwa przepisu</th>
                    <th class="col-4">Opcje</th>
                </tr>
            </thead>
            <tbody id="recipes"></tbody>
        </table>
      </div>
      <div class="modal-footer" style="display: inline;">
      <div class="modal-header">
        <div id="modal_title" class="modal-title">Dodaj danie</div>
      </div>
        <form id="dayClick" action="{{ route('calendar_recipe.store') }}" method="post">
            @csrf
            <div class="form-group">
            <select class="form-select recipe_id" data-route="{{ route('searchRecipe') }}" id="recipe_id" name="recipe_id" style="width: 100%" aria-label="Default select example">
            </select>
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" id="start_at" name="start_at" placeholder="Data i czas rozpoczęcia">
            </div>

            <div class="form-group">
                <input type="hidden" class="form-control" id="end_at" name="end_at" placeholder="Data i czas zakończenia">
            </div>

            <div class="form-group">
                <label>Kolor tła</label>
                <input type="color" class="form-control" name="background_color" value="#0000ff">
            </div>

            <div class="form-group">
                <label>Kolor tekstu</label>
                <input type="color" class="form-control" name="text_color" value="#ffffff">
            </div>

            <div class="form-group">
                
            </div>
            <button type="submit" class="btn btn-success col-12">Dodaj przepis</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <script>
        $(document).ready(function() {
            $('.recipe_id').select2({
                dropdownParent: $("#exampleModal"),
                ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('searchRecipe') }}",
                type: 'post',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        search: params.term,
                    }
                },
                processResults: function (response) {
                console.log(response);
                    return {
                        results: response
                    }
                },
                cache: true,
            }
            }).on('select2:select', function (e) {
                console.log(e);
                var data = e.params.data;  
            });
        })
    </script>
@endsection

