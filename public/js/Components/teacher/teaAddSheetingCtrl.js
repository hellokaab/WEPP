app.controller('teaAddSheetingCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-sheeting-add",dtJsToDtDB(new Date()));

    $scope.myGroups = findMyGroup($scope.user.id);
    $scope.sheetGroups = findSheetGroupSharedToMe($scope.user.id);
    $scope.sheets = findAllSheetSharedToMe($scope.user.id);

    // Set Default
    $scope.openSheetName = '';
    $scope.userGroupID = '0';
    $scope.sendLateMode = '0';
    $scope.hiddenMode = '0';

    $scope.selectSheet = [];
    var allowed_file_type = "";
    //----------------------------------------------------------------------
    $scope.ticSheet = function () {
        $scope.selectSheet = [];
        $('[id^=sheet_]').each(function () {
            if ($(this).prop('checked')) {
                $scope.selectSheet.push($(this).attr('id').substr(6));
            }
        });
    };
    //----------------------------------------------------------------------
    $scope.ticAllInSg = function (SID) {
        $scope.sheets.forEach(function(sheet) {
            if(sheet.sheet_group_id === SID){
                if($('#sg_'+SID)[0].checked){
                    $('#sheet_'+sheet.id)[0].checked = true;
                } else {
                    $('#sheet_'+sheet.id)[0].checked = false;
                }
            }
        });
        $scope.ticSheet();
    };
    //----------------------------------------------------------------------
    $scope.viewSheet = function (sheetGroupID) {
        if ($('#group_' + sheetGroupID).hasClass('fa-plus-square')) {
            $('#group_' + sheetGroupID).removeClass('fa-plus-square');
            $('#group_' + sheetGroupID).addClass('fa-minus-square');
            $('#group_' + sheetGroupID).parent().parent().children('div').show();
        } else {
            $('#group_' + sheetGroupID).removeClass('fa-minus-square');
            $('#group_' + sheetGroupID).addClass('fa-plus-square');
            $('#group_' + sheetGroupID).parent().parent().children('div').hide();
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
    $scope.openSheet = function () {
        $('#notice_sheeting_name').hide();
        $('#notice_sheeting_usr_grp').hide();
        $('#notice_sheeting_begin').hide();
        $('#notice_sheeting_end').hide();
        $('#notice_file_type').hide();

        allowed_file_type = getFileType();

        completeSheetName = $scope.openSheetName.length > 0;
        completeUserGroup = $scope.userGroupID > 0;
        completeNoDuplicate = true;
        if(completeSheetName){
            completeNoDuplicate = findSheetingByNameAndGroup($scope.openSheetName,$scope.userGroupID);
        }
        completeSheet = $scope.selectSheet.length > 0;
        completeSheetingBegin = $('#sheetingBegin').val().length > 0;
        completeSheetingEnd = $('#sheetingEnd').val().length > 0;
        completeFileType = allowed_file_type.length > 0;

        if(completeSheetName
            && completeUserGroup
            && completeNoDuplicate
            && completeSheet
            && completeSheetingBegin
            && completeSheetingEnd
            && completeFileType){

            dateBegin = new Date(dtPickerToDtJs($('#sheetingBegin').val()));
            dateEnd = new Date(dtPickerToDtJs($('#sheetingEnd').val()));

            $('#add_sheeting_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            data = {
                user_id: $scope.user.id,
                sheeting_name: $scope.openSheetName,
                group_id: $scope.userGroupID,
                sheet: $scope.selectSheet,
                start_date_time: dtJsToDtDB(dateBegin),
                end_date_time: dtJsToDtDB(dateEnd),
                allowed_file_type: allowed_file_type,
                send_late : $scope.sendLateMode,
                hide_sheeting : $scope.hiddenMode,
            };
            createSheeting(data);
        } else {
            if (!completeFileType) {
                $('#notice_file_type').html('* กรุณาระบุไฟล์ที่อนุญาตให้ส่ง').show();
            }
            if (!completeSheetingEnd) {
                $('#notice_sheeting_end').html('* กรุณาระบุเวลาสิ้นสุด').show();
            }
            if (!completeSheetingBegin) {
                $('#notice_sheeting_begin').html('* กรุณาระบุเวลาเริ่ม').show();
            }
            if (!completeSheet) {
                $('#notice_sheet').html('* กรุณาระบุใบงานที่ใช้ในการสั่งงาน').show();
                $('[ng-model=userGroupId]').focus();
            }
            if (!completeUserGroup) {
                $('#notice_sheeting_usr_grp').html('* กรุณาระบุกลุ่มเรียน').show();
                $('[ng-model=userGroupId]').focus();
            }
            if (!completeSheetName) {
                $('#notice_sheeting_name').html('* กรุณาระบุชื่อการสั่งงาน').show();
                $('[ng-model=openSheetName]').focus();
            }

            if (!completeNoDuplicate) {
                $('#notice_sheeting_name').html('* มีการสั่งงานนี้ในกลุ่มเรียนที่เลือกแล้ว').show();
                $('[ng-model=openSheetName]').focus();
            }
        }
    }
    //----------------------------------------------------------------------
    $scope.goBack = function () {
        window.history.back();
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        window.location.href = url+'teacher-sheeting-history';
    });
}]);