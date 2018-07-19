<!DOCTYPE html>
<html lang="en" ng-app = "myApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('page-title') - Software for worksheet and examination programming practice</title>
    <link rel="icon" href="{!! asset('img/rmuti.ico') !!}"/>
    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="css/side_nav.css" rel="stylesheet">
    <link href="font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="waitMe/waitMe.css " rel="stylesheet" type="text/css">
    <link href="LineControl-Editor/editor.css " rel="stylesheet" type="text/css">
    <link href="dateTimePicker/DateTimePicker.min.css " rel="stylesheet" type="text/css">
    <link href="css/myCustom.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="highlight/styles/atom-one-dark.css">
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/side_nav.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="app/app.js"></script>
    <script src="waitMe/waitMe.js"></script>
    <script src="js/ajaxCtrl.js"></script>
    <script src="js/dirPagination.js"></script>
    <script src="LineControl-Editor/editor.js"></script>
    <script src="dateTimePicker/DateTimePicker.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="highlight/highlight.pack.js"></script>


    <script src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
    <link rel="stylesheet" href="jquery-ui-1.12.1.custom/jquery-ui.css">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/fullcalendar.min.js"></script>--}}
    {{--<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/locale-all.js'></script>--}}
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/fullcalendar.min.css">--}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale-all.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
    <script>hljs.initHighlightingOnLoad();</script>
    <script>
        window.onunload = function() {
            window.history.forward();
//            location.reload();
        };
        app.controller("urlCtrl", function($scope) {
            $scope.myUrl = '{{ URL::asset('') }}';
        });
        var url = '{{ URL::asset('') }}';
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
    </script>
    <script>
        // ------------- For Product -------------
        var user = checkUser();
        if(user == 404){
            window.location.href = url;
        } else {
            var type="";
            if (user.user_type == 't'){
                type = "อาจารย์";
            }else if(user.user_type == 's') {
                type = "นักศึกษา";
            }else {
                type = "เจ้าหน้าที่";
            }
            var name = "คุณ "+user.fname_th+" "+user.lname_th+' ('+type+')';
        }

        // ------------- For Dev -------------
//        if(checkAdmin() != 500){
//            var user = checkUser();
//            if(user == 404){
//                window.location.href = url;
//            } else {
//                var type="";
//                if (user.user_type == 't'){
//                    type = "อาจารย์";
//                }else if(user.user_type == 's') {
//                    type = "นักศึกษา";
//                }else {
//                    type = "เจ้าหน้าที่";
//                }
//                var name = "คุณ "+user.fname_th+" "+user.lname_th+' ('+type+')';
//            }
//        } else {
//            window.location.href = url;
//        }
    </script>
    <script>
        /*Gobal Function*/
        function dtJsToDtDB(date) {
            var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
            date = date.toLocaleString('th-TH');
            dt = date.split(' ');
            d = dt[0].split('/');
            r = (d[2] - 543) + '-' + d[1] + '-' + d[0] + ' ' + dt[1];
            return r;
        }

        function dtPickerToDtJs(date) {
            dt = date.split(' ');
            d = dt[0].split('-');
            jsDt = d[1] + '/' + d[0] + '/' + d[2] + ' ' + dt[1];
            return jsDt;
        }

        function dtDBToDtPicker(date) {
            dt = date.split(' ');
            d = dt[0].split('-');
            r = (d[2]) + '-' + d[1] + '-' + d[0] + ' ' + dt[1];
            return r.substring(0, 16);
        }

        function dtDBToDtJs(date) {
            dt = date.split(' ');
            d = dt[0].split('-');
            jsDt = d[1] + '/' + d[2] + '/' + d[0] + ' ' + dt[1];
            return jsDt;
        }


        var entityMap = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': '&quot;',
            "'": '&#39;',
            "/": '&#x2F;'
        };

        function escapeHtml(string) {
            return String(string).replace(/[&<>"'\/]/g, function (s) {
                return entityMap[s];
            });
        }

        function decapeHtml(str) {
            str = str.replace(/&amp;/g, '&');
            str = str.replace(/&lt;/g, '<');
            str = str.replace(/&gt;/g, '>');
            str = str.replace(/&quot;/g, '"');
            str = str.replace(/&#39;/g, "'");
            str = str.replace(/&#x2F;/g, '/');
            return str;
        }
    </script>
    <style>
        @media (max-width: 767px) {
            .modal-custom {
                width: 50%;
                margin-left: 25%
            }
        }

        @media (min-width: 992px){
            .modal-custom {
                width: 20%;
                padding-right: 12px;
            }
        }
    </style>
</head>
<body id="page-top" class="index" ng-controller="urlCtrl">
@include('layouts.navigation')

@include('layouts.side_nav')
<!-- Success Modal -->
<div class="modal fade" id="success_modal" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
            <div class="modal-body" style="text-align: center">
                <h1 style="color: #28a745">สำเร็จ&nbsp;&nbsp;<i class="fa fa-check-circle" aria-hidden="true"></i></h1>
            </div>
            <div class="modal-footer">
                <button id="okSuccess" type="button" class="btn btn-outline-success" data-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>

<!-- Unsuccess Modal -->
<div class="modal fade" id="unsuccess_modal" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
            <div class="modal-body" style="text-align: center">
                <h1 style="color: #dc3545">ผิดพลาด&nbsp;&nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i></h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>