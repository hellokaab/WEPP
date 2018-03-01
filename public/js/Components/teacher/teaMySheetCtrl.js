app.controller('teaMySheetCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    $scope.sheetGroup = findMySheetGroup($scope.user.id);

    $scope.groupID = 0;
    $scope.selectRow = '10';
    $scope.sortS = 'sheet_group_name';
    $scope.sheets = "";
    $('#sheet_trial').Editor();
    //----------------------------------------------------------------------
    $scope.addSheetGroup = function () {
        $scope.sheetGroupName = '';
        $('#notice_add_sheet_grp').hide();
        $('#add_sheet_group_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=sheetGroupName]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.enterAdd = function() {
        $scope.okAddSheetGroup();
    };
    //----------------------------------------------------------------------
    $scope.okAddSheetGroup = function () {
        if ($scope.sheetGroupName.length > 0) {
            $('#add_sheet_group_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });
            var data = {
                user_id : $scope.user.id,
                sheet_group_name: $scope.sheetGroupName
            };
            createSheetGroup(data);
        } else {
            $('#notice_add_sheet_grp').html('* กรุณาระบุชื่อกลุ่มใบงาน').show();
        }
    }
    //----------------------------------------------------------------------
    $scope.selectGroup = function (data) {
        $('#div_sheet_list').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $('#div_sheet_list').show();
        $('[ng-model=panelGroupName]').html(data.sheet_group_name);
        $scope.groupID = data.id;
        setTimeout(function () {
            $scope.sheets = findSheetByEGID(data.id);
            $scope.$apply();
            $('#div_sheet_list').waitMe('hide');
            window.location.href = "#div_sheet_list";
        }, 100);

    };
    //----------------------------------------------------------------------
    $scope.editSheetGroup = function (data) {
        $scope.CurrentIndex = $scope.sheetGroup.indexOf(data);
        $scope.sheetGroupName = data.sheet_group_name;
        $scope.groupID = data.id;
        $('#notice_edit_sheet_grp').hide();
        $('#edit_sheet_group_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=sheetGroupName]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.okEditSheetGroup = function () {
        if ($scope.sheetGroupName.length > 0) {
            var data = {
                id : $scope.groupID,
                sheet_group_name: $scope.sheetGroupName,
                user_id : $scope.user.id
            };
            $('#edit_sheet_group_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });
            editSheetGroup(data);
        } else {
            $('#notice_edit_sheet_grp').html('* กรุณาระบุชื่อกลุ่มใบงาน').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.deleteSheetGroup = function (data) {
        $scope.sheetGroupName = data.sheet_group_name;
        $scope.groupID = data.id;
        $('#delete_sheet_group_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDeleteSheetGroup = function () {
        var data = {
            id : $scope.groupID
        };
        $('#delete_sheet_group_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deleteSheetGroup(data);
    };
    //----------------------------------------------------------------------
    $scope.addSheet = function () {
        window.location.href = url+"teacher-sheet-add-"+$scope.groupID;
    };
    //----------------------------------------------------------------------
    $scope.detailSheet = function (data) {
        $scope.sheetID = data.id;
        $scope.sheetId = data.id;
        $('#sheetName').html(data.sheet_name);
        $('#fullScore').html(data.full_score);
        $('#notation').html(data.notation);
        $scope.quizzes = findQuizBySID(data.id);
        $('#detail_sheet_modal').modal({backdrop: 'static'});

        $('#objective_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $('#theory_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $('.Editor-editor').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $('#input_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $('#output_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        var fileDataSh = readFileSh(data);
        $('#objective').html(fileDataSh.objective);
        $('#theory').html(fileDataSh.theory);
        $('#sheet_trial').Editor('setText', decapeHtml(fileDataSh.trial));
        $('.Editor-editor').attr('contenteditable', false);
        $('[id^=menuBarDiv]').hide();
        $('[id^=statusbar]').hide();
        $('#sheetInput').html(fileDataSh.input);
        $('#sheetOutput').html(fileDataSh.output);

        $('#objective_part').waitMe('hide');
        $('#theory_part').waitMe('hide');
        $('.Editor-editor').waitMe('hide');
        $('#input_part').waitMe('hide');
        $('#output_part').waitMe('hide');
    }
    //----------------------------------------------------------------------
        $scope.editSheet = function () {
        window.location.href = url+"teacher-sheet-edit-"+$scope.sheetID;
    };
    //----------------------------------------------------------------------
    $scope.gotoEditSheet = function (data) {
        window.location.href = url+"teacher-sheet-edit-"+data.id;
    };
    //----------------------------------------------------------------------
    $scope.deleteSheet = function (data) {
        $scope.deleteName = data.sheet_name;
        $scope.sheetID = data.id;
        $('#delete_sheet_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDelete = function () {
        $('#edit_sheet_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deleteSheet($scope.sheetID);
    };
    //----------------------------------------------------------------------
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        if($scope.sortKey === 'sheet_group_name'){
            $scope.reverseS = !$scope.reverseS; //if true make it false and vice versa
            if($scope.sortS === 'sheet_group_name'){
                $scope.sortS = '-sheet_group_name';
            } else {
                $scope.sortS = 'sheet_group_name';
            }
        }
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        location.reload();
    });
}]);