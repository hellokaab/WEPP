app.controller('stdListCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.students = findAllStudent();

    $scope.selectRow = '10';
    //----------------------------------------------------------------------
    $scope.deleteStudent = function (data) {
        checkStudentWillBeDelete(data.id);
        $scope.studentName = data.prefix+data.fname_th+" "+data.lname_th;
        $scope.userID = data.id;
        $('#delete_student_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDelete = function () {
        $('#delete_student_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deleteStudent($scope.userID);
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        location.reload();
    });
    //----------------------------------------------------------------------
    function checkStudentWillBeDelete(UID) {
        var willDelete = studentWillBeDelete(UID);
        var delete_msg = "";
        if(willDelete.re || willDelete.rs){
            delete_msg += "(ข้อมูล ";
            if(willDelete.re){
                delete_msg += "ข้อสอบที่ส่ง,";
            }
            if(willDelete.rs){
                delete_msg += "ใบงานที่ส่ง,";
            }
            delete_msg = delete_msg.substring(0,delete_msg.length-1)+" ของผู้ใช้นี้จะถูกลบไปด้วย)";
            $("#delete_msg").html(delete_msg);
        }
    }
}]);