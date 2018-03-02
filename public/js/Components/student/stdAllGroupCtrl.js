app.controller('stdAllGroupCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    if($window.user.user_type === 't'){
        keepHistory($window.user.id,"teacher-group-all",dtJsToDtDB(new Date()));
    } else if ($window.user.user_type === 's'){
        keepHistory($window.user.id,"student-group-all",dtJsToDtDB(new Date()));
    }

    $scope.allGroup = findAllGroup();
    $scope.selectRow = '10';
    $scope.groupID = 0;
    $scope.groupPASS = "";
    $scope.sortG = 'group_name';

    //----------------------------------------------------------------------
    $scope.clickGroup = function (group) {
        if($scope.user.id === group.user_id){
            window.location.href = url+"teacher-group-my-in-"+group.id;
        } else {
            var checkJoin = checkJoinGroup($scope.user.id,group.id);
            if(checkJoin){
                if($scope.user.user_type === 't'){
                    window.location.href = url+"teacher-group-other-in-"+$scope.groupID;
                } else if ($scope.user.user_type === 's'){
                    window.location.href = url+"student-group-in-"+$scope.groupID;
                }
            } else {
                $scope.groupID = group.id;
                $scope.groupPASS = group.group_password;
                $scope.groupName = group.group_name;
                $('#join_group_modal').modal({backdrop: 'static'});
                setTimeout(function () {
                    $('[ng-model=joinPass]').focus();
                }, 200);
            }
        }
    }
    //----------------------------------------------------------------------
    $scope.okJoinGroup = function () {
        $('#notice_pass_grp').hide();
        if($scope.groupPASS === $scope.joinPass){
            $('#join_group_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            var status = "";
            $scope.user.user_type === 't' ? status = "a" : status = "s";

            setTimeout(function () {
                createJoinGroup($scope.user.id,$scope.groupID,status);
                if($scope.user.user_type === 't'){
                    window.location.href = url+"teacher-group-other-in-"+$scope.groupID;
                } else if ($scope.user.user_type === 's'){
                    window.location.href = url+"student-group-in-"+$scope.groupID;
                }
            }, 100);
        } else {
            $('#notice_pass_grp').html('* รหัสผ่านไม่ถูกต้อง').show();
            $('[ng-model=joinPass]').focus();
        }
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