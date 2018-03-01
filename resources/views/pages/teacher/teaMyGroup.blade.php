@extends('layouts.userSite')
@section('content')
    <script src="js/Components/teacher/teaMyGroupCtrl.js"></script>
    <div ng-controller="groupCtrl" style="display: none" id="group_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>กลุ่มเรียน</li>
                <li class="active">กลุ่มเรียนของฉัน</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default ">
                <div class="panel-heading" style="height: 51px"><label style="padding-top: 5px">กลุ่มเรียนของฉัน</label>
                    <button class="btn btn-sm btn-outline-success" href="" ng-click="addGroup()" style="float: right"><i class="fa fa-plus"> เพิ่มกลุ่มเรียน</i></button>
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
                                    <th ng-click="sort('group_name')" style="cursor:pointer;width: 40%">ชื่อกลุ่มเรียน  <i class="fa" ng-show="sortKey=='group_name'" ng-class="{'fa-caret-up':reverseGN,'fa-caret-down':!reverseGN}"></i></th>
                                    <th style="width:40%">ผู้ดูแลกลุ่ม</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-show="myGroup.length > 0" dir-paginate="m in myGroup|orderBy:sortGN|filter:search|itemsPerPage:selectRow">
                                    <td><a href="" ng-click="inGroup(m)"><%m.group_name%></a></td>
                                    <td>อ.<%m.fname_th+" "+m.lname_th%></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-warning" title="แก้ไขกลุ่มเรียน" style="cursor:pointer" ng-click="editGroup(m)">
                                            <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"
                                               data-toggle="modal"></i>
                                        </button>
                                        &nbsp;&nbsp;
                                        <button class="btn btn-sm btn-outline-danger" title="ลบกลุ่มเรียน" style="cursor:pointer" ng-click="deleteGroup(m)">
                                            <i class="fa fa-trash fa-lg" aria-hidden="true" data-toggle="modal"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr ng-hide="myGroup.length > 0">
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
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Group Modal -->
        <div class="modal fade" id="add_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-success" id="add_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">เพิ่มกลุ่มเรียน</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อกลุ่มเรียน</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="groupName"
                                           ng-keyup="$event.keyCode === 13 && enterAdd()" maxlength="200"/>
                                    <div class="notice" id="notice_name_add_grp" style="display: none">
                                        กรุณาระบุชื่อกลุ่มเรียน
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">รหัสเข้ากลุ่ม</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="password" class="form-control" ng-model="passwordGroup"
                                           ng-keyup="$event.keyCode === 13 && enterOkAdd()" maxlength="8"
                                           placeholder="อย่างน้อย 4 ตัวอักษร"/>
                                    <div class="notice" id="notice_pass_add_grp" style="display: none">
                                        กรุณาระบุรหัสเข้ากลุ่ม
                                    </div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success" ng-click="okAddGroup()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Group Modal -->
        <div class="modal fade" id="edit_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-warning" id="edit_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">แก้ไขกลุ่มเรียน</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อกลุ่มเรียน</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="groupName"
                                           ng-keyup="$event.keyCode === 13 && enterEdit()" maxlength="200"/>
                                    <div class="notice" id="notice_name_edit_grp" style="display: none">
                                        กรุณาระบุชื่อกลุ่มเรียน
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">รหัสเข้ากลุ่ม</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="password" class="form-control" ng-model="passwordEditGroup"
                                           ng-keyup="$event.keyCode === 13 && enterOkEdit()" maxlength="8"
                                           placeholder="อย่างน้อย 4 ตัวอักษร"/>
                                    <div class="notice" id="notice_pass_edit_grp" style="display: none">
                                        กรุณาระบุรหัสเข้ากลุ่ม
                                    </div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" ng-click="okEditGroup()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Group Modal -->
        <div class="modal fade" id="delete_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ยืนยันการลบรายการ</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบกลุ่มเรียนนี้หรือไม่</div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="groupNameDelete" disabled/>
                        <div style="padding-top: 3%; text-align: center">(ข้อมูลทุกอย่างที่เกี่ยวข้องกับกลุ่มเรียนนี้จะถูกลบไปด้วย)
                        </div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDeleteGroup()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#group_div').css('display', 'block');
            $("#side_group").removeAttr('class');
            $('#side_group').attr('class', 'active');
            $("#group_chevron").removeAttr('class');
            $("#group_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_group').attr('class', 'collapse in');
            $('#side_my_group').attr('class', 'active');
        });
    </script>
@endsection