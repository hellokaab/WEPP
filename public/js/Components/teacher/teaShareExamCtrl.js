app.controller('teaShareExamCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-exam-share",dtJsToDtDB(new Date()));
    $scope.examGroupSharedToMe = findExamGroupSharedNotMe($scope.user.id);
    $scope.groupID = 0;
    $scope.selectRow = '10';
    $scope.sortE = 'exam_group_name';
    $scope.sortC = 'creater';
    $scope.exams = "";
    $('#exam_content').Editor();

    //----------------------------------------------------------------------
    $scope.selectGroup = function (data) {
        $('#div_exam_list').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $('#div_exam_list').show();
        $('[ng-model=panelGroupName]').html(data.exam_group_name);
        $scope.groupID = data.id;

        setTimeout(function () {
            $scope.exams = findExamSharedToMe($scope.user.id,data.id);
            $scope.$apply();
            $('#div_exam_list').waitMe('hide');
            window.location.href = "#div_exam_list";
        }, 100);
    };
    //----------------------------------------------------------------------
    $scope.detailExam = function (data) {
        $scope.examID = data.id;
        $('#examName').html(data.exam_name);
        $('#examTimeLimit').html(data.time_limit);
        $('#examMemLimit').html(data.memory_size);

        $('#fullScore').html(data.full_score);
        $('#cutWrongAnswer').html(data.cut_wrongans);
        $('#cutComplieError').html(data.cut_comerror);
        $('#cutOverMem').html(data.cut_overmemory);
        $('#cutOverTime').html(data.cut_overtime);
        $scope.keywords = findKeywordByEID(data.id);

        $('#detail_exam_modal').modal({backdrop: 'static'});

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

        var fileData = readFileEx(data);
        $('#exam_content').Editor('setText', decapeHtml(fileData.content));
        $('.Editor-editor').attr('contenteditable', false);
        $('[id^=menuBarDiv]').hide();
        $('[id^=statusbar]').hide();
        $('#examInput').html(fileData.input);
        $('#examOutput').html(fileData.output);
        $('.Editor-editor').waitMe('hide');
        $('#input_part').waitMe('hide');
        $('#output_part').waitMe('hide');

    };
    //----------------------------------------------------------------------
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        if($scope.sortKey === 'exam_group_name'){
            $scope.reverseE = !$scope.reverseE; //if true make it false and vice versa
            if($scope.sortE === 'exam_group_name'){
                $scope.sortE = '-exam_group_name';
            } else {
                $scope.sortE = 'exam_group_name';
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
    $scope.copyExam = function (data) {
        window.location.href = url+"teacher-exam-copy-"+data.id;
    };
}]);