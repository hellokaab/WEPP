app.controller('stdViewSheetCtrl', ['$scope', '$window', function ($scope, $window) {
    keepHistory($window.user.id,"student-sheeting-doing-"+$window.sheetingID,dtJsToDtDB(new Date()));
    $scope.sheeting = $window.sheeting;
    $scope.inputMode = 'key_input';
    $scope.allowedFileType = $scope.sheeting.allowed_file_type.split(",");
    $scope.groupData = findGroupDataByID($scope.sheeting.group_id);
    $scope.sheetSheeting = findSheetSheetingInViewSheet($scope.sheeting.id,user.id);
    $(document).ready(function () {
        $scope.selectFileType = $scope.allowedFileType[0];
    });
    $('#sheet_trial').Editor();
    $scope.objective = "";
    $scope.theory = "";
    $scope.thisStatus = "";
    $scope.tab = "s";

    var checked = 0;
    var count = 0;
    $scope.resSheetID = 0;
    var send = false;
    //----------------------------------------------------------------------
    $scope.startSheet = function (data) {
        $scope.tab = "s";
        $scope.inputMode = 'key_input';
        $('#notice_sheet_key_ans').hide();
        $('#notice_sheet_file_ans').hide();
        $('#li_s').attr('class','active');
        $('#li_o').removeAttr('class');
        $('#fileType').removeAttr('disabled');
        $('#keyInputChk').removeAttr('disabled');
        $('#fileInputChk').removeAttr('disabled');
        $('[ng-model=codeSheet]').removeAttr('disabled');

        send = false;
        $scope.codeSheet = "";
        document.getElementById('file_ans').value = "";
        $scope.CurrentIndex = $scope.sheetSheeting.indexOf(data);
        $scope.thisSheet = data;
        $scope.sheetID = data.sheet_id;
        $scope.thisStatus = data.current_status;
        $scope.quiz = findQuizBySID(data.sheet_id);


        $('#detail_sheet_modal').modal({backdrop: 'static'});
        $('#detail_sheet_part').waitMe({
            effect: 'win8_linear',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        if($scope.thisStatus === 'a'){
            $('#fileType').attr('disabled','disabled');
            $('#keyInputChk').attr('disabled','disabled');
            $('#fileInputChk').attr('disabled','disabled');
            $('[ng-model=codeSheet]').attr('disabled','disabled');
        }
    };
    //----------------------------------------------------------------------
    $('#detail_sheet_modal').on('shown.bs.modal', function(){ // ฟังก์ชันนี้เป็นฟังก์ชันที่จะทำงานหลังจาก modal แสดงเสร็จเรียบร้อยแล้ว
        var sheetData = findSheetByID($scope.thisSheet.sheet_id);
        $('#sheet_name').html(sheetData.sheet_name);
        var readFile = readFileSh(sheetData);
        var listObjective = new Array();
        if(readFile.objective != ""){
            listObjective = readFile.objective.split("\n");
        }
        $scope.objective = listObjective;

        var listTheory = new Array();
        if(readFile.theory != ""){
            listTheory = readFile.theory.split("\n");
        }
        $scope.theory = listTheory;

        var sheetTrial = readFile.trial;
        $('#sheet_trial').Editor('setText', decapeHtml(sheetTrial));
        $('.Editor-editor').attr('contenteditable', false);
        $('[id^=menuBarDiv]').hide();
        $('[id^=statusbar]').hide();

        //ค้นหาข้อมูลใบงานที่เก่าที่ส่ง
        var resSheet = findOldCodeInResSheet($scope.thisSheet.sheeting_id,$scope.thisSheet.sheet_id,user.id);
        $scope.resSheetID = resSheet.resSheetID;
        var code = "";
        (resSheet.code).forEach(function(codes) {
            code+=codes;
        });
        $('#old_code').html(escapeHtml(code));
        $('mycode').each(function(i, block) {
            hljs.highlightBlock(block);
        });

        $scope.resQuiz = findResQuizByRSID($scope.resSheetID);
        ($scope.resQuiz).forEach(function(res) {
            $('#quizAns_'+res.quiz_id).val(res.quiz_ans);
        });
        $scope.$apply();
        $('#detail_sheet_part').waitMe('hide');

    });
    //----------------------------------------------------------------------
    $('#detail_sheet_modal').on('hidden.bs.modal', function(){
        checked = 0;
        if(send){
            checkOrderSh($scope.resSheetID);
        }
    });
    //----------------------------------------------------------------------
    $scope.okSend = function () {
        checked = 0;
        var result = "";
        check_intime = checkSendLate();
        if(check_intime.inTime){
            if($scope.inputMode === 'key_input'){
                if($scope.codeSheet.length > 0){
                    data = {
                        STID : $scope.sheeting.id,
                        SID : $scope.sheetID,
                        UID : user.id,
                        code : $scope.codeSheet,
                        mode : "key",
                        send_date_time : dtJsToDtDB(new Date()),
                        send_late : check_intime.late
                    };
                    // ถ้าเป็นไฟล์ .c
                    if($scope.selectFileType === "c"){
                        sendSheetC(data);
                    }

                    // ถ้าเป็นไฟล์ .cpp
                    else if($scope.selectFileType === "cpp"){
                        sendSheetCpp(data);
                    }

                    // ถ้าเป็นไฟล์ .java
                    else if($scope.selectFileType === "java"){
                        sendSheetJava(data);
                    }

                    // ถ้าเป็นไฟล์ .cs
                    else if($scope.selectFileType === "cs"){
                        sendSheetCs(data);
                    }
                    sendQuiz();
                } else {
                    if($scope.thisStatus != 'a'){
                        $('#notice_sheet_key_ans').html('* กรุณาใส่โค้ดโปรแกรม').show();
                    } else {
                        sendQuiz();
                        $('#detail_sheet_modal').modal('hide');
                    }
                }
            } else {
                if($("#file_ans")[0].files.length > 0){
                    checkFile = checkFileType($("#file_ans")[0].files);
                    if(checkFile){
                        $window.sheetID = $scope.sheetID;
                        $('#AnsFileForm').submit();

                        data = {
                            path : $window.sheet_path,
                            STID : $scope.sheeting.id,
                            SID : $scope.sheetID,
                            UID : user.id,
                            mode : "file",
                            send_date_time : dtJsToDtDB(new Date()),
                            send_late : check_intime.late
                        };

                        // ถ้าเป็นไฟล์ .c
                        if($scope.selectFileType === "c"){
                            if(($("#file_ans")[0].files).length > 1){
                                $('#detail_sheet_part').waitMe('hide');
                                $('#err_message').html('ไม่อนุญาตให้ส่งไฟล์ .c มากกว่า 1 ไฟล์');
                                $('#fail_modal').modal('show');
                            } else {
                                sendSheetC(data);
                            }
                        }

                        // ถ้าเป็นไฟล์ .cpp
                        else if($scope.selectFileType === "cpp"){
                            sendSheetCpp(data);
                        }

                        // ถ้าเป็นไฟล์ .java
                        else if($scope.selectFileType === "java"){
                            sendSheetJava(data);
                        }

                        // ถ้าเป็นไฟล์ .cs
                        else if($scope.selectFileType === "cs"){
                            sendSheetCs(data);
                        }
                        sendQuiz();
                    } else {
                        $('#detail_sheet_part').waitMe('hide');
                        $('#err_message').html('ประเภทของไฟล์ที่คุณส่ง ไม่ตรงกับประเภทไฟล์ที่ระบุ');
                        $('#fail_modal').modal('show');
                    }
                } else {
                    $('#notice_sheet_file_ans').html('* กรุณาเลือกไฟล์').show();
                }
            }
        } else {
            $('#detail_sheet_modal').modal('hide');
            $('#err_message').html('ไม่สามารถส่งใบงานได้ เนื่องจากหมดเวลาทำใบงาน');
            $('#fail_modal').modal('show');
        }

    }
    //----------------------------------------------------------------------
    function checkSendLate() {
        var sendLate =
            {
                inTime : true,
                late : 0
            };
        now = new Date(dtDBToDtJs(getDateNow()));
        // endTime = dtPickerToDtJS($scope.sheeting.end_date_time);
        endTime = dtDBToDtJs($scope.sheeting.end_date_time);
        endTime = new Date(endTime);
        // endTime = new Date(endTime.valueOf()+ endTime.getTimezoneOffset() * 60000);

        if(now > endTime){
            if($scope.sheeting.send_late === "0"){
                sendLate =
                    {
                        inTime : false,
                        late : 0
                    };
            } else {
                sendLate =
                    {
                        inTime : true,
                        late : 1
                    };
            }
        }
        return sendLate;
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
                if(ext.toLowerCase() === "h" && $scope.selectFileType === "cpp"){
                    return true;
                } else {
                    return false;
                }
            }
        }
        return true;
    }
    //----------------------------------------------------------------------
    function sendSheetC(data) {
        var sendSheetC = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'c-send-sheet',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        $scope.resSheetID = xhr.responseJSON;
                        $('#detail_sheet_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_sheet_modal').modal('hide');
                        $('#err_message').html('ไม่สามารถส่งโปรแกรมที่มีไลบารี่ conio.h ได้');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_sheet_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
        return sendSheetC;
    }
    //----------------------------------------------------------------------
    function sendSheetCpp(data) {
        var sendSheetCpp = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cpp-send-sheet',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        $scope.resSheetID = xhr.responseJSON;
                        $('#detail_sheet_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_sheet_modal').modal('hide');
                        $('#err_message').html('ไม่สามารถส่งโปรแกรมที่มีไลบารี่ conio.h ได้');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_sheet_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
        return sendSheetCpp;
    }
    //----------------------------------------------------------------------
    function sendSheetJava(data) {
        var sendSheetJava = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'java-send-sheet',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        $scope.resSheetID = xhr.responseJSON;
                        $('#detail_sheet_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_sheet_modal').modal('hide');
                        $('#err_message').html('โค้ดที่ส่งไม่ใช่ Default package กรุณาแก้ไข package ของโค้ด');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_sheet_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function sendSheetCs(data) {
        var sendSheetCs = $.ajax({
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cs-send-sheet',
            data:JSON.stringify(data),
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        send = true;
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = 'Q';
                        // $scope.$apply();
                        $scope.resSheetID = xhr.responseJSON;
                        $('#detail_sheet_modal').modal('hide');
                    } else if (xhr.status == 209) {
                        $('#detail_sheet_modal').modal('hide');
                        $('#err_message').html('โค้ดที่ส่งห้ามมี namespace');
                        $('#fail_modal').modal('show');
                    } else {
                        $('#detail_sheet_modal').modal('hide');
                        $('#unsuccess_modal').modal({backdrop: 'static'});
                    }
                }
            }
        }).responseJSON;
        return sendSheetCs;
    }
    //----------------------------------------------------------------------
    function checkOrderSh(pathSheetID) {
        $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'sheeting-check-queue-sheet',
            data:{pathSheetID:pathSheetID},
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    // ถ้าตัวเองคือคนแรก
                    if (xhr.status == 200) {
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = 'P';
                        $scope.$apply();
                        checked = 1;
                        var fileType = xhr.responseJSON;
                        if(fileType === "c"){
                            compileAndRunC(pathSheetID);
                        }
                        else if(fileType === "cpp"){
                            compileAndRunCpp(pathSheetID);
                        }
                        else if(fileType === "java"){
                            compileAndRunJava(pathSheetID);
                        }
                        else if(fileType === "cs"){
                            compileAndRunCs(pathSheetID);
                        }
                    } else { // ถ้าไม่ใช่คนแรก
                        // รอตรวจนานเกิน 6 วินาที
                        if (count > 5) {
                            deleteFirstQueue();
                            checkOrderSh(pathSheetID);
                            count = 0;
                        }
                    }
                    count++;
                    if (!checked) {
                        setTimeout(function () {
                            checkOrderSh(pathSheetID);
                        }, 1000);
                    }
                }
            }
        });
    }
    //----------------------------------------------------------------------
    function compileAndRunC(pathSheetID) {
        var testCompile = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'c-compile',
            data:{
                mode:"sheet",
                pathSheetID:pathSheetID,
                sheet_id : $scope.sheetID
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();
                        deleteMyQueue(pathSheetID);
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function compileAndRunCpp(pathSheetID) {
        var testCompile = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cpp-compile',
            data:{
                mode:"sheet",
                pathSheetID:pathSheetID,
                sheet_id : $scope.sheetID
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();
                        deleteMyQueue(pathSheetID);
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function compileAndRunJava(pathSheetID) {
        var testCompile = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'java-compile',
            data:{
                mode:"sheet",
                pathSheetID:pathSheetID,
                sheet_id : $scope.sheetID
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();
                        deleteMyQueue(pathSheetID);
                    }
                }
            }
        }).responseJSON;
    }
    //----------------------------------------------------------------------
    function compileAndRunCs(pathSheetID) {
        var pathBat = $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'cs-create-bat',
            data:{
                mode:"sheet",
                pathSheetID:pathSheetID,
                sheet_id : $scope.sheetID
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
                mode:"sheet",
                pathSheetID:pathSheetID,
                sheet_id : $scope.sheetID,
                pathBat : pathBat
            },
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        $scope.sheetSheeting[$scope.CurrentIndex].current_status = xhr.responseJSON;
                        $scope.$apply();
                        deleteMyQueue(pathSheetID);
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
            url: url + 'sheeting-delete-queue-sheet',
            async: false,
        })
    }
    //----------------------------------------------------------------------
    function deleteMyQueue(RSID) {
        $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'sheeting-delete-my-queue',
            data:{ res_sheet_id : RSID},
            async: false,
        })
    }
    //----------------------------------------------------------------------
    function sendQuiz() {
        $('[id^=quizAns_]').each(function() {
            var eid = $(this)[0].id;
            var Qid = eid.split('_')[1];
            var testCompile = $.ajax({
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                headers: {
                    Accept: "application/json"
                },
                url: url + 'sheeting-send-quiz',
                data:{
                    quiz_id: Qid,
                    res_sheet_id:$scope.resSheetID,
                    quiz_ans : $(this).val()
                },
                async: false,
            }).responseJSON;
        });
    }
}]);