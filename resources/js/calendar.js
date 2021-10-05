$(document).ready(function() {
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
        eventRender: function(event, element, view) {
            $(element).each(function() {
                $(this).attr('date-num', event.start.format('YYYY-MM-DD'));
            });
        },
        eventAfterAllRender: function(view) {
            for (cDay = view.start.clone(); cDay.isBefore(view.end); cDay.add(1, 'day')) {
                var dateNum = cDay.format('YYYY-MM-DD');
                var dayEl = $('.fc-day-top[data-date="' + dateNum + '"]');
                var eventCount = $(`.fc-event[date-num="${dateNum}"]`).length;
                var html = eventCount != 0 ? '<div class="event-count">' : '<div class="event-count none">';
                html +=
                    eventCount +
                    `<span> ${$.i18n._('dashboard/calendar.sessions')}</span>` +
                    '</div>';
                dayEl.append(html);
            }
        },
        dayClick: function(start) {
            var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
            $.ajax({
                url: "/api/sessions-day",
                data: 'start=' + start,
                type: "POST",
                success: function(data) {
                    $('#sessions').remove();
                    $('#table').append(`<tbody id="sessions"></tbody>`);
                    let tbody = $('#sessions');
                    if (data.length == 0)
                        tbody.append(`<tr class="nothing-to-display"><td class="text-center" colspan="4">${$.i18n._('dashboard/common.nothingToDisplay')}</td></tr>`);
                    else {
                        data.forEach(session => {
                            let el = `<tr><td title="${ $.i18n._('dashboard/calendar.name') }">${session.name}</td>`;
                            el += `<td title="${ $.i18n._('dashboard/calendar.startAt') }">${session.start}</td>`;
                            el += `<td title="${ $.i18n._('dashboard/calendar.endAt') }">${session.end}</td>`;
                            el += `<td title="${ $.i18n._('dashboard/calendar.confirmPresence') }">`;
                                if(session.confirmPresence) el += `<a href="${session.confirmPresence}"><button>${ $.i18n._('dashboard/calendar.confirmPresence') }</button></a>`;
                                el += `</td>`;
                                if(session.sessionOpened)
                                    el += `<td title="${ $.i18n._('dashboard/calendar.join') }"><a class="join-permission" target="_blank" rel="noopener noreferrer" href="${session.route}"><button>${ $.i18n._('dashboard/calendar.join') }</button></a></td>`;
                                else el +=`<td title="${ $.i18n._('dashboard/calendar.join') }"></td>`;`
                            </tr>`;
                            tbody.append(el);
                        })
                    }
                }
            });
            $('#sessionsDay').modal('show');
        },

    });
});