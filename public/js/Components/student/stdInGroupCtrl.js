app.controller('stdInGroupCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    if($window.user.user_type === 't'){
        keepHistory($window.user.id,"teacher-group-other-in-"+$window.groupID,dtJsToDtDB(new Date()));
    } else if ($window.user.user_type === 's'){
        keepHistory($window.user.id,"student-group-in-"+$window.groupID,dtJsToDtDB(new Date()));
    }

    $scope.screenSize = parseInt(screen.width);
    $scope.groupData = findGroupDataByID($window.groupID);
    $scope.myPermissionsInGroup = findMyPermissionsInGroup($scope.user.id,$scope.groupData.id);
    $scope.test = findMyPermissionsInGroup($scope.user.id,$scope.groupData.id);
    $scope.examingComing = findSTDExamingItsComing($scope.groupData.id);
    $scope.examingEnding = findExamingItsEnding($scope.groupData.id);
    $scope.sheeting = findSTDSheetingByGroupID($scope.groupData.id);
    $scope.sendExamHistory = [];

    //----------------------------------------------------------------------
    $scope.checkStart = function (examing) {
        var currentDate = new Date();
        var examingDate = new Date(dtDBToDtJs(examing.start_date_time));
        // examingDate = new Date(examingDate.valueOf()+ examingDate.getTimezoneOffset() * 60000);
        if(currentDate > examingDate){
            return true;
        }
        return false;
    }
    //----------------------------------------------------------------------
    $scope.exitGroup = function () {
        $scope.groupName = $scope.groupData.group_name;
        $('#exit_group_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okExit = function () {
        $('#exit_group_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        exitGroup($scope.user.id,$scope.groupData.id,user.user_type);
    };
    //----------------------------------------------------------------------
    $scope.admitExaming = function (data) {
        $scope.examing = data;
        $scope.examingPassword = "";
        $('#admit_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('#examing_password').focus();
        }, 200);

    };
    //----------------------------------------------------------------------
    $scope.admitSheeting = function (data) {
        window.location.href = url+'student-sheeting-doing-'+data.id;
    };
    //----------------------------------------------------------------------
    $scope.okAdmitExaming = function () {
        if($scope.examingPassword === $scope.examing.examing_pass){
            if($scope.examing.ip_group === ""){
                checkRandomExam($scope.examing);
                window.location.href = url+'student-examing-doing-'+$scope.examing.id;
            } else {
                var in_network = $.ajax({
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    headers: {
                        Accept: "application/json"
                    },
                    url:url + 'examing-check-ip',
                    data: $scope.examing,
                    async: false,
                }).responseJSON;
                if(in_network){
                    checkRandomExam($scope.examing);
                    window.location.href = url+'student-examing-doing-'+$scope.examing.id;
                } else {
                    $('#admit_modal').modal('hide');
                    $('#network_fail_modal').modal({backdrop: 'static'});
                }
            }

        } else {
            $('#admit_modal').modal('hide');
            $('#fail_modal').modal({backdrop: 'static'});
        }
    };
    //----------------------------------------------------------------------
    function checkRandomExam(examing) {
        if(examing.examing_mode === 'r'){
            var randomExam = findExamRandomByUID(user.id,examing.id);
            if(randomExam.length == 0){
                var examExaming = findExamExamingByExamingID(examing.id);
                var randoms = [];
                for(i = 0 ; i < examing.amount ; i++){
                    var randomNum;
                    do{
                        var randomNum = Math.floor((Math.random() * examExaming.length));
                        var duplicate = false;
                        for(j = 0 ; j < randoms.length ; j++){
                            if(randoms[j] === randomNum){
                                duplicate = true;
                            }
                        }
                    }while(duplicate);
                    randoms.push(randomNum);
                }
                for(i = 0 ; i < randoms.length ; i++){
                    var num = randoms[i];
                    data = {
                        examing_id : examing.id,
                        user_id : user.id,
                        exam_id : examExaming[num].exam_id,
                    };
                    addRandomExam(data);
                }
            }
        }
    }
    //----------------------------------------------------------------------
    $scope.checkInTime = function (data) {
        var inTime = false;
        now = new Date();
        // startTime = dtPickerToDtJs(data.start_date_time);
        startTime = dtDBToDtJs(data.start_date_time);
        startTime = new Date(startTime);
        // startTime = new Date(startTime.valueOf()+ startTime.getTimezoneOffset() * 60000);
        // endTime = dtPickerToDtJs(data.end_date_time);
        endTime = dtDBToDtJs(data.end_date_time);
        endTime = new Date(endTime);
        // endTime = new Date(endTime.valueOf()+ endTime.getTimezoneOffset() * 60000);

        if(now >= startTime && now <= endTime){
            inTime = true;
        }
        return inTime;
    };
    //----------------------------------------------------------------------
    $scope.checkSendLate = function (data) {
        var sendLate = false;
        if(data.send_late ==='1'){
            sendLate = true;
        }
        return sendLate;
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
    //----------------------------------------------------------------------
    $scope.viewHistory = function (data) {
        $scope.sendExamHistory = findMySendExamHistory($scope.user.id,data.id);

        $('#history_modal').modal({backdrop: 'static'});
        $('#history_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        setTimeout(function () {
            $scope.sendExamHistory.forEach(function(pathRes) {
                pathRes.code = getCode(pathRes.path);
                pathRes.readResrun = readFileResRun(pathRes.res_run);
            });

            $('#history_part').waitMe('hide');
        }, 420);

    }
    //----------------------------------------------------------------------
    $scope.viewCode = function (data) {
        if ($('#detail_' + data.id).attr('style') === 'display: none;') {

            var code = "";
            (data.code).forEach(function(codes) {
                code+=codes;
            });
            $('#code_' + data.id).html(escapeHtml(code));
            $('#resrun_' + data.id).html(data.readResrun);

            $('mycode').each(function(i, block) {
                hljs.highlightBlock(block);
            });

            $('#detail_' + data.id).show();
        }
        else {
            $('#detail_' + data.id).hide();
        }
    };
    //----------------------------------------------------------------------
    $scope.viewPoint = function (data) {
        window.open(url+'student-board-exam-'+data.id, '_blank');
        window.focus();
    }
    //----------------------------------------------------------------------
    $scope.viewSheetPoint = function (data) {
        window.open(url+'student-board-sheet-'+data.id, '_blank');
        window.focus();
    }
    //----------------------------------------------------------------------

}]);