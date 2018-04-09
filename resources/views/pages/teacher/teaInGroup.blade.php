@extends('layouts.userSite')
@section('page-title', 'กลุ่มเรียนของฉัน')
@section('content')
    <script src="js/Components/teacher/teaInGroupCtrl.js"></script>
    <script>
        var groupId = {{$groupID}};
    </script>
    <div ng-controller="teaInGroupCtrl" style="display: none" id="tea_in_group_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li><a href="{{ url('/teacher-group-my')}}">กลุ่มเรียนของฉัน</a></li>
                <li class="active"><%groupData.group_name%></li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default ">
                <div class="panel-heading" style="height: 54px"><label style="font-size: 20px;color: #337ab7;padding-top: 5px"><%groupData.group_name%></label>
                    <button class="btn btn-outline-warning" href="" ng-click="editGroup()" style="float: right"><i class="fa fa-pencil-square-o"> </i> แก้ไขกลุ่ม</button>
                </div>
                <div class="panel-body">
                    <br>
                    <div class="col-md-12 col-xs-12" style="padding: 0px">
                        <label style="text-decoration:underline;font-size: 18px;padding-top: 5px">การสอบ</label><button class="btn btn-sm btn-outline-success" href="" ng-click="openExaming()" style="float: right"><i class="fa fa-plus"> </i> เปิดสอบ</button>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                {{--<th style="width: 20%">ชื่อการสอบ</th>--}}
                                {{--<th style="width: 20%;text-align: center">เริ่มต้น</th>--}}
                                {{--<th style="width: 20%;text-align: center">สิ้นสุด</th>--}}
                                {{--<th style="width: 23%;text-align: center">ซ่อน/แสดง</th>--}}
                                {{--<th style="width: 17%;text-align: center"></th>--}}
                                <th>ชื่อการสอบ</th>
                                <th style="text-align: center">เริ่มต้น</th>
                                <th style="text-align: center">สิ้นสุด</th>
                                <th style="text-align: center">ซ่อน/แสดง</th>
                                <th style="text-align: center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="e in examingComing" ng-show="examingComing.length > 0">
                                <td><%e.examing_name%></td>
                                <td style="text-align: center"><%e.start_date_time%></td>
                                <td style="text-align: center"><%e.end_date_time%></td>
                                <td>
                                    <div class="radio inline-form-control">
                                        <div class="col-md-6" style="text-align: center">
                                            <input type="radio" name="hide_examing_<%e.id%>" id="hide_ex_<%e.id%>" value="0" ng-click="changeHidden(e,'he')">
                                            <label for="hide_ex_<%e.id%>" style="padding-left: 2px">ซ่อน</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center">
                                            <input type="radio" name="hide_examing_<%e.id%>" id="show_ex_<%e.id%>" value="1" ng-click="changeHidden(e,'se')">
                                            <label for="show_ex_<%e.id%>" style="padding-left: 2px">แสดง</label>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-warning" title="แก้ไข" style="cursor:pointer" ng-click="editExaming(e)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-purple" title="score board" style="cursor:pointer" ng-click="viewScore(e)">
                                        <i class="fa fa-trophy fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewPoint(e)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-danger" title="ลบ" style="cursor:pointer" ng-click="deleteData(e,'ex')">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="examingComing.length > 0">
                                <td>ไม่พบข้อมูล</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <br>
                    <div class="col-md-12 col-xs-12" style="padding: 0px">
                        <label style="text-decoration:underline;font-size: 18px;padding-top: 5px">ประวัติการเปิดสอบ</label>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                {{--<th style="width: 20%">ชื่อการสอบ</th>--}}
                                {{--<th style="width: 20%;text-align: center">เริ่มต้น</th>--}}
                                {{--<th style="width: 20%;text-align: center">สิ้นสุด</th>--}}
                                {{--<th style="width: 23%;text-align: center">การเข้าถึงประวัติการสอบ</th>--}}
                                {{--<th style="width: 17%;text-align: center"></th>--}}
                                <th >ชื่อการสอบ</th>
                                <th style="text-align: center">เริ่มต้น</th>
                                <th style="text-align: center">สิ้นสุด</th>
                                <th style="text-align: center">การเข้าถึงประวัติการสอบ</th>
                                <th style="text-align: center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="e in examingEnding" ng-show="examingEnding.length > 0">
                                <td><%e.examing_name%></td>
                                <td style="text-align: center"><%e.start_date_time%></td>
                                <td style="text-align: center"><%e.end_date_time%></td>
                                <td>
                                    <div class="radio inline-form-control">
                                        <div class="col-md-6" style="text-align: center">
                                            <input type="radio" name="hide_history_<%e.id%>" id="hide_hi_<%e.id%>" value="0" ng-click="changeToAllow(e,'0')">
                                            <label for="hide_hi_<%e.id%>" style="padding-left: 2px">อนุญาต</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center">
                                            <input type="radio" name="hide_history_<%e.id%>" id="show_hi_<%e.id%>" value="1" ng-click="changeToAllow(e,'1')">
                                            <label for="show_hi_<%e.id%>" style="padding-left: 2px">ไม่อนุญาต</label>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-warning" title="แก้ไข" style="cursor:pointer" ng-click="editExaming(e)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-purple" title="score board" style="cursor:pointer" ng-click="viewScore(e)">
                                        <i class="fa fa-trophy fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewPoint(e)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-danger" title="ลบ" style="cursor:pointer" ng-click="deleteData(e,'ex')">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="examingEnding.length > 0">
                                <td>ไม่พบข้อมูล</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <br>
                    </div>
                    <br>
                    <br>
                    <div class="col-md-12 col-xs-12" style="padding: 0px">
                        <label style="text-decoration:underline;font-size: 18px;padding-top: 5px">ใบงาน</label><button class="btn btn-sm btn-outline-success" href="" ng-click="openSheeting()" style="float: right"><i class="fa fa-plus"> </i> สั่งงาน</button>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>ชื่อใบงาน</th>
                                <th style="text-align: center">เริ่มต้น</th>
                                <th style="text-align: center">สิ้นสุด</th>
                                <th style="text-align: center">ซ่อน/แสดง</th>
                                <th style="text-align: center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="st in sheeting" >
                                <td><%st.sheeting_name%></td>
                                <td style="text-align: center"><%st.start_date_time%></td>
                                <td style="text-align: center"><%st.end_date_time%></td>
                                <td>
                                    <div class="radio inline-form-control">
                                        <div class="col-md-6" style="text-align: center">
                                            <input type="radio" name="hide_sheeting_<%st.id%>" id="hide_sh_<%st.id%>" value="0" ng-click="changeHidden(st,'hs')">
                                            <label for="hide_sh_<%st.id%>" style="padding-left: 2px">ซ่อน</label>
                                        </div>
                                        <div class="col-md-6" style="text-align: center">
                                            <input type="radio" name="hide_sheeting_<%st.id%>" id="show_sh_<%st.id%>" value="1" ng-click="changeHidden(st,'ss')">
                                            <label for="show_sh_<%st.id%>" style="padding-left: 2px">แสดง</label>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-warning" title="แก้ไข" style="cursor:pointer" ng-click="editSheeting(st)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewSheetPoint(st)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-sm btn-outline-danger" title="ลบ" style="cursor:pointer" ng-click="deleteData(st,'sh')">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="sheeting.length > 0">
                                <td>ไม่พอข้อมูล</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{--Member List --}}
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default ">
                <div class="panel-heading">
                    <b>รายชื่อสมาชิก</b>
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
                                <th>รหัสนักศึกษา</th>
                                <th>ชื่อ - นามสกุล</th>
                                <th>สถานะ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="memberList.length > 0" dir-paginate="m in memberList|orderBy:stu_id|filter:search|itemsPerPage:selectRow">
                                <td><%m.stu_id%></td>
                                <td><%m.fullName%></td>
                                <td ng-show="m.status === 's'">นักศึกษา</td>
                                <td ng-show="m.status === 'a'">อาจารย์ผู้ช่วยสอน</td>
                                <td ng-show="m.status === 'as'">ผู้ช่วยสอน</td>
                                <td ng-show="m.status === 'ao'">เจ้าหน้าที่ผู้ช่วยสอน</td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-primary" title="รายละเอียด" style="cursor:pointer" ng-click="showProfile(m)">
                                        <i class="fa fa-address-card fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-sm btn-outline-warning" title="จัดการสิทธิ์" style="cursor:pointer" ng-click="managePermissions(m)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-sm btn-outline-danger" title="ลบ" style="cursor:pointer" ng-click="deleteMember(m)">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="memberList.length > 0">
                                <td>ไม่พบรายชื่อสมาชิก</td>
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
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">รหัสเข้ากลุ่ม</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="passwordGroup"
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

        <!-- Profile Modal -->
        <div class="modal fade" id="detail_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-info" id="detail_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color:#555">ข้อมูลส่วนตัว</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">คำนำหน้า</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="prefix" disabled/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อ-นามสกุล</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="name" disabled/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">อีเมล</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="email" disabled/>
                                </div>
                            </div>

                            <div class="form-group" ng-hide="cardId === ''">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">รหัสประจำตัวนักศึกษา</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="cardId" disabled/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">คณะ</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="faculty" disabled/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">สาขา</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="department" disabled/>
                                </div>
                            </div>

                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <!-- Model footer -->
                        <div class="modal-footer">
                            <button class="btn btn-outline-info" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage Permissions Modal -->
        <div class="modal fade" id="permissions_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="panel panel-warning" id="permissions_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">จัดการสิทธิ์</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-horizontal" role="form" style="padding-top: 7%">
                                <div class="form-group">
                                    <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อ-นามสกุล</label>
                                    <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                        <input type="text" class="form-control" ng-model="manage_name" disabled/>
                                    </div>
                                </div>
                                {{--<!-- un use -->--}}
                                {{--<div class="form-group"></div>--}}
                            </div>
                            <div class="col-md-offset-1 col-md-10">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center">สิทธิ์การเข้าถึง</th>
                                        <th style="text-align: center">อนุญาต</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>อนุญาตให้เข้าถึงข้อสอบที่ส่งของนักศึกษาในกลุ่ม</td>
                                        <td style="text-align: center;vertical-align: middle;">
                                            <input type="checkbox" id="view_ex">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>อนุญาตให้แก้ไขคะแนนของข้อสอบที่ส่งของนักศึกษาในกลุ่ม</td>
                                        <td style="text-align: center;vertical-align: middle;">
                                            <input type="checkbox" id="edit_ex">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>อนุญาตให้เข้าถึงใบงานที่ส่งของนักศึกษาในกลุ่ม</td>
                                        <td style="text-align: center;vertical-align: middle;">
                                            <input type="checkbox" id="view_sh">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>อนุญาตให้แก้ไขคะแนนของใบงานที่ส่งของนักศึกษาในกลุ่ม</td>
                                        <td style="text-align: center;vertical-align: middle;">
                                            <input type="checkbox" id="edit_sh">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" ng-click="okManage()">บันทึก</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Member Modal -->
        <div class="modal fade" id="delete_member_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_member_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">ยืนยันการทำรายการ</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบสมาชิกคนนี้ออกจากกลุ่มหรือไม่</div>
                        <br>
                        <input ng-model="memberName" value="" style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"  disabled/>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDeleteMember()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Modal -->
        <div class="modal fade" id="confirm_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-info" id="confirm_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #555">ยืนยันการทำรายการ</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center" id="message_confirm">คุณต้องการซ่อนการสอบนี้หรือไม่</div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="confirmName" disabled/>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-info" ng-click="okChange()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" ng-click="cancelChange()">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Allow Modal -->
        <div class="modal fade" id="change_allow_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-info" id="change_allow_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #555">ยืนยันการทำรายการ</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center">คุณต้องการเปลี่ยนแปลงการเข้าถึงประวัติการสอบนี้</div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="examingName" disabled/>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-info" ng-click="okAllow()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" ng-click="cancelAllow()">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="delete_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">ยืนยันการทำรายการ</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center" id="message_delete">คุณต้องการลบการสอบนี้หรือไม่</div>
                        <br>
                        <input ng-model="deleteName" value="" style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"  disabled/>
                        <div style="padding-top: 3%; text-align: center" id="message_delete_2">(ข้อมูลการสอบ, ไฟล์ที่นักศึกษาส่งในการสอบนี้จะถูกลบไปด้วย)</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDelete()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Score Board Modal -->
        <div class="modal fade" id="score_modal" role="dialog">
            <div class="modal-dialog" style="width: 95%">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="panel panel-purple" id="score_part" style="margin-bottom: 0">
                        <!-- Panel header -->
                        <div class="panel-heading">
                            <h3 class="panel-title">Score board</h3>
                        </div>
                        <!-- Form -->
                        <div class="text-center">
                            <h3 id="examing_title"><%examing.examing_name%></h3>
                        </div>
                        <br>
                        <div style="margin-right: 3%; margin-left: 3%;">
                            <table class="table table-hover table-striped">
                                <thead id="score_board_hd"></thead>
                                <tbody id="score_board_tb"></tbody>
                            </table>
                        </div>
                        <br>
                        <!-- Model footer -->
                        <div class="modal-footer">
                            <div class="text-left hidden-print hidden-xs hidden-sm" style="margin-left: 2%">
                                <b>หมายเหตุ:</b>
                                <i>
                                    <x class="accpet">Accept</x> /
                                    <x class="imperfect">Imperfect</x> /
                                    <x class="wrong_ans">Wrong answer</x> /
                                    <x class="complie_err">Compile error</x> /
                                    <x class="over_time">Over runtime</x> /
                                    <x class="over_mem">Over memory</x>
                                </i>
                            </div>
                            <button type="button" class="btn btn-outline-purple" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var page_permission = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'permission-group-teacher',
            data:{ group_id : groupId, user_id : user.id},
            async: false,
        }).responseJSON;

        if(page_permission == 404){
            alert("คุณไม่สามารเข้าใช้งานหน้านี้ได้");
            window.location.href = url+'home';
        }

        $(document).ready(function () {
            $('#tea_in_group_div').css('display', 'block');
            $("#side_group").removeAttr('class');
            $('#side_group').attr('class', 'active');
            $("#group_chevron").removeAttr('class');
            $("#group_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_group').attr('class', 'collapse in');
            $('#side_my_group').attr('class', 'active');
        });

        function viewDetailExam(exam_id) {
            window.open(url+'detail-exam-' + exam_id, '', 'scrollbars=1, width=1000, height=600');
            return false;
        }
    </script>
@endsection