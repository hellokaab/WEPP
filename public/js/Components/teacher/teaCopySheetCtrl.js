app.controller('teaCopySheetCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-sheet-copy-"+$window.sheetID,dtJsToDtDB(new Date()));
    $scope.mySheetGroup = findMySheetGroup($scope.user.id);
    $scope.teacher = findAllTeacher();

    var newQuizzes = new Array();
    $scope.sheetData = findSheetByID($window.sheetID);
    $scope.quizzes = findQuizBySID($window.sheetID);
    $scope.selectTeacher = [];
    $('#sheet_trial').Editor();

    // Sheet name
    $scope.sheetName = $scope.sheetData.sheet_name;

    // Sheet group
    $(document).ready(function () {
        // Sheet group
        $('#sheet_group').val('0');
    });

    var fileData = readFileSh($scope.sheetData);
    // Sheet objective
    $scope.objective = fileData.objective;

    // Sheet theory
    $scope.theory = fileData.theory;

    // Sheet trial
    $('#sheet_trial').Editor('setText', decapeHtml(fileData.trial));

    // Sheet input
    $scope.inputMode = 'no_input';
    $scope.input = '';
    if ($scope.sheetData.sheet_input_file) {
        $scope.inputMode = 'key_input';
        $scope.input = fileData.input;
    }

    // Sheet output
    $scope.outputMode = 'key_output';
    $scope.output = fileData.output;

    // Sheet main code
    $scope.classTestMode = '0';
    $scope.main = '';
    if ($scope.sheetData.main_code) {
        $scope.classTestMode = '1';
        $scope.main = fileData.main;
    }

    // Sheet casesensitive
    $scope.casesensitive = $scope.sheetData.case_sensitive;

    // Sheet score
    $scope.sheetScore = $scope.sheetData.full_score;

    // Sheet notation
    $scope.sheetNotation = $scope.sheetData.notation;

    $scope.completeScoreNumeric = true;
    //----------------------------------------------------------------------
    $scope.changeInputMode = function () {
        $scope.input = '';
        $('[name=sheet_file_input]').val('');
    };
    //----------------------------------------------------------------------
    $scope.changeOutputMode = function () {
        $scope.output = '';
        $('[name=sheet_file_output]').val('');
    };
    //----------------------------------------------------------------------
    $scope.changeClassTestMode = function () {
        $scope.main = '';
    };
    //----------------------------------------------------------------------
    $scope.checkFullScore = function () {
        $('#notice_sheet_score').hide();

        $scope.completeScoreNumeric = false;
        if ($.isNumeric($scope.sheetScore) && $scope.sheetScore.indexOf('.') < 0 && $scope.sheetScore > 0) {
            $scope.completeScoreNumeric = true;
        } else {
            $('#notice_sheet_score').html('* กรุณาระบุเฉพาะจำนวนเต็มบวกเท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.copySheet = function () {
        $('#notice_sheet_name').hide();
        $('#notice_sheet_group').hide();
        $('#notice_sheet_trial').hide();
        $('#notice_sheet_txt_input').hide();
        $('#notice_sheet_file_input').hide();
        $('#notice_sheet_txt_output').hide();
        $('#notice_sheet_file_output').hide();
        $('#notice_sheet_main_input').hide();
        $('#notice_sheet_score').hide();
        $('#notice_sheet_notation').hide();
        $('#notice_sheet_theory').hide();
        $('#notice_sheet_objective').hide();
        $('[id^=notice_old_sheet_quiz_]').each(function () {
            $(this).hide();
        });
        $('[id^=notice_old_quiz_score_]').each(function () {
            $(this).hide();
        });
        $('[id^=notice_sheet_quiz_]').each(function () {
            $(this).hide();
        });
        $('[id^=notice_quiz_score_]').each(function () {
            $(this).hide();
        });

        $scope.completeSheetName = $scope.sheetName.length > 0;
        if ($scope.completeSheetName) {
            $scope.completeNoDuplicate = findSheetByName($scope.sheetName, $('#sheet_group').val(), user.id);
        }
        $scope.completeSelectSheetgroup = $('#sheet_group').val() > '0' ? true : false ;
        $scope.completeTrialContent = $('#sheet_trial').Editor("getText").length > 0;
        $scope.completeInputMode = $scope.inputMode === 'no_input' ? true :
            $scope.inputMode === 'key_input' ? ($scope.input === '' ? false : true) :
                $scope.inputMode === 'file_input' ? ($('[name=sheet_file_input]').val() === '' ? false : checkTxtFile($('[name=sheet_file_input]').val())) : false;
        $scope.completeOutputMode = $scope.outputMode === 'key_output' ? ($scope.output === '' ? false : true) :
            $scope.outputMode === 'file_output' ? ($('[name=sheet_file_output]').val() === '' ? false : checkTxtFile($('[name=sheet_file_output]').val())) : false;
        $scope.completeClassTest = $scope.classTestMode === '0' ? true :
            $scope.classTestMode === '1' ? ($scope.main === '' ? false : true) : false;
        $scope.completeScore = $scope.sheetScore.toString().length > 0;
        $scope.completeQuiz = checkQuiz();

        if ($scope.completeSheetName
            && $scope.completeSelectSheetgroup
            && $scope.completeNoDuplicate
            && $scope.completeTrialContent
            && $scope.completeInputMode
            && $scope.completeOutputMode
            && $scope.completeClassTest
            && $scope.completeScoreNumeric
            && $scope.completeQuiz
            && $scope.completeScore
        ){
            $('#copy_sheet_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            createContentFile(escapeHtml($('#sheet_trial').Editor("getText")), function (result) {
                var resultJson = JSON.parse(result);
                $scope.trialPath = resultJson.trial_path;
                var trial_path_split = $scope.trialPath.split('/');
                var path = "";
                for(var i=0;i<trial_path_split.length-1;i++){
                    path += trial_path_split[i]+"*";
                }

                if ($scope.inputMode === 'no_input') {
                    $scope.inputPath = "";
                } else if ($scope.inputMode === 'key_input') {
                    $scope.inputPath = resultJson.input_path;
                } else {
                    $window.pathSheet = path;
                    $('#inputFileForm').submit();
                    $scope.inputPath = $window.input_path;
                }

                if ($scope.outputMode === 'key_output') {
                    $scope.outputPath = resultJson.output_path;
                } else {
                    $window.pathSheet = path;
                    $('#outputFileForm').submit();
                    $scope.outputPath = $window.output_path;
                }

                if($scope.classTestMode == 1){
                    $scope.mainPath = resultJson.main_path;
                } else {
                    $scope.mainPath = "";
                }

                $scope.objectivePath = resultJson.objective_path;
                $scope.theoryPath = resultJson.theory_path;

                getQuiz();
                data = {
                    user_id: $scope.user.id,
                    sheet_group_id: $('#sheet_group').val(),
                    sheet_name: $scope.sheetName,
                    sheet_trial: $scope.trialPath,
                    sheet_input_file: $scope.inputPath,
                    sheet_output_file: $scope.outputPath,
                    objective: $scope.objectivePath,
                    theory: $scope.theoryPath,
                    notation: $scope.sheetNotation,
                    full_score: $scope.sheetScore,
                    main_code: $scope.mainPath,
                    case_sensitive: $scope.casesensitive,
                    quiz : newQuizzes
                };
                if($scope.selectTeacher.length>0){
                    data.shared = $scope.selectTeacher;
                } else {
                    var share = new Array();
                    data.shared = share;
                }
                createSheet(data);
            });
        } else {
            if (!$scope.completeScoreNumeric) {
                $('#notice_sheet_score').html('* กรุณาระบุเฉพาะจำนวนเต็มบวกเท่านั้น').show();
                $('[ng-model=sheetScore]').focus();
            }

            if (!$scope.completeScore) {
                $('#notice_sheet_score').html('* กรุณาระบุคะแนนการทดลอง').show();
                $('[ng-model=sheetScore]').focus();
            }

            if (!$scope.completeClassTest) {
                $('#notice_sheet_main_input').html('* กรุณาระบุ method main ของใบงาน').show();
                $('[ng-model=main]').focus();
            }

            if (!$scope.completeOutputMode) {
                if ($scope.outputMode === 'key_output') {
                    $('#notice_sheet_txt_output').html('* กรุณาระบุ Output ของใบงาน').show();
                    $('[ng-model=output]').focus();
                } else {
                    if ($('[name=sheet_file_output]').val() === '') {
                        $('#notice_sheet_file_output').html('* กรุณาระบุไฟล์ Output ของใบงาน').show();
                    } else {
                        $('#notice_sheet_file_output').html('* กรุณาระบุไฟล์ .txt เท่านั้น').show();
                    }
                    $('[name=sheet_file_output]').focus();
                }
            }

            if (!$scope.completeInputMode) {
                if ($scope.inputMode === 'key_input') {
                    $('#notice_sheet_txt_input').html('* กรุณาระบุ Input ของใบงาน').show();
                    $('[ng-model=input]').focus();
                } else {
                    if ($('[name=sheet_file_input]').val() === '') {
                        $('#notice_sheet_file_input').html('* กรุณาระบุไฟล์ Input ของใบงาน').show();
                    } else {
                        $('#notice_sheet_file_input').html('* กรุณาระบุไฟล์ .txt เท่านั้น').show();
                    }
                    $('[name=sheet_file_input]').focus();
                }
            }

            if (!$scope.completeTrialContent) {
                $('#notice_sheet_trial').html('* กรุณาระบุรายละเอียดของการทดลอง').show();
                $('.Editor-editor').focus();
            }

            if (!$scope.completeSelectSheetgroup) {
                $('#notice_sheet_group').html('* กรุณาเลือกกลุ่มใบงาน').show();
                $('#sheet_group').focus();
            }

            if (!$scope.completeSheetName) {
                $scope.completeNoDuplicate = true;
                $('#notice_sheet_name').html('* กรุณาระบุชื่อใบงาน').show();
                $('[ng-model=sheetName]').focus();
            }

            if (!$scope.completeNoDuplicate) {
                $('#notice_sheet_name').html('* ใบงานนี้มีอยู่แล้ว').show();
                $('[ng-model=sheetName]').focus();
            }
        }
    }
    //----------------------------------------------------------------------
    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }
    //----------------------------------------------------------------------
    function checkTxtFile(filename) {
        var ext = getExtension(filename);
        if (ext.toLowerCase() === "txt") {
            return true;
        } else {
            return false;
        }
    }
    //----------------------------------------------------------------------
    function getQuiz() {
        newQuizzes = new Array();
        $('[id^=oldQuiz_part_]').each(function () {
            if(!(($(this).children().children().children()[0].value).trim().length === 0
                && ($(this).children().children().children()[2].value).trim().length === 0
                && ($(this).children().children().children()[3].value).trim().length === 0))
            {
                data = {
                    quiz : ($(this).children().children().children()[0].value).trim(),
                    answer : ($(this).children().children().children()[2].value).trim(),
                    score : ($(this).children().children().children()[3].value).trim()
                }
                newQuizzes.push(data);
            }
        });

        $('[id^=quiz_part_]').each(function () {
            if(!(($(this).children().children().children()[0].value).trim().length === 0
                && ($(this).children().children().children()[2].value).trim().length === 0
                && ($(this).children().children().children()[3].value).trim().length === 0))
            {
                data = {
                    quiz : ($(this).children().children().children()[0].value).trim(),
                    answer : ($(this).children().children().children()[2].value).trim(),
                    score : ($(this).children().children().children()[3].value).trim()
                }
                newQuizzes.push(data);
            }
        });
    }
    //----------------------------------------------------------------------
    function checkQuiz() {
        var checked = true;
        $('[id^=oldQuiz_part_]').each(function () {
            thisID = ($(this)[0].id).split('_')[2];
            if(!(($(this).children().children().children()[0].value).trim().length === 0
                && ($(this).children().children().children()[2].value).trim().length === 0
                && ($(this).children().children().children()[3].value).trim().length === 0))
            {
                if (($(this).children().children().children()[0].value).trim().length === 0){
                    $('#notice_old_sheet_quiz_'+thisID).html('* กรุณาระบุคำถาม').show();
                    checked = false
                }
                if(($(this).children().children().children()[3].value).trim().length === 0){
                    $('#notice_old_quiz_score_'+thisID).html('* กรุณาระบุคะแนนคำถาม').show();
                    checked = false
                }else if(!$.isNumeric($(this).children().children().children()[3].value)){
                    $('#notice_old_quiz_score_'+thisID).html('* กรุณาระบุคะแนนให้ถูกต้อง').show();
                    checked = false
                }
            }
        });

        $('[id^=quiz_part_]').each(function () {
            thisID = ($(this)[0].id).split('_')[2];
            if(!(($(this).children().children().children()[0].value).trim().length === 0
                && ($(this).children().children().children()[2].value).trim().length === 0
                && ($(this).children().children().children()[3].value).trim().length === 0))
            {
                if (($(this).children().children().children()[0].value).trim().length === 0){
                    $('#notice_sheet_quiz_'+thisID).html('* กรุณาระบุคำถาม').show();
                    checked = false
                }
                if(($(this).children().children().children()[3].value).trim().length === 0){
                    $('#notice_quiz_score_'+thisID).html('* กรุณาระบุคะแนนคำถาม').show();
                    checked = false
                }else if(!$.isNumeric($(this).children().children().children()[3].value)){
                    $('#notice_quiz_score_'+thisID).html('* กรุณาระบุคะแนนให้ถูกต้อง').show();
                    checked = false
                }
            }
        });
        return checked;
    }
    //----------------------------------------------------------------------
    $scope.addUserShare = function () {
        $('#add_user_to_share_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okAddTeacher = function () {
        $('#add_user_share_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $scope.selectTeacher = [];
        $('[id^=tea_]').each(function () {
            if ($(this).prop('checked')) {
                var indexOfStevie = $scope.teacher.findIndex(i => i.id == $(this).attr('id').substr(4));
                $scope.selectTeacher.push($scope.teacher[indexOfStevie]);
            }
        });
        $('#add_user_share_part').waitMe('hide');
        $('#add_user_to_share_modal').modal('hide');
    };
    //----------------------------------------------------------------------
    $scope.ticExam = function (id) {
        if($('#tea_'+id)[0].checked){
            $('#tea_'+id)[0].checked = false;
        } else {
            $('#tea_'+id)[0].checked = true;
        }
    };
    //----------------------------------------------------------------------
    $('#select_all').on('change',function () {
        if($('#select_all')[0].checked){
            $('[id^=tea_]').each(function () {
                $(this)[0].checked = true;
            });
        } else {
            $('[id^=tea_]').each(function () {
                $(this)[0].checked = false;
            });
        }
    })
    //----------------------------------------------------------------------
    function createContentFile(trial, callback) {
        $.post("js/Components/CreateTextFileSH.php", {
            objective : $scope.objective,
            theory : $scope.theory,
            trial: trial,
            input : $scope.input,
            output : $scope.output,
            main: $scope.main,
            userID : user.id,
            userName : user.fname_en+"_"+user.lname_en,
            sheet_group_id: $('#sheet_group').val()
        }, function (data) {
            callback(data);
        });
    }
    //----------------------------------------------------------------------
    $('#okSuccess').on('click',function () {
        window.location.href = url+'teacher-sheet-my';
    });
    //----------------------------------------------------------------------
    $scope.goBack = function () {
        window.history.back();
    };
}]);