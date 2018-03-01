@extends('layouts.userSite')
@section('content')
    <script src="js/Components/teacher/teaEditExamCtrl.js"></script>
    <script>
        var examID = {{$examID}};
    </script>
    <style>
        @media (max-width: 767px) {
            .modal-share-user {
                width: 95%;
            }

            .col-edit-keyword {
                margin-top: 7px;
                padding-right: 5px;
            }

            .col-delete-keyword {
                margin-top: 7px;
                padding-left: 5px;
            }
        }

        @media (min-width: 992px){
            .modal-share-user {
                width: 75%;
                padding-right: 0px;
            }

            .col-edit-keyword {
                padding-left: 0;
            }

            .col-delete-keyword {
                padding-left: 0;
            }
        }
    </style>
    <div ng-controller="teaEditExamCtrl"  style="display: none" id="edit_exam_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>คลังข้อสอบ</li>
                <li><a href="{{ url('/teacher-exam-my')}}">ข้อสอบของฉัน</a></li>
                <li class="active">แก้ไขข้อสอบ</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default" id="edit_exam_part">
                <div class="panel-heading">
                    <b style="color: #555">แก้ไขข้อสอบ</b>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal" role="form">
                        {{--Exam Name--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ชื่อข้อสอบ <b class="danger">*</b></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" ng-model="examName" maxlength="30" autofocus/>
                                <div class="notice" id="notice_exam_name" style="display: none">กรุณาระบุชื่อข้อสอบ
                                </div>
                            </div>
                        </div>

                        {{--Exam Group--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">กลุ่มข้อสอบ <b class="danger">*</b></label>
                            <div class="col-md-4">
                                <select class="form-control" id="ddl_group">
                                    <option style="display: none"></option>
                                    <option ng-repeat="g in myExamGroup" value="<%g.id%>"><%g.exam_group_name%></option>
                                </select>
                            </div>
                        </div>

                        {{--Exam Contents--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">รายละเอียด <b class="danger">*</b></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="exam_content"></textarea>
                                <div class="notice" id="notice_exam_content" style="display: none">
                                    กรุณาระบุรายละเอียดของข้อสอบ
                                </div>
                            </div>
                        </div>

                        {{--<!-- Input -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Input</label>
                            <div class="col-md-offset-0 col-md-9 col-xs-offset-1 col-xs-11">
                                <div class="radio">
                                    <div class="col-md-4">
                                        <input type="radio" name="input" id="keyInputChk" value="key_input"
                                               ng-model="inputMode" ng-click="changeInputMode()">
                                        <label for="keyInputChk">Keyboard input</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="input" id="txtInputChk" value="file_input"
                                               ng-model="inputMode" ng-click="changeInputMode()">
                                        <label for="txtInputChk">Text file</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="input" id="noInputChk" value="no_input"
                                               ng-model="inputMode" ng-click="changeInputMode()">
                                        <label for="noInputChk">ไม่มี Input</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-show="inputMode == 'key_input'">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-9">
                                <textarea class="form-control io_textarea" ng-model="input" rows="5"
                                          placeholder="ใส่ Input ที่นี่"></textarea>
                                <div class="notice" id="notice_exam_txt_input" style="display: none">กรุณาระบุ Input
                                    ของข้อสอบ
                                </div>
                            </div>
                        </div>

                        <form id="inputFileForm" action="javascript:submitInputForm();" method="post" enctype = "multipart/form-data">
                            <div class="form-group" ng-show="inputMode == 'file_input'">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-4">
                                    <input type="file" class="inline-form-control" ng-bind="input"
                                           name="exam_file_input"
                                           accept=".txt">
                                    <div class="notice" id="notice_exam_file_input" style="display: none">กรุณาระบุไฟล์
                                        Input ของข้อสอบ
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </div>
                        </form>

                        {{--<!-- Output -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Output <b class="danger">*</b></label>
                            <div class="col-md-offset-0 col-md-9 col-xs-offset-1 col-xs-11">
                                <div class="radio">
                                    <div class="col-md-4">
                                        <input type="radio" name="output" id="keyOutputChk" value="key_output"
                                               ng-model="outputMode" ng-click="changeOutputMode()">
                                        <label for="keyOutputChk">Keyboard output</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="output" id="txtOutputChk" value="file_output"
                                               ng-model="outputMode" ng-click="changeOutputMode()">
                                        <label for="txtOutputChk">Text file</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-show="outputMode == 'key_output'">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-9">
                                <textarea class="form-control io_textarea" ng-model="output" rows="5"
                                          placeholder="ใส่ Output ที่นี่"></textarea>
                                <div class="notice" id="notice_exam_txt_output" style="display: none">กรุณาระบุ Output
                                    ของข้อสอบ
                                </div>
                            </div>
                        </div>

                        <form id="outputFileForm" action="javascript:submitOutputForm();" method="post" enctype = "multipart/form-data">
                            <div class="form-group" ng-show="outputMode == 'file_output'">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-4">
                                    <input type="file" class="inline-form-control"
                                           name="exam_file_output" accept=".txt">
                                    <div class="notice" id="notice_exam_file_output" style="display: none">กรุณาระบุไฟล์
                                        Output ของข้อสอบ
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </div>
                        </form>

                        {{--<!-- Main code -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Class test</label>
                            <div class="col-md-offset-0 col-md-9 col-xs-offset-1 col-xs-11">
                                <div class="radio">
                                    <div class="col-md-2">
                                        <input type="radio" name="classMode" id="has_main" value="1"
                                               ng-model="classTestMode" ng-click="changeClassTestMode()">
                                        <label for="has_main">ใช่</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" name="classMode" id="no_main" value="0"
                                               ng-model="classTestMode" ng-click="changeClassTestMode()">
                                        <label for="no_main">ไม่ใช่</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-show="classTestMode == 1">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-9">
                                <textarea class="form-control io_textarea" ng-model="main" rows="10"
                                          placeholder="ใส่โค้ดเมธอด main ที่นี่"></textarea>
                                <div class="notice" id="notice_exam_main_input" style="display: none">กรุณาระบุข้อมูล
                                    Method main ของข้อสอบ
                                </div>
                            </div>
                        </div>

                        {{--<!-- Case sensitivity -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Case sensitivity</label>
                            <div class="col-md-offset-0 col-md-9 col-xs-offset-1 col-xs-11">
                                <div class="radio">
                                    <div class="col-md-2">
                                        <input type="radio" name="caseSensitive" id="case_sensitive" value="1"
                                               ng-model="casesensitive">
                                        <label for="case_sensitive">ใช่</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" name="caseSensitive" id="case_insensitive" value="0"
                                               ng-model="casesensitive">
                                        <label for="case_insensitive">ไม่ใช่</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--<!-- Keyword -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Keyword </label>
                            <div class="col-md-9">
                                <div id="keyword_part">
                                    <div class="form-group has-feedback" ng-repeat="k in keywords" style="padding-left: 15px;padding-right: 15px">
                                        <div class="row">
                                        <div class="col-md-8" > <!-- style="padding-left: 0;padding-right: 0" -->
                                            <input type="text" id="oldKeyword_<%k.id%>" value="<%k.keyword_data%>" class="form-control has-feedback" disabled/>
                                        </div>
                                        <div class="col-md-2 col-xs-6 col-edit-keyword" > <!-- style="padding-right: 0" -->
                                            <button class="btn btn-outline-warning btn-block" data-toggle="modal" ng-click="editKeyword(k.id,k.keyword_data)">
                                                <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>  แก้ไข
                                            </button>
                                        </div>
                                        <div class="col-md-2 col-xs-6 col-delete-keyword" > <!-- style="padding-right: 0" -->
                                            <button class="btn btn-outline-danger btn-block" data-toggle="modal" ng-click="deleteKeyword(k.id)">
                                                <i class="fa fa-trash fa-lg" aria-hidden="true"></i>  ลบ
                                            </button>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback" style="padding-left: 15px;padding-right: 15px">
                                        <input type="text" class="form-control has-feedback" id="exam_keyword_1"
                                               maxlength="200" placeholder="เพิ่มคีย์เวิร์ด"/>
                                    </div>
                                </div>
                                <button id="add_keyword" class="btn btn-outline-success btn-sm" style="display: none">
                                    <i class="fa fa-plus"></i> เพิ่มคีย์เวิร์ด
                                </button>
                            </div>
                        </div>

                        {{--Share--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">แบ่งปันถึง</label>
                            <div class="col-md-9">
                                <h5 ng-repeat="st in selectTeacher">อ.<%st.fname_th%> <%st.lname_th%></h5>
                                <button class="btn btn-outline-info btn-sm" ng-click="addUserShare()">
                                    <i class="fa fa-plus"></i> เลือกผู้ที่ต้องการแบ่งปัน
                                </button>
                            </div>
                        </div>

                        {{--Select User To Share Exam--}}
                        <div class="modal fade" id="add_user_to_share_modal" role="dialog">
                            <div class="modal-dialog modal-share-user">
                                <div class="modal-content">
                                    <div class="panel panel-info" id="add_user_share_part" style="margin-bottom: 0">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" style="color: #555">เลือกบุคคลที่ต้องการแบ่งปัน</h3>
                                        </div>
                                        <div class="panel-body">
                                            <b>รายชื่อผู้สอนในระบบ</b>
                                            <br>
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 5%"><input type="checkbox" id="select_all"></th>
                                                        <th style="width: 25%">ชื่อ - นามสกุล</th>
                                                        <th style="width: 40%">คณะ</th>
                                                        <th style="width: 30%">สาขาวิชา</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr ng-repeat="t in teacher" ng-if="t.id != user.id" ng-show="teacher.length > 1">
                                                        <td><input type="checkbox" id="tea_<%t.id%>"> </td>
                                                        <td ng-click="ticExam(t.id)">อ.<%t.fname_th%> <%t.lname_th%></td>
                                                        <td ng-click="ticExam(t.id)"><%t.faculty%></td>
                                                        <td ng-click="ticExam(t.id)"><%t.department%></td>
                                                    </tr>
                                                    <tr ng-hide="teacher.length > 1">
                                                        <td></td>
                                                        <td>ไม่พบข้อมูล</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-success" ng-click="okAddTeacher()">ตกลง</button>
                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ยกเลิก</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--<!-- Limit -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ข้อจำกัด<b class="danger">*</b></label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <label class="small">ขนาดหน่วยความจำ (KB)</label>
                                        <input type="text" class="form-control" ng-model="memLimit" id="mem_limit"
                                               ng-keyup="checkMemLimit()" maxlength="6"/>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="small">เวลาในการประมวลผล (sec)</label>
                                        <input type="text" class="form-control" ng-model="timeLimit" id="time_limit"
                                               ng-keyup="checkTimeLimit()" maxlength="6"/>
                                    </div>
                                </div>
                                <div class="notice" id="notice_exam_limit" style="display: none">
                                    กรุณาระบุข้อมูลข้อจำกัดให้ครบถ้วนสมบูรณ์
                                </div>
                            </div>
                        </div>

                        {{--<!-- Score -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">การให้คะแนน<b class="danger">*</b></label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <label class="small">คะแนนเต็ม</label>
                                        <input type="text" class="form-control" ng-model="fullScore" id="full_score"
                                               ng-keyup="checkFullScore()" maxlength="6"/>
                                    </div>
                                </div>
                                <div class="notice" id="notice_exam_score" style="display: none">
                                    กรุณาระบุข้อมูลการให้คะแนนให้ครบถ้วนสมบูรณ์
                                </div>
                            </div>
                        </div>

                        {{--<!-- Decrease Score -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">การหักคะแนน<b class="danger">*</b></label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3 col-xs-6 text-center">
                                        <label class="small">คำตอบผิดพลาด</label>
                                        <input type="text" class="form-control" ng-model="cutWrongAnswer"
                                               id="cut_wrong_ans"
                                               ng-keyup="checkCutWrongAnswer()" maxlength="6"/>
                                    </div>
                                    <div class="col-md-3 col-xs-6 text-center">
                                        <label class="small">รูปแบบโค้ดไม่ถูกต้อง</label>
                                        <input type="text" class="form-control" ng-model="cutComplieError"
                                               id="cut_compile_err"
                                               ng-keyup="checkCutComplieError()" maxlength="6"/>
                                    </div>
                                    <div class="col-md-3 col-xs-6 text-center">
                                        <label class="small">หน่วยความจำเกิน</label>
                                        <input type="text" class="form-control" ng-model="cutOverMem" id="cut_over_mem"
                                               ng-keyup="checkCutOverMem()" maxlength="6"/>
                                    </div>
                                    <div class="col-md-3 col-xs-6 text-center">
                                        <label class="small">เวลาประมวณผลเกิน</label>
                                        <input type="text" class="form-control" ng-model="cutOverTime"
                                               id="cut_over_time"
                                               ng-keyup="checkCutOverTime()" maxlength="6"/>
                                    </div>
                                </div>
                                <div class="notice" id="notice_exam_descore" style="display: none">
                                    กรุณาระบุข้อมูลการหักคะแนนให้ครบถ้วนสมบูรณ์
                                </div>
                            </div>
                        </div>
                        <br>

                        {{--<!--Submit part -->--}}
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-success btn-block" ng-click="editExam()" style="margin-top: 7px">บันทึกข้อสอบ</button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-danger btn-block" ng-click="goBack()" style="margin-top: 7px">ยกเลิก</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Keyword Modal -->
        <div class="modal fade" id="edit_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-warning" id="edit_keyword_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">แก้ไขคีย์เวิร์ด</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-offset-0 col-md-4 col-xs-offset-1 col-xs-11 control-label">ข้อมูลคีย์เวิร์ด</label>
                                <div class="col-md-offset-0 col-md-6 col-xs-offset-1 col-xs-10">
                                    <textarea class="form-control io_textarea" ng-model="keyword" maxlength="200"></textarea>
                                    <div class="notice" id="notice_keyword" style="display: none">กรุณาระบุคีย์เวิร์ด</div>
                                </div>
                            </div>
                            <!-- un use -->
                            <div class = "form-group"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" ng-click="okEditKeyword()">บันทึก</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Keyword Modal -->
        <div class="modal fade" id="delete_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_keyword_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">ยืนยันการทำรายการ</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบคีย์เวิร์ดนี้หรือไม่</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" ng-click="okDeleteKeyword()">ตกลง</button>
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var pathExam = "";
        var input_path = "";
        var output_path = "";
        var $numberOnly = $("#time_limit,#cut_wrong_ans,#cut_compile_err,#cut_over_mem,#cut_over_time");
        var $numberNoDot = $("#full_score, #mem_limit");

        // -- Keyword
        _keyword_id = 1;
        $('#keyword_part').on('input', function () {
            keyword = $(this).children().children('#exam_keyword_' + _keyword_id).val();
            if (keyword.length > 0) {
                $('#add_keyword').show();
            } else {
                $('#add_keyword').hide();
            }
        });

        $('#add_keyword').click(function () {
            addFieldKeyword();
        });

        $('#keyword_part').keypress(function (e) {
            if (e.which === 13)
                addFieldKeyword();
        });

        function addFieldKeyword() {
            count = 1;
            $('[id^=exam_keyword_]').each(function () {
                if (this.value.length === 0)
                    $(this).parent().remove();
                else
                    count++;
            });

            _keyword_id++;
            $('#keyword_part').append('<div class="form-group has-feedback" style="padding-left: 15px;padding-right: 15px"><input type="text" class="form-control" id="exam_keyword_' + _keyword_id + '" placeholder="เพิ่มคีย์เวิร์ด" maxlength="200"/></div>');
            $('#add_keyword').hide();
            $('#exam_keyword_' + _keyword_id).focus();
        }

        function submitInputForm() {
            var formData = new FormData($('#inputFileForm')[0]);
            input_path = $.ajax({
                url: url+'/uploadFile/'+pathExam,
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false
            }).responseJSON;
            return false;
        }

        function submitOutputForm() {
            var formData = new FormData($('#outputFileForm')[0]);
            output_path = $.ajax({
                url: url+'/uploadFile/'+pathExam,
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false
            }).responseJSON;
            return false;
        }

        $numberOnly.keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything

                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }

        });

        $numberNoDot.keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [8, 9, 27, 13]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything

                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }

        });

        $(document).ready(function () {
            $('#edit_exam_div').css('display', 'block');
            $("#side_exam_store").removeAttr('class');
            $('#side_exam_store').attr('class', 'active');
            $("#exam_chevron").removeAttr('class');
            $("#exam_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_exam').attr('class', 'collapse in');
            $('#side_my_exam').attr('class', 'active');
        });
    </script>
@endsection