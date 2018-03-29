@extends('layouts.userSite')
@section('page-title','ข้อมูลส่วนตัว')
@section('content')
    <script src="js/Components/profileCtrl.js"></script>
    <style>
        @media (max-width: 767px) {
            .container-custom {
                width: 100%;
            }
        }

        @media (min-width: 992px){
            .container-custom {
                width: 65%;
            }
        }
    </style>
    <div ng-controller="profileCtrl" id="profile_div" style="display: none">
        <div class="container container-custom">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>ข้อมูลส่วนตัว</b>
                    </div>
                    <div class="panel-body">
                        <div class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">คำนำหน้า</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="prefix" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">ชื่อ</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="fname" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">นามสกุล</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="lname" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">รหัสประชาชน</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="personalID" disabled>
                                </div>
                            </div>

                            <div class="form-group" ng-hide="stuID ===''">
                                <label class="col-md-3 col-xs-12 control-label">รหัสนักศึกษา</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="stuID" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">คณะ</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="faculty" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">สาขา</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="department" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">อีเมล</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="email" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#profile_div').css('display', 'block');
        });
    </script>
@endsection