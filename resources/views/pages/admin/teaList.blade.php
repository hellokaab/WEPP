@extends('layouts.adminSite')
@section('page-title','รายชื่ออาจารย์ในระบบ')
@section('content')
    <script src="js/Components/admin/teaListCtrl.js"></script>
    <div ng-controller="teaListCtrl" style="display: none" id="tea_list_div">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b style="color: #555">รายชื่ออาจารย์ในระบบ</b>
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
                                    <th style="width: 25%">ชื่อ - นามสกุล</th>
                                    <th style="width: 30%">คณะ</th>
                                    <th style="width: 25%">สาขาวิชา</th>
                                    <th style="width: 20%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-show="teachers.length > 0" dir-paginate="t in teachers|filter:search|itemsPerPage:selectRow">
                                    <td><%t.prefix%><%t.fname_th%> <%t.lname_th%></td>
                                    <td><%t.faculty%></td>
                                    <td><%t.department%></td>
                                    <td style="text-align: center">
                                        <button class="btn btn-sm btn-outline-danger" title="ลบออกจากระบบ" ng-click="deleteTeacher(t)">
                                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i> ลบออกจากระบบ
                                        </button>
                                    </td>
                                </tr>
                                <tr ng-hide="teachers.length > 0">
                                    <td>ไม่พบข้อมูล</td>
                                    <td></td>
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
            </div>
        </div>

        <!-- Delete Teacher Modal -->
        <div class="modal fade" id="delete_teacher_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger" id="delete_teacher_part" style="margin-bottom: 0">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #fff">ยืนยันการทำรายการ</h3>
                        </div>
                        <!-- Form -->
                        <div style="padding-top: 7%; text-align: center">คุณต้องการลบผู้ใช้งานนี้หรือไม่</div>
                        <br>
                        <input style="margin-left: 10%; width: 80%" type="text" class="form-control text-center"
                               ng-model="teacherName" disabled/>
                        <div id="delete_msg" style="padding-top: 3%; text-align: center">(ข้อมูลทุกอย่างที่เกี่ยวข้องกับผู้ใช้นี้จะถูกลบไปด้วย)
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
        $(document).ready(function () {
            $('#tea_list_div').css('display', 'block');
            $('#side_teaList').attr('class','active');
        });
    </script>
@endsection