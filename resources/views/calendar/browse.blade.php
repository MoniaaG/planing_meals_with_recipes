@extends('layouts.app')

@section('scripts-start')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.3.0/main.min.js"></script>
<link href="{{asset('fullcalendar/main.css')}}" rel='stylesheet' />
<script src="{{asset('fullcalendar/main.js')}}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        editable: true,
        headerToolbar: {
            center: 'dayGridMonth,timeGridFourDay' // buttons for switching between views
        },
        views: {
            timeGridFourDay: {
                type: 'timeGrid',
                duration: { days: 7 },
                buttonText: '7 dni'
            },
            dayGridMonth: {
                buttonText: 'miesiąc'
            }
        },
        locale: 'pl'
    });
    calendar.render();
});
</script>
@endsection

@section('content')
    <div class="container">
        <div id="calendar"></div>
    </div>
@endsection

