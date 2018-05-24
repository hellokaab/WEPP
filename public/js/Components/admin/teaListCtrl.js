app.controller('teaListCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.teachers = findAllTeacher();

    $scope.userID = 0;
    $scope.selectRow = '10';
    //----------------------------------------------------------------------
    $scope.deleteTeacher = function (data) {
        checkTeacherWillBeDelete(data.id);
        $scope.teacherName = data.prefix+data.fname_th+" "+data.lname_th;
        $scope.userID = data.id;
        $('#delete_teacher_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDelete = function () {
        $('#delete_teacher_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deleteTeacher($scope.userID);
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        location.reload();
    });
    //----------------------------------------------------------------------
    function checkTeacherWillBeDelete(UID) {
        var willDelete = teacherWillBeDelete(UID);
        var delete_msg = "";
        if(willDelete.ex || willDelete.sh || willDelete.em || willDelete.st){
            delete_msg += "(ข้อมูล ";
            if(willDelete.ex){
                delete_msg += "ข้อสอบ,";
            }
            if(willDelete.sh){
                delete_msg += "ใบงาน,";
            }
            if(willDelete.ex){
                delete_msg += "การเปิดสอบ,";
            }
            if(willDelete.sh){
                delete_msg += "การสั่งงาน,";
            }
            delete_msg = delete_msg.substring(0,delete_msg.length-1)+" ของผู้ใช้นี้จะถูกลบไปด้วย)";
            $("#delete_msg").html(delete_msg);
        }else {
            $("#delete_msg").html(delete_msg);
        }
    }
}]);