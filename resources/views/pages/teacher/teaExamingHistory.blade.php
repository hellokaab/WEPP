@extends('layouts.userSite')
@section('page-title','ประวัติการเปิดสอบ')
@section('content')
    <script src="js/Components/teacher/teaExamingHistoryCtrl.js"></script>
    <div ng-controller="teaExamingHistoryCtrl" style="display: none" id="exam_history_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>จัดการการสอบ</li>
                <li class="active">ประวัติการเปิดสอบ</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b style="color: #555">ประวัติการเปิดสอบ</b>
                </div>
                <div class="panel-body">
                    <br>
                    <div class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-md-2 control-label">กลุ่มเรียน: </label>
                            <div class="col-md-5">
                                <select class="form-control" ng-model="groupID" ng-change="groupChange()">
                                    <option value="0">กรุณาเลือก</option>
                                    <option ng-repeat="g in groups" value="<%g.id%>"><%g.group_name%></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" ng-show="groupID != 0">
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
                    <div class="col-md-12 table-responsive" ng-show="groupID != 0">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="width: 20%">ชื่อการสอบ</th>
                                <th style="width: 15%">โหมดการสอบ</th>
                                <th style="width: 20%;text-align: center">เริ่มต้น</th>
                                <th style="width: 20%;text-align: center">สิ้นสุด</th>
                                <th style="width: 25%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="e in examings|filter:search|itemsPerPage:selectRow">
                                <td><%e.examing_name%></td>
                                <td ng-show="e.examing_mode === 'n'">เรียงตามลำดับ</td>
                                <td ng-show="e.examing_mode === 'r'">สุ่ม</td>
                                <td style="text-align: center"><%e.start_date_time%></td>
                                <td style="text-align: center"><%e.end_date_time%></td>
                                <td style="text-align: center;width: 20%">
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
                                    <button class="btn btn-sm btn-outline-danger" title="ลบ" style="cursor:pointer" ng-click="deleteExaming(e)">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
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

        <!-- Delete Modal -->
        <div class="modal fade" id="delete_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">ยืนยันการทำรายการ</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบประวัติการเปิดสอบนี้หรือไม่</div>
                        <br>
                        <input ng-model="examingName" value="" style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"  disabled/>
                        <div style="padding-top: 3%; text-align: center">(ข้อมูลการสอบ, ไฟล์ที่นักศึกษาส่งในการสอบนี้จะถูกลบไปด้วย)</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDeleteExaming()">ตกลง</button>
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
        if(user.user_type != 't'){
            alert("คุณไม่สามารเข้าใช้งานหน้านี้ได้");
            window.location.href = url+'home';
        }

        $(document).ready(function () {
            $('#exam_history_div').css('display','block');
            $("#side_examing").removeAttr('class');
            $('#side_examing').attr('class', 'active');
            $("#examing_chevron").removeAttr('class');
            $("#examing_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_examing').attr('class', 'collapse in');
            $('#side_history_examing').attr('class', 'active');
        });

        function viewDetailExam(exam_id) {
            window.open(url+'detail-exam-' + exam_id, '', 'scrollbars=1, width=1000, height=600');
            return false;
        }
    </script>
@endsection