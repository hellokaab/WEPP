app.controller('teaInGroupCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-group-my-in-"+$window.groupId,getDateNow());
    $scope.groupID = $window.groupId;
    $scope.groupData = findGroupDataByID($scope.groupID);
    $scope.examingComing = findExamingItsComing($scope.groupData.id);
    $scope.examingEnding = findExamingItsEnding($scope.groupData.id);
    $scope.sheeting = findSheetingByGroupID($scope.groupData.id);
    $scope.memberList = findMemberGroup($scope.groupData.id);
    $scope.selectRow = "10";

    $(document).ready(function () {
        for(i=0;i<$scope.examingComing.length;i++){
            if($scope.examingComing[i].hide_examing === "0"){
                document.getElementById("hide_ex_"+$scope.examingComing[i].id).checked = true;
            } else {
                document.getElementById("show_ex_"+$scope.examingComing[i].id).checked = true;
            }
        }

        for(i=0;i<$scope.examingEnding.length;i++){
            if($scope.examingEnding[i].hide_history === "0"){
                document.getElementById("hide_hi_"+$scope.examingEnding[i].id).checked = true;
            } else {
                document.getElementById("show_hi_"+$scope.examingEnding[i].id).checked = true;
            }
        }

        for(i=0;i<$scope.sheeting.length;i++){
            if($scope.sheeting[i].hide_sheeting === "0"){
                document.getElementById("hide_sh_"+$scope.sheeting[i].id).checked = true;
            } else {
                document.getElementById("show_sh_"+$scope.sheeting[i].id).checked = true;
            }
        }
    });

    //----------------------------------------------------------------------
    $scope.editGroup = function () {
        $scope.groupName = $scope.groupData.group_name;
        $scope.passwordGroup = $scope.groupData.group_password;

        $('#notice_name_edit_grp').hide();
        $('#notice_pass_edit_grp').hide();
        $('#edit_group_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=groupName]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.enterEdit = function() {
        $('[ng-model=passwordGroup]').focus();
    };
    $scope.enterOkEdit = function() {
        $scope.okEditGroup();
    };
    //----------------------------------------------------------------------
    $scope.okEditGroup = function () {
        $('#notice_name_add_grp').hide();
        $('#notice_pass_add_grp').hide();

        if ($scope.groupName.length > 0 && $scope.passwordGroup.length > 3) {
            data = {
                id      : $scope.groupID,
                group_name: $scope.groupName,
                group_password : $scope.passwordGroup,
                user_id   :$scope.user.id
            }

            $('#edit_group_part').waitMe({
                effect: 'win8_linear',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            setTimeout(function () {
                editGroup(data);
            }, 100)
        } else if ($scope.groupName.length === 0) {
            $('#notice_name_edit_grp').html('* กรุณาระบุชื่อกลุ่มเรียน').show();
            $('[ng-model=groupName]').focus();
        } else if ($scope.passwordGroup.length < 4) {
            $('#notice_pass_edit_grp').html('* ต้องมีความยาวอย่างน้อย 4 ตัวอักษร').show();
            $('[ng-model=passwordGroup]').focus();
        }
    }
    //----------------------------------------------------------------------
    $scope.showProfile = function (data) {

        $('#detail_modal').modal({backdrop: 'static'});

        var userProfile = findUserByID(data.user_id);
        $scope.prefix = userProfile.prefix;
        $scope.name = userProfile.fname_th+" "+userProfile.lname_th;
        $scope.email = userProfile.email;
        $scope.cardId = userProfile.stu_id;
        $scope.faculty = userProfile.faculty;
        $scope.department = userProfile.department;

        $('#detail_part').waitMe('hide');

    };
    //----------------------------------------------------------------------
    $scope.managePermissions = function (data) {
        $('#permissions_modal').modal({backdrop: 'static'});
        $('#permissions_part').waitMe({
            effect: 'win8_linear',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $scope.dataInGroup = data;
        $scope.manage_name = data.fullName;
        data.view_exam === '1' ? $("#view_ex")[0].checked = true:$("#view_ex")[0].checked = false
        data.view_sheet === '1' ? $("#view_sh")[0].checked = true:$("#view_sh")[0].checked = false
        data.edit_exam === '1' ? $("#edit_ex")[0].checked = true:$("#edit_ex")[0].checked = false
        data.edit_sheet === '1' ? $("#edit_sh")[0].checked = true:$("#edit_sh")[0].checked = false
        $('#permissions_part').waitMe('hide');
    };
    //----------------------------------------------------------------------
    $scope.okManage = function () {

        $('#permissions_part').waitMe({
            effect: 'win8_linear',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        data = {
            id: $scope.dataInGroup.id,
            view_exam : $("#view_ex")[0].checked == true ? 1 : 0,
            view_sheet : $("#view_sh")[0].checked == true ? 1 : 0,
            edit_exam : $("#edit_ex")[0].checked == true ? 1 : 0,
            edit_sheet : $("#edit_sh")[0].checked == true ? 1 : 0
        }
        if($scope.dataInGroup.status === 'a'){
            data.status = 'a';
        } else if ($scope.dataInGroup.status === 'ao'){
            data.status = 'ao';
        } else {
            if($("#view_ex")[0].checked == false && $("#view_sh")[0].checked == false && $("#edit_ex")[0].checked == false && $("#edit_sh")[0].checked == false){
                data.status = 's';
            } else {
                data.status = 'as';
            }
        }

        managePermissions(data);
    }
    //----------------------------------------------------------------------
    $scope.deleteMember = function (data) {
        $scope.memberName = data.fullName;
        $scope.memberId = data.user_id;
        $('#delete_member_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDeleteMember = function () {
        var data = {
            group_id :$scope.groupData.id,
            user_id   :$scope.memberId,
        };

        $('#delete_member_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        $.ajax ({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'group-delete-join',
            data: data,
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $('#delete_member_part').waitMe('hide');
                        $('#delete_member_modal').modal('hide');
                        $scope.memberList = findMemberGroup($scope.groupData.id);
                        $('#success_modal').modal({backdrop: 'static'});
                    } else {
                        $('#delete_member_part').waitMe('hide');
                        $('#delete_member_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        });
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        location.reload();
    });
    //----------------------------------------------------------------------
    $scope.openExaming = function() {
        window.location.href = url+"teacher-examing-add";
    };
    //----------------------------------------------------------------------
    $scope.openSheeting = function() {
        window.location.href = url+"teacher-sheeting-add";
    };
    //----------------------------------------------------------------------
    $scope.changeHidden = function(obj,mode) {
        // หมายเหตุ
        // he หมายถึง ซ่อนข้อสอบ
        // se หมายถึง แสดงข้อสอบ
        // hs หมายถึง ซ่อนใบงาน
        // ss หมายถึง แสดงใบงาน
        if(mode === 'he' || mode === 'se'){
            if(mode === 'he' && obj.hide_examing === '1'){
                $('#message_confirm').html('คุณต้องการซ่อนการสอบนี้หรือไม่');
                $scope.confirmName = obj.examing_name;
                $('#confirm_modal').modal({backdrop: 'static'});
                $scope.changeID = obj.id;
                $scope.changeMode = mode;

            } else if(mode === 'se' && obj.hide_examing === '0'){
                $('#message_confirm').html('คุณต้องการแสดงการสอบนี้หรือไม่');
                $scope.confirmName = obj.examing_name;
                $('#confirm_modal').modal({backdrop: 'static'});
                $scope.changeID = obj.id;
                $scope.changeMode = mode;
            }
        } else if(mode === 'hs' || mode === 'ss'){
            if(mode === 'hs' && obj.hide_sheeting === '1'){
                $('#message_confirm').html('คุณต้องการซ่อนการสั่งงานนี้หรือไม่');
                $scope.confirmName = obj.sheeting_name;
                $('#confirm_modal').modal({backdrop: 'static'});
                $scope.changeID = obj.id;
                $scope.changeMode = mode;

            } else if (mode === 'ss' && obj.hide_sheeting === '0'){
                $('#message_confirm').html('คุณต้องการแสดงการสั่งงานนี้หรือไม่');
                $scope.confirmName = obj.sheeting_name;
                $('#confirm_modal').modal({backdrop: 'static'});
                $scope.changeID = obj.id;
                $scope.changeMode = mode;
            }
        }
    };
    //----------------------------------------------------------------------
    $scope.okChange = function() {
        var link = "";
        // หมายเหตุ
        // he หมายถึง ซ่อนข้อสอบ
        // se หมายถึง แสดงข้อสอบ
        // hs หมายถึง ซ่อนใบงาน
        // ss หมายถึง แสดงใบงาน
        if($scope.changeMode === 'he'){
            var data = {
                id        : $scope.changeID,
                hide_examing: "0",
            };
            link = 'examing-change-hidden';

        } else if($scope.changeMode === 'se'){
            var data = {
                id        : $scope.changeID,
                hide_examing: "1",
            };
            link = 'examing-change-hidden';

        } else if($scope.changeMode === 'hs'){
            var data = {
                id        : $scope.changeID,
                hide_sheeting: "0",
            };
            link = 'sheeting-change-hidden';

        } else if($scope.changeMode === 'ss'){
            var data = {
                id        : $scope.changeID,
                hide_sheeting: "1",
            };
            link = 'sheeting-change-hidden';
        }

        $('#confirm_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        $.ajax ({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + link,
            data: data,
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $('#confirm_part').waitMe('hide');
                        $('#confirm_modal').modal('hide');
                        $('#success_modal').modal({backdrop: 'static'});
                    } else {
                        $('#confirm_part').waitMe('hide');
                        $('#confirm_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        });
    };
    //----------------------------------------------------------------------
    $scope.cancelChange = function() {
        // หมายเหตุ
        // he หมายถึง ซ่อนข้อสอบ
        // se หมายถึง แสดงข้อสอบ
        // hs หมายถึง ซ่อนใบงาน
        // ss หมายถึง แสดงใบงาน
        if($scope.changeMode === 'he'){
            document.getElementById("show_ex_"+$scope.changeID).checked = true;
        } else if($scope.changeMode === 'se'){
            document.getElementById("hide_ex_"+$scope.changeID).checked = true;
        } else if($scope.changeMode === 'hs'){
            document.getElementById("show_sh_"+$scope.changeID).checked = true;
        } else if($scope.changeMode === 'ss'){
            document.getElementById("hide_sh_"+$scope.changeID).checked = true;
        }
        $('#confirm_modal').modal('hide');
    };
    //----------------------------------------------------------------------
    $scope.changeToAllow = function(data,mode) {
        if(data.hide_history != mode){
            $scope.changeHistoryMode = mode;
            $scope.examingName = data.examing_name;
            $scope.examingID = data.id;
            $('#change_allow_modal').modal({backdrop: 'static'});
        }
    };
    //----------------------------------------------------------------------
    $scope.cancelAllow = function() {
        if($scope.changeHistoryMode === '0'){
            document.getElementById("show_hi_"+$scope.examingID).checked = true;
        } else {
            document.getElementById("hide_hi_"+$scope.examingID).checked = true;
        }
        $('#change_allow_modal').modal('hide');
    };
    //----------------------------------------------------------------------
    $scope.okAllow = function() {
        var data = {
            id        : $scope.examingID,
            hide_history: $scope.changeHistoryMode,
        };

        $('#change_allow_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        $.ajax ({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'examing-change-history',
            data: data,
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $('#change_allow_part').waitMe('hide');
                        $('#change_allow_modal').modal('hide');
                        $('#success_modal').modal({backdrop: 'static'});
                    } else {
                        $('#change_allow_part').waitMe('hide');
                        $('#change_allow_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        });
    };
    //----------------------------------------------------------------------
    $scope.viewPoint = function (data) {
        window.open(url+'teacher-board-exam-'+data.id, '_blank');
        window.focus();
    }
    //----------------------------------------------------------------------
    $scope.viewSheetPoint = function (data) {
        window.open(url+'teacher-board-sheet-'+data.id, '_blank');
        window.focus();
    }
    //----------------------------------------------------------------------
    $scope.editExaming = function (data) {
        window.location.href = url+"teacher-examing-edit-"+data.id;
    };
    //----------------------------------------------------------------------
    $scope.editSheeting = function (data) {
        window.location.href = url+"teacher-sheeting-edit-"+data.id;
    };
    //----------------------------------------------------------------------
    $scope.deleteData = function (data,mode) {
        $scope.deleteMode = mode;
        if($scope.deleteMode === 'ex'){
            $scope.deleteName = data.examing_name;
            $('#message_delete').html('คุณต้องการลบการสอบนี้หรือไม่');
            $('#message_delete_2').html('(ข้อมูลการสอบ และไฟล์ที่นักศึกษาส่งในการสอบนี้จะถูกลบไปด้วย)');
        } else if ($scope.deleteMode === 'sh'){
            $scope.deleteName = data.sheeting_name;
            $('#message_delete').html('คุณต้องการลบการสั่งงานนี้หรือไม่');
            $('#message_delete_2').html('(ข้อมูลการสั่งงาน และไฟล์ที่นักศึกษาส่งในการสั่งงานนี้จะถูกลบไปด้วย)');
        }
        $scope.deleteID = data.id;
        $('#delete_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.confirmDeleteData = function () {
        if($scope.deleteMode === 'ex'){
            $('#message_delete_3').html('คุณแน่ใจแล้วที่จะลบการเปิดสอบนี้หรือไม่');
        } else if ($scope.deleteMode === 'sh'){
            $('#message_delete_3').html('คุณแน่ใจแล้วที่จะลบการสั่งงานนี้หรือไม่');
        }
        $('#delete_modal').modal('hide');
        $('#delete_again_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDelete = function () {
        $('#delete_again_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        if ($scope.deleteMode === 'ex'){
            deleteExaming($scope.deleteID);
        } else if ($scope.deleteMode === 'sh'){
            deleteSheeting($scope.deleteID);
        }
    };
    //----------------------------------------------------------------------
    $scope.viewScore = function (data) {
        $('#score_modal').modal({backdrop: 'static'});

        $('#score_board_hd').children().remove();
        $('#score_board_tb').children().remove();
        $('#examing_title').html(data.examing_name);

        var examInScoreboard = findExamInScoreboard(data.id);
        try {
            head = '';
            num = 0;

            examInScoreboard.forEach(function(exam) {
                head += '<th class="hidden-print hidden-xs hidden-sm" style="text-align: center"><a href="#" onclick="return viewDetailExam('+exam.exam_id+')">' + exam.exam_name + '</a></th>';
                num++;
            });

            // จัดการ thead (หัวตาราง)
            $('#score_board_hd').append('<tr><th>ลำดับ</th><th>รหัสนักศึกษา</th><th class="hidden-print hidden-xs hidden-sm">ชื่อ-นามสกุล</th>' + head + '<th style="text-align: center">จำนวนข้อที่ผ่าน</th></tr>');

            var scoreBoard = dataInScoreboard(data);
            i = 1;
            scoreBoard.forEach(function(list) {
                body = '';
                total_accept = 0;
                // status = '-',accept = 0, imperfect = 0, wrong = 0, compile = 0, time = 0, memory = 0;
                (list.res_status).forEach(function(res) {
                    if(res.length>0){
                        s = 'class="hidden-print hidden-xs hidden-sm">' + res[0].sum_accep + '/' + res[0].sum_imp + '/' + res[0].sum_wrong + '/' + res[0].sum_comerror + '/' + res[0].sum_overtime + '/' + res[0].sum_overmem;
                        if(res[0].current_status === "a"){
                            total_accept++;
                            body += '<td style="text-align: center;background-color: ' + (i % 2 === 0 ? '#A0D468' : '#8CC152') + '" ' + s + '</td>';
                        } else if (res[0].current_status === "i"){
                            body += '<td style="text-align: center;background-color: ' + (i % 2 === 0 ? '#FFCE54' : '#F6BB42') + '" ' + s + '</td>';
                        } else if (res[0].current_status === "w"){
                            // str += '<td style="color: #fff; background-color: ' + (i % 2 === 1 ? '#ED5565' : '#DA4453') + '" ' + s + '</td>';
                            body += '<td style="text-align: center;background-color: ' + (i % 2 === 0 ? '#ED5565' : '#DA4453') + '" ' + s + '</td>';
                        } else if (res[0].current_status === "c"){
                            body += '<td style="text-align: center;background-color: ' + (i % 2 === 0 ? '#CCD1D9' : '#AAB2BD') + '" ' + s + '</td>';
                        } else if (res[0].current_status === "t"){
                            body += '<td style="text-align: center;background-color: ' + (i % 2 === 0 ? '#4FC1E9' : '#3BAFDA') + '" ' + s + '</td>';
                        } else if (res[0].current_status === "m"){
                            body += '<td style="text-align: center;background-color: ' + (i % 2 === 0 ? '#EC87C0' : '#D770AD') + '" ' + s + '</td>';
                        } else if (res[0].current_status === "q"){
                            body += '<td style="text-align: center;background-color: ' + (i % 2 === 0 ? '' : '') + '" ' + s + '</td>';
                        }
                    } else {
                        s = 'class="hidden-print hidden-xs hidden-sm">' + 0 + '/' + 0 + '/' + 0 + '/' + 0 + '/' + 0 + '/' + 0;
                        body += '<td style="text-align: center" ' + s + '</td>';
                    }
                });
                $('#score_board_tb').append('<tr><td>' + i + '</td><td>' + list.stu_id + '</td><td class="hidden-print hidden-xs hidden-sm">' + list.full_name + body + '<td style="text-align: center">'+ total_accept + '/' + num +'</td></tr>');
                i++;
            });
        } catch (err) {
            $('#score_board_hd').children().remove();
            $('#score_board_tb').children().remove();
        }
    };
}]);