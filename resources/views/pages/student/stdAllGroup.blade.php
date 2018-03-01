@extends('layouts.userSite')
@section('content')
    <script src="js/Components/student/stdAllGroupCtrl.js"></script>
    <div ng-controller="stdAllGroupCtrl" style="display: none" id="std_group_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>กลุ่มเรียน</li>
                <li class="active">กลุ่มเรียนทั้งหมด</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default ">
                <div class="panel-heading">
                    <b>กลุ่มเรียนทั้งหมด</b>
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
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="allGroup.length > 0" dir-paginate="g in allGroup|orderBy:[sortG,sortC]|filter:search|itemsPerPage:selectRow">
                                <td><a href="" ng-click="clickGroup(g)"><%g.group_name%></a></td>
                                <td><%g.creater%></td>
                            </tr>
                            <tr ng-hide="allGroup.length > 0">
                                <td>ไม่พบข้อมูล</td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <dir-pagination-controls
                                max-size="5"
                                direction-links="true"
                                boundary-links="true" >
                        </dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>

        <!-- Join Group Modal -->
        <div class="modal fade" id="join_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-success" id="join_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">เข้าร่วมกลุ่ม</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อกลุ่มเรียน</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="groupName" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">รหัสเข้ากลุ่ม</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="password" class="form-control" ng-model="joinPass"
                                           maxlength="8" ng-keyup="$event.keyCode === 13 && okJoinGroup()"
                                           placeholder="อย่างน้อย 4 ตัวอักษร"/>
                                    <div class="notice" id="notice_pass_grp" style="display: none">
                                        รหัสผ่านไม่ถูกต้อง
                                    </div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success" ng-click="okJoinGroup()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#std_group_div').css('display', 'block');
            if(user.user_type === 's'){
                $("#side_std_group").removeAttr('class');
                $('#side_std_group').attr('class', 'active');
                $("#std_group_chevron").removeAttr('class');
                $("#std_group_chevron").attr('class','fa2 fa-chevron-down');
                $('#demo_std_group').attr('class', 'collapse in');
                $('#side_std_allGroup').attr('class', 'active');
            } else if(user.user_type === 't'){
                $('#group_div').css('display', 'block');
                $("#side_group").removeAttr('class');
                $('#side_group').attr('class', 'active');
                $("#group_chevron").removeAttr('class');
                $("#group_chevron").attr('class','fa2 fa-chevron-down');
                $('#demo_group').attr('class', 'collapse in');
                $('#side_all_group').attr('class', 'active');
            }
        });
    </script>
@endsection