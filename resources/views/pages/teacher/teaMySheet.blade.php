@extends('layouts.userSite')
@section('page-title','ใบงานของฉัน')
@section('content')
    <script src="js/Components/teacher/teaMySheetCtrl.js"></script>
    <div ng-controller="teaMySheetCtrl" style="display: none" id="sheet_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>คลังใบงาน</li>
                <li class="active">ใบงานของฉัน</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading" style="height: 51px">
                    <label style="color: #555;font-size: 18px;padding-top: 5px">กลุ่มใบงานของฉัน</label>
                    <button class="btn btn-sm btn-outline-success" href="" ng-click="addSheetGroup()" style="float: right"><i class="fa fa-plus"></i>
                        เพิ่มกลุ่มใบงาน</button>
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
                                <th ng-click="sort('sheet_group_name')" style="cursor:pointer">กลุ่มใบงาน  <i class="fa" ng-show="sortKey=='sheet_group_name'" ng-class="{'fa-caret-up':reverseS,'fa-caret-down':!reverseS}"></i></th>
                                <th>ผู้สร้างกลุ่มใบงาน</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="sheetGroup.length > 0" dir-paginate="s in sheetGroup|orderBy:sortS|filter:search|itemsPerPage:selectRow">
                                <td><a href="#div_sheet_list" ng-click="selectGroup(s)"><%s.sheet_group_name%></a></td>
                                <td><%s.creater%></td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-warning" title="แก้ไขกลุ่มใบงาน" style="cursor:pointer" ng-click="editSheetGroup(s)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-sm btn-outline-danger" title="ลบกลุ่มใบงาน" style="cursor:pointer" ng-click="deleteSheetGroup(s)">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="sheetGroup.length > 0">
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
                <div class="panel-footer" id="move">
                    <b class="notice">*</b> คลิกที่ชื่อกลุ่มใบงานเพื่อดูรายชื่อใบงาน
                </div>
            </div>
        </div>

        {{--Sheet List--}}
        <div class="col-md-7 col-xs-12" id="div_sheet_list" style="display: none">
            <div class="panel panel-default">
                <div class="panel-heading" style="height: 51px">
                    <label style="color: #555;padding-top: 5px" ng-model="panelGroupName">ทดสอบ</label>
                    <button class="btn btn-sm btn-outline-success" ng-click="addSheet()" style="float: right"><i class="fa fa-plus"></i>
                        เพิ่มใบงาน</button>
                </div>
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12 table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>โจทย์</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="sheets.length > 0" ng-repeat="s in sheets">
                                <td><a href="" ng-click="detailSheet(s)"><%s.sheet_name%></a></td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-primary btn-sm" title="รายละเอียด" style="cursor:pointer" ng-click="detailSheet(s)">
                                        <i class="fa fa-tasks fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-warning btn-sm" title="แก้ไขใบงาน" style="cursor:pointer" ng-click="gotoEditSheet(s)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-danger btn-sm" title="ลบใบงาน" style="cursor:pointer" ng-click="deleteSheet(s)">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="sheets.length > 0">
                                <td>ไม่พบข้อมูล</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Sheet Group -->
        <div class="modal fade" id="add_sheet_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-success" id="add_sheet_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">เพิ่มกลุ่มใบงาน</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อกลุ่มใบงาน</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="sheetGroupName"
                                           ng-keyup="$event.keyCode === 13 && enterAdd()" maxlength="50"
                                           placeholder="ชื่อกลุ่มใบงาน">
                                    <div class="notice" id="notice_add_sheet_grp" style="display: none">
                                        กรุณาระบุชื่อกลุ่มใบงาน
                                    </div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success" ng-click="okAddSheetGroup()">เพิ่ม</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Sheet Group -->
        <div class="modal fade" id="edit_sheet_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-warning" id="edit_sheet_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">แก้ไขกลุ่มใบงาน</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อกลุ่มใบงาน</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="sheetGroupName" maxlength="50">
                                    <div class="notice" id="notice_edit_sheet_grp" style="display: none">
                                        *กรุณาระบุชื่อกลุ่มใบงาน
                                    </div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" ng-click="okEditSheetGroup()">บันทึก</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Sheet Group Modal -->
        <div class="modal fade" id="delete_sheet_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_sheet_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ยืนยันการลบรายการ</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center"><b>คุณต้องการลบกลุ่มใบงานนี้หรือไม่</b></div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="sheetGroupName" disabled/>
                        <div style="padding-top: 3%; text-align: center">(ข้อมูลใบงานทั้งหมดที่อยู่ในกลุ่มใบงานนี้จะถูกลบไปด้วย)</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDeleteSheetGroup()">ลบ</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Sheet Modal -->
        <div class="modal fade" id="detail_sheet_modal" role="dialog">
            <div class="modal-dialog" style="width: 98%; padding-right: 12px">
                <div class="modal-content">
                    <div class="panel panel-primary" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">รายละเอียดใบงาน</h3>
                        </div>
                        <div class="panel-body">
                            <h4 class="text-center" id="sheetName"></h4>
                            <br>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        <b>วัตถุประสงค์:</b>
                                        <div id="objective_part">
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="objective" rows="5" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <b>ทฤษฎีที่เกี่ยวข้อง:</b>
                                        <div id="theory_part">
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="theory" rows="5" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <b>การทดลอง:</b>
                                <div id="sheet_trial"></div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        <b>อินพุท:</b>
                                        <div id="input_part">
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="sheetInput" rows="10" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <b>เอาท์พุท:</b>
                                        <div id="output_part">
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="sheetOutput" rows="10" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="list-group">
                                                    <a class="list-group-item">
                                                        <span class="badge badge-default" id="fullScore">100</span>
                                                        คะแนนเต็มของใบงาน
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12"><b>หมายเหตุ : </b></div>
                                            <div class="col-md-6"><span id="notation"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <h4 style="padding-left: 15px" ng-show="quizzes.length > 0">คำถามท้ายการทดลอง</h4>
                            <div class="col-md-6" ng-repeat="q in quizzes" style="margin-top: 20px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>คำถามข้อที่ <%$index+1%>:</b>
                                        <div>
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="quiz_1" rows="3" disabled><%q.quiz_data%></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <b>คำตอบ :</b>
                                        <div>
                                            <input type="text" class="form-control" style="background-color: #fff"
                                                   id="answer_1" value="<%q.quiz_ans%>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <b>คะแนน :</b>
                                        <div>
                                            <input type="text" class="form-control" style="background-color: #fff"
                                                   id="score_1" value="<%q.quiz_score%>" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" ng-click="editSheet()">แก้ไข</button>
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Sheet Modal -->
        <div class="modal fade" id="delete_sheet_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_sheet_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ยืนยันการทำรายการ</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบใบงานนี้หรือไม่</div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="deleteName" disabled/>
                        <div style="padding-top: 3%; text-align: center">(ข้อมูลใบงาน,คำถามท้ายการทดลอง,ไฟล์ input,ไฟล์ output
                            จะถูกลบไปด้วย)
                        </div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDelete()">ตกลง</button>
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
            $('#sheet_div').css('display', 'block');
            $("#side_sheet_store").removeAttr('class');
            $('#side_sheet_store').attr('class', 'active');
            $("#sheet_chevron").removeAttr('class');
            $("#sheet_chevron").attr('class', 'fa2 fa-chevron-down');
            $('#demo_sheet').attr('class', 'collapse in');
            $('#side_my_sheet').attr('class', 'active');
        });
    </script>
@endsection