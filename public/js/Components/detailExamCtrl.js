app.controller('detailExamCtrl', ['$scope', '$window', function ($scope, $window) {
    $('#exam_content').Editor();
    $scope.exam = findExamByID($window.examID);
    $('#examName').html($scope.exam.exam_name);
    $('#examTimeLimit').html($scope.exam.time_limit);
    $('#examMemLimit').html($scope.exam.memory_size);

    $('#fullScore').html($scope.exam.full_score);
    $('#cutWrongAnswer').html($scope.exam.cut_wrongans);
    $('#cutComplieError').html($scope.exam.cut_comerror);
    $('#cutOverMem').html($scope.exam.cut_overmemory);
    $('#cutOverTime').html($scope.exam.cut_overtime);

    $scope.keywords = findKeywordByEID($scope.exam.id);

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

    var fileData = readFileEx($scope.exam);
    $('#exam_content').Editor('setText', decapeHtml(fileData.content));
    $('.Editor-editor').attr('contenteditable', false);
    $('[id^=menuBarDiv]').hide();
    $('[id^=statusbar]').hide();
    $('#examInput').html(fileData.input);
    $('#examOutput').html(fileData.output);
    //
    $('.Editor-editor').waitMe('hide');
    $('#input_part').waitMe('hide');
    $('#output_part').waitMe('hide');
    //----------------------------------------------------------------------
    function decapeHtml(str) {
        str = str.replace(/&amp;/g, '&');
        str = str.replace(/&lt;/g, '<');
        str = str.replace(/&gt;/g, '>');
        str = str.replace(/&quot;/g, '"');
        str = str.replace(/&#39;/g, "'");
        str = str.replace(/&#x2F;/g, '/');
        return str;
    }
}]);