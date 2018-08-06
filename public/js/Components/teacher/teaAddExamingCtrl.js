app.controller('teaAddExamingCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-examing-add",dtJsToDtDB(new Date()));
    $scope.myGroups = findMyGroup($scope.user.id);
    $scope.examGroup = findExamGroupSharedToMe($scope.user.id);
    $scope.exams = findAllExamSharedToMe($scope.user.id);

    // Set Default
    $scope.openExamName = '';
    $scope.description = '';
    $scope.userGroupID = '0';
    $scope.amountExam = '0';
    $scope.examingMode = 'n';
    $scope.examingPassword = '';
    $scope.ipMode = '0';
    $scope.gatewayIp = '';
    $scope.allowNetwork = '';
    $scope.hiddenMode = '0';
    $scope.historyMode = '0';

    $scope.selectExam = [];
    $scope.randomExam = [];
    var allowed_file_type = "";

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
    $scope.openExam = function () {
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
            completeNoDuplicate = findExamingByNameAndGroup($scope.openExamName,$scope.userGroupID);
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

            $('#add_examing_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            data = {
                user_id: $scope.user.id,
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
                hide_examing : $scope.hiddenMode,
                hide_history : $scope.historyMode,
            }
            createExaming(data);

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
    };
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