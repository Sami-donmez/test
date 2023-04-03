@extends('layouts.app')

@section('content')
    <div class="container  m-b-30">
        <div class="row">
            <div class="col-9 text-white p-t-40 p-b-90">
                <h5>Planning</h5>
                <p class="opacity-75"> Hie kunt u uw planning bijwerken en de afspraken inzien waarvoor u bent uitgenodigd door een collega.</p>
                <button class="btn btn-light btn-outline-dark btn-rounded" onclick="getAddModal()"><i class="fa fa-plus m-r-5"></i>Nieuwe afspraak plannen</button>
            </div>
            <div class="col-3 text-white p-t-40 p-b-90">
                <div class="text-md-right">
                    <p>
                        <a href="" class="btn  btn-primary" >Individueel</a>
                        <a href="" class="btn btn-primary">Team
                        </a>
                    </p>
                    <p class="opacity-75">De kalender wordt gedeeld met:</p>
                    <div class="avatar-group">
                        <div class="avatar">
                            <a href="planning.php?type=all"><span class="avatar-title rounded-circle bg-warning">Team</span></a>
                        </div>
                        <
                        <div class="avatar select-avatar">
                            <a href="planning.php?" class=""></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container pull-up">
        <div class="row">
            <div class="col-md-12 m-b-30">
                <div class="card">
                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="modal fade show" id="calendar-update" tabindex="-1" role="dialog" aria-labelledby="workflow-addLabel" aria-modal="true">
        <div class="modal-dialog modal-xxl modal-dialog-align-top-left" style="max-width: 90%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notesModalLabel">Afspraak plannen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <input type="hidden" class="form-control" name="id" required id="id">

                                        <th>Titel:</th>
                                        <td>
                                            <input type="text" class="form-control" name="title-show" required id="title-show">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Details:</th>
                                        <td>
                                            <textarea name="detail-show" class="form-control" id="detail-show"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Start:</th>
                                        <td>
                                            <input type="datetime-local" class="form-control" name="start-show" id="start-show">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Eind:</th>
                                        <td>
                                            <input type="datetime-local" class="form-control" name="end-show" id="end-show">
                                        </td>
                                    </tr>
                                    <tr id="    url-show-area">
                                        <th>Vergaderlink:</th>
                                        <td>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend mx-2">
                                                    <a href="https://meet.google.com/" target="_blank"><img src="./assets/img/meet.png" style="height: 24px;width: 24px;margin: 5px"></a>
                                                    <a href="https://zoom.us/" target="_blank"><img src="./assets/img/zoom.png" style="height: 24px;width: 24px;margin: 5px"></a>
                                                    <a href="https://teams.microsoft.com/" target="_blank"><img src="./assets/img/teams.png" style="height: 24px;width: 24px;margin: 5px"></a>
                                                </div>
                                                <input type="text" class="form-control" name="url" id="url-show">
                                            </div>
                                        </td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" onclick="calendarDelete()">gebeurtenis verwijderen</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" onclick="calendarUpdate()">Opslaan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="calendar-add" tabindex="-1" role="dialog" aria-labelledby="workflow-addLabel" aria-modal="true">
        <div class="modal-dialog modal-xxl modal-dialog-align-top-left" style="max-width: 90%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="notesModalLabel">Afspraak plannen</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>Titel:</th>
                                        <td>
                                            <input type="hidden" class="form-control" name="cancidate_id" id="cancidate_id">

                                            <input type="text" class="form-control" name="title" required id="title">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Details:</th>
                                        <td>
                                            <textarea name="detail" class="form-control" id="detail"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Start:</th>
                                        <td>
                                            <input type="datetime-local" class="form-control" name="start" id="start">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Eind:</th>
                                        <td>
                                            <input type="datetime-local" class="form-control" name="end" id="end">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Vergaderlink:</th>
                                        <td>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend mx-2">
                                                    <a href="https://meet.google.com/" target="_blank"><img src="./assets/img/meet.png" style="height: 24px;width: 24px;margin: 5px"></a>
                                                    <a href="https://zoom.us/" target="_blank"><img src="./assets/img/zoom.png" style="height: 24px;width: 24px;margin: 5px"></a>
                                                    <a href="https://teams.microsoft.com/" target="_blank"><img src="./assets/img/teams.png" style="height: 24px;width: 24px;margin: 5px"></a>
                                                </div>
                                                <input type="text" class="form-control" name="url" id="url">
                                            </div>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" onclick="calendarCreate()">Opslaan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>

    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.4/locales-all.global.min.js'></script>

    <script>
        $(function () {

            'use strict';
            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                locale: 'nl',
                buttonIcons: false, // show the prev/next text
                weekNumbers: true,
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                eventSources: [
                    // your event source
                    {
                        url: '{{route('calendar.data')}}',
                        method: 'POST',
                        failure: function() {
                            alert('there was an error while fetching events!');
                        },
                        color: 'yellow',   // a non-ajax option
                        textColor: 'black' // a non-ajax option
                    }

                ],
                eventClick: function(info) {
                    getModal(info.event.id);
                    // change the border color just for fun
                },
            });

            calendar.render();

            /* initialize the external events
             -----------------------------------------------------------------*/
            function init_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    })

                })
            }

            init_events($('#external-events .js-external-event'));

            /* ADDING EVENTS */
            var currColor = '#B1C2D9';
            $('.js-event-color').click(function (e) {
                e.preventDefault()
                //Save color
                currColor = $(this).css('background-color');
                //Add color effect to button
                $('#add-new-event').css({'background-color': currColor, 'border-color': currColor})
            })
            $('#add-new-event').click(function (e) {
                e.preventDefault()
                //Get value and make sure it is not null
                var val = $('#new-event').val();
                if (val.length == 0) {
                    return
                }

                //Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color': currColor,
                    'color': '#fff'
                }).addClass('js-external-event m-b-10 rounded p-all-10 text-white ');
                event.html(val);
                $('#external-events').prepend(event);

                //Add draggable funtionality
                init_events(event);

                //Remove event from text input
                $('#new-event').val('')
            })
        })
        function getAddModal() {
            $('#calendar-add').modal('show');
        }
        function getModal(id){
            $.ajax({
                url: "{{route('calendar.data')}}",
                crossDomain: true,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $("#url-show-area").show();
                    $("#id").val(data.id);
                    $("#title-show").val(data.title);
                    $("#detail-show").val(data.detail);
                    $("#start-show").val(data.start_date);
                    $("#end-show").val(data.finish_date);
                    console.log(data.type == 'leave',data.type , 'leave')
                    if (data.type == 'leave'){
                        $("#url-show").val(data.url);
                        $("#url-show-area").hide();
                    }

                    $('#calendar-update').modal('show');
                }
            });
        }
        function calendarUpdate(){
            $.ajax({
                type: "POST",
                url: "./api/planning.php?key=c133a7a26e14f6e30f542b6ca83e405c&request=update_calendar",
                data: {
                    id: $("#id").val(),
                    title: $("#title-show").val(),
                    detail: $("#detail-show").val(),
                    start: $("#start-show").val(),
                    end: $("#end-show").val(),
                    url: $("#url-show").val(),
                },
                success: function(data) {
                    $("#calendar-add").modal("hide");
                    // location.reload();
                }
            });
        }
        function calendarDelete(){
            var id =  $("#id").val();
            $.ajax({
                type: "POST",
                url: "./api/planning.php?key=c133a7a26e14f6e30f542b6ca83e405c&request=delete_calendar&id="+id,
                success: function(data) {
                    $("#calendar-add").modal("hide");
                    location.reload();
                }
            });
        }
        function calendarCreate(){

            $.ajax({
                type: "POST",
                url: "./api/planning.php?key=c133a7a26e14f6e30f542b6ca83e405c&request=create_calendar",
                data: {
                    title: $("#title").val(),
                    detail: $("#detail").val(),
                    start: $("#start").val(),
                    end: $("#end").val(),
                    url: $("#url").val(),
                    id: null,
                },
                success: function(data) {
                    $("#calendar-add").modal("hide");
                    location.reload();
                }
            });
        }
        function datetimeLocal(datetime) {
            console.log(datetime);
            const dt = new Date(datetime);
            //dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
            return dt.toISOString().slice(0, 16);
        }


    </script>

@endsection
