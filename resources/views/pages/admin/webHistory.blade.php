@extends('layouts.adminSite')
@section('page-title','ประวัติการใช้งานระบบ')
@section('content')
    <link href="dateTimePicker/DateTimePicker.min.css " rel="stylesheet" type="text/css">
    <script src="dateTimePicker/DateTimePicker.min.js"></script>
    <script src="js/Components/admin/webHistoryCtrl.js"></script>
    <div ng-controller="webHistoryCtrl" style="display: none" id="history_list_div">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b style="color: #555">ประวัติการใช้งานระบบ</b>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <label class="col-md-12 col-xs-12 control-label"
                                   style="margin-top: 14px;color: red">ช่วงเวลาที่ต้องการค้นหา*</label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        {{--Begin Time--}}
                        <div class="col-md-5 col-xs-12">
                            <div class="form-group">
                                <label class="col-md-2 col-xs-2 control-label" style="margin-top: 14px;padding-right: 0px">จาก </label>
                                <div class="col-md-10 col-xs-10" style="padding-right: 0px;padding-top: 7px">
                                    <input type="text" style="background-color: #fff;cursor: pointer" class="form-control historyBegin" id="historyBegin" data-field="datetime" data-startend="start" data-startendelem=".historyEnd" readonly placeholder="คลิกเลือกเวลา"/>
                                    <div class="notice" id="notice_history_begin" style="display: none">กรุณาระบุช่วงเวลาที่ต้องการค้นหาให้ครบ</div>
                                </div>
                            </div>
                        </div>
                        {{--End Time--}}
                        <div class="col-md-5 col-xs-12">
                            <div class="form-group">
                                <label class="col-md-2 col-xs-2 control-label" style="margin-top: 14px;padding-right: 0px">ถึง </label>
                                <div class="col-md-10 col-xs-10" style="padding-right: 0px;padding-top: 7px">
                                    <input type="text" style="background-color: #fff;cursor: pointer" class="form-control historyEnd" id="historyEnd" data-field="datetime" data-startend="end" data-startendelem=".historyBegin" readonly placeholder="คลิกเลือกเวลา"/>
                                    <div class="notice" id="notice_history_end" style="display: none">กรุณาระบุช่วงเวลาที่ต้องการค้นหาให้ครบ</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12" style="padding-top: 7px">
                            <div class="col-md-12 col-md-offset-0 col-xs-offset-2 col-xs-8">
                                <button ng-click="findHistory()" class="btn btn-outline-success btn-block">ค้นหา</button>
                            </div>
                        </div>
                    </div>
                    {{--<br>--}}
                    <br>
                    <br>
                    <div class="row" ng-show="completeFind">
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
                    <div class="col-md-12 table-responsive" ng-show="completeFind">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>ชื่อ - นามสกุล</th>
                                <th>สถานะ</th>
                                <th>หน้าที่เข้าใช้งาน</th>
                                <th>ip address</th>
                                <th>วัน-เวลา</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-show="history.length > 0" dir-paginate="h in history|orderBy:sortH|filter:search|itemsPerPage:selectRow">
                                <td><a href="" ng-click="viewProfile(h)"><%h.prefix%><%h.fname_th%> <%h.lname_th%></a></td>
                                <td><%h.user_type%></td>
                                <td><%h.page%></td>
                                <td><%h.ip%></td>
                                <td><%h.time_stamp%></td>
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
        <div id="dtBox"></div>

        <!-- Profile Modal -->
        <div class="modal fade" id="profile_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-success" id="profile_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ประวัติผู้ใช้งาน</h3>
                        </div>
                        <div class="form-horizontal" role="form" style="padding-top: 7%">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">คำนำหน้า</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="prefix" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">ชื่อ</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="fname" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">นามสกุล</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="lname" disabled>
                                </div>
                            </div>

                            <div class="form-group" ng-hide="stuID ===''">
                                <label class="col-md-3 col-xs-12 control-label">รหัสนักศึกษา</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="stuID" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">คณะ</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="faculty" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">สาขา</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="department" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">สถานะ</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control" ng-model="userType" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-default" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#history_list_div').css('display', 'block');
            $('#side_webHis').attr('class','active');
            setDataTimePart();
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

        function dtPickerToDtDB(date) {
            dt = date.split(' ');
            d = dt[0].split('-');
            r = (d[2]) + '-' + d[1] + '-' + d[0] + ' ' + (dt[1]+":00");
            return r;
        }
    </script>
@endsection