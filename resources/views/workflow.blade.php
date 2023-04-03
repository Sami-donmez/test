@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-12">
            <a class="btn btn-success" href="{{route('leads.create')}}">Add</a>
            <button class="btn btn-success">Archive</button>
        </div>
        <div class="col-12">
            <div class="white_card">
                <div class="card-body">
                    <div id="myKanban"></div>
                </div>
            </div>
        </div>
        <div class="container  m-b-30">
            <div class="row">
                <div class="col-12 text-white p-t-40 p-b-90">
                    <h5>Kandidatenflow</h5>
                    <p class="opacity-75"></p>
                </div>
            </div>
        </div>

        <div class="container-fluid pull-up">
            <div class="row">
                <div class="col-12">
                    <div class="kanban-workspace row" id="kanban-main"></div>
                </div>
            </div>
        </div>


    </div>
    <style>
        .card .avatar-group {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 36px 1.25rem;
            padding-bottom: 0;
            justify-content: end;
        }

        .card-header {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #kanban-main {
            display: flex; /* [1] */
            flex-wrap: nowrap; /* [1] */
            overflow-x: auto; /* [1] */
            -webkit-overflow-scrolling: touch; /* [4] */
            -ms-overflow-style: -ms-autohiding-scrollbar; /* [5] */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: grab;
        }

        .kanban-list-container {
            cursor: default !important;
        }

        .modal {
            overflow-y: auto !important;
        }

        .kanban-workspace .kanban-list {
            width: 300px;
            min-width: 300px;
            padding: 10px
        }

        .kanban-workspace .kanban-list .kanban-list-container {
            padding: 10px;
            border-radius: .25rem;
            background-color: #d2ddec
        }

        .kanban-workspace .kanban-list .kanban-list-container .kanban-list-wrapper {
            padding: 10px 0
        }

        .kanban-board-title {
            margin: 0;
            padding: 5px;
            transition: all ease .2s;
            border-radius: .25rem
        }

        .kanban-board-title:focus, .kanban-board-title:hover {
            outline: 0;
            background-color: rgba(0, 0, 0, .15)
        }

        .kanban-item {
            margin-bottom: 10px;
            transiton: all ease .2s
        }

        .kanban-ribbion {
            position: absolute;
            right: 0;
            left: 0;
            height: 4px;
            border-radius: .25rem
        }

    </style>
@endsection

