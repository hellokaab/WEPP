app.controller('webHistoryCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.history = "";
    $scope.selectRow = '10';

    $scope.completeFind = false;
    //----------------------------------------------------------------------
    $scope.findHistory = function () {
        $('#notice_history_end').hide();
        $('#notice_history_begin').hide();

        completeHistoryBegin = $('#historyBegin').val().length > 0;
        completeHistoryEnd = $('#historyEnd').val().length > 0;
        if(completeHistoryBegin && completeHistoryEnd){
            $scope.completeFind = true;
            dateBegin = new Date(dtPickerToDtJs($('#historyBegin').val()));
            dateEnd = new Date(dtPickerToDtJs($('#historyEnd').val()));
            $scope.history = findWebHistoryRange(dtJsToDtDB(dateBegin),dtJsToDtDB(dateEnd));
        } else {
            if(!completeHistoryEnd){
                $('#notice_history_end').html('* กรุณาระบุช่วงเวลาที่ต้องการค้นหาให้ครบ').show();
            }
            if(!completeHistoryBegin){
                $('#notice_history_begin').html('* กรุณาระบุช่วงเวลาที่ต้องการค้นหาให้ครบ').show();
            }
        }
    };
    //----------------------------------------------------------------------
    $scope.viewProfile = function (data) {
        $('#profile_modal').modal({backdrop: 'static'});
        $('#profile_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        setTimeout(function () {
            $scope.prefix = data.prefix;
            $scope.fname = data.fname_th;
            $scope.lname = data.lname_th;
            $scope.stuID = data.stu_id;
            $scope.faculty = data.faculty;
            $scope.department = data.department;
            $scope.userType = data.user_type;
            $scope.$apply();
            $('#profile_part').waitMe('hide');
            // window.location.href = "#div_exam_list";
        }, 100);
    };
}]);
