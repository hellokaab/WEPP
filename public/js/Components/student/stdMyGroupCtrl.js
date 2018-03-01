app.controller('stdMyGroupCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    // keepHistory($window.user.id,"student-group-my",dtJsToDtDB(new Date()));

    $scope.myJoinGroup = findMyJoinGroup($scope.user.id);

    $scope.selectRow = '10';
    $scope.groupID = 0;
    $scope.sortG = 'group_name';
    //----------------------------------------------------------------------
    $scope.exitGroup = function (group) {
        $scope.groupID = group.group_id;
        $scope.groupName = group.group_name;
        $('#exit_group_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okExit = function () {
        $('#exit_group_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        setTimeout(function () {
            exitGroup($scope.user.id,$scope.groupID,user.user_type);
        },100);
    }
    //----------------------------------------------------------------------
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        if($scope.sortKey === 'group_name'){
            $scope.reverseG = !$scope.reverseG; //if true make it false and vice versa
            if($scope.sortG === 'group_name'){
                $scope.sortG = '-group_name';
            } else {
                $scope.sortG = 'group_name';
            }
        } else {
            $scope.reverseC = !$scope.reverseC; //if true make it false and vice versa
            if($scope.sortC === 'creater'){
                $scope.sortC = '-creater';
            } else {
                $scope.sortC = 'creater';
            }
        }
    };
}]);