@extends('layouts.userSite')
@section('page-title','ประวัติการสั่งงาน')
@section('content')
    <script src="js/Components/teacher/teaSheetingHistoryCtrl.js"></script>
    <div ng-controller="teaSheetingHistoryCtrl" style="display: none" id="sheet_history_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>จัดการการสั่งงาน</li>
                <li class="active">ประวัติการสั่งงาน</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b style="color: #555">ประวัติการสั่งงาน</b>
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
                                <th style="width: 20%">ชื่อการสั่งงาน</th>
                                <th style="width: 20%;text-align: center">เริ่มต้น</th>
                                <th style="width: 20%;text-align: center">สิ้นสุด</th>
                                <th style="width: 15%;text-align: center">ส่งเกินเวลา</th>
                                <th style="width: 25%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="sh in sheeting|filter:search|itemsPerPage:selectRow">
                                <td><%sh.sheeting_name%></td>
                                <td style="text-align: center"><%sh.start_date_time%></td>
                                <td style="text-align: center"><%sh.end_date_time%></td>
                                <td ng-show="sh.send_late === '0'" style="text-align: center">ไม่อนุญาต</td>
                                <td ng-show="sh.send_late === '1'" style="text-align: center">อนุญาต</td>
                                <td style="text-align: center;width: 20%">
                                    <button class="btn btn-sm btn-outline-warning" title="แก้ไข" style="cursor:pointer" ng-click="editSheeting(sh)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-primary" title="สรุปผลคะแนน" style="cursor:pointer" ng-click="viewPoint(sh)">
                                        <i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-danger" title="ลบ" style="cursor:pointer" ng-click="deleteSheeting(sh)">
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
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบประวัติการสั่งงานนี้หรือไม่</div>
                        <br>
                        <input ng-model="sheetingName" value="" style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"  disabled/>
                        <div style="padding-top: 3%; text-align: center">(ข้อมูลการสั่งงาน, ไฟล์ที่นักศึกษาส่งในการสั่งงานนี้จะถูกลบไปด้วย)</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDeleteSheeting()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
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
            $('#sheet_history_div').css('display','block');
            $("#side_sheeting").removeAttr('class');
            $('#side_sheeting').attr('class', 'active');
            $("#sheeting_chevron").removeAttr('class');
            $("#sheeting_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_sheeting').attr('class', 'collapse in');
            $('#side_history_sheeting').attr('class', 'active');
        });
    </script>
@endsection