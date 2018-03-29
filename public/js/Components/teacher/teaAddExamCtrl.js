app.controller('teaAddExamCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-exam-add-"+$window.groupID,dtJsToDtDB(new Date()));

    var keywords = new Array();
    $scope.myExamGroup = findMyExamGroup($scope.user.id);
    $scope.teacher = findAllTeacher();
    $('#exam_content').Editor();
    // Default
    $scope.inputMode = 'no_input';
    $scope.outputMode = 'key_output';
    $scope.classTestMode = '0';
    $scope.casesensitive = '1';
    $scope.completeNoDuplicate = true;
    $scope.examName = '';
    $scope.output = '';
    $scope.input = '';
    $scope.main = '';
    $scope.selectTeacher = [];

    $(document).ready(function () {
        $('#ddl_group').val($window.groupID);
    });

    //----------------------------------------------------------------------
    $scope.changeInputMode = function () {
        $scope.input = '';
        $('[name=exam_file_input]').val('');
    };
    //----------------------------------------------------------------------
    $scope.changeOutputMode = function () {
        $scope.output = '';
        $('[name=exam_file_output]').val('');
    };
    //----------------------------------------------------------------------
    $scope.changeClassTestMode = function () {
        $scope.main = '';
    };
    //----------------------------------------------------------------------
    $scope.checkMemLimit = function () {
        $('#notice_exam_limit').hide();

        $scope.completeMemLimit = false;
        if ($.isNumeric($scope.memLimit) && $scope.memLimit.indexOf('.') < 0 && $scope.memLimit > 0) {
            $scope.completeMemLimit = true;
        } else {
            $('#notice_exam_limit').html('* กรุณาระบุเฉพาะจำนวนเต็มบวกเท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkTimeLimit = function () {
        $('#notice_exam_limit').hide();

        $scope.completeTimeLimit = false;
        if ($.isNumeric($scope.timeLimit) && $scope.timeLimit > 0) {
            $scope.completeTimeLimit = true;
        } else {
            $('#notice_exam_limit').html('* กรุณาระบุเวลาในการประมวลผลให้ถูกต้อง').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkFullScore = function () {
        $('#notice_exam_score').hide();

        $scope.completeFullScore = false;
        if ($.isNumeric($scope.fullScore) && $scope.fullScore.indexOf('.') < 0 && $scope.fullScore > 0) {
            $scope.completeFullScore = true;
        } else {
            $('#notice_exam_score').html('* กรุณาระบุเฉพาะจำนวนเต็มบวกเท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutWrongAnswer = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutWrongAnswer = false;
        if ($.isNumeric($scope.cutWrongAnswer)) {
            $scope.completeCutWrongAnswer = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนคำตอบผิดพลาดให้ถูกต้อง').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutComplieError = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutComplieError = false;
        if ($.isNumeric($scope.cutComplieError)) {
            $scope.completeCutComplieError = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนรูปแบบโค้ดไม่ถูกต้อง ให้ถูกต้อง').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutOverMem = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutOverMem = false;
        if ($.isNumeric($scope.cutOverMem)) {
            $scope.completeCutOverMem = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนหน่วยความจำเกินให้ถูกต้อง').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutOverTime = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutOverTime = false;
        if ($.isNumeric($scope.cutOverTime)) {
            $scope.completeCutOverTime = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนเวลาประมวณผลเกินให้ถูกต้อง').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.addExam = function () {
        $('#notice_exam_descore').hide();
        $('#notice_exam_score').hide();
        $('#notice_exam_limit').hide();
        $('#notice_exam_main_input').hide();
        $('#notice_exam_txt_output').hide();
        $('#notice_exam_file_output').hide();
        $('#notice_exam_txt_input').hide();
        $('#notice_exam_file_input').hide();
        $('#notice_exam_group').hide();
        $('#notice_exam_content').hide();
        $('#notice_exam_name').hide();

        $scope.completeExamName = $scope.examName.length > 0;
        if ($scope.completeExamName) {
            $scope.completeNoDuplicate = findExamByName($scope.examName, $('#ddl_group').val(), user.id);
        }
        $scope.completeSelectExamGroup = $('#ddl_group').val() > '0' ? true : false;
        $scope.completeExamContent = $('#exam_content').Editor("getText").length > 0;
        $scope.completeInputMode = $scope.inputMode === 'no_input' ? true :
            $scope.inputMode === 'key_input' ? ($scope.input === '' ? false : true) :
                $scope.inputMode === 'file_input' ? ($('[name=exam_file_input]').val() === '' ? false : checkTxtFile($('[name=exam_file_input]').val())) : false;
        $scope.completeOutputMode = $scope.outputMode === 'key_output' ? ($scope.output === '' ? false : true) :
            $scope.outputMode === 'file_output' ? ($('[name=exam_file_output]').val() === '' ? false : checkTxtFile($('[name=exam_file_output]').val())) : false;
        $scope.completeClassTest = $scope.classTestMode === '0' ? true :
            $scope.classTestMode === '1' ? ($scope.main === '' ? false : true) : false;


        if ($scope.completeExamName
            && $scope.completeNoDuplicate
            && $scope.completeSelectExamGroup
            && $scope.completeExamContent
            && $scope.completeInputMode
            && $scope.completeOutputMode
            && $scope.completeClassTest
            && $scope.completeMemLimit
            && $scope.completeTimeLimit
            && $scope.completeFullScore
            && $scope.completeCutWrongAnswer
            && $scope.completeCutComplieError
            && $scope.completeCutOverMem
            && $scope.completeCutOverTime) {

            $('#add_exam_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            createContentFile(escapeHtml($('#exam_content').Editor("getText")), function (result) {
                var resultJson = JSON.parse(result);
                $scope.contentPath = resultJson.content_path;
                var content_path_split = $scope.contentPath.split('/');
                var path = "";
                for(var i=0;i<content_path_split.length-1;i++){
                    path += content_path_split[i]+"*";
                }
                if ($scope.inputMode === 'no_input') {
                    $scope.inputPath = "";
                } else if ($scope.inputMode === 'key_input') {
                    $scope.inputPath = resultJson.input_path;
                } else {
                    $window.pathExam = path;
                    $('#inputFileForm').submit();
                    $scope.inputPath = $window.input_path;
                }

                if ($scope.outputMode === 'key_output') {
                    $scope.outputPath = resultJson.output_path;
                } else {
                    $window.pathExam = path;
                    $('#outputFileForm').submit();
                    $scope.outputPath = $window.output_path;
                }

                if($scope.classTestMode == 1){
                    $scope.mainPath = resultJson.main_path;
                } else {
                    $scope.mainPath = "";
                }

                getKeyword();
                data = {
                    user_id: $window.user.id,
                    exam_group_id: $('#ddl_group').val(),
                    exam_name: $scope.examName,
                    exam_data: $scope.contentPath,
                    exam_inputfile: $scope.inputPath,
                    exam_outputfile: $scope.outputPath,
                    memory_size: $scope.memLimit,
                    time_limit: $scope.timeLimit,
                    full_score: $scope.fullScore,
                    cut_wrongans: $scope.cutWrongAnswer,
                    cut_comerror: $scope.cutComplieError,
                    cut_overmemory: $scope.cutOverMem,
                    cut_overtime: $scope.cutOverTime,
                    main_code: $scope.mainPath,
                    case_sensitive: $scope.casesensitive,
                    keyword: keywords,
                };
                if($scope.selectTeacher.length>0){
                    data.shared = $scope.selectTeacher;
                } else {
                    var share = new Array();
                    data.shared = share;
                }
                createExam(data);
            });

        } else {
            if (!$scope.completeCutOverTime) {
                $('#notice_exam_descore').html('* กรุณาระบุข้อมูลการหักคะแนนให้ครบถ้วนและถูกต้อง').show();
                $('[ng-model=cutOverTime]').focus();
            }
            if (!$scope.completeCutOverMem) {
                $('#notice_exam_descore').html('* กรุณาระบุข้อมูลการหักคะแนนให้ครบถ้วนและถูกต้อง').show();
                $('[ng-model=cutOverMem]').focus();
            }
            if (!$scope.completeCutComplieError) {
                $('#notice_exam_descore').html('* กรุณาระบุข้อมูลการหักคะแนนให้ครบถ้วนและถูกต้อง').show();
                $('[ng-model=cutComplieError]').focus();
            }
            if (!$scope.completeCutWrongAnswer) {
                $('#notice_exam_descore').html('* กรุณาระบุข้อมูลการหักคะแนนให้ครบถ้วนและถูกต้อง').show();
                $('[ng-model=cutWrongAnswer]').focus();
            }

            if (!$scope.completeFullScore) {
                $('#notice_exam_score').html('* กรุณาระบุข้อมูลการให้คะแนนให้ครบถ้วนสมบูรณ์').show();
                $('[ng-model=fullScore]').focus();
            }

            if (!$scope.completeTimeLimit) {
                $('#notice_exam_limit').html('* กรุณาระบุข้อมูลข้อจำกัดให้ครบถ้วนสมบูรณ์').show();
                $('[ng-model=timeLimit]').focus();
            }
            if (!$scope.completeMemLimit) {
                $('#notice_exam_limit').html('* กรุณาระบุข้อมูลข้อจำกัดให้ครบถ้วนสมบูรณ์').show();
                $('[ng-model=memLimit]').focus();
            }

            if (!$scope.completeClassTest) {
                $('#notice_exam_main_input').html('* กรุณาระบุ method main ของข้อสอบ').show();
                $('[ng-model=main]').focus();
            }

            if (!$scope.completeOutputMode) {
                if ($scope.outputMode === 'key_output') {
                    $('#notice_exam_txt_output').html('* กรุณาระบุ Output ของข้อสอบ').show();
                    $('[ng-model=output]').focus();
                } else {
                    if ($('[name=exam_file_output]').val() === '') {
                        $('#notice_exam_file_output').html('* กรุณาระบุไฟล์ Output ของข้อสอบ').show();
                    } else {
                        $('#notice_exam_file_output').html('* กรุณาระบุไฟล์ .txt เท่านั้น').show();
                    }
                    $('[name=exam_file_output]').focus();
                }
            }
            if (!$scope.completeInputMode) {
                if ($scope.inputMode === 'key_input') {
                    $('#notice_exam_txt_input').html('* กรุณาระบุ Input ของข้อสอบ').show();
                    $('[ng-model=input]').focus();
                } else {
                    if ($('[name=exam_file_input]').val() === '') {
                        $('#notice_exam_file_input').html('* กรุณาระบุไฟล์ Input ของข้อสอบ').show();
                    } else {
                        $('#notice_exam_file_input').html('* กรุณาระบุไฟล์ .txt เท่านั้น').show();
                    }
                    $('[name=exam_file_input]').focus();
                }
            }

            if (!$scope.completeExamContent) {
                $('#notice_exam_content').html('* กรุณาระบุรายละเอียดของข้อสอบ').show();
                $('.Editor-editor').focus();
            }

            if (!$scope.completeSelectExamGroup) {
                $('#notice_exam_group').html('* กรุณาเลือกกลุ่มข้อสอบ').show();
                $('[ng-model=groupID]').focus();
            }

            if (!$scope.completeExamName) {
                $scope.completeNoDuplicate = true;
                $('#notice_exam_name').html('* กรุณาระบุชื่อข้อสอบ').show();
                $('[ng-model=examName]').focus();
            }

            if (!$scope.completeNoDuplicate) {
                $('#notice_exam_name').html('* ข้อสอบนี้มีอยู่แล้ว').show();
                $('[ng-model=examName]').focus();
            }

        }
    };
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
    function getKeyword() {
        keywords = new Array();

        $('[id^=exam_keyword_]').each(function () {
            if (this.value.length > 0)
                keywords.push(this.value);
        });
    }
    //----------------------------------------------------------------------
    function createContentFile(content, callback) {
        $.post("js/Components/CreateTextFileEX.php", {
            Content: content,
            input : $scope.input,
            output : $scope.output,
            main: $scope.main,
            userID : user.id,
            userName : user.fname_en+"_"+user.lname_en,
            exam_group_id: $('#ddl_group').val()
        }, function (data) {
            callback(data);
        });
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
    $('#okSuccess').on('click',function () {
        window.location.href = url+'teacher-exam-my';
    });
    //----------------------------------------------------------------------
    $scope.goBack = function () {
        window.history.back();
    };


}]);