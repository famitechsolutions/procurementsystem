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
                dayRender: function (day, cell) {

                    var originalClass = cell[0].className;
                    cell[0].className = originalClass + ' hasmenu1';
                    $('.fc-day-top').addClass(' hasmenu1');
                    //fc-day-top
                    $.contextMenu({
                        selector: '.hasmenu1',
                        // trigger:'left',
                        callback: function(key, options,event) {
                          var m = "clicked: " + key;
                        //   window.console && console.log(m) || alert(m);
                        //Item clicked can be accessed via options.$trigger
                        //Get date clicked
                        var dateItem=options.$trigger.closest('.hasmenu1').find('.fc-day-top'),
                        date=$(dateItem).attr("data-date");//dateItem.attr("data-date");

                          console.log(date);
                        },
                        items: {
                          "schedule_one_on_one": {
                            name: "Schedule One on One After",
                            icon: "edit",
                            accesskey: "schedule"
                          },
                          // words are truncated to their first letter (here: p)
                          "add_task": {
                            name: "Add Task",
                            icon: "edit",
                            accesskey: "add_task",
                          }
                        }
                      });


                },
                eventRender: function(event, element) {
                    var startTime = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                    var endTime =event.end!=null? $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss'):'';

                    var location = "";
                    $(element).popover({
                        title: event.title,
                        placement:'top',
                        trigger : 'hover',
                        content: startTime + " to " + endTime + " " + location,
                        container:'body'
                    }).popover('show');
                    // var originalClass = element[0].className;
                    // element[0].className = originalClass + ' hasmenu1';
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
                dayClick: function(start, end, allDay, jsEvent, view) { 
                    $(this).popover({
                        html:true,
                        title: "Actions",
                        placement:'top',
                        trigger : 'hover',
                        content: function(){
                            return `<a class="btn" id="new-task"><i class="fa fa-plus text-primary"></i> Add New Task</a>
                        <a class="btn" id="new-schedule"><i class="fa fa-plus text-primary"></i> Schedule One-On-One Meeting</a>`;},
                        container:'body'
                    });
                    $(this).popover('toggle');
                    $(this).on('shown.bs.popover', function () {
                        $('.popover-body a').click(function(){
                            var key=$(this).attr("id");
                            alert(key);
                            //custom logic
                         });
                    })
                  },
                select: function (start, end, allDay) {
                    // var title = prompt("Task Title");
                    
                    // if (title) {
                    //     var startFormatted = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');
                    //     var endFormatted = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

                    //     calendar.fullCalendar('renderEvent',{
                    //         title:title,
                    //         start:start,end:end,allDay:allDay
                    //     },true);
                    //     calendar.fullCalendar("unselect");

                    //     $.ajax({
                    //         type: 'POST',
                    //         url: ajax_page,
                    //         data: {uploadUserTask: "uploadUserTask", content: title, start_time: startFormatted, end_time: endFormatted, user_id: user_id},
                    //         success: function (html) {
                                
                    //         }
                    //     });

                    //     //Save the task in the database
                    //     calendar.fullCalendar('refetchEvents');
                    // }
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
            // calendar.on('contextmenu',function(event){
            //     var view = calendar.fullCalendar('getView');
            //     var hit = view.queryHit(event.pageX,event.pageY);
            //     // var span = view.getHitSpan(hit);
            //     //span.start is the date you clicked on
            //     console.log(hit);
            //     });
        }
    });
})(jQuery);