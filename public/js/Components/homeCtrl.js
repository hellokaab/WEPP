app.controller('homeCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;

    var current_time = new Date();
    keepHistory($window.user.id,"home",dtJsToDtDB(current_time));
    var user_online = findWebHistory();
    $scope.userOnline = user_online;

    var event = findMyEvent($scope.user.id,$scope.user.user_type);
    console.log(event);
    var ppEvent = prepareEventString(event);
    console.log(ppEvent);

    $(document).ready(function () {
        $('#side_home').attr('class','active');
        $('#home_div').show();

        $('#calendar').fullCalendar({
            themeSystem: 'jquery-ui',
            header: { center: 'month,agendaWeek,agendaDay' },
            weekends: true, // will hide Saturdays and Sundays
            // theme: 'standard',
            locale: 'th',
            // contentHeight: 600,
            events: ppEvent,
            eventLimit: true, // for all non-agenda views
            views: {
                agenda: {
                    eventLimit: 6 // adjust to 6 only for agendaWeek/agendaDay
                },
                week: {
                    columnHeaderFormat:'dd D'// options apply to basicWeek and agendaWeek views
                }
            },
            eventClick: function(eventObj) {
                if (eventObj.url) {
                    window.location.href = eventObj.url;
                }
            }
        });

        var tab = 'm';
        changeTitle();
        $('.fc-prev-button').on('click',function () {
            changeTitle();
        });

        $('.fc-next-button').on('click',function () {
            changeTitle();
        });

        $('.fc-month-button').on('click',function () {
            if(tab != 'm'){
                changeTitle();
                tab = 'm'
            }
        });

        $('.fc-agendaWeek-button').on('click',function () {
            if(tab != 'w'){
                changeTitle();
                tab = 'w'
            }
        });

        $('.fc-agendaDay-button').on('click',function () {
            if(tab != 'd'){
                changeTitle();
                tab = 'd'
            }
        });

        $('.fc-today-button').on('click',function () {
            changeTitle();
        });
    });

    function changeTitle() {
        var title = $('.fc-left').children().html();
        var spilt = title.split(" ");
        spilt[spilt.length-1] = parseInt(spilt[spilt.length-1])+543;

        title = "";
        for(i = 0;i<spilt.length;i++){
            title +=  spilt[i]+" ";
        }
        $('.fc-left').children().html(title);

    }

    function prepareEventString(event) {
        var arrEvent = new Array();
        event.forEach(function(e) {
            var link = $scope.user.user_type === 't' ? 'teacher-group-my-in-' : 'student-group-in-';
            var eventData = {
                title:  e.event_name,
                start:  prepareDatetimeString(e.start_date_time),
                end: prepareDatetimeString(e.end_date_time),
                allDay: false,
                color: e.type === 'E' ? '#5cb85c' : '#337ab7',
                url: url+link+e.group_id
            }

            arrEvent.push(eventData);
        });
        return arrEvent;
    }

    function prepareDatetimeString(date) {
        dt = date.split(' ');
        d = dt[0]+'T'+dt[1];
        return d;
    }
}]);
