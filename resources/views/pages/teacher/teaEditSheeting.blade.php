@extends('layouts.userSite')
@section('content')
    <script src="js/Components/teacher/teaEditSheetingCtrl.js"></script>
    <script>
        var sheetingID = {{$sheetingID}};
        var sheet_sheeting = findSheetSheetingBySheetingID(sheetingID);
    </script>
    <div ng-controller="teaEditSheetingCtrl" style="display: none" id="edit_sheeting_div">
        <div class="col-md-12 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home')}}">หน้าหลัก</a></li>
                <li>จัดการการสั่งงาน</li>
                <li><a href="{{ url('/teacher-sheeting-history')}}">ประวัติการสั่งงาน</a></li>
                <li class="active">แก้ไขการสั่งงาน</li>
            </ol>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default" id="edit_sheeting_part">
                <div class="panel-heading">
                    <b style="color: #555">แก้ไขการสั่งงาน</b>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal" role="form">
                        {{--Sheeting Name--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ชื่อการสั่งงาน <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" ng-model="openSheetName" maxlength="200" autofocus/>
                                <div class="notice" id="notice_sheeting_name" style="display: none">กรุณาระบุชื่อการสั่งงาน</div>
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
                                <div class="notice" id="notice_sheeting_usr_grp" style="display: none">กรุณาระบุกลุ่มเรียน</div>
                            </div>
                        </div>

                        {{--Select Sheet--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ใบงาน <b class="danger">*</b></label>
                            <div class="col-md-offset-0 col-md-8 col-xs-offset-1 col-xs-11">
                                <div class="row">
                                    <label class="control-label" style="padding-left: 15px" ng-show="sheetGroups.length == 0">ไม่พบข้อมูล</label>
                                    <div class="col-md-12 checkbox" ng-repeat="sg in sheetGroups">
                                        <input type="checkbox" id="sg_<%sg.id%>" style="margin-left: 0px" ng-click="ticAllInSg(sg.id)">
                                        <a href="" ng-click="viewSheet(sg.id)" style="padding-left: 20px">
                                            <i id="group_<%sg.id%>" class="fa fa-plus-square"></i> <%sg.sheet_group_name%>
                                        </a>
                                        &nbsp;&nbsp;&nbsp;<b style="font-weight: 500" ng-show="sg.user_id != user.id">(<%sg.creater%>)</b>
                                        <div style="padding-left: 50px; padding-top: 5px; display: none" ng-repeat="sh in sheets" ng-if="sh.sheet_group_id === sg.id">
                                            <input type="checkbox" id="sheet_<%sh.id%>" ng-click="ticSheet()">
                                            <label style="padding: 0" for="sheet_<%sh.id%>" ng-click="ticSheet()"><%sh.sheet_name%></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="notice" id="notice_sheet" style="display: none">กรุณาระบุใบงานที่ใช้ในการสั่งงาน</div>
                            </div>
                        </div>

                        {{--Begin Time--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">เวลาเริ่มต้น <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <input type="text" style="background-color: #fff;cursor: pointer" class="form-control sheetingBegin" id="sheetingBegin" data-field="datetime" data-startend="start" data-startendelem=".sheetingEnd" readonly placeholder="คลิกเลือกเวลา"/>
                                <div class="notice" id="notice_sheeting_begin" style="display: none">กรุณาระบุเวลาเริ่มต้น</div>
                            </div>
                        </div>

                        {{--End Time--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">เวลาสิ้นสุด <b class="danger">*</b></label>
                            <div class="col-md-5">
                                <input type="text" style="background-color: #fff;cursor: pointer" class="form-control sheetingEnd" id="sheetingEnd" data-field="datetime" data-startend="end" data-startendelem=".sheetingBegin" readonly placeholder="คลิกเลือกเวลา"/>
                                <div class="notice" id="notice_sheeting_end" style="display: none">กรุณาระบุเวลาสิ้นสุด</div>
                            </div>
                        </div>

                        {{--Select File Type--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">ไฟล์ที่อนุญาตให้ส่ง </label>
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

                        {{--Allow Send Late--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">อนุญาติให้ส่งเกินเวลา</label>
                            <div class="col-md-8">
                                <div class="radio">
                                    <div class="col-md-2">
                                        <input type="radio" name="send_late" id="allow" value="1" ng-model="sendLateMode">
                                        <label for="allow">อนุญาต</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="send_late" id="not_allow" value="0" ng-model="sendLateMode">
                                        <label for="not_allow">ไม่อนุญาต</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Hide Sheeting--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">แสดงในกลุ่มเรียน</label>
                            <div class="col-md-8">
                                <div class="radio">
                                    <div class="col-md-2">
                                        <input type="radio" name="hide_sheeting" id="hide_sh" value="0" ng-model="hiddenMode">
                                        <label for="hide_sh">ซ่อน</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="hide_sheeting" id="show_sh" value="1" ng-model="hiddenMode">
                                        <label for="show_sh">แสดง</label>
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
                                <input type="button" class="btn btn-outline-success btn-block" style="margin-top: 7px" value="บันทึกการสั่งงาน" ng-click="editOpenSheet()"/>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-outline-danger btn-block" ng-click="goBack()" style="margin-top: 7px">ยกเลิก</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="dtBox"></div>
    </div>
    <script>
        $(document).ready(function () {
            $('#edit_sheeting_div').css('display','block');
            $("#side_sheeting").removeAttr('class');
            $('#side_sheeting').attr('class', 'active');
            $("#sheeting_chevron").removeAttr('class');
            $("#sheeting_chevron").attr('class','fa2 fa-chevron-down');
            $('#demo_sheeting').attr('class', 'collapse in');
            $('#side_history_sheeting').attr('class', 'active');

            setDataTimePart();
            // จัดการติกใบงานที่เลือกไว้
            $('[id^=sheet_]').each(function () {
                sheetID = $(this).attr('id').substr(6);
                $(sheet_sheeting).each(function (k, v) {
                    if (parseInt(sheetID) === v.sheet_id) {
                        $('#sheet_' + sheetID).attr('checked', 'checked');
                        $('#sheet_' + sheetID).parent().parent().children('div').show();
                        $('#sheet_' + sheetID).parent().parent().children().children('.fa-plus-square').addClass('fa-minus-square');
                        $('#sheet_' + sheetID).parent().parent().children().children('.fa-plus-square').removeClass('fa-plus-square');
                    }
                });

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
        });
    </script>
@endsection