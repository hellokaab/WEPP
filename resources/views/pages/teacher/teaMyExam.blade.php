@extends('layouts.userSite')
@section('page-title','ข้อสอบของฉัน')
@section('content')
    <script src="js/Components/teacher/teaMyExamCtrl.js"></script>
    <div ng-controller="teaMyExamCtrl" style="display: none" id="my_exam_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>คลังข้อสอบ</li>
                <li class="active">ข้อสอบของฉัน</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading" style="height: 51px">
                    <label style="color: #555;font-size: 18px;padding-top: 5px">กลุ่มข้อสอบของฉัน</label>
                    <button class="btn btn-sm btn-outline-success" href="" ng-click="addExamGroup()" style="float: right"><i class="fa fa-plus"></i>
                        เพิ่มกลุ่มข้อสอบ</button>
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
                                <th ng-click="sort('exam_group_name')" style="cursor:pointer">กลุ่มข้อสอบ  <i class="fa" ng-show="sortKey=='exam_group_name'" ng-class="{'fa-caret-up':reverseE,'fa-caret-down':!reverseE}"></i></th>
                                <th>ผู้สร้างกลุ่มข้อสอบ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="examGroup.length > 0" dir-paginate="g in examGroup|orderBy:sortE|filter:search|itemsPerPage:selectRow">
                                <td><a href="#div_exam_list" ng-click="selectGroup(g)"><%g.exam_group_name%></a></td>
                                <td><%g.creater%></td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-warning" title="แก้ไขกลุ่มข้อสอบ" style="cursor:pointer" ng-click="editExamGroup(g)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-sm btn-outline-danger" title="ลบกลุ่มข้อสอบ" style="cursor:pointer" ng-click="deleteExamGroup(g)">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="examGroup.length > 0">
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
                    <b class="notice">*</b> คลิกที่ชื่อกลุ่มข้อสอบเพื่อรายชื่อข้อสอบ
                </div>
            </div>
        </div>

        {{--Exam List--}}
        <div class="col-md-7 col-xs-12" id="div_exam_list" style="display: none">
            <div class="panel panel-default">
                <div class="panel-heading" style="height: 51px">
                    <label style="color: #555;padding-top: 5px" ng-model="panelGroupName">ทดสอบ</label>
                    <button class="btn btn-sm btn-outline-success" ng-click="addExam()" style="float: right"><i class="fa fa-plus"></i>
                        เพิ่มข้อสอบ</button>
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
                            <tr ng-show="exams.length > 0" ng-repeat="e in exams">
                                <td><a href="" ng-click="detailExam(e)"><%e.exam_name%></a></td>
                                <td style="text-align: center">
                                    <button class="btn btn-sm btn-outline-primary btn-sm" title="รายละเอียด" style="cursor:pointer" ng-click="detailExam(e)">
                                        <i class="fa fa-tasks fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-warning btn-sm" title="แก้ไขข้อสอบ" style="cursor:pointer" ng-click="gotoEditExam(e)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-sm btn-outline-danger btn-sm" title="ลบข้อสอบ" style="cursor:pointer" ng-click="deleteExam(e)">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-hide="exams.length > 0">
                                <td>ไม่พบข้อมูล</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Exam Group -->
        <div class="modal fade" id="add_exam_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-success" id="add_exam_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">เพิ่มกลุ่มข้อสอบ</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อกลุ่มข้อสอบ</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="examGroupName"
                                           ng-keyup="$event.keyCode === 13 && enterAdd()" maxlength="50"
                                           placeholder="ชื่อกลุ่มข้อสอบ">
                                    <div class="notice" id="notice_add_exam_grp" style="display: none">
                                        กรุณาระบุชื่อกลุ่มข้อสอบ
                                    </div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success" ng-click="okAddExamGroup()">เพิ่ม</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Exam Group -->
        <div class="modal fade" id="edit_exam_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-warning" id="edit_exam_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">แก้ไขกลุ่มข้อสอบ</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ชื่อกลุ่มข้อสอบ</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <input type="text" class="form-control" ng-model="examGroupName" maxlength="50">
                                    <div class="notice" id="notice_edit_exam_grp" style="display: none">
                                        *กรุณาระบุชื่อกลุ่มข้อสอบ
                                    </div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class="form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" ng-click="okEditExamGroup()">บันทึก</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Exam Group Modal -->
        <div class="modal fade" id="delete_exam_group_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_exam_group_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ยืนยันการลบรายการ</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center"><b>คุณต้องการลบกลุ่มข้อสอบนี้หรือไม่</b></div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="examGroupName" disabled/>
                        <div style="padding-top: 3%; text-align: center">(ข้อมูลข้อสอบทั้งหมดที่อยู่ในกลุ่มข้อสอบนี้จะถูกลบไปด้วย)</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDeleteExamGroup()">ลบ</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Exam Modal -->
        <div class="modal fade" id="detail_exam_modal" role="dialog">
            <div class="modal-dialog" style="width: 98%; padding-right: 12px">
                <div class="modal-content">
                    <div class="panel panel-primary" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">รายละเอียดข้อสอบ</h3>
                        </div>
                        <div class="panel-body">

                            <h4 class="text-center" id="examName"></h4>
                            <br>
                            <div class="col-md-3"><b>Time limit:</b> <span id="examTimeLimit"></span> Seconds</div>
                            <div class="col-md-3"><b>Memory limit:</b> <span id="examMemLimit"></span> KB</div>
                            <div class="col-md-12">
                                <br>
                                <b>โจทย์:</b>
                                <div id="exam_content"></div>
                            </div>


                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        <b>อินพุท:</b>
                                        <div id="input_part">
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="examInput" rows="10" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <b>เอาท์พุท:</b>
                                        <div id="output_part">
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="examOutput" rows="10" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <b>เกณฑ์การให้คะแนน:</b>
                                                <div class="list-group">
                                                    <a class="list-group-item">
                                                        <span class="badge badge-default" id="fullScore">100</span>
                                                        - คะแนนเต็ม
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <b>เกณฑ์การหักคะแนน:</b>
                                                <div class="list-group">
                                                    <a class="list-group-item">
                                                        <span class="badge badge-default" id="cutWrongAnswer">10</span>
                                                        - คำตอบผิดพลาด
                                                    </a>
                                                    <a class="list-group-item">
                                                        <span class="badge badge-default" id="cutComplieError">10</span>
                                                        - รูปแบบโค้ดไม่ถูกต้อง
                                                    </a>
                                                    <a class="list-group-item">
                                                        <span class="badge badge-default" id="cutOverMem">10</span>
                                                        - หน่วยความจำเกิน
                                                    </a>
                                                    <a class="list-group-item">
                                                        <span class="badge badge-default" id="cutOverTime">10</span>
                                                        - เวลาประมวณผลเกิน
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <br>
                                        <b>คีย์เวิร์ด:</b>
                                        <br>
                                        <br>
                                        <ul id="list_keyword">
                                            <li ng-repeat="k in keywords"><%k.keyword_data%></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" ng-click="editExam()">แก้ไข</button>
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Exam Modal -->
        <div class="modal fade" id="delete_exam_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_exam_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ยืนยันการทำรายการ</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบข้อสอบนี้หรือไม่</div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="examName" disabled/>
                        <div style="padding-top: 3%; text-align: center">(ข้อมูลข้อสอบ,คีย์เวิร์ด,ไฟล์ input,ไฟล์ output
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
            $('#my_exam_div').css('display', 'block');
            $("#side_exam_store").removeAttr('class');
            $('#side_exam_store').attr('class', 'active');
            $("#exam_chevron").removeAttr('class');
            $("#exam_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_exam').attr('class', 'collapse in');
            $('#side_my_exam').attr('class', 'active');
        });
    </script>
@endsection