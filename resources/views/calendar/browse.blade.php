@extends('layouts.app')

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.js"></script>
@if(app()->getLocale() == 'pl')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/pl.js"></script>
@endif
<link rel="stylesheet" href="{{asset('css/calendar.css')}}" />
<script src="{{asset('js/calendar.js')}}" defer></script>
@endsection

@section('content')
<!-- Modal -->

<div class="modal fade" id="sessionsDay" tabindex="-1" role="dialog" aria-labelledby="sessionsDayLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-white">
                <h3 style="font-size: 20px;">
                    <i class="fas fa-calendar-alt mr-2"></i> {{ __('dashboard/calendar.browseCalendar') }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row px-4 overflow-auto">
                        <table id="table" class="responsive-table-lg">
                            <thead>
                                <tr>
                                    <th>{{ __('dashboard/calendar.name') }}</th>
                                    <th>{{ __('dashboard/calendar.startAt') }}</th>
                                    <th>{{ __('dashboard/calendar.endAt') }}</th>
                                    <th>{{ __('dashboard/common.actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid py-5 px-3 px-md-5" style="font-size: 16px !important;">
    <div class="row m-0">
        <div id='calendar'></div>
    </div>
</div>




@endsection