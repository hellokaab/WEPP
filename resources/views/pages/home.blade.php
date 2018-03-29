@extends('layouts.userSite')
@section('page-title','หน้าหลัก')
@section('content')
    <script src="js/Components/homeCtrl.js"></script>
    <div ng-controller="homeCtrl" style="display: none" id="home_div">
        <div class="row" style="height: 100%">
            <div class="col-md-12" style="height: 100%">
                <div class="col-md-8" style="height: 100%">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b style="color: #555">ปฏิทินกิจกรรมของฉัน</b>
                        </div>
                        <div class="panel-body">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="height: 100%">
                    <div class="panel panel-default" style="height: 100%">
                        <div class="panel-heading" style="height: 100%">
                            <b style="color: #555">ผู้ที่ใช้งานระบบในปัจจุบัน</b>
                        </div>
                        <div class="panel-body" style="height: 100px">
                            <ul>
                                <li ng-repeat="u in userOnline"><a><%u.user_type == 't' ? "อ." : u.prefix%><%u.fname_th%> <%u.lname_th%></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection