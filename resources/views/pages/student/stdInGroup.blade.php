@extends('layouts.userSite')
@section('content')
    <script src="js/Components/student/stdInGroupCtrl.js"></script>
    <script>
        var groupID = {{$groupID}};
    </script>
    <style>
        @media (max-width: 767px) {
            .highlight-custom {
                height: 240px;
                max-height: 480px;
            }
        }

        @media (min-width: 992px){
            .highlight-custom {
                height: 480px;
                max-height: 480px;
            }
        }
    </style>
    <div ng-controller="stdInGroupCtrl" style="display: none" id="in_group_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>กลุ่มเรียน</li>
                <li ng-if="user.user_type ==='s'"><a href="{{ url('/student-group-my')}}">กลุ่มเรียนของฉัน</a></li>
                <li ng-if="user.user_type ==='t'"><a href="{{ url('/teacher-group-join')}}">กลุ่มเรียนที่ฉันเข้าร่วม</a></li>
                <li class="active"><%groupData.group_name%> (<%groupData.creater%>)</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading" style="height: 54px">
                    <label style="color: #337ab7;font-size: 20px;padding-top: 5px"><%groupData.group_name%> (<%groupData.creater%>)</label>
                    <button class="btn btn-outline-danger" href="" ng-if="screenSize >= 768" ng-click="exitGroup()" style="float: right"><i class="fa fa-sign-out"></i>
                        ออกจากกลุ่ม</button>
                </div>
                <div class="panel-body">
                    <label style="text-decoration:underline;font-size: 18px;padding-top: 5px">การสอบ</label>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="width: 25%">ชื่อการสอบ</th>
                                <th style="width: 25%;text-align: center">เริ่มต้น</th>
                                <th style="width: 25%;text-align: center">สิ้นสุด</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="e in examingComing" ng-show="examingComing.length > 0">
                                <td><%e.examing_name%></td>
                                <td style="text-align: center"><%e.start_date_time%></td>
                                <td style="text-align: center"><%e.end_date_time%></td>
                                <td style="text-align: center" ng-show="myPermissionsInGroup.status === 's'">
                                    <button id="btn_examing_<%e.id%>" ng-if="checkStart(e)" class="btn btn-sm btn-outline-success" ng-click="admitExaming(e)"> <!-- style="visibility: hidden" -->
                                        <i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> เข้าสอบ
                                    </button>
                                </td>
                                <td style="text-align: center" ng-show="myPermissionsInGroup.status === 'a' || myPermissionsInGroup.status === 'as'">
                                    <button class="btn btn-sm btn-outline-purple" title="score board" style="cursor:pointer" ng-click="viewScore(e)">
                                        <i class="fa fa-trophy fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="examingComing.length > 0">
                                <td>ไม่พบข้อมูล</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <br>
                    <label style="text-decoration:underline;font-size: 18px;padding-top: 5px">ประวัติการสอบ</label>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="width: 25%">ชื่อการสอบ</th>
                                <th style="width: 25%;text-align: center">เริ่มต้น</th>
                                <th style="width: 25%;text-align: center">สิ้นสุด</th>
                                <th style="width: 25%;text-align: center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="e in examingEnding" ng-show="examingEnding.length > 0">
                                <td><%e.examing_name%></td>
                                <td style="text-align: center"><%e.start_date_time%></td>
                                <td style="text-align: center"><%e.end_date_time%></td>
                                <td style="text-align: center" ng-show="myPermissionsInGroup.status === 's'">
                                    <button ng-show="e.hide_history ==='0'" class="btn btn-sm btn-outline-purple" title="ประวัติการส่งข้อสอบ" style="cursor:pointer" ng-click="viewHistory(e)">
                                        <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> ประวัติการส่งข้อสอบ
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewPoint(e)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                                <td style="text-align: center" ng-show="myPermissionsInGroup.status === 'a' || myPermissionsInGroup.status === 'as'">
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewPoint(e)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i> สรุปผลคะแนน
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="examingEnding.length > 0">
                                <td>ไม่พบข้อมูล</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <br>
                    <label style="text-decoration:underline;font-size: 18px;padding-top: 5px">ใบงาน</label>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="width: 20%">ชื่อใบงาน</th>
                                <th style="width: 20%;text-align: center">เริ่มต้น</th>
                                <th style="width: 20%;text-align: center">สิ้นสุด</th>
                                <th style="width: 20%;text-align: center">ส่งเกินเวลา</th>
                                <th style="width: 20%;text-align: center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="st in sheeting" >
                                <td><%st.sheeting_name%></td>
                                <td style="text-align: center"><%st.start_date_time%></td>
                                <td style="text-align: center"><%st.end_date_time%></td>
                                <td ng-show="st.send_late === '0'" style="text-align: center">ไม่อนุญาต</td>
                                <td ng-show="st.send_late === '1'" style="text-align: center">อนุญาต</td>
                                <td style="text-align: center" ng-show="myPermissionsInGroup.status === 's'">
                                    <button id="btn_sheeting_<%st.id%>" class="btn btn-sm btn-outline-success"
                                            ng-click="admitSheeting(st)" ng-if="checkInTime(st) || checkSendLate(st)">
                                        <i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> เข้าทำใบงาน
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewSheetPoint(st)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                                <td style="text-align: center" ng-show="myPermissionsInGroup.status === 'a' || myPermissionsInGroup.status === 'as'">
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewSheetPoint(st)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i> สรุปผลคะแนน
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

        <!-- Admit Modal -->
        <div class="modal fade" id="admit_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-success" id="admit_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">รหัสผ่านเข้าสอบ</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">รหัสผ่านเข้าสอบ</label>
                                <div class = "col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input id="examing_password" type="password" class="form-control" ng-model="examingPassword" ng-keyup="$event.keyCode === 13 && okAdmitExaming()" maxlength="8">
                                </div>
                            </div>
                            <!-- un use -->
                            <div class = "form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success" ng-click="okAdmitExaming()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fail Modal -->
        <div id="fail_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">ข้อผิดพลาด</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center" id="err_message">รหัสผ่านเข้าสอบไม่ถูกต้อง</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ตกลง</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Network Fail Modal -->
        <div class="modal fade" id="network_fail_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="panel panel-danger" style="margin-bottom: 0">
                        <!-- Panel header -->
                        <div class="panel-heading">
                            <h3 class="panel-title">ข้อจำกัดด้านเครือข่าย</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center">เครือข่ายที่ท่านเชื่อมต่อไม่ได้รับอนุญาตให้ทำข้อสอบ</div>
                        <br>
                        <!-- Model footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ตกลง</button>
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

        <!-- History Examing Modal -->
        <div class="modal fade" id="history_modal" role="dialog">
            <div class="modal-dialog" style="width: 95%">
                <div class="modal-content">
                    <div class="panel panel-purple" id="history_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">ประวัติการส่งข้อสอบ</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <h4 class="text-center">ประวัติการส่งข้อสอบ</h4>
                                <br>
                            </div>
                            <div class="col-md-12" ng-repeat="h in sendExamHistory">
                                <div class="row" id="res_list_<%h.id%>" style="margin-bottom: 10px">
                                    <div class="col-md-1"><b>ส่งครั้งที่: </b> <%$index + 1%> </div>
                                    <div class="col-md-3"><b>โจทย์: </b> <%h.exam_name%> </div>
                                    <div class="col-md-3"><b>สถานะ: </b> <%(h.status === 'q') ? 'ค้างคิวตรวจ' :
                                        (h.status === 'a') ? 'ผ่าน' :
                                        (h.status === 'w') ? 'คำตอบผิด' :
                                        (h.status === 'm') ? 'ความจำเกินกำหนด' :
                                        (h.status === 't') ? 'เวลาเกินกำหนด' :
                                        (h.status === 'c') ? 'คอมไพล์ไม่ผ่าน' :
                                        (h.status === '9') ? 'PPPPP-' :
                                        (h.status === '8') ? 'PPPP--' :
                                        (h.status === '7') ? 'PPP---' :
                                        (h.status === '6') ? 'PP----' :
                                        (h.status === '5') ? 'P-----' : 'ไม่ทราบสถานะ'%>
                                    </div>
                                    <div class="col-md-3"><b>เวลาส่ง: </b> <% h.send_date_time%> </div>
                                    <div class="col-md-2"><a href="#res_list_<%h.id%>" ng-click="viewCode(h)">รายละเอียด <span class="caret"></span></a></div>
                                </div>
                                <div class="row" id="detail_<%h.id%>" style="display: none;">
                                    <div style="margin-bottom: 10px">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-3"><b>เวลาที่ใช้: </b> <% h.time == null  ? '-' : h.time %> วินาที</div>
                                        <div class="col-md-3"><b>หน่วยความจำที่ใช้: </b> <% h.memory == null ? '-' : h.memory %> KB</div>
                                        <br>
                                        <br>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6"><b>โค้ดที่ส่ง: </b>
                                                    {{--tag mycode สร้างขึ้นมาเอง อยู่ในไฟล์ myCustom.css--}}
                                                    <mycode id="code_<%h.id%>" class="pre-scrollable highlight-custom"></mycode>
                                                </div>
                                                <div class="col-md-6"><b>ผลการรัน: </b>
                                                    {{--tag mycode สร้างขึ้นมาเอง อยู่ในไฟล์ myCustom.css--}}
                                                    <mycode id="resrun_<%h.id%>" class="nohighlight hljs pre-scrollable highlight-custom"></mycode>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-purple" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#in_group_div').css('display', 'block');
            if(user.user_type === 's') {
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

        function viewDetailExam(exam_id) {
            window.open(url+'detail-exam-' + exam_id, '', 'scrollbars=1, width=1000, height=600');
            return false;
        }
    </script>
@endsection