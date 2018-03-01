app.controller('detailSheetCtrl', ['$scope', '$window', function ($scope, $window) {
    $('#sheet_trial').Editor();
    $scope.sheet = findSheetByID($window.sheetID);
    $('#sheetName').html($scope.sheet.sheet_name);
    $('#fullScore').html($scope.sheet.full_score);
    $('#notation').html($scope.sheet.notation);
    $scope.quizzes = findQuizBySID($scope.sheet.id);

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

    var fileDataSh = readFileSh($scope.sheet);
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
