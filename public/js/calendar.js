/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/calendar.js ***!
  \**********************************/
$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var calendar = $('#calendar').fullCalendar({
    editable: true,
    header: {
      left: 'prev, next,today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    }
  });
});
/******/ })()
;