app.controller('stdViewExamCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"student-examing-doing-"+$window.examingID,dtJsToDtDB(new Date()));

    $scope.examing = $window.examing;
    $scope.groupData = findGroupDataByID($scope.examing.group_id);
    $scope.inputMode = 'key_input'
    $scope.allowedFileType = $scope.examing.allowed_file_type.split(",");
    if($scope.examing.examing_mode === 'n'){
        $scope.examExaming = findExamExamingInViewExam($scope.examing.id,$scope.user.id);
    } else {
        $scope.examExaming = findExamRandomInViewExam($scope.examing.id,$scope.user.id);
    }
    $(document).ready(function () {
        $scope.selectFileType = $scope.allowedFileType[0];
    });
    $('#exam_content').Editor();

    var checked = 0;
    var count = 0;
    var pathExamID = 0;
    var send = false;
    //----------------------------------------------------------------------
    $scope.startExam = function (data) {
        send = false;
        $scope.codeExam = "";
        document.getElementById('file_ans').value = "";
        $scope.CurrentIndex = $scope.examExaming.indexOf(data);
        $scope.examID = data.exam_id;
        $('#detail_exam_modal').modal({backdrop: 'static'});
        $('#detail_exam_part').waitMe({
            effect: 'win8_linear',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        var examData = findExamByID(data.exam_id);
        $('#exam_name').html(examData.exam_name);
        $('#exam_time').html(examData.time_limit);
        $('#exam_memory').html(examData.memory_size);
        var examContent = readExamContent(examData);
        $('#exam_content').Editor('setText', decapeHtml(examContent));
        $('.Editor-editor').attr('contenteditable', false);
        $('[id^=menuBarDiv]').hide();
        $('[id^=statusbar]').hide();
        $('#full_score').html(examData.full_score);
        $('#imper_score').html(examData.accep_imperfect * examData.full_score / 100.0);
        $('#cut_wrongans').html(examData.cut_wrongans);
        $('#cut_comerror').html(examData.cut_comerror);
        $('#cut_overmemory').html(examData.cut_overmemory);
        $('#cut_overtime').html(examData.cut_overtime);
        $('#detail_exam_part').waitMe('hide');
    }
    //----------------------------------------------------------------------
    $scope.okSend = function () {
        $('#detail_exam_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        checked = 0;
        var result = "";
        $('#notice_exam_key_ans').hide();
        $('#notice_exam_file_ans').hide();
        if($scope.inputMode === 'key_input'){
            if($scope.codeExam.length > 0){
                data = {
                    EMID : $scope.examing.id,
                    EID : $scope.examID,
                    UID : $scope.user.id,
                    code : $scope.codeExam,
                    mode : "key",
                    send_date_time : dtJsToDtDB(new Date())
                };
                // ถ้าเป็นไฟล์ .c
                if($scope.selectFileType === "c"){
                    sendExamC(data);
                }

                // ถ้าเป็นไฟล์ .cpp
                else if($scope.selectFileType === "cpp"){
                    sendExamCpp(data);
                }

                // ถ้าเป็นไฟล์ .java
                else if($scope.selectFileType === "java"){
                    sendExamJava(data);
                }

                // ถ้าเป็นไฟล์ .cs
                else if($scope.selectFileType === "cs"){
                    sendExamCs(data);
                }
            } else {
                $('#notice_exam_key_ans').html('* กรุณาใส่โค้ดโปรแกรม').show();
            }
        } else {
            if($("#file_ans")[0].files.length > 0){
                checkFile = checkFileType($("#file_ans")[0].files);
                if(checkFile){
                    $window.examID = $scope.examID;
                    $('#AnsFileForm').submit();

                    data = {
                        path : $window.exam_path,
                        EMID : $scope.examing.id,
                        EID : $scope.examID,
                        UID : $scope.user.id,
                        mode : "file",
                        send_date_time : dtJsToDtDB(new Date())
                    };

                    // ถ้าเป็นไฟล์ .c
                    if($scope.selectFileType === "c"){
                        if(($("#file_ans")[0].files).length > 1){
                            $('#detail_exam_part').waitMe('hide');
                            $('#err_message').html('ไม่อนุญาตให้ส่งไฟล์ .c มากกว่า 1 ไฟล์');
                            $('#fail_modal').modal('show');
                        } else {
                            sendExamC(data);
                        }
                    }

                    // ถ้าเป็นไฟล์ .cpp
                    else if($scope.selectFileType === "cpp"){
                        if(($("#file_ans")[0].files).length > 1){
                            $('#detail_exam_part').waitMe('hide');
                            $('#err_message').html('ไม่อนุญาตให้ส่งไฟล์ .cpp มากกว่า 1 ไฟล์');
                            $('#fail_modal').modal('show');
                        } else {
                            sendExamCpp(data);
                        }
                    }

                    // ถ้าเป็นไฟล์ .java
                    else if($scope.selectFileType === "java"){
                        sendExamJava(data);
                    }

                    // ถ้าเป็นไฟล์ .cs
                    else if($scope.selectFileType === "cs"){
                        sendExamCs(data);
                    }
                } else {
                    $('#detail_exam_part').waitMe('hide');
                    $('#err_message').html('ประเภทของไฟล์ที่คุณส่ง ไม่ตรงกับประเภทไฟล์ที่ระบุ');
                    $('#fail_modal').modal('show');
                }
            } else {
                $('#notice_exam_file_ans').html('* กรุณาเลือกไฟล์').show();
            }
        }
    }
    //----------------------------------------------------------------------
    function getExtension(file) {
        var parts = file.name.split('.');
        return parts[parts.length - 1];
    }
    //----------------------------------------------------------------------
    function checkFileType(files) {
        for(i =0;i<files.length;i++){
            var ext = getExtension(files[i]);
            if (ext.toLowerCase() === $scope.selectFileType) {

            } else {
                return false;
            }
        }
        return true;
    }
    //----------------------------------------------------------------------
    function sendExamC(data) {
        var sendExamC = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'c-send-exam',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.examExaming[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        pathExamID = xhr.responseJSON;
                        $('#detail_exam_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_exam_modal').modal('hide');
                        $('#err_message').html('ไม่สามารถส่งโปรแกรมที่มีไลบารี่ conio.h ได้');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_exam_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
        return sendExamC;
    }
    //----------------------------------------------------------------------
    function sendExamCpp(data) {
        var sendExamCpp = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cpp-send-exam',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.examExaming[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        pathExamID = xhr.responseJSON;
                        $('#detail_exam_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_exam_modal').modal('hide');
                        $('#err_message').html('ไม่สามารถส่งโปรแกรมที่มีไลบารี่ conio.h ได้');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_exam_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
        return sendExamCpp;
    }
    //----------------------------------------------------------------------
    function sendExamJava(data) {
        var sendExamJava = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'java-send-exam',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.examExaming[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        pathExamID = xhr.responseJSON;
                        $('#detail_exam_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_exam_modal').modal('hide');
                        $('#err_message').html('โค้ดที่ส่งไม่ใช่ Default package กรุณาแก้ไข package ของโค้ด');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_exam_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
        return sendExamJava;
    }
    //----------------------------------------------------------------------
    function sendExamCs(data) {
        var sendExamCs = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cs-send-exam',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.examExaming[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        pathExamID = xhr.responseJSON;
                        $('#detail_exam_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_exam_modal').modal('hide');
                        $('#err_message').html('โค้ดที่ส่งห้ามมี namespace');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_exam_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
        return sendExamCs;
    }
    //----------------------------------------------------------------------
    $('#detail_exam_modal').on('hidden.bs.modal', function(){
        checked = 0;
        if(send){
            checkOrderEx(pathExamID);
        }
    });
    //----------------------------------------------------------------------
    function checkOrderEx(pathExamID) {
        $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'examing-check-queue-exam',
            data:{pathExamID:pathExamID},
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    // ถ้าตัวเองคือคนแรก
                    if (xhr.status == 200) {
                        $scope.examExaming[$scope.CurrentIndex].current_status = 'P';
                        $scope.$apply();
                        checked = 1;
                        var fileType = xhr.responseJSON;
                        if(fileType === "c"){
                            compileAndRunC(pathExamID);
                        }
                        else if(fileType === "cpp"){
                            compileAndRunCpp(pathExamID);
                        }
                        else if(fileType === "java"){
                            compileAndRunJava(pathExamID);
                        }
                        else if(fileType === "cs"){
                            compileAndRunCs(pathExamID);
                        }
                    } else { // ถ้าไม่ใช่คนแรก
                        // รอตรวจนานเกิน 9 วินาที
                        if (count > 2) {
                            deleteFirstQueue();
                            count = 0;
                        }
                    }
                    count++;
                    if (!checked) {
                        setTimeout(function () {
                            checkOrderEx(pathExamID);
                        }, 3000);
                    }
                }
            }
        });
    }
    //----------------------------------------------------------------------
    function compileAndRunC(pathExamID) {
        var testCompile = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'c-compile',
            data:{
                mode:"exam",
                pathExamID:pathExamID,
                exam_id : $scope.examID
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.examExaming[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();

                        deleteFirstQueue();
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function compileAndRunCpp(pathExamID) {
        var testCompile = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cpp-compile',
            data:{
                mode:"exam",
                pathExamID:pathExamID,
                exam_id : $scope.examID
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.examExaming[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();
                        deleteFirstQueue();
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function compileAndRunJava(pathExamID) {
        var testCompile = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'java-compile',
            data:{
                mode:"exam",
                pathExamID:pathExamID,
                exam_id : $scope.examID
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.examExaming[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();

                        deleteFirstQueue();
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function compileAndRunCs(pathExamID) {
        var pathBat = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cs-create-bat',
            data:{
                mode:"exam",
                pathExamID:pathExamID,
                exam_id : $scope.examID
            },
            async: false,
        }).responseJSON;

        var testCompile = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cs-compile',
            data:{
                mode:"exam",
                pathExamID:pathExamID,
                exam_id : $scope.examID,
                pathBat : pathBat
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.examExaming[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();
                        deleteFirstQueue();
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function deleteFirstQueue() {
        $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'examing-delete-queue-exam',
            async: false,
        })
    }
    //----------------------------------------------------------------------
    $scope.viewScore = function (data) {
        $('#score_modal').modal({backdrop: 'static'});

        $('#score_board_hd').children().remove();
        $('#score_board_tb').children().remove();

        var examInScoreboard = findExamInScoreboard($scope.examing.id);
        try {
            head = '';
            num = 0;

            examInScoreboard.forEach(function(exam) {
                head += '<th class="hidden-print hidden-xs hidden-sm" style="text-align: center">' + exam.exam_name + '</th>';
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
    }
}]);