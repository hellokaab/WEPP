app.controller('teaEditExamingCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-examing-edit-"+$window.examingID,dtJsToDtDB(new Date()));
    $scope.myGroups = findMyGroup($scope.user.id);
    $scope.examGroup = findExamGroupSharedToMe($scope.user.id);
    $scope.exams = findAllExamSharedToMe($scope.user.id);
    $scope.examing = findExamingByID($window.examingID);
    $scope.examExamings = $window.exam_examing;

    // Initial values of this examing
    $scope.openExamName = $scope.examing.examing_name;
    $scope.description = $scope.examing.description;
    $scope.userGroupID = $scope.examing.group_id;
    $scope.examingMode = $scope.examing.examing_mode;
    $scope.examingPassword = $scope.examing.examing_pass;
    $scope.ipMode = $scope.examing.ip_group.length > 0 ? '1' : '0';
    $scope.gatewayIp = '';
    $scope.allowNetwork = $scope.examing.ip_group;
    $scope.hiddenMode = $scope.examing.hide_examing;
    $scope.historyMode = $scope.examing.hide_history;
    $scope.fileType = $scope.examing.allowed_file_type.split(",");

    $scope.selectExam = [];
    for (i = 0; i < $scope.examExamings.length; i++)
        $scope.selectExam.push($scope.examExamings[i].exam_id);

    $scope.randomExam = [];
    for (i = 1; i <= $scope.examing.amount; i++)
        $scope.randomExam.push(i);
    $scope.amountExam = $scope.examing.amount;

    for (i = 0; i < $scope.fileType.length; i++){
        $('#file_type_'+$scope.fileType[i])[0].checked = true;
    }

    $('#examingBegin').val(dtDBToDtPicker($scope.examing.start_date_time));
    $('#examingEnd').val(dtDBToDtPicker($scope.examing.end_date_time));

    $(document).ready(function () {
        $('[ng-model=userGroupID]').val($scope.userGroupID);
        $('[ng-model=amountExam]').val($scope.amountExam);
    });

    var allowed_file_type = "";
    var deleteExamExaming = new Array();
    //----------------------------------------------------------------------
    $scope.ticExam = function () {
        $scope.selectExam = [];
        $scope.randomExam = [];
        i = 1;
        $('[id^=exam_]').each(function () {
            if ($(this).prop('checked')) {
                $scope.selectExam.push($(this).attr('id').substr(5));
                $scope.randomExam.push(i++);
            }
        });
        $scope.amountExam = '0';
        // $('[ng-model=amountExam]').val(0);
    };
    //----------------------------------------------------------------------
    $scope.ticAllInEG = function (EGID) {
        $scope.exams.forEach(function(exam) {
            if(exam.exam_group_id === EGID){
                if($('#sec_'+EGID)[0].checked){
                    $('#exam_'+exam.id)[0].checked = true;
                } else {
                    $('#exam_'+exam.id)[0].checked = false;
                }
            }
        });
        $scope.ticExam();
    };
    //----------------------------------------------------------------------
    $scope.viewExam = function (examGroupID) {
        if ($('#group_' + examGroupID).hasClass('fa-plus-square')) {
            $('#group_' + examGroupID).removeClass('fa-plus-square');
            $('#group_' + examGroupID).addClass('fa-minus-square');
            $('#group_' + examGroupID).parent().parent().children('div').show();
        } else {
            $('#group_' + examGroupID).removeClass('fa-minus-square');
            $('#group_' + examGroupID).addClass('fa-plus-square');
            $('#group_' + examGroupID).parent().parent().children('div').hide();
        }
    };
    //----------------------------------------------------------------------
    $scope.randomPassword = function () {
        $scope.examingPassword = Math.floor(Math.random() * 9000) + 1000;
    };
    //----------------------------------------------------------------------
    $scope.addNetwork = function () {
        $scope.allowNetwork = $scope.allowNetwork.length === 0 ? $scope.allowNetwork : $scope.allowNetwork + '\n';
        $scope.allowNetwork = $scope.allowNetwork + $scope.gatewayIp;
        $scope.gatewayIp = '';
    };
    //----------------------------------------------------------------------
    $scope.clearIP = function () {
        $scope.allowNetwork = '';
    };
    //----------------------------------------------------------------------
    $scope.editExaming = function () {
        $('#notice_gateway_ip').hide();
        $('#notice_examing_begin').hide();
        $('#notice_examing_end').hide();
        $('#notice_exam').hide();
        $('#notice_examing_usr_grp').hide();
        $('#notice_examing_name').hide();
        $('#notice_file_type').hide();
        $('#notice_amount').hide();

        allowed_file_type = getFileType();

        completeExamName = $scope.openExamName.length > 0;
        completeUserGroup = $scope.userGroupID > 0;
        completeNoDuplicate = true;
        if(completeExamName){
            if ($scope.openExamName != $scope.examing.examing_name){
                completeNoDuplicate = findExamingByNameAndGroup($scope.openExamName,$scope.userGroupId);
            }

        }
        completeExam = $scope.selectExam.length > 0;
        completeExamingBegin = $('#examingBegin').val().length > 0;
        completeExamingEnd = $('#examingEnd').val().length > 0;
        completeAllowNetwork = $scope.allowNetwork.length > 0;
        completeIP = $scope.ipMode === '0' ? true : (completeAllowNetwork ? true : false);
        completeFileType = allowed_file_type.length > 0;
        completeAmount = true;
        if($scope.examingMode === 'r'){
            completeAmount = $scope.amountExam > 0 ? true : false
        }

        if (completeExamName && completeUserGroup && completeExam && completeExamingBegin && completeExamingEnd && completeIP && completeNoDuplicate && completeFileType && completeAmount) {
            dateBegin = new Date(dtPickerToDtJs($('#examingBegin').val()));
            dateEnd = new Date(dtPickerToDtJs($('#examingEnd').val()));

            $('#edit_examing_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            for(i=0;i<$scope.examExamings.length;i++){
                var EID = $scope.examExamings[i].exam_id;
                var indexOfStevie = $scope.selectExam.indexOf(EID);
                if(indexOfStevie == -1){
                    deleteExamExaming.push(EID)
                }
            }

            data = {
                id: $scope.examing.id,
                examing_name: $scope.openExamName,
                description: $scope.description,
                group_id: $scope.userGroupID,
                exam: $scope.selectExam,
                examing_mode: $scope.examingMode,
                amount: $scope.examingMode === 'n' ? $scope.selectExam.length : $scope.amountExam,
                start_date_time: dtJsToDtDB(dateBegin),
                end_date_time: dtJsToDtDB(dateEnd),
                examing_pass: $scope.examingPassword,
                ip_group: $scope.allowNetwork,
                allowed_file_type: allowed_file_type,
                deleteExamExaming:deleteExamExaming,
                hide_examing : $scope.hiddenMode,
                hide_history : $scope.historyMode,
            }
            updateExaming(data);

        } else {
            if (!completeIP) {
                $('#notice_gateway_ip').html('* กรุณาระบุเครือข่ายที่อนุณาตให้ทำข้อสอบ').show();
                $('[ng-model=gatewayIp]').focus();
            }
            if (!completeFileType) {
                $('#notice_file_type').html('* กรุณาระบุไฟล์ที่อนุญาตให้ส่ง').show();
            }
            if (!completeExamingEnd) {
                $('#notice_examing_end').html('* กรุณาระบุเวลาสิ้นสุดการสอบ').show();
            }
            if (!completeExamingBegin) {
                $('#notice_examing_begin').html('* กรุณาระบุเวลาเริ่มสอบ').show();
            }
            if (!completeAmount) {
                $('#notice_amount').html('* กรุณาระบุจำนวนข้อสอบที่ต้องการสุ่ม').show();
                $('[ng-model=amountExam]').focus();
            }
            if (!completeExam) {
                $('#notice_exam').html('* กรุณาระบุข้อสอบที่ใช้ในการสอบ').show();
                $('[ng-model=userGroupID]').focus();
            }
            if (!completeUserGroup) {
                $('#notice_examing_usr_grp').html('* กรุณาระบุกลุ่มเรียน').show();
                $('[ng-model=userGroupID]').focus();
            }
            if (!completeExamName) {
                $('#notice_examing_name').html('* กรุณาระบุชื่อการสอบ').show();
                $('[ng-model=openExamName]').focus();
            }

            if (!completeNoDuplicate) {
                $('#notice_examing_name').html('* มีการสอบนี้ในกลุ่มเรียนที่เลือกแล้ว').show();
                $('[ng-model=openExamName]').focus();
            }
        }
    }
    //----------------------------------------------------------------------
    function getFileType() {
        var array_file_type = new Array();
        $('[id^=file_type_]').each(function () {
            if ($(this).prop('checked')) {
                array_file_type.push($(this).attr('value'));
            }
        });
        var file_type = "";
        for(var i=0;i<array_file_type.length;i++){
            file_type += array_file_type[i];
            if(i != array_file_type.length-1){
                file_type += ",";
            }
        }
        return file_type;
    }
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        window.location.href = url+'teacher-examing-history';
    });
    //----------------------------------------------------------------------
    $scope.goBack = function () {
        window.history.back();
    };
}]);