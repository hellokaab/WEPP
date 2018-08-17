@extends('layouts.userSite')
@section('page-title','ข้อสอบที่แบ่งปันกับฉัน')
@section('content')
    <script src="js/Components/teacher/teaShareExamCtrl.js"></script>
    <div ng-controller="teaShareExamCtrl" style="display: none" id="exam_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>คลังข้อสอบ</li>
                <li class="active">ข้อสอบที่แบ่งปันกับฉัน</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading" >
                    <b style="color: #555">กลุ่มข้อสอบที่แบ่งปันกับฉัน</b>
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
                                <th ng-click="sort('creater')" style="cursor:pointer">ผู้สร้างกลุ่มข้อสอบ  <i class="fa" ng-show="sortKey=='creater'" ng-class="{'fa-caret-up':reverseC,'fa-caret-down':!reverseC}"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="examGroupSharedToMe.length > 0" dir-paginate="g in examGroupSharedToMe|orderBy:[sortE,sortC]|filter:search|itemsPerPage:selectRow">
                                <td><a href="#div_exam_list" ng-click="selectGroup(g)"><%g.exam_group_name%></a></td>
                                <td style="width: 50%;"><%g.creater%></td>
                            </tr>
                            <tr ng-hide="examGroupSharedToMe.length > 0">
                                <td>ไม่พบข้อมูล</td>
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
                    <label style="color: #555" ng-model="panelGroupName">ทดสอบ</label>
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
                                    <button class="btn btn-sm btn-outline-success" title="คัดลอกข้อสอบ" style="cursor:pointer"
                                            ng-click="copyExam(e)">
                                        <i class="fa fa-clone fa-lg" aria-hidden="true"></i>
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
                            <div class="col-md-3"><b>Time limit : </b> <span id="examTimeLimit"></span> Seconds</div>
                            <div class="col-md-3"><b>Memory limit : </b> <span id="examMemLimit"></span> KB</div>
                            <div class="col-md-12">
                                <br>
                                <b>โจทย์:</b>
                                <div id="exam_content"></div>
                            </div>


                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        <b>Input</b>
                                        <div id="input_part">
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="examInput" rows="10" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <b>Output</b>
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
                                                <b>เกณฑ์การให้คะแนน</b>
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
                                                <b>เกณฑ์การหักคะแนน</b>
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
                                        <b ng-hide="keywords.length == 0">Keyword</b>
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
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ปิด</button>
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
            $('#exam_div').css('display', 'block');
            $("#side_exam_store").removeAttr('class');
            $('#side_exam_store').attr('class', 'active');
            $("#exam_chevron").removeAttr('class');
            $("#exam_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_exam').attr('class', 'collapse in');
            $('#side_shared_exam').attr('class', 'active');
        });
    </script>
@endsection