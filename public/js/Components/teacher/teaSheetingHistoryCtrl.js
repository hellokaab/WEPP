app.controller('teaSheetingHistoryCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-sheeting-history",dtJsToDtDB(new Date()));
    $scope.groups = findMyGroup($scope.user.id);
    // $scope.sheeting = findSheetingByUserID($scope.user.id);
    $scope.sheeting = "";

    //console.log($scope.sheeting);

    // Set Default
    $scope.groupID = "0";
    $scope.selectRow = "10";
    //----------------------------------------------------------------------
    $scope.editSheeting = function (data) {
        window.location.href = url+"teacher-sheeting-edit-"+data.id;
    };
    //----------------------------------------------------------------------
    $scope.deleteSheeting = function (data) {
        $scope.sheetingName = data.sheeting_name;
        $scope.sheetingID = data.id;
        $('#delete_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDeleteSheeting = function () {
        $('#delete_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deleteSheeting($scope.sheetingID);
    };
    //----------------------------------------------------------------------
    $scope.viewPoint = function (data) {
        window.open(url+'teacher-board-sheet-'+data.id, '_blank');
        window.focus();
    }
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        window.location.href = url+'teacher-examing-history';
    });
    //----------------------------------------------------------------------
    $scope.groupChange = function () {
        $scope.sheeting = findSheetingByUserIDAndGroup($scope.user.id,$scope.groupID);
    }
    //----------------------------------------------------------------------
    $scope.exportSheetScore = function () {
        window.open("js/Components/ExportSheetScore.php?group_id=" + $scope.groupID);
    }

}]);