app.controller('teaShareSheetCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;

    $scope.sheetGroupSharedToMe = findSheetGroupSharedNotMe($scope.user.id);
    $scope.sheetGroupID = 0;
    $scope.selectRow = '10';
    $scope.sortS = 'sheet_group_name';
    $scope.sortC = 'creater';
    $scope.sheets = "";
    $('#sheet_trial').Editor();
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
            $scope.sheets = findSheetSharedToMe($scope.user.id,data.id);
            $scope.$apply();
            $('#div_sheet_list').waitMe('hide');
            window.location.href = "#div_sheet_list";
        }, 100);
    };
    //----------------------------------------------------------------------
    $scope.detailSheet = function (data) {
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
        } else {
            $scope.reverseC = !$scope.reverseC; //if true make it false and vice versa
            if($scope.sortC === 'creater'){
                $scope.sortC = '-creater';
            } else {
                $scope.sortC = 'creater';
            }
        }
    };
    //----------------------------------------------------------------------
    $scope.copySheet = function (data) {
        window.location.href = url+"teacher-sheet-copy-"+data.id;
    };
}]);