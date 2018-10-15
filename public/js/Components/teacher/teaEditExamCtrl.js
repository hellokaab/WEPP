app.controller('teaEditExamCtrl', ['$scope', '$window', function ($scope, $window) {
    $scope.user = $window.user;
    keepHistory($window.user.id,"teacher-exam-edit-"+$window.examID,getDateNow());
    var newKeywords = new Array();
    $scope.examID = $window.examID;
    $scope.examData = findExamByID($scope.examID);
    $scope.groupID = $scope.examData.exam_group_id;
    $scope.keywords = findKeywordByEID($scope.examID);
    $scope.teacher = findAllTeacher();
    $scope.sharedUser = findSharedUserNotMe($scope.examID,$scope.user.id);
    $scope.selectTeacher = [];
    var sharedUserToDelete = new Array();
    setOldSharedUser();
    $scope.myExamGroup = findMyExamGroup($scope.user.id);
    $('#exam_content').Editor();
    $("#uploadImageBar").children().attr("accept","image/png,image/jpg,image/gif");

    // Exam name
    $scope.examName = $scope.examData.exam_name;

    $(document).ready(function () {
        // Exam group
        $('#ddl_group').val($scope.groupID);
        // Checked Old Shared User
        setCheckedOldSharedUser();
    });
    var fileData = readFileEx($scope.examData);

    // Exam content
    $('#exam_content').Editor('setText', decapeHtml(fileData.content));

    // Exam input
    $scope.inputMode = 'no_input';
    $scope.input = '';
    if ($scope.examData.exam_input_file) {
        $scope.inputMode = 'key_input';
        $scope.input = fileData.input;
    }

    // Exam output
    $scope.outputMode = 'key_output';
    $scope.output = fileData.output;

    // Exam main code
    $scope.classTestMode = '0';
    $scope.main = '';
    if ($scope.examData.main_code) {
        $scope.classTestMode = '1';
        $scope.main = fileData.main;
    }

    // Exam casesensitive
    $scope.casesensitive = $scope.examData.case_sensitive;

    // Exam limit
    $scope.memLimit = $scope.examData.memory_size > 0 ? $scope.examData.memory_size : "";
    $scope.timeLimit = $scope.examData.time_limit;

    // Exam score
    $scope.fullScore = $scope.examData.full_score;

    // Exam cut score
    $scope.cutWrongAnswer = $scope.examData.cut_wrongans;
    $scope.cutComplieError = $scope.examData.cut_comerror;
    $scope.cutOverMem = $scope.examData.cut_overmemory;
    $scope.cutOverTime = $scope.examData.cut_overtime;

    $scope.completeMemLimit = true;
    $scope.completeTimeLimit = true;
    $scope.completeFullScore = true;
    $scope.completeCutWrongAnswer = true;
    $scope.completeCutComplieError = true;
    $scope.completeCutOverMem = true;
    $scope.completeCutOverTime = true;

    //----------------------------------------------------------------------
    function setOldSharedUser() {
        for (i=0;i<$scope.sharedUser.length;i++){
            var UID = $scope.sharedUser[i].user_id;
            var indexOfStevie = $scope.teacher.findIndex(i => i.id == UID);
            $scope.selectTeacher.push($scope.teacher[indexOfStevie]);
        }
    }
    //----------------------------------------------------------------------
    function setCheckedOldSharedUser() {
        for (i=0;i<$scope.sharedUser.length;i++){
            var UID = $scope.sharedUser[i].user_id;
            $('#tea_'+UID)[0].checked = true;
        }
    }
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

        if($scope.memLimit.length > 0) {
            if ($.isNumeric($scope.memLimit) && $scope.memLimit.indexOf('.') < 0 && $scope.memLimit > 0) {
                $scope.completeMemLimit = true;
            } else {
                $scope.completeMemLimit = false;
                $('#notice_exam_limit').html('* กรุณาระบุเฉพาะจำนวนเต็ม และมากกว่า 0 เท่านั้น').show();
            }
        } else {
            $scope.completeMemLimit = true;
        }
    };
    //----------------------------------------------------------------------
    $scope.checkTimeLimit = function () {
        $('#notice_exam_limit').hide();

        if($scope.timeLimit.length > 0) {
            if ($.isNumeric($scope.timeLimit) && $scope.timeLimit > 0) {
                $scope.completeTimeLimit = true;
            } else {
                $scope.completeTimeLimit = false;
                $('#notice_exam_limit').html('* กรุณาระบุเวลาในการประมวลให้มากกว่า 0 เท่านั้น').show();
            }
        } else {
            $scope.completeTimeLimit = true;
        }
    };
    //----------------------------------------------------------------------
    $scope.checkFullScore = function () {
        $('#notice_exam_score').hide();

        $scope.completeFullScore = false;
        if ($.isNumeric($scope.fullScore) && $scope.fullScore.indexOf('.') < 0 && $scope.fullScore > 0) {
            $scope.completeFullScore = true;
        } else {
            $('#notice_exam_score').html('* กรุณาระบุเฉพาะจำนวนเต็ม และมากกว่า 0 เท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutWrongAnswer = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutWrongAnswer = false;
        if ($.isNumeric($scope.cutWrongAnswer)) {
            $scope.completeCutWrongAnswer = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนคำตอบผิดพลาดเป็นตัวเลขเท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutComplieError = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutComplieError = false;
        if ($.isNumeric($scope.cutComplieError)) {
            $scope.completeCutComplieError = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนรูปแบบโค้ดไม่ถูกต้องเป็นตัวเลขเท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutOverMem = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutOverMem = false;
        if ($.isNumeric($scope.cutOverMem)) {
            $scope.completeCutOverMem = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนหน่วยความจำเกินเป็นตัวเลขเท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.checkCutOverTime = function () {
        $('#notice_exam_descore').hide();

        $scope.completeCutOverTime = false;
        if ($.isNumeric($scope.cutOverTime)) {
            $scope.completeCutOverTime = true;
        } else {
            $('#notice_exam_descore').html('* กรุณาระบุการหักคะแนนเวลาประมวณผลเกินเป็นตัวเลขเท่านั้น').show();
        }
    };
    //----------------------------------------------------------------------
    $scope.editKeyword = function (keywordID, content) {
        $('#edit_modal').modal({backdrop: 'static'});
        $('#notice_keyword').hide();
        $scope.keyword = content;
        $scope.keywordID = keywordID;
        setTimeout(function () {
            $('[ng-model=keyword]').focus();
        }, 200);
    };
    //----------------------------------------------------------------------
    $scope.okEditKeyword = function () {
        if ($scope.keyword.length === 0) {
            $('#notice_keyword').html('กรุณาระบุคีย์เวิร์ด').show();
        } else {
            $('#edit_keyword_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });
            $.ajax({
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                headers: {
                    Accept: "application/json"
                },
                url: url + 'exam-edit-keyword',
                data: {
                    id: $scope.keywordID,
                    keyword_data: $scope.keyword
                },
                async: false,
                complete: function (xhr) {
                    if (xhr.readyState == 4) {
                        $('#edit_keyword_part').waitMe('hide');
                        if (xhr.status == 200) {
                            $scope.keywords = findKeywordByEID($scope.examID);
                            $('#edit_modal').modal('hide');
                        } else {
                            alert("ผิดพลาด");
                        }
                    }
                },
            });
        }
    };
    //----------------------------------------------------------------------
    $scope.deleteKeyword = function (keywordID) {
        $scope.keywordID = keywordID;
        $('#delete_modal').modal({backdrop: 'static'});
    };
    //----------------------------------------------------------------------
    $scope.okDeleteKeyword = function () {
        $('#delete_keyword_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });
        $.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                Accept: "application/json"
            },
            url: url + 'exam-delete-keyword',
            data: {id: $scope.keywordID},
            async: false,
            complete: function (xhr) {
                if (xhr.readyState == 4) {
                    $('#delete_keyword_part').waitMe('hide');
                    if (xhr.status == 200) {
                        $scope.keywords = findKeywordByEID($scope.examID);
                        $('#delete_modal').modal('hide');

                    } else {
                        $('#delete_modal').modal('hide');
                        alert("ผิดพลาด");
                    }
                }
            },
        });
    };
    //----------------------------------------------------------------------
    $scope.editExam = function () {
        $('#notice_exam_descore').hide();
        $('#notice_exam_score').hide();
        $('#notice_exam_limit').hide();
        $('#notice_exam_main_input').hide();
        $('#notice_exam_txt_output').hide();
        $('#notice_exam_file_output').hide();
        $('#notice_exam_txt_input').hide();
        $('#notice_exam_file_input').hide();
        $('#notice_exam_content').hide();
        $('#notice_exam_name').hide();
        $scope.completeExamName = $scope.examName.length > 0;
        if ($scope.completeExamName) {
            if ($scope.examName === $scope.examData.exam_name && $('#ddl_group').val() == $scope.examData.exam_group_id) {
                $scope.completeNoDuplicate = true;
            } else {
                $scope.completeNoDuplicate = findExamByName($scope.examName ,$('#ddl_group').val() ,user.id);
            }

        }
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

            $('#edit_exam_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            createContentFile(escapeHtml($('#exam_content').Editor("getText")), function (result) {
                var resultJson = JSON.parse(result);
                for(i=0;i<$scope.sharedUser.length;i++){
                    var UID = $scope.sharedUser[i].user_id;
                    var indexOfStevie = $scope.selectTeacher.findIndex(i => i.id == UID);
                    if(indexOfStevie == -1){
                        sharedUserToDelete.push(UID)
                    }
                }

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
                    id: $scope.examData.id,
                    exam_group_id: $('#ddl_group').val(),
                    exam_name: $scope.examName,
                    exam_data: $scope.contentPath,
                    exam_inputfile: $scope.inputPath,
                    exam_outputfile: $scope.outputPath,
                    memory_size: $scope.memLimit.length > 0 ? $scope.memLimit : 0,
                    time_limit: $scope.timeLimit.length > 0 ? $scope.timeLimit : 5,
                    full_score: $scope.fullScore,
                    cut_wrongans: $scope.cutWrongAnswer,
                    cut_comerror: $scope.cutComplieError,
                    cut_overmemory: $scope.cutOverMem,
                    cut_overtime: $scope.cutOverTime,
                    main_code: $scope.mainPath,
                    case_sensitive: $scope.classTestMode,
                    keyword: newKeywords,
                    shared: $scope.selectTeacher,
                    deleteShared:sharedUserToDelete,
                };
                editExam(data);
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
    function getKeyword() {
        newKeywords = new Array();
        $('[id^=exam_keyword_]').each(function () {
            if (this.value.length > 0)
                newKeywords.push(this.value);
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
    });
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
    $('#okSuccess').on('click',function () {
        window.location.href = url+'teacher-exam-my';
    });
    //----------------------------------------------------------------------
    $scope.goBack = function () {
        window.history.back();
    };
}]);