@section('js')

    <script>
        $.ajax({
            url: "{{route('tasks.data.kanban')}}", success: function (result) {
                var object = result.data;
                var kanban = new jKanban({
                    element: '#myKanban',                                           // selector of the kanban container
                    gutter: '15px',                                       // gutter of the board
                    widthBoard: '250px',                                      // width of the board
                    responsivePercentage: false,                                    // if it is true I use percentage in the width of the boards and it is not necessary gutter and widthBoard
                    dragItems: true,                                         // if false, all items are not draggable
                    boards: object,                                         // json of boards
                    dragBoards: true,                                         // the boards are draggable, if false only item can be dragged
                    itemAddOptions: {
                        enabled: false,                                              // add a button to board for easy item creation
                        content: '+',                                                // text or html content of the board button
                        class: 'kanban-title-button btn btn-default btn-xs',         // default class of the button
                        footer: false                                                // position the button on footer
                    },
                    itemHandleOptions: {
                        enabled: false,                                 // if board item handle is enabled or not
                        handleClass: "item_handle",                         // css class for your custom item handle
                        customCssHandler: "drag_handler",                        // when customHandler is undefined, jKanban will use this property to set main handler class
                        customCssIconHandler: "drag_handler_icon",                   // when customHandler is undefined, jKanban will use this property to set main icon handler class. If you want, you can use font icon libraries here
                        customHandler: "<span class='item_handle'>+</span> %title% "  // your entirely customized handler. Use %title% to position item title
                                                                                      // any key's value included in item collection can be replaced with %key%
                    },
                    click: function (el) {
                    },                             // callback when any board's item are clicked
                    context: function (el, event) {
                    },                      // callback when any board's item are right clicked
                    dragEl: function (el, source) {
                    },                     // callback when any board's item are dragged
                    dragendEl: function (el) {
                    },                             // callback when any board's item stop drag
                    dropEl: function (el, target, source, sibling) {
                    },    // callback when any board's item drop in a board
                    dragBoard: function (el, source) {
                    },                     // callback when any board stop drag
                    dragendBoard: function (el) {
                    },                             // callback when any board stop drag
                    buttonClick: function (el, boardId) {
                    },                     // callback when the board's button is clicked
                    propagationHandlers: [],                                         // the specified callback does not cancel the browser event. possible values: "click", "context"
                })
            }
        });


        (function ($) {
            'use strict';

            // horizontal scroll
            var down = false;
            var downSortable = true;
            var posX = 0;

            $("#kanban-main").mousedown(function (e) {
                down = true;
                posX = e.pageX + $("#kanban-main").scrollLeft();
                $("#kanban-main").css("cursor", "grabbing");
            });

            $("#kanban-main").mousemove(function (e) {
                if (down && downSortable) {
                    $("#kanban-main").scrollLeft(posX - e.pageX);
                }
            });

            $("#kanban-main").mouseup(function (e) {
                down = false;
                $("#kanban-main").css("cursor", "grab");
            });

            $(document).on("click", ".kanban-item-title", function (e) {
                console.log('tıklanıyı')
                var id = $(this).data('id');
                $("#name-show").val("");
                $("#surname-show").val("");
                $("#phone-show").val("");
                $("#email-show").val("");
                $("#sector-show").val("");
                $("#position-show").val("");
                $("#cancidate_update_id").val();
                $("#note").val();
                $.ajax({
                    type: "POST",
                    url: "./api/kanban.php?key=c133a7a26e14f6e30f542b6ca83e405c&request=get_cancidate_detail",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        console.log('tıklanıyı2');
                        $("#name-show").val(data.name);
                        $("#surname-show").val(data.surname);
                        $("#phone-show").val(data.phone);
                        $("#email-show").val(data.email);
                        $("#sector-show").val(data.sector);
                        $("#position-show").val(data.position);
                        $("#cancidate_update_id").val(data.id);
                        $("#note").val(data.note);
                        var divstr = "<table class='table'>"
                        divstr += "<tr><td>Totale score</td><td>" + data.score.total + "</td></tr>";
                        divstr += "<tr><td>Gemiddelde score</td><td>" + data.score.average + "</td></tr>";

                        data.score.score.forEach(function (val, index) {
                            divstr += "<tr><td>Score " + (index + 1) + "</td><td>" + val + "</td></tr>";
                        });
                        divstr += "</table>"
                        $('#scores').html(divstr);

                        $("#cancidate_detail").modal("show");
                    }
                });
            });
            $(document).on("click", ".js-delete-list", function (e) {
                e.preventDefault();
                $(this).parents('.kanban-list').remove();
            });

            $(document).on("keypress", ".kanban-board-title", function (e) {
                if (e.keyCode == '13') {
                    e.preventDefault();
                }
            });
            $(document).on("click", ".js-addCard", function (e) {
                e.preventDefault();
                $(this).parents('.kanban-list').find(".kanban-list-wrapper").append('<div class="kanban-item-create"> ' +
                    '<div class="card"> <div class="card-body"> <textarea class=" form-control kaban-name"' +
                    ' placeholder="Enter title here"></textarea> <div class="p-t-10 text-right"> <a href="#" ' +
                    'class="btn-sm btn-white m-r-10 js-kaban-cancel">cancel</a>' +
                    ' <a href="#" class="btn-sm btn-primary js-kaban-save">save</a> ' +
                    '</div> </div> </div> </div>');
            });
            $(document).on("click", ".js-kaban-save", function (e) {
                e.preventDefault();
                addTask(this);
            });

            $(document).on("click", ".js-kaban-cancel", function (e) {
                $(this).parents('.kanban-list').find(".kanban-item-create").remove();
                kanbanSortable();
            });

            $(document).on("click", "#js-add-board", function (e) {
                e.preventDefault();
                $(".kanban-workspace").append($("#kanban-list-template").html());
                kanbanSortable()

            });

            function kanbanSortable() {
                // $('.kanban-list-wrapper').sortable({
                //     connectWith: ".kanban-list-wrapper",
                //     start: function (event, ui) {
                //         downSortable = false;
                //         $(ui.item).addClass('tilt');
                //     },
                //     stop: function (event, ui) {
                //         downSortable = true;
                //         $(ui.item).removeClass('tilt');
                //         updateTask(ui.item);
                //     }
                // });
                var sortableIn = 0;
                $(".avatar-group").sortable({
                    connectWith: ".avatar-group",
                    start: function (event, ui) {
                        downSortable = false;
                        if ($(ui.item).parent().hasClass("avatar-main")) {
                            var cloned = $(ui.item).clone();
                            $(cloned).attr("style", "");
                            $(ui.item).before(cloned);
                        }
                    },
                    over: function (event, ui) {
                        sortableIn = 1;
                    },
                    out: function (event, ui) {
                        sortableIn = 0;

                    },
                    beforeStop: function (event, ui) {
                        var assign = false;
                        downSortable = true;
                        if (!$(ui.item).parent().hasClass("avatar-main")) {
                            $(ui.item).addClass("avatar-xs");
                            $(ui.item).removeClass("avatar-sm");
                            assign = true;
                        }
                        if (sortableIn == 0) {
                            removeTaskEmployee(ui.item);
                            assign = false;
                        } else {
                            var employees = [];
                            $(ui.item).parent().children().each(function () {
                                if (employees.includes($(this).data('id'))) {
                                    ui.item.remove();
                                    assign = false;
                                } else {
                                    employees.push($(this).data('id'));
                                }
                            });
                        }
                        if (assign) {
                            assignTask(ui.item);
                        }
                    }
                });
                /*
                // sortable lists
                if (window.innerWidth >= 960) {
                    $('.kanban-workspace').sortable({
                        cancel: '[contenteditable],textarea'
                    });
                }
                */

            }

            function assignTask(el) {
                var id_employee = $(el).data("id");
                var id_offer = $(el).parent().parent().parent().data("id");
                var id_te = "";
                if ($(el).data("idte")) {
                    id_te = $(el).data("idte");
                }

                $.ajax({
                    type: "POST",
                    url: "./assets/php/ajax.php?request=assignTask",
                    data: {
                        id_offer: id_offer,
                        id_employee: id_employee,
                        id_te: id_te
                    },
                    success: function (raw) {
                        if (raw.status == "error") {
                            console.log(raw.data);
                        } else if (raw.status == "success") {
                            if (typeof raw.data !== "undefined") {
                                $(el).data("idte", raw.data);
                            }
                        }
                    }
                });
            }

            function addTask(el) {
                $(el).parent().parent().parent().parent().remove();
                /*
                var id_board = $(el).parent().parent().parent().parent().parent().data('id');
                var title = $(el).parent().parent().children("textarea").val();

                $.ajax({
                    type: "POST",
                    url: "./assets/php/ajax.php?addTask",
                    data: {
                        title: title,
                        id_board: id_board
                    },
                    success: function (data) {
                        if(data == 'error'){
                            console.log('Error updating task.');
                        }else{
                            $(el).parent().parent().parent().parent().parent().append('<div class="kanban-item" data-id="' + data + '">' +
                                ' <div class="card"> <div class="card-header"> ' +
                                '<span class="kanban-item-title"> ' + title + ' </span>' +
                                '</div> </div> </div>');
                            $(el).parent().parent().parent().parent().remove();
                            kanbanSortable();
                        }
                    }
                });
                */

                console.log("function is disabled");

            }

            function updateTask(el) {
                var id_offer = el.data('id');
                var id_list = $(el).parent().data('id');
                $.ajax({
                    type: "POST",
                    url: "./assets/php/ajax.php?request=updateTask",
                    data: {
                        id_offer: id_offer,
                        id_list: id_list
                    },
                    complete: function (data) {
                        if (data.responseJSON != 'success') {
                            console.log('Error updating task');
                            console.log(data);
                        }
                    }
                });
            }

            function removeTaskEmployee(el) {
                var id_te = $(el).data('idte');
                $(el).remove();
                if (id_te) {
                    $.ajax({
                        type: "POST",
                        url: "./assets/php/ajax.php?request=removeTaskEmployee",
                        data: {
                            id_te: id_te
                        },
                        success: function (data) {
                            if (data.status != 'success') {
                                console.log('Error removing task.');
                            }
                        }
                    });
                }
            }

            function getEmployees() {
                $.ajax({
                    type: "POST",
                    url: "./assets/php/ajax.php?request=getEmployees",
                    success: function (raw) {
                        if (raw.status == 'success') {
                            for (let i = 0; i < raw.data.length; i++) {
                                var employee = raw.data[i];
                                // disabled reserved pool ID 99 for now (used for tasks without assigned employees, can be changed to 0 or "")
                                if (employee.id !== '99') {
                                    $(".avatar-main").append(
                                        `<div class="avatar avatar-sm" data-id="` + employee.id + `">
                                    <span class="avatar-title rounded-circle bg-dark border border-light">` + employee.name[0] + employee.surname[0] + `</span>
                                </div>`);
                                }
                            }
                        } else {
                            console.log('Error getting employees: '.raw.data);
                        }
                    }
                });
            }

            function getLists() {
                // get branche from url
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const b = urlParams.get('b');
                var id = $('#vacany_id').val();

                $.ajax({
                    type: "POST",
                    url: "./api/kanban.php?key=c133a7a26e14f6e30f542b6ca83e405c&request=get_cancidate",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        $("#kanban-main").empty();
                        if (data.length > 0) {
                            for (let i = 0; i < data.length; i++) {
                                var items = "";
                                console.log(i);
                                var vacanies = data[i].data;
                                for (let j = 0; j < vacanies.length; j++) {
                                    var item = vacanies[j];
                                    var people = '';
                                    var ribbon = '';
                                    // for (let k = 0; k < item.employees.length; k++) {
                                    //     var employee = offer.employees[k];
                                    //     people +=
                                    //         `<div class="avatar avatar-xs ui-sortable-handle" data-id="` + employee.id + `" data-idte="` + employee.idte + `">
                                    //             <span class="avatar-title rounded-circle bg-dark border border-light">` + employee.name[0] + employee.surname[0] + `</span>
                                    //         </div>`
                                    // }


                                    switch (item.priority) {
                                        case '0':
                                            ribbon = '<div class="kanban-ribbion bg-primary"></div>';
                                            break;
                                        case '1':
                                            ribbon = '<div class="kanban-ribbion bg-success"></div>';
                                            break;
                                        case '2':
                                            ribbon = '<div class="kanban-ribbion bg-warning"></div>';
                                            break;
                                        case '3':
                                            ribbon = '<div class="kanban-ribbion bg-danger"></div>';
                                            break;
                                        default:
                                            ribbon = '<div class="kanban-ribbion bg-info"></div>';
                                            break;
                                    }

                                    if (item.type == "zakelijk") {
                                        var offerIcon = "<i class='fa fa-building'></i> ";
                                    } else {
                                        var offerIcon = "<i class='fa fa-user'></i> ";
                                    }

                                    if (item.report == 1) {
                                        offerIcon += "<i class='fa fa-file-alt'></i> ";
                                    }
                                    var btn = ''
                                    if (i == 0) {
                                        btn += '<a style="z-index: 999" onclick="getModal(' + item.id + ')" class="btn btn-info"><span class="mdi mdi-calendar-plus"></span></a>\n'

                                    }
                                    if (i == 2) {
                                        btn += '<a style="z-index: 999" onclick="statusUpdate(' + item.id + ',4)" class="btn btn-success">Aannemen</a>'
                                        btn += '<a style="z-index: 999" onclick="statusUpdate(' + item.id + ',5)" class="btn btn-danger">Afwijzen</a>'
                                    }

                                    items +=
                                        `<div class="kanban-item" data-id="` + item.id + `">
                                    <div class="card">
                                        ` + ribbon + `
                                        <div class="card-header">
                                            <span class="kanban-item-title" data-id="` + item.id + `">` + offerIcon + item.name + " " + item.surname + `</span>
                                        </div>

                                        <div class="card-footer">
                                        ` + btn + `
                                        </div>
                                    </div>
                                </div>`;
                                }
                                $("#kanban-main").append(
                                    `<div class="kanban-list col-md-auto col">
                                <div class="kanban-list-container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="w-100 kanban-board-title h5">` + data[i].title + `</div>
                                        </div>
                                    </div>
                                    <div class="kanban-list-wrapper ui-sortable" data-id="` + data[i].id + `">
                                    ` + items + `
                                    </div>
                                    <div class="m-b-10">
                                    </div>
                                </div>
                            </div>`);
                                kanbanSortable();
                            }

                        }
                    }
                });
            }

            getLists();
            getEmployees();
        })(window.jQuery);
    </script>
@endsection
