@extends('layouts.userSite')
@section('page-title','ทำใบงาน')
@section('content')
    <script src="js/Components/student/stdViewSheetCtrl.js"></script>
    <script>
        var sheetingID = {{$sheetingID}};
        var sheeting = findSheetingByID(sheetingID);
    </script>
    <div ng-controller="stdViewSheetCtrl" style="display: none" id="view_sheet_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>กลุ่มเรียน</li>
                <li ng-if="user.user_type ==='s'"><a href="{{ url('/student-group-my')}}">กลุ่มเรียนของฉัน</a></li>
                <li ng-if="user.user_type ==='t'"><a href="{{ url('/teacher-group-join')}}">กลุ่มเรียนที่ฉันเข้าร่วม</a></li>
                <li ng-if="user.user_type ==='s'"><a href="{{ url('/student-group-in-<%sheeting.group_id%>')}}"><%groupData.group_name%> (<%groupData.creater%>)</a></li>
                <li ng-if="user.user_type ==='t'"><a href="{{ url('/teacher-group-other-in-<%sheeting.group_id%>')}}"><%groupData.group_name%> (<%groupData.creater%>)</a></li>
                <li class="active"><%sheeting.sheeting_name%></li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b><%sheeting.sheeting_name%></b>
                </div>
                <div class="panel-body">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>ลำดับที่</th>
                                <th>ชื่อใบงาน</th>
                                <th>สถานะ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="s in sheetSheeting">
                                <td style="width: 10%"><%$index + 1%></td>
                                <td style="width: 40%"><%s.sheet_name%></td>
                                <td style="width: 30%"><%  s.current_status==='q'?'การส่งผิดพลาด(กรุณาส่งใหม่)':
                                    s.current_status==='a'?'ผ่าน':
                                    s.current_status==='w'?'คำตอบผิด':
                                    s.current_status==='m'?'ความจำเกินกำหนด':
                                    s.current_status==='t'?'เวลาเกินกำหนด':
                                    s.current_status==='c'?'คอมไพล์ไม่ผ่าน':
                                    s.current_status==='Q'?'กำลังรอคิวตรวจ...':
                                    s.current_status==='P'?'กำลังตรวจ...':
                                    s.current_status==='9'?'ถูกต้องบางส่วน(90%)':
                                    s.current_status==='8'?'ถูกต้องบางส่วน(80%)':
                                    s.current_status==='7'?'ถูกต้องบางส่วน(70%)':
                                    s.current_status==='6'?'ถูกต้องบางส่วน(60%)':
                                    s.current_status==='5'?'ถูกต้องบางส่วน(50%)' : '-'
                                    %></td>
                                <td style="width: 20%">
                                    <button class="btn btn-outline-primary btn-sm" ng-click="startSheet(s)">
                                        <i class="fa fa-pencil"></i> ทำใบงาน
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Sheet Modal -->
        <div class="modal fade" id="detail_sheet_modal" role="dialog">
            <div class="modal-dialog" style="width: 95%">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="panel panel-primary" id="detail_sheet_part" style="margin-bottom: 0">
                        <!-- Panel header -->
                        <div class="panel-heading">
                            <h3 class="panel-title">ทำใบงาน</h3>
                        </div>
                        <!-- Form -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center" id="sheet_name"></h4>
                                    <br>
                                </div>
                                <div class="col-md-12" ng-show="objective != '' || theory !='' ">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <h5><b>วัตถุประสงค์</b></h5>
                                                <ul>
                                                    <li ng-repeat="ob in objective">&nbsp;&nbsp;<%ob%></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <h5><b>ทฤษฎีที่เกี่ยวข้อง</b></h5>
                                                <ul>
                                                    <li ng-repeat="th in theory track by $index">&nbsp;&nbsp;<%th%></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div id="sheet_trial"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <br>
                                        <i ng-show="selectFileType == 'c' || selectFileType == 'cpp'" style="color: red">* โปรแกรมที่ส่งห้ามใช้ไลบารี่ conio.h</i>
                                        {{--<i ng-show="selectFileType == 'java'" style="color: red">* ไม่ควรตั้งชื่อคลาสเป็น Main</i>--}}
                                        <i ng-show="selectFileType == 'java'" style="color: red">* โปรแกรมที่ส่งต้องเป็น default package เท่านั้น</i>
                                        <i ng-show="selectFileType == 'cs'" style="color: red">* โปรแกรมที่ส่งห้ามมี namespace</i>
                                    </div>
                                </div>
                            </div>
                            <br class="hidden-xs hidden-sm">
                            <br>
                            <div class="row">
                                <div class="text-center"><b>การส่งคำตอบ</b></div>
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs">
                                        <li class="active" id="li_s"><a data-toggle="tab" href="" ng-click="tab = 's'">ส่งโค้ด</a></li>
                                        <li id="li_o"><a  data-toggle="tab" href="" ng-click="tab = 'o'">โค้ดที่ส่งแล้ว</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="row">
                                            <br>
                                            <div class="col-md-12" ng-show="tab === 's'">
                                                <b style="padding-left: 8.5%;">ประเภทไฟล์ที่ส่ง</b>
                                            </div>
                                            <div class="col-md-12" style="padding-top: 15px" ng-show="tab === 's'">
                                                <div class="col-md-3">
                                                    <div class="col-md-12">
                                                        <select class="form-control" ng-model="selectFileType" id="fileType">
                                                            {{--<option style="display: none"></option>--}}
                                                            <option ng-repeat="ft in allowedFileType" value="<%ft%>">.<%ft%></option>
                                                        </select>
                                                    </div>
                                                    <br>
                                                    <br class="hidden-xs hidden-sm">
                                                    <br class="hidden-xs hidden-sm">
                                                    <div class="col-md-12" style="text-align: center;padding-bottom: 10px">
                                                        <b>รูปแบบการส่ง</b>
                                                    </div>
                                                    <div class="col-md-4" ></div>
                                                    <div class="col-md-offset-0 col-md-8 col-xs-offset-1 col-xs-11">
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
                                                        <textarea ng-model="codeSheet" class="form-control io_textarea" placeholder="ใส่โค้ดคำตอบที่นี่" rows="8"></textarea>
                                                        <div class="notice" id="notice_sheet_key_ans" style="display: none">กรุณาใส่โค้ดโปรแกรม</div>
                                                    </div>

                                                    <form id="AnsFileForm" action="javascript:submitAnsForm();" method="post" enctype = "multipart/form-data">
                                                        <div class="form-group" ng-show="inputMode == 'file_input'">
                                                            <div class="col-md-4">
                                                                <input type="file" id="file_ans" class="inline-form-control" name="file_ans[]" multiple="" accept=".<%selectFileType%>">
                                                                <div class="notice" id="notice_sheet_file_ans" style="display: none">กรุณาเลือกไฟล์</div>
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-offset-2 col-md-8" ng-show="tab === 'o'">
                                                {{--tag mycode สร้างขึ้นมาเอง อยู่ในไฟล์ myCustom.css--}}
                                                <mycode id="old_code" class="pre-scrollable" style="height: 340px;max-height: 510px;">ไม่พบข้อมูล</mycode>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <br class="hidden-xs hidden-sm">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <b>สถานะการส่งคำตอบ : </b><b style="color: green" ng-show="thisStatus === 'a'">ผ่าน</b><b style="color: red" ng-show="thisStatus != 'a'">ยังไม่ผ่าน</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row" ng-show="quiz.length > 0">
                                <div class="text-center"><b>คำถามท้ายการทดลอง</b></div>
                                <br>
                                <div class="col-xs-12">
                                <div class="form-horizontal" role="form" ng-repeat="q in quiz">
                                    <div class="form-group">
                                        <label class="col-md-2 col-xs-3 control-label">ข้อที่ <%$index+1%> :</label>
                                        <label class="col-md-8 control-label" style="text-align: left"><b><%q.quiz_data%> (<%q.quiz_score%> คะแนน)</b></label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">ตอบ :</label>
                                        <div class="col-md-8">
                                            <textarea id="quizAns_<%q.id%>" class="form-control io_textarea" placeholder="ใส่คำตอบที่นี่" rows="2"></textarea>
                                            {{--<input type="text" id="quizAns_<%q.id%>" class="form-control" maxlength="200" />--}}
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- Model footer -->
                        <div class="modal-footer">
                            <button ng-click="okSend()" type="button" class="btn btn-outline-primary">ส่งใบงาน</button>
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
        var page_permission = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'permission-sheeting-doing',
            data:{ sheeting_id : sheetingID, user_id : user.id},
            async: false,
        }).responseJSON;

        if(page_permission == 404){
            alert("คุณไม่สามารเข้าใช้งานหน้านี้ได้");
            window.location.href = url+'home';
        }
        var sheet_path = "";
        var second = 0;
        var timer;
        var sheetID = "";
        $(document).ready(function () {
            $('#view_sheet_div').css('display', 'block');
        });

        function submitAnsForm() {
            var formData = new FormData($('#AnsFileForm')[0]);
            sheet_path = $.ajax({
                url: url+'uploadSheetFile/'+sheetingID+'/'+sheetID+'/'+user.id,
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