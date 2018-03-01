<!DOCTYPE html>
<html lang="en" ng-app = "myApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/side_nav.css" rel="stylesheet">
    <link href="font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="waitMe/waitMe.css " rel="stylesheet" type="text/css">
    <link href="css/myCustom.css" rel="stylesheet" type="text/css">
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/side_nav.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="app/app.js"></script>
    <script src="waitMe/waitMe.js"></script>
    <script src="js/ajaxCtrl.js"></script>
    <script src="js/dirPagination.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script>
        app.controller("urlCtrl", function($scope) {
            $scope.myUrl = '{{ URL::asset('') }}';
        });
        var url = '{{ URL::asset('') }}';
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
<script>
    var admin =   $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-find-admin',
        async: false,
    }).responseJSON;
    if(admin == 500){
        alert("กรุณา Login ด้วยบัญชี admin");
        window.location.href = url;
    }
    var user = {
        user_type : "a"
    };
    var name = "Admin";
</script>
@include('layouts.navigation')

@include('layouts.side_nav')
<!-- Success Modal -->
<div class="modal fade" id="success_modal" role="dialog">
    <div class="modal-dialog modal-custom" style="width: 20%;padding-right: 12px">
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
    <div class="modal-dialog modal-custom" style="width: 20%;padding-right: 12px">
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