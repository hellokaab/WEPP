@extends('layouts.userSite')
@section('content')
    <script src="js/Components/teacher/teaCopySheetCtrl.js"></script>
    <script>
        var sheetID = {{$sheetID}};
    </script>
    <style>
        @media (max-width: 767px) {
            .modal-share-user {
                width: 95%;
            }
        }

        @media (min-width: 992px){
            .modal-share-user {
                width: 75%;
                padding-right: 0px;
            }
        }
    </style>
    <div ng-controller="teaCopySheetCtrl" style="display: none" id="copy_sheet_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>คลังใบงาน</li>
                <li><a href="{{ url('/teacher-sheet-share')}}">ใบงานที่แบ่งกันกับฉัน</a></li>
                <li class="active">คัดลอกใบงาน</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default" id="copy_sheet_part">
                <div class="panel-heading">
                    <b style="color: #555">คัดลอกใบงาน</b>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal" role="form">
                        {{--sheetName--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ชื่อใบงาน <b class="danger">*</b></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" ng-model="sheetName" maxlength="30" autofocus/>
                                <div class="notice" id="notice_sheet_name" style="display: none">กรุณาระบุชื่อใบงาน</div>
                            </div>
                        </div>

                        {{--sheet_group--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">กลุ่มใบงาน <b class="danger">*</b></label>
                            <div class="col-md-4">
                                <select class="form-control" id="sheet_group">
                                    <option style="display: none" value="0">กรุณาเลือก</option>
                                    <option ng-repeat="s in mySheetGroup" value="<%s.id%>"><%s.sheet_group_name%></option>
                                </select>
                            </div>
                        </div>

                        {{--Objective--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">วัตถุประสงค์ </label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="sheet_objective" placeholder="ใส่จุดประสงค์ที่นี้" rows="5" ng-model="objective"></textarea>
                                <div class="notice" id="notice_sheet_objective" style="display: none">
                                    กรุณาระบุวัตถุประสงค์ของใบงาน
                                </div>
                            </div>
                        </div>

                        {{--Theory--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ทฤษฎีที่เกี่ยวข้อง </label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="sheet_theory" placeholder="ใส่ทฤษฏีที่นี่" rows="5" ng-model="theory"></textarea>
                                <div class="notice" id="notice_sheet_theory" style="display: none">
                                    กรุณาระบุทฤษฎีที่เกี่ยวข้อง
                                </div>
                            </div>
                        </div>

                        {{--Trial--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">การทดลอง <b class="danger">*</b></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="sheet_trial"></textarea>
                                <div class="notice" id="notice_sheet_trial" style="display: none">
                                    กรุณาระบุการทดลอง
                                </div>
                            </div>
                        </div>

                        {{--<!-- Input -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Input </label>
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
                                <div class="notice" id="notice_sheet_txt_input" style="display: none">กรุณาระบุ Input
                                    ของข้อสอบ
                                </div>
                            </div>
                        </div>

                        <form id="inputFileForm" action="javascript:submitInputForm();" method="post" enctype = "multipart/form-data">
                            <div class="form-group" ng-show="inputMode == 'file_input'">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-4">
                                    <input type="file" class="inline-form-control" ng-bind="input"
                                           name="sheet_file_input"
                                           accept=".txt">
                                    <div class="notice" id="notice_sheet_file_input" style="display: none">กรุณาระบุไฟล์
                                        Input ของใบงาน
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
                                <div class="notice" id="notice_sheet_txt_output" style="display: none">กรุณาระบุ Output
                                    ของใบงาน
                                </div>
                            </div>
                        </div>

                        <form id="outputFileForm" action="javascript:submitOutputForm();" method="post" enctype = "multipart/form-data">
                            <div class="form-group" ng-show="outputMode == 'file_output'">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-4">
                                    <input type="file" class="inline-form-control"
                                           name="sheet_file_output" accept=".txt">
                                    <div class="notice" id="notice_sheet_file_output" style="display: none">กรุณาระบุไฟล์
                                        Output ของใบงาน
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </div>
                        </form>

                        {{--<!-- Main code -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Class test </label>
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
                                <div class="notice" id="notice_sheet_main_input" style="display: none">กรุณาระบุข้อมูล
                                    Method main ของใบงาน
                                </div>
                            </div>
                        </div>

                        {{--<!-- Case sensitivity -->--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Case sensitivity </label>
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
                        <br>

                        {{--Share--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">แบ่งปันถึง:</label>
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

                        {{--sheet score--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">คะแนนการทดลอง <b class="danger">*</b></label>
                            <div class="col-md-3">
                                <input id="sheet_score" type="text" class="form-control" ng-model="sheetScore"  ng-keyup="checkFullScore()" maxlength="6" autofocus/>
                                <div class="notice" id="notice_sheet_score" style="display: none">กรุณาระบุคะแนนการทดลอง</div>
                            </div>
                            {{--Notation--}}
                            <label class="col-md-1 control-label">หมายเหตุ </label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" ng-model="sheetNotation" maxlength="30" autofocus/>
                                <div class="notice" id="notice_sheet_notation" style="display: none">กรุณาระบุหมายเหตุ</div>
                            </div>
                        </div>

                        <br>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" style="text-align: left">
                                    <label class="col-md-4 control-label text-left" style="font-size: 18px">คำถามท้ายการทดลอง</label>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="form-group">
                            <div id="question_part">
                                <div class="form-group has-feedback" ng-repeat="q in quizzes" style="padding-left: 15px;padding-right: 15px">
                                    <div id="oldQuiz_part_<%q.id%>" style="padding-left: 15px;padding-right: 15px">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">คำถาม </label>
                                            <div class="col-md-9">
                                                <textarea class="form-control io_textarea has-feedback" id="old_sheet_quiz_<%q.id%>" rows="3"
                                                          placeholder="ใส่คำถามที่นี่" ><%q.quiz_data%></textarea>
                                                <div class="notice" id="notice_old_sheet_quiz_<%q.id%>" style="display: none">กรุณาระบุคำถาม</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">คำตอบ </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control has-feedback" id="old_sheet_answer_<%q.id%>"
                                                       maxlength="200" placeholder="ใส่คำตอบ" value="<%q.quiz_ans%>"/>
                                            </div>
                                            <label class="col-md-1 control-label">คะแนน </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control has-feedback" id="old_quiz_score_<%q.id%>"
                                                       maxlength="6" placeholder="ใส่คะแนน" value="<%q.quiz_score%>" />
                                                <div class="notice" id="notice_old_quiz_score_<%q.id%>" style="display: none">กรุณาระบุคะแนน</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-feedback" style="padding-left: 15px;padding-right: 15px">
                                    <div id="quiz_part_1" style="padding-left: 15px;padding-right: 15px">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">คำถาม </label>
                                            <div class="col-md-9">
                                                <textarea class="form-control io_textarea has-feedback" id="sheet_quiz_1" rows="3"
                                                          placeholder="ใส่คำถามที่นี่"></textarea>
                                                <div class="notice" id="notice_sheet_quiz_1" style="display: none">กรุณาระบุคำถาม</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">คำตอบ </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control has-feedback" id="sheet_answer_1"
                                                       maxlength="200" placeholder="ใส่คำตอบ"/>
                                            </div>
                                            <label class="col-md-1 control-label">คะแนน </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control has-feedback" id="quiz_score_1"
                                                       maxlength="6" placeholder="ใส่คะแนน"/>
                                                <div class="notice" id="notice_quiz_score_1" style="display: none">กรุณาระบุคะแนน</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="padding-left: 15px;padding-right: 15px">
                                <div class="col-lg-offset-2 col-lg-8">
                                    <button id="add_question" class="btn btn-outline-success btn-sm">
                                        <i class="fa fa-plus"></i> เพิ่มคำถามท้ายการทดลอง
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{--<!--Submit part -->--}}
                        <br>
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <input type="button" class="btn btn-outline-success btn-block" style="margin-top: 7px" ng-click="copySheet()"
                                       value="บันทึกใบงาน"/>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-outline-danger btn-block" style="margin-top: 7px" ng-click="goBack()">ยกเลิก</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var pathSheet = "";
        var input_path = "";
        var output_path = "";
        var $numberNoDot = $("#sheet_score");

        $(document).ready(function () {
            $('#copy_sheet_div').css('display', 'block');
            $("#side_sheet_store").removeAttr('class');
            $('#side_sheet_store').attr('class', 'active');
            $("#sheet_chevron").removeAttr('class');
            $("#sheet_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_sheet').attr('class', 'collapse in');
            $('#side_shared_sheet').attr('class', 'active');
        });

        _quiz_id = 1;
        $('#add_question').click(function () {
            addFieldQuestion();
        });

        function addFieldQuestion() {

            $('[id^=oldQuiz_part_]').each(function () {
                if ($(this).children().children().children()[0].value.length === 0
                    && $(this).children().children().children()[2].value.length === 0
                    && $(this).children().children().children()[3].value.length === 0)
                    $(this).parent().remove();
            });

            $('[id^=quiz_part_]').each(function () {
                if ($(this).children().children().children()[0].value.length === 0
                    && $(this).children().children().children()[2].value.length === 0
                    && $(this).children().children().children()[3].value.length === 0)
                    $(this).parent().remove();
            });

            _quiz_id++;
            $('#question_part').append(
                '<div class="form-group has-feedback" style="padding-left: 15px;padding-right: 15px">' +
                '<div id="quiz_part_'+_quiz_id+'" style="padding-left: 15px;padding-right: 15px">' +
                '<div class="form-group">'+
                '<label class="col-md-2 control-label">คำถาม </label>'+
                '<div class="col-md-9">'+
                '<textarea class="form-control io_textarea has-feedback" id="sheet_quiz_'+_quiz_id+'" rows="3" placeholder="ใส่คำถามที่นี่"></textarea>'+
                '<div class="notice" id="notice_sheet_quiz_'+_quiz_id+'" style="display: none">กรุณาระบุคำถาม</div>'+
                '</div>'+
                '</div>'+
                '<div class="form-group">'+
                '<label class="col-md-2 control-label">คำตอบ </label>'+
                '<div class="col-md-6">'+
                '<input type="text" class="form-control has-feedback" id="sheet_answer_'+_quiz_id+'" maxlength="200" placeholder="ใส่คำตอบ"/>'+
                '</div>'+
                '<label class="col-md-1 control-label">คะแนน </label>'+
                '<div class="col-md-2">'+
                '<input type="text" class="form-control has-feedback" id="quiz_score_'+_quiz_id+'" maxlength="200" placeholder="ใส่คะแนน"/>'+
                '<div class="notice" id="notice_quiz_score_'+_quiz_id+'" style="display: none">กรุณาระบุคะแนน</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'
            );
//            $('#add_question').hide();
            $('#sheet_quiz_' + _quiz_id).focus();

        }

        function submitInputForm() {
            var formData = new FormData($('#inputFileForm')[0]);
            input_path = $.ajax({
                url: url+'/uploadFileSh/'+pathSheet,
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
                url: url+'/uploadFileSh/'+pathSheet,
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false
            }).responseJSON;
            return false;
        }

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
    </script>
@endsection