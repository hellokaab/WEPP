app.controller('groupCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-group-my",dtJsToDtDB(new Date()));
    $scope.myGroup = findMyGroup($window.user.id)

    $scope.selectRow = '10';
    $scope.sortGN = 'group_name';

    //----------------------------------------------------------------------
    $scope.inGroup = function (data) {
        window.location.href = url+"teacher-group-my-in-"+data.id;
    };
    //----------------------------------------------------------------------
    $scope.addGroup = function () {
        $scope.groupName = '';
        $scope.passwordGroup = '';
        $('#notice_name_add_grp').hide();
        $('#notice_pass_add_grp').hide();
        $('#add_group_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=groupName]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.enterAdd = function() {
        $('[ng-model=passwordGroup]').focus();
    };

    $scope.enterOkAdd = function() {
        $scope.okAddGroup();
    };
    //----------------------------------------------------------------------
    $scope.okAddGroup = function () {
        $('#notice_name_add_grp').hide();
        $('#notice_pass_add_grp').hide();

        if ($scope.groupName.length > 0 && $scope.passwordGroup.length > 3) {
            var data = {
                user_id : $window.user.id,
                group_name: $scope.groupName,
                group_password : $scope.passwordGroup
            }

            $('#add_group_part').waitMe({
                effect: 'win8_linear',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });
            setTimeout(function () {
                addGroup(data);
            }, 100);

        } else if ($scope.groupName.length === 0) {
            $('#notice_name_add_grp').html('* กรุณาระบุชื่อกลุ่มเรียน').show();
            $('[ng-model=groupName]').focus();
        } else if ($scope.passwordGroup.length < 4) {
            $('#notice_pass_add_grp').html('* ต้องมีความยาวอย่างน้อย 4 ตัวอักษร').show();
            $('[ng-model=passwordGroup]').focus();
        }
    }
    //----------------------------------------------------------------------
    $scope.enterEdit = function() {
        $('[ng-model=passwordGroup]').focus();
    };
    $scope.enterOkEdit = function() {
        $scope.okEditGroup();
    };
    //----------------------------------------------------------------------
    $scope.editGroup = function (data) {
        $scope.groupName = data.group_name;
        $scope.passwordEditGroup = data.group_password;
        $scope.groupId = data.id;

        $('#notice_name_edit_grp').hide();
        $('#notice_pass_edit_grp').hide();
        $('#edit_group_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=groupName]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.okEditGroup = function () {
        $('#notice_name_edit_grp').hide();
        $('#notice_pass_edit_grp').hide();

        if ($scope.groupName.length > 0 && $scope.passwordEditGroup.length > 3) {
            data = {
                id      : $scope.groupId,
                group_name: $scope.groupName,
                group_password : $scope.passwordEditGroup,
                user_id   :$scope.user.id
            }

            $('#edit_group_part').waitMe({
                effect: 'win8_linear',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });
            setTimeout(function () {
                editGroup(data);
            }, 100)
        } else if ($scope.groupName.length === 0) {
            $('#notice_name_edit_grp').html('* กรุณาระบุชื่อกลุ่มเรียน').show();
            $('[ng-model=groupName]').focus();
        } else if ($scope.passwordEditGroup.length < 4) {
            $('#notice_pass_edit_grp').html('* ต้องมีความยาวอย่างน้อย 4 ตัวอักษร').show();
            $('[ng-model=passwordGroup]').focus();
        }
    }
    //----------------------------------------------------------------------
    $scope.deleteGroup = function (data) {
        $scope.groupNameDelete = data.group_name;
        $scope.groupId = data.id;
        $('#delete_group_modal').modal({backdrop: 'static'});
    }
    //----------------------------------------------------------------------
    $scope.okDeleteGroup = function () {
        $('#delete_group_part').waitMe({
            effect: 'win8_linear',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        setTimeout(function () {
            deleteGroup($scope.groupId);
        }, 100)
    }
    //----------------------------------------------------------------------
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        if($scope.sortKey === 'group_name'){
            $scope.reverseGN = !$scope.reverseGN; //if true make it false and vice versa
            if($scope.sortGN === 'group_name'){
                $scope.sortGN = '-group_name';
            } else {
                $scope.sortGN = 'group_name';
            }
        }
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        location.reload();
    });
}]);