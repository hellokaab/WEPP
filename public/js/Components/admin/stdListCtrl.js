app.controller('stdListCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.students = findAllStudent();

    $scope.selectRow = '10';
    //----------------------------------------------------------------------
    $scope.deleteStudent = function (data) {
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
}]);