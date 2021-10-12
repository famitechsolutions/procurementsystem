(function ($) {
    'use strict';
    $(function () {
        if ($('#calendar').length) {
            var today = new Date();
            var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                defaultDate: date,
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                eventSources: [
                    ajax_page + '&loadCalendarEvents=loadCalendarEvents'
                ],
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    var title = prompt("Task Title");
                    if (title) {
                        var start = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');
                        var end = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

                        $.ajax({
                            type: 'POST',
                            url: ajax_page,
                            data: {uploadUserTask: "uploadUserTask", content: title, start_time: start, end_time: end, user_id: user_id},
                            success: function (html) {
                            }
                        });

                        //Save the task in the database
                        calendar.fullCalendar('refetchEvents');
                    }
                },
                eventResize: function (event) {
                    var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss'),
                            end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss'),
                            title = event.title,
                            id = event.id;
                    //Save the task in the database
                    calendar.fullCalendar('refetchEvents');

                },
                eventDrop: function (event) {//On moving the event form place to place
                    var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss'),
                            end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss'),
                            title = event.title,
                            id = event.id;
                    $.ajax({
                        type: 'POST',
                        url: ajax_page,
                        data: {updateUserTask: "updateUserTask", id: id, content: title, start_time: start, end_time: end, user_id: user_id},
                        success: function (html) {
                        }
                    });
                    //Save the task changes in the database
                    calendar.fullCalendar('refetchEvents');
                },
                eventClick: function (event) {
                    var modal=$('#calendar-event'),timeContent=$.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                    modal.modal('show');
                    modal.find('.modal-title').html(event.title);
                    modal.find('#event-description').html(event.description);
                    if(event.is_task){
                        modal.find('#event-description').append(event.response_form);
                    }
                    if(event.end!==null){
                        // timeContent+=' - ' + $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
                    }
                    modal.find('#event-time').html(timeContent);
                    //loadCalendarEvent();
                }
            });
        }
    });
})(jQuery);