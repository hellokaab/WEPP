@extends('layouts.userSite')
@section('content')
    <script src="js/Components/student/stdViewExamCtrl.js"></script>
    <script>
        var examingID = {{$examingID}};
        var examing = findExamingByID(examingID);
    </script>
    <div ng-controller="stdViewExamCtrl" style="display: none" id="view_exam_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>กลุ่มเรียน</li>
                <li ng-if="user.user_type ==='s'"><a href="{{ url('/student-group-my')}}">กลุ่มเรียนของฉัน</a></li>
                <li ng-if="user.user_type ==='t'"><a href="{{ url('/teacher-group-join')}}">กลุ่มเรียนที่ฉันเข้าร่วม</a></li>
                <li ng-if="user.user_type ==='s'"><a href="{{ url('/student-group-in-<%examing.group_id%>')}}"><%groupData.group_name%> (<%groupData.creater%>)</a></li>
                <li ng-if="user.user_type ==='t'"><a href="{{ url('/teacher-group-other-in-<%examing.group_id%>')}}"><%groupData.group_name%> (<%groupData.creater%>)</a></li>
                <li class="active"><%examing.examing_name%></li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b><%examing.examing_name%></b>
                    <span id="time" class="hidden-xs hidden-sm" style="float: right"><i class="fa fa-clock-o"></i> กำลังคำนวณเวลาในการสอบ...</span>
                </div>
                <div class="panel-body">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>ข้อที่</th>
                                <th>ชื่อข้อสอบ</th>
                                <th>สถานะ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="e in examExaming">
                                <td style="width: 10%"><%$index + 1%></td>
                                <td style="width: 40%"><%e.exam_name%></td>
                                <td style="width: 30%"><%e.current_status==='q'?'ค้างคิวตรวจ':
                                    e.current_status==='a'?'ผ่าน':
                                    e.current_status==='w'?'คำตอบผิด':
                                    e.current_status==='m'?'ความจำเกินกำหนด':
                                    e.current_status==='t'?'เวลาเกินกำหนด':
                                    e.current_status==='c'?'คอมไพล์ไม่ผ่าน':
                                    e.current_status==='Q'?'กำลังรอคิวตรวจ...':
                                    e.current_status==='P'?'กำลังตรวจ...':
                                    e.current_status==='9'?'PPPPP-':
                                    e.current_status==='8'?'PPPP--':
                                    e.current_status==='7'?'PPP---':
                                    e.current_status==='6'?'PP----':
                                    e.current_status==='5'?'P-----' : '-'
                                    %></td>
                                <td style="width: 20%">
                                    <button ng-show="e.current_status != 'a'" class="btn btn-outline-success btn-sm" ng-click="startExam(e)">
                                        <i class="fa fa-pencil"></i> ทำข้อสอบ
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-offset-5 col-md-2 col-xs-offset-2 col-xs-8">
                <button ng-click="viewScore(examing)" class="btn btn-block btn-outline-purple"><i class="fa fa-trophy" aria-hidden="true"></i> Score board</button>
            </div>
        </div>

        <!-- Detail Exam Modal -->
        <div class="modal fade" id="detail_exam_modal" role="dialog">
            <div class="modal-dialog" style="width: 95%">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="panel panel-success" id="detail_exam_part" style="margin-bottom: 0">
                        <!-- Panel header -->
                        <div class="panel-heading">
                            <h3 class="panel-title">ทำข้อสอบ</h3>
                        </div>
                        <!-- Form -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center" id="exam_name"></h4>
                                    <br>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-3"><b>Time limit: </b><span id="exam_time">NaN</span> Seconds</div>
                                    <div class="col-md-3"><b>Memory limit: </b><span id="exam_memory">NaN</span> KB</div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div id="exam_content"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <b>หมายเหตุ: </b>
                                        <i>
                                            <u>Full score</u> <span id="full_score">NaN</span> คะแนน,
                                            <u>Imperfect score</u> <span id="imper_score">NaN</span> คะแนน,
                                            <u>Wrong answer</u> -<span id="cut_wrongans">NaN</span> คะแนน,
                                            <u>Compile error</u> -<span id="cut_comerror">NaN</span> คะแนน,
                                            <u>Over memory</u> -<span id="cut_overmemory">NaN</span> คะแนน,
                                            <u>Over time</u> -<span id="cut_overtime">NaN</span> คะแนน
                                        </i>
                                        <br>
                                        <br>
                                        {{--<i ng-show="selectFileType == 'c' || selectFileType == 'cpp'" style="color: red">* โปรแกรมที่ส่งห้ามมี comment</i>--}}
                                        {{--<i ng-show="selectFileType == 'java'" style="color: red">* ไม่ควรตั้งชื่อคลาสเป็น Main</i>--}}
                                        <i ng-show="selectFileType == 'java'" style="color: red">* โปรแกรมที่ส่งต้องเป็น default package เท่านั้น</i>
                                        <i ng-show="selectFileType == 'cs'" style="color: red">* โปรแกรมที่ส่งห้ามมี namespace</i>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row">
                                <div class="text-center"><b>การส่งคำตอบ</b></div>
                                <br>
                                <div class="col-md-12">
                                    <b style="padding-left: 8.5%;">ประเภทไฟล์ที่ส่ง</b>
                                </div>
                                <div class="col-md-12" style="padding-top: 15px">
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <select class="form-control" ng-model="selectFileType">
                                                {{--<option style="display: none"></option>--}}
                                                <option ng-repeat="ft in allowedFileType" value="<%ft%>">.<%ft%></option>
                                            </select>
                                        </div>
                                        <br>
                                        <br>
                                        <br>
                                        <div class="col-md-12" style="text-align: center;padding-bottom: 10px">
                                            <b>รูปแบบการส่ง</b>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-offset-0 col-md-8 col-xs-offset-1 col-xs-11" style="z-index: 5">
                                            <div class="radio">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <input type="radio" name="input" id="keyInputChk" value="key_input" ng-model="inputMode" checked>
                                                        <label for="keyInputChk">พิมพ์โค้ด</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <input type="radio" name="input" id="fileInputChk" value="file_input" ng-model="inputMode">
                                                        <label for="fileInputChk">อัพโหลด File</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row" ng-show="inputMode === 'key_input'">
                                            <textarea ng-model="codeExam" class="form-control io_textarea" placeholder="ใส่โค้ดคำตอบที่นี่" rows="8"></textarea>
                                            <div class="notice" id="notice_exam_key_ans" style="display: none">กรุณาใส่โค้ดโปรแกรม</div>
                                        </div>

                                        <form id="AnsFileForm" action="javascript:submitAnsForm();" method="post" enctype = "multipart/form-data">
                                            <div class="form-group" ng-show="inputMode == 'file_input'">
                                                <div class="col-md-4">
                                                    <input type="file" id="file_ans" class="inline-form-control" name="file_ans[]" multiple="" accept=".<%selectFileType%>">
                                                    <div class="notice" id="notice_exam_file_ans" style="display: none">กรุณาเลือกไฟล์</div>
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Model footer -->
                        <div class="modal-footer">
                            <button ng-click="okSend()" type="button" class="btn btn-outline-success">ส่งตรวจ</button>
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
                            {{--<div class="col-md-12 table-responsive">--}}
                                <table class="table table-hover table-striped">
                                    <thead id="score_board_hd"></thead>
                                    <tbody id="score_board_tb"></tbody>
                                </table>
                            {{--</div>--}}
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

        <!-- Fail Modal -->
        <div id="fail_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title">ข้อผิดพลาด</h3>
                        </div>
                        <div style="padding-top: 7%; text-align: center" id="err_message">โค้ดที่ส่งไม่ใช่ Default package กรุณาแก้ไข package ของโค้ด</div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">ตกลง</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var exam_path = "";
        var second = 0;
        var timer;
        var examID = "";
        $(document).ready(function () {
            $('#view_exam_div').css('display', 'block');
            getNow();
        });

        function getNow() {
            a = new Date();
            b = dtDBToDtJs(examing.end_date_time);
            b = new Date(b);
            total = b.getTime() - a.getTime();
            second = parseInt(total / 1000);
            clearInterval(timer);
            countdown();
        }

        function countdown() {
            timer = setInterval(function () {
                if (second <= 0)
                    window.location.href = url+'student-group-in-'+examing.group_id;
                hour = parseInt(second / 3600);
                min = parseInt((second % 3600) / 60);
                sec = second % 60;
                $('#time').html('<i class="fa fa-clock-o"></i> เหลือเวลาสอบ ' + hour + ' ชั่วโมง ' + min + ' นาที ' + sec + ' วินาที');
                second--;

                if (second % 600 === 0)
                    getNow();
                if (second % 600 === 600 || second % 600 === 599 || second % 600 === 598 || second % 600 === 597 || second % 600 === 596)
                    $('#time').html('<i class="fa fa-clock-o"></i> สิ้นสุดการสอบเวลา ' + examing.end_date_time);

            }, 1000);
        }

        function submitAnsForm() {
            var formData = new FormData($('#AnsFileForm')[0]);
            exam_path = $.ajax({
                url: url+'/uploadExamFile/'+examingID+'/'+examID+'/'+user.id,
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false
            }).responseJSON;
            return false;
        }
    </script>
@endsection