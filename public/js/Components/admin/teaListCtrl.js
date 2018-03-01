app.controller('teaListCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.teachers = findAllTeacher();

    $scope.userID = 0;
    $scope.selectRow = '10';
    //----------------------------------------------------------------------
    $scope.deleteTeacher = function (data) {
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
}]);