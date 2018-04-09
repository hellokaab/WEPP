@extends('layouts.userSite')
@section('page-title','กลุ่มเรียนที่เข้าร่วม')
@section('content')
    <script src="js/Components/student/stdMyGroupCtrl.js"></script>
    <div ng-controller="stdMyGroupCtrl" style="display: none" id="std_my_group_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>กลุ่มเรียน</li>
                <li class="active" ng-if="user.user_type ==='s'">กลุ่มเรียนของฉัน</li>
                <li class="active" ng-if="user.user_type ==='t'">กลุ่มเรียนที่ฉันเข้าร่วม</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default ">
                <div class="panel-heading">
                    <b ng-if="user.user_type ==='s'">กลุ่มเรียนของฉัน</b>
                    <b ng-if="user.user_type ==='t'">กลุ่มเรียนที่ฉันเข้าร่วม</b>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-xs-12" style="text-align: center">
                            <div class="form-group">
                                <label class="col-md-1 col-xs-2 control-label"
                                       style="margin-top: 14px;padding-right: 0px">ค้นหา</label>
                                <div class="col-md-4 col-xs-10" style="padding-right: 0px;padding-top: 7px">
                                    <input type="text" id="txt_search" class="form-control" ng-model="search"
                                           placeholder="ค้นหา">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12" style="text-align: center">
                            <label class="col-md-offset-4 col-md-2 col-xs-2 control-label" style="margin-top: 14px">แสดง</label>
                            <div class="col-md-4 col-xs-8" style="padding-right: 0px;padding-top: 7px">
                                <select class="form-control" ng-model="selectRow">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <label class="col-md-2 col-xs-2 control-label" style="margin-top: 14px">แถว</label>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th ng-click="sort('group_name')" style="cursor:pointer">กลุ่มเรียน  <i class="fa" ng-show="sortKey=='group_name'" ng-class="{'fa-caret-up':reverseG,'fa-caret-down':!reverseG}"></i></th>
                                <th ng-click="sort('creater')" style="cursor:pointer">ผู้ดูแลกลุ่ม  <i class="fa" ng-show="sortKey=='creater'" ng-class="{'fa-caret-up':reverseC,'fa-caret-down':!reverseC}"></i></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="myJoinGroup.length > 0" dir-paginate="g in myJoinGroup|orderBy:[sortG,sortC]|filter:search|itemsPerPage:selectRow">
                                <td ng-if="user.user_type ==='s'"><a href="{{ url('/student-group-in-<%g.group_id%>')}}"><%g.group_name%></a></td>
                                <td ng-if="user.user_type ==='t'"><a href="{{ url('/teacher-group-other-in-<%g.group_id%>')}}"><%g.group_name%></a></td>
                                <td><%g.creater%></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger" title="ออกจากกลุ่ม" style="cursor:pointer" ng-click="exitGroup(g)">
                                        <i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="myJoinGroup.length > 0">
                                <td>ไม่พบข้อมูลกลุ่มเรียน</td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exit Group Modal -->
        <div class="modal fade" id="exit_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="exit_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ยืนยันการทำรายการ</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center">คุณต้องการออกจากกลุ่มเรียนนี้หรือไม่</div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="groupName" disabled/>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okExit()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#std_my_group_div').css('display', 'block');
            if(user.user_type === 's' || user.user_type === 'o') {
                $("#side_std_group").removeAttr('class');
                $('#side_std_group').attr('class', 'active');
                $("#std_group_chevron").removeAttr('class');
                $("#std_group_chevron").attr('class', 'fa2 fa-chevron-down');
                $('#demo_std_group').attr('class', 'collapse in');
                $('#side_std_myGroup').attr('class', 'active');
            } else  if(user.user_type === 't'){
                $('#group_div').css('display', 'block');
                $("#side_group").removeAttr('class');
                $('#side_group').attr('class', 'active');
                $("#group_chevron").removeAttr('class');
                $("#group_chevron").attr('class','fa2 fa-chevron-down');
                $('#demo_group').attr('class', 'collapse in');
                $('#side_join_group').attr('class', 'active');
            }
        });
    </script>
@endsection