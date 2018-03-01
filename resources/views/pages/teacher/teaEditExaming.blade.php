@extends('layouts.userSite')
@section('content')
    <script src="js/Components/teacher/teaEditExamingCtrl.js"></script>
    <script>
        var examingID = {{$examingID}};
        var exam_examing = findExamExamingByExamingID(examingID);
    </script>
    <div ng-controller="teaEditExamingCtrl" style="display: none" id="edit_examing_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>จัดการการสอบ</li>
                <li><a href="{{ url('/teacher-examing-history')}}">ประวัติการเปิดสอบ</a></li>
                <li class="active">แก้ไขการเปิดสอบ</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default" id="edit_examing_part">
                <div class="panel-heading">
                    <b style="color: #555">ตั้งค่าการเปิดสอบ</b>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal" role="form">
                        {{--Examing Name--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ชื่อการสอบ <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" ng-model="openExamName" maxlength="200" autofocus/>
                                <div class="notice" id="notice_examing_name" style="display: none">กรุณาระบุชื่อการสอบ</div>
                            </div>
                        </div>

                        {{--Select Group--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">กลุ่มเรียน <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <select class="form-control" ng-model="userGroupID">
                                    <option value="0">กรุณาเลือก</option>
                                    <option ng-repeat="g in myGroups" value="<%g.id%>"><%g.group_name%></option>
                                </select>
                                <div class="notice" id="notice_examing_usr_grp" style="display: none">กรุณาระบุกลุ่มเรียน</div>
                            </div>
                        </div>

                        {{--Select Exam--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ข้อสอบ <b class="danger">*</b></label>
                            <div class="col-md-offset-0 col-md-8 col-xs-offset-1 col-xs-11">
                                <div class="row">
                                    <label class="control-label" style="padding-left: 15px" ng-show="examGroup.length == 0">ไม่พบข้อมูล</label>
                                    <div class="col-md-12 checkbox" ng-repeat="eg in examGroup">
                                        <input type="checkbox" id="sec_<%eg.id%>" style="margin-left: 0px" ng-click="ticAllInEG(eg.id)">
                                        <a href="" ng-click="viewExam(eg.id)" style="padding-left: 20px">
                                            <i id="group_<%eg.id%>" class="fa fa-plus-square"></i> <%eg.exam_group_name%>
                                        </a>
                                        &nbsp;&nbsp;&nbsp;<b style="font-weight: 500" ng-show="eg.user_id != user.id">(<%eg.creater%>)</b>
                                        <div style="padding-left: 50px; padding-top: 5px; display: none" ng-repeat="e in exams" ng-if="e.exam_group_id === eg.id">
                                            <input type="checkbox" id="exam_<%e.id%>" ng-click="ticExam()">
                                            <label style="padding: 0" for="exam_<%e.id%>" ng-click="ticExam()"><%e.exam_name%></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="notice" id="notice_exam" style="display: none">กรุณาระบุข้อสอบที่ใช้ในการสอบ</div>
                            </div>
                        </div>

                        {{--Exam Mode--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">โหมดการสอบ </label>
                            <div class="col-md-offset-0 col-md-8 col-xs-offset-1 col-xs-11">
                                <div class="radio">
                                    <div class="col-md-3">
                                        <input type="radio" name="examingMode" id="normalChk" value="n" ng-model="examingMode">
                                        <label for="normalChk" style="padding: 0">เรียงตามลำดับ</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="examingMode" id="randomChk" value="r" ng-model="examingMode">
                                        <label for="randomChk" style="padding: 0">สุ่ม</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Amount--}}
                        <div class="form-group" ng-show="examingMode === 'r'">
                            <label class="col-md-2 control-label">จำนวนข้อ <b class="danger">*</b></label>
                            <div class="col-md-3">
                                <select class="form-control" ng-model="amountExam">
                                    <option value="0">กรุณาเลือก</option>
                                    <option value="<%a%>" ng-repeat="a in randomExam"><%a%></option>
                                </select>
                                <div class="notice" id="notice_amount" style="display: none">กรุณาระบุจำนวนข้อสอบที่ต้องการสุ่ม</div>
                            </div>
                        </div>

                        {{--Begin Time--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">เวลาเริ่มสอบ <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <input type="text" style="background-color: #fff;cursor: pointer" class="form-control examingBegin" id="examingBegin" data-field="datetime" data-startend="start" data-startendelem=".examingEnd" readonly placeholder="คลิกเลือกเวลา"/>
                                <div class="notice" id="notice_examing_begin" style="display: none">กรุณาระบุเวลาเริ่มสอบ</div>
                            </div>
                        </div>

                        {{--End Time--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">เวลาสิ้นสุดการสอบ <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <input type="text" style="background-color: #fff;cursor: pointer" class="form-control examingEnd" id="examingEnd" data-field="datetime" data-startend="end" data-startendelem=".examingBegin" readonly placeholder="คลิกเลือกเวลา"/>
                                <div class="notice" id="notice_examing_end" style="display: none">กรุณาระบุเวลาสิ้นสุดการสอบ</div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">รหัสเข้าสอบ </label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" ng-model="examingPassword" maxlength="8"/>
                                <a href="" style="float: right; margin-top: 5px" ng-click="randomPassword()">
                                    <i class="fa fa-random" aria-hidden="true"></i> สุ่มรหัสผ่าน
                                </a>
                            </div>
                        </div>

                        {{--Select File Type--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ไฟล์ที่อนุญาตให้ส่ง <b class="danger">*</b></label>
                            <div class="col-md-offset-0 col-md-8 col-xs-offset-1 col-xs-11">
                                <div class="row">
                                    <div class="col-md-2 checkbox">
                                        <input type="checkbox" id="file_type_c" value="c" style="margin-left: 0px">
                                        <label for="file_type_c">.c</label>
                                    </div>
                                    <div class="col-md-2 checkbox">
                                        <input type="checkbox" id="file_type_cpp" value="cpp" style="margin-left: 0px">
                                        <label for="file_type_cpp">.cpp</label>
                                    </div>
                                    <div class="col-md-2 checkbox">
                                        <input type="checkbox" id="file_type_java" value="java" style="margin-left: 0px">
                                        <label for="file_type_java">.java</label>
                                    </div>
                                    <div class="col-md-2 checkbox">
                                        <input type="checkbox" id="file_type_cs" value="cs" style="margin-left: 0px">
                                        <label for="file_type_cs">.cs</label>
                                    </div>
                                </div>
                                <div class="notice" id="notice_file_type" style="display: none">กรุณาระบุไฟล์ที่อนุญาตให้ส่ง</div>
                            </div>
                        </div>

                        <!-- IP -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">จำกัดเฉพาะ IP </label>
                            <div class="col-md-8">
                                <div class="radio">
                                    <div class="col-md-2">
                                        <input type="radio" name="limitIp" id="unlimitIP" value="0" ng-model="ipMode">
                                        <label for="unlimitIP">ไม่ใช่</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="limitIp" id="limitIP" value="1" ng-model="ipMode">
                                        <label for="limitIP">ใช่</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gateway IP -->
                        <div class="form-group" ng-show="ipMode === '1'">
                            <label class="col-md-2 control-label">กลุ่ม IP </label>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" ng-model="gatewayIp" maxlength="15" placeholder="เช่น 172.17.5.254"/>
                                        <div class="notice" id="notice_gateway_ip" style="display: none">กรุณาระบุเครือข่ายที่อนุญาตให้ทำข้อสอบ</div>
                                        <button ng-click="addNetwork()" class="btn btn-outline-info" style="margin-top: 15px"><i class="fa fa-arrow-down" aria-hidden="true"></i> เพิ่มเครือข่าย</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-show="ipMode === '1'">
                            <label class="col-md-2 control-label">เครือข่ายที่ได้รับอนุญาต <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control io_textarea time" ng-model="allowNetwork" maxlength="100" rows="5" disabled></textarea>
                                        <a href="" style="float: right" ng-click="clearIP()"><i class="fa fa-refresh" aria-hidden="true"></i> ล้างรายการเครือข่าย</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{--Hide Examing--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">แสดงในกลุ่มเรียน </label>
                            <div class="col-md-8">
                                <div class="radio">
                                    <div class="col-md-2">
                                        <input type="radio" name="hide_examing" id="hide_ex" value="0" ng-model="hiddenMode">
                                        <label for="hide_ex">ซ่อน</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="hide_examing" id="show_ex" value="1" ng-model="hiddenMode">
                                        <label for="show_ex">แสดง</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--History Examing--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">เข้าถึงประวัติการสอบ </label>
                            <div class="col-md-8">
                                <div class="radio">
                                    <div class="col-md-2">
                                        <input type="radio" name="hide_history" id="hide_his" value="0" ng-model="historyMode">
                                        <label for="hide_his">อนุญาต</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="hide_history" id="show_his" value="1" ng-model="historyMode">
                                        <label for="show_his">ไม่อนุญาต</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        <!--Submit part -->
                        <div class = "form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-success btn-block" value="เปิดสอบ" ng-click="editExaming()" style="margin-top: 7px">บันทึก</button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-danger btn-block" ng-click="goBack()" style="margin-top: 7px">ยกเลิก</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <b class="danger">*</b> จำเป็นต้องกรอกข้อมูล
                </div>
            </div>
        </div>
        <div id="dtBox"></div>
    </div>
    <script>
        $(document).ready(function () {
            // จัดการติกข้อสอบที่เลือกไว้
            $('[id^=exam_]').each(function () {
                examID = $(this).attr('id').substr(5);
                $(exam_examing).each(function (k, v) {
                    if (parseInt(examID) === v.exam_id) {
                        $('#exam_' + examID).attr('checked', 'checked');
                        $('#exam_' + examID).parent().parent().children('div').show();
                        $('#exam_' + examID).parent().parent().children().children('.fa-plus-square').addClass('fa-minus-square');
                        $('#exam_' + examID).parent().parent().children().children('.fa-plus-square').removeClass('fa-plus-square');
                    }
                });

            });

            setDataTimePart();

            $('#edit_examing_div').css('display','block');
            $("#side_examing").removeAttr('class');
            $('#side_examing').attr('class', 'active');
            $("#examing_chevron").removeAttr('class');
            $("#examing_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_examing').attr('class', 'collapse in');
            $('#side_history_examing').attr('class', 'active');
        });

        function setDataTimePart() {
            var bIsPopup = displayPopup();

            $("#dtBox").DateTimePicker({
                isPopup: bIsPopup,
                addEventHandlers: function () {
                    var dtPickerObj = this;
                    $(window).resize(function () {
                        bIsPopup = displayPopup();
                        dtPickerObj.setIsPopup(bIsPopup);
                    });
                }
            });
        }
        function displayPopup() {
            if ($(document).width() >= 768)
                return true;
            else
                return true;
        }
    </script>
@endsection