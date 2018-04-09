app.controller('psnListCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.personnel = findAllPersonnel();

    $scope.userID = 0;
    $scope.selectRow = '10';
    //----------------------------------------------------------------------
    $scope.deletePersonnel = function (data) {
        $scope.personnelName = data.prefix+data.fname_th+" "+data.lname_th;
        $scope.userID = data.id;
        $('#delete_personnel_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDelete = function () {
        $('#delete_personnel_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deletePersonnel($scope.userID);
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        location.reload();
    });
}]);