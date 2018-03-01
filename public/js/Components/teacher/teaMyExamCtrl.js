app.controller('teaMyExamCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    $scope.examGroup = findMyExamGroup($scope.user.id);

    $scope.groupID = 0;
    $scope.selectRow = '10';
    $scope.sortE = 'exam_group_name';
    $scope.exams = "";
    $('#exam_content').Editor();

    //----------------------------------------------------------------------
    $scope.addExamGroup = function () {
        $scope.examGroupName = '';
        $('#notice_add_exam_grp').hide();
        $('#add_exam_group_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=examGroupName]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.enterAdd = function() {
        $scope.okAddExamGroup();
    };
    //----------------------------------------------------------------------
    $scope.okAddExamGroup = function () {
        if ($scope.examGroupName.length > 0) {
            $('#add_exam_group_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });
            var data = {
                user_id : $scope.user.id,
                exam_group_name: $scope.examGroupName
            };
            createExamGroup(data);
        } else {
            $('#notice_add_exam_grp').html('* กรุณาระบุชื่อกลุ่มข้อสอบ').show();
        }
    };
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
            $scope.exams = findExamByEGID(data.id);
            $scope.$apply();
            $('#div_exam_list').waitMe('hide');
            window.location.href = "#div_exam_list";
        }, 100);

    };
    //----------------------------------------------------------------------
    $scope.addExam = function () {
        window.location.href = url+"teacher-exam-add-"+$scope.groupID;
    };
    //----------------------------------------------------------------------
    $scope.editExamGroup = function (data) {
        $scope.CurrentIndex = $scope.examGroup.indexOf(data);
        $scope.examGroupName = data.exam_group_name;
        $scope.groupID = data.id;
        $('#notice_edit_exam_grp').hide();
        $('#edit_exam_group_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=examGroupName]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.okEditExamGroup = function () {
        if ($scope.examGroupName.length > 0) {
            var data = {
                id : $scope.groupID,
                exam_group_name: $scope.examGroupName,
                user_id : $scope.user.id
            };
            $('#edit_exam_group_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });
            editExamGroup(data);
        } else {
            $('#notice_edit_exam_grp').html('* กรุณาระบุชื่อกลุ่มข้อสอบ').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.deleteExamGroup = function (data) {
        $scope.examGroupName = data.exam_group_name;
        $scope.groupID = data.id;
        $('#delete_exam_group_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDeleteExamGroup = function () {
        var data = {
            id : $scope.groupID
        };
        $('#delete_exam_group_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deleteExamGroup(data);
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
    $scope.editExam = function () {
        window.location.href = url+"teacher-exam-edit-"+$scope.examID;
    };
    //----------------------------------------------------------------------
    $scope.gotoEditExam = function (data) {
        window.location.href = url+"teacher-exam-edit-"+data.id;
    };
    //----------------------------------------------------------------------
    $scope.deleteExam = function (data) {
        $scope.examName = data.exam_name;
        $scope.examID = data.id;
        $('#delete_exam_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDelete = function () {
        $('#edit_exam_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        deleteExam($scope.examID);
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
        }
    };
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        location.reload();
    });
}]);