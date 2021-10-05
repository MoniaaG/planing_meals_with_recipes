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
  $('#calendar').fullCalendar({
    editable: false,
    displayEventTime: false,
    displayEventEnd: false,
    events: "/api/calendar-events",
    eventRender: function eventRender(event, element, view) {
      $(element).each(function () {
        $(this).attr('date-num', event.start.format('YYYY-MM-DD'));
      });
    },
    eventAfterAllRender: function eventAfterAllRender(view) {
      for (cDay = view.start.clone(); cDay.isBefore(view.end); cDay.add(1, 'day')) {
        var dateNum = cDay.format('YYYY-MM-DD');
        var dayEl = $('.fc-day-top[data-date="' + dateNum + '"]');
        var eventCount = $(".fc-event[date-num=\"".concat(dateNum, "\"]")).length;
        var html = eventCount != 0 ? '<div class="event-count">' : '<div class="event-count none">';
        html += eventCount + "<span> ".concat($.i18n._('dashboard/calendar.sessions'), "</span>") + '</div>';
        dayEl.append(html);
      }
    },
    dayClick: function dayClick(start) {
      var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
      $.ajax({
        url: "/api/sessions-day",
        data: 'start=' + start,
        type: "POST",
        success: function success(data) {
          $('#sessions').remove();
          $('#table').append("<tbody id=\"sessions\"></tbody>");
          var tbody = $('#sessions');
          if (data.length == 0) tbody.append("<tr class=\"nothing-to-display\"><td class=\"text-center\" colspan=\"4\">".concat($.i18n._('dashboard/common.nothingToDisplay'), "</td></tr>"));else {
            data.forEach(function (session) {
              var el = "<tr><td title=\"".concat($.i18n._('dashboard/calendar.name'), "\">").concat(session.name, "</td>");
              el += "<td title=\"".concat($.i18n._('dashboard/calendar.startAt'), "\">").concat(session.start, "</td>");
              el += "<td title=\"".concat($.i18n._('dashboard/calendar.endAt'), "\">").concat(session.end, "</td>");
              el += "<td title=\"".concat($.i18n._('dashboard/calendar.confirmPresence'), "\">");
              if (session.confirmPresence) el += "<a href=\"".concat(session.confirmPresence, "\"><button>").concat($.i18n._('dashboard/calendar.confirmPresence'), "</button></a>");
              el += "</td>";
              if (session.sessionOpened) el += "<td title=\"".concat($.i18n._('dashboard/calendar.join'), "\"><a class=\"join-permission\" target=\"_blank\" rel=\"noopener noreferrer\" href=\"").concat(session.route, "\"><button>").concat($.i18n._('dashboard/calendar.join'), "</button></a></td>");else el += "<td title=\"".concat($.i18n._('dashboard/calendar.join'), "\"></td>");
              "\n                            </tr>";
              tbody.append(el);
            });
          }
        }
      });
      $('#sessionsDay').modal('show');
    }
  });
});
/******/ })()
;