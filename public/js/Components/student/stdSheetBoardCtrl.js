app.controller('stdSheetBoardCtrl', ['$scope', '$window', function ($scope, $window) {
    keepHistory($window.user.id, "student-board-sheet-" + $window.sheetingID, getDateNow());
    $scope.sheetingID = $window.sheetingID;
    $scope.sheeting = findSheetingByID($scope.sheetingID);
    $scope.group = findGroupDataByID($scope.sheeting.group_id);
    $scope.sheetSheeting = findSheetSheetingInSheetBoard($scope.sheetingID);
    $scope.myPermissionsInGroup = findMyPermissionsInGroup(user.id, $scope.sheeting.group_id);

    $scope.thisSheet = findSheetByID($scope.sheetSheeting[0].sheet_id);
    $scope.quizThisSheet = findQuizBySID($scope.thisSheet.id);
    $scope.sumScoreQuiz = sumFullScoreQuizInSheet();
    $scope.dataInSheetBoard = dataInSheetBoard($scope.sheeting.group_id, $scope.thisSheet.id, $scope.sheetingID)

    $scope.changeScore = false;
    $scope.edited = false;
    $scope.sheetStatus = "";
    $(document).ready(function () {
        $('#select_sheet_id').val($scope.thisSheet.id);
    });
    //----------------------------------------------------------------------
    function sumFullScoreQuizInSheet() {
        var score = 0;
        $scope.quizThisSheet.forEach(function (quiz) {
            score += quiz.quiz_score;
        });
        return score;
    }

    //----------------------------------------------------------------------
    $('#select_sheet_id').on('change', function () {

        $('#table_part').waitMe({
            effect: 'facebook',
            bg: 'rgba(255,255,255,0.9)',
            color: '#3bafda'
        });

        setTimeout(function () {
            $scope.thisSheet = findSheetByID($('#select_sheet_id').val());
            $scope.quizThisSheet = findQuizBySID($scope.thisSheet.id);
            $scope.sumScoreQuiz = sumFullScoreQuizInSheet();
            $scope.dataInSheetBoard = dataInSheetBoard($scope.sheeting.group_id, $scope.thisSheet.id, $scope.sheetingID);
            $scope.$apply();
            $('#table_part').waitMe('hide');
        }, 200);
    });
    //----------------------------------------------------------------------
    $scope.viewResSheet = function (data) {
        if ($scope.myPermissionsInGroup.view_sheet === '1') {
            $('#res_sheet_modal').modal({backdrop: 'static'});
            $('#res_sheet_part').waitMe({
                effect: 'facebook',
                bg: 'rgba(255,255,255,0.9)',
                color: '#3bafda'
            });

            setTimeout(function () {
                $("#stdName").html(data.full_name);
                $("#stdCode").html(data.stu_id);

                $scope.resSheet = findResSheetByID(data.res_sheet_id);

                $('#std_score').val($scope.resSheet.score);
                $scope.currentTrialScore = $scope.resSheet.score;

                var codes = getCode($scope.resSheet.path);
                var code = "";
                (codes).forEach(function (codes) {
                    code += codes;
                });
                $('#std_code').html(escapeHtml(code));

                var teaOutput = readFileSh($scope.thisSheet).output;
                $('#tea_output').html('<span class="hljs-right">' + teaOutput + '</span>' + '<br><br><br>' + '<span class="hljs-lengt">ความยาวข้อความทั้งหมด : ' + teaOutput.length + ' ตัวอักษร' + '</span>');

                var readResrun = $scope.resSheet.res_run === null ? "" : readFileResRun($scope.resSheet.res_run);
                $('#resrun').html(changColor(readResrun, teaOutput));

                $('mycode').each(function (i, block) {
                    hljs.highlightBlock(block);
                });

                $scope.resQuiz = findResQuizByRSID($scope.resSheet.id);
                ($scope.resQuiz).forEach(function (res) {
                    $('#quizAns_' + res.quiz_id).val(res.quiz_ans);
                    $('#quiz_score_' + res.quiz_id).val(res.score);
                });

                if ($scope.myPermissionsInGroup.edit_sheet === '0') {
                    $('#btn_edit_trial_score').css('display', 'none');
                    $('[id^=edit_quiz_]').each(function () {
                        $(this).css('display', 'none');
                    });
                }

                $scope.resSheet.current_status === 'a' ? $("#status_sheet").css('color', 'green') : $("#status_sheet").css('color', 'red');
                $("#status_sheet").html($scope.resSheet.current_status === 'q' ? 'การส่งผิดพลาด' :
                    $scope.resSheet.current_status === 'a' ? 'ผ่าน' :
                        $scope.resSheet.current_status === 'w' ? 'คำตอบผิด' :
                            $scope.resSheet.current_status === 'm' ? 'ความจำเกินกำหนด' :
                                $scope.resSheet.current_status === 't' ? 'เวลาเกินกำหนด' :
                                    $scope.resSheet.current_status === 'c' ? 'คอมไพล์ไม่ผ่าน' :
                                        $scope.resSheet.current_status === 'Q' ? 'กำลังรอคิวตรวจ...' :
                                            $scope.resSheet.current_status === 'P' ? 'กำลังตรวจ...' :
                                                $scope.resSheet.current_status === '9' ? 'ถูกต้องบางส่วน(90%)' :
                                                    $scope.resSheet.current_status === '8' ? 'ถูกต้องบางส่วน(80%)' :
                                                        $scope.resSheet.current_status === '7' ? 'ถูกต้องบางส่วน(70%)' :
                                                            $scope.resSheet.current_status === '6' ? 'ถูกต้องบางส่วน(60%)' :
                                                                $scope.resSheet.current_status === '5' ? 'ถูกต้องบางส่วน(50%)' : '-');

                $('#send_time_sheet').html($scope.resSheet.send_date_time);
                $('#send_ip').html($scope.resSheet.ip);
                $('#res_sheet_part').waitMe('hide');
            }, 200);
        } else {
            $('#fail_modal').modal({backdrop: 'static'});
            $('#err_message').html("คุณไม่ได้รับสิทธิในการเข้าถึงใบงานที่ส่ง");
        }
    }
    //----------------------------------------------------------------------
    function changColor(resrun, teaOuput) {
        str = '';
        insertMyCut = false;
        strAfterChange = '';
        try {
            for (i = 0; i < resrun.length || i < teaOuput.length; i++) {
                if (teaOuput[i] === resrun[i]) {
                    if (insertMyCut) {
                        str += "myCut" + resrun[i];
                        insertMyCut = false;
                    } else {
                        str += resrun[i];
                    }
                } else {
                    if (insertMyCut) {
                        if (i > resrun.length - 1) {
                            str += "";
                        } else {
                            str += resrun[i];
                        }
                    } else {
                        if (i > resrun.length - 1) {
                            str += "myCut" + "";
                        } else {
                            str += "myCut" + resrun[i];
                        }
                        insertMyCut = true;
                    }
                }
            }
            if (insertMyCut) {
                str += "myCut";
            }
        } catch (err) {
        }

        arrayStr = str.split('myCut');
        for (i = 0; i < arrayStr.length; i++) {
            if (i % 2 === 0) {
                strAfterChange += '<span class="hljs-right">' + arrayStr[i] + '</span>';
            } else {
                strAfterChange += '<span class="hljs-wrong">' + arrayStr[i] + '</span>';
            }
        }
        strAfterChange += '<br><br><br>' + '<span class="hljs-lengt">' + 'ความยาวข้อความทั้งหมด : ' + resrun.length + ' ตัวอักษร' + '</span>';
        return strAfterChange;
    }

    //----------------------------------------------------------------------
    $scope.editTrialScore = function () {
        $('#notice_std_score').hide();
        $('#success_std_score').hide();
        $scope.changeScore = true;
        $('#std_score').removeAttr('disabled');
        $('#std_score').focus();
    };
    //----------------------------------------------------------------------
    $scope.okEditTrialScore = function () {
        if ($.isNumeric($scope.stdScore) && $scope.stdScore > 0) {
            if ($scope.stdScore <= $scope.thisSheet.full_score) {
                $('#notice_std_score').hide();
                editTrialScore($scope.resSheet.id, $scope.stdScore);
                $('#success_std_score').html('* บันทึกคะแนนสำเร็จ').show();
                $('#std_score').attr('disabled', 'disabled');
                $scope.currentTrialScore = $scope.stdScore;
                $scope.changeScore = false;
                $scope.edited = true;
            } else {
                $('#notice_std_score').html('* กรุณาระบุคะแนนไม่เกินคะแนนเต็มของใบงาน').show();
                $('#std_score').focus();
            }
        } else {
            $('#notice_std_score').html('* กรุณาระบุคะแนนให้ถูกต้อง').show();
            $('#std_score').focus();
        }
    };
    //----------------------------------------------------------------------
    $scope.cancelEditTrialScore = function () {
        $('#notice_std_score').hide();
        $('#success_std_score').hide();
        $('#std_score').attr('disabled', 'disabled');
        $('#std_score').val($scope.currentTrialScore);
        $scope.changeScore = false;
    };
    //----------------------------------------------------------------------
    $('#res_sheet_modal').on('hidden.bs.modal', function () {
        if ($scope.edited) {
            location.reload();
        }
    });
    //----------------------------------------------------------------------
    $scope.editScoreQuiz = function (id) {
        $('#notice_quiz_score_' + id).hide();
        $('#success_quiz_score_' + id).hide();
        $('#edit_quiz_' + id).css('display', 'none');
        $('#save_quiz_' + id).css('display', 'inline');
        $('#cancel_quiz_' + id).css('display', 'inline');
        $('#quiz_score_' + id).removeAttr('disabled');
        $('#quiz_score_' + id).focus();
    }
    //----------------------------------------------------------------------
    $scope.cancelScoreQuiz = function (id) {
        $('#notice_quiz_score_' + id).hide();
        $('#success_quiz_score_' + id).hide();
        $('#edit_quiz_' + id).css('display', 'block');
        $('#save_quiz_' + id).css('display', 'none');
        $('#cancel_quiz_' + id).css('display', 'none');
        $('#quiz_score_' + id).attr('disabled', 'disabled');
        var indexOfStevie = $scope.resQuiz.findIndex(i => i.quiz_id == id);
        $('#quiz_score_' + id).val($scope.resQuiz[indexOfStevie].score);
    }
    //----------------------------------------------------------------------
    $scope.saveScoreQuiz = function (id) {
        if ($.isNumeric($('#quiz_score_' + id).val()) && $('#quiz_score_' + id).val() > 0) {
            var indexOfStevie = $scope.quizThisSheet.findIndex(i => i.id == id);
            if ($('#quiz_score_' + id).val() <= $scope.quizThisSheet[indexOfStevie].quiz_score) {
                $('#notice_quiz_score_' + id).hide();
                var indexOfResQuiz = $scope.resQuiz.findIndex(i => i.quiz_id == id);
                editQuizScore($scope.resQuiz[indexOfResQuiz].id, $('#quiz_score_' + id).val());
                $('#success_quiz_score_' + id).html('* บันทึกคะแนนสำเร็จ').show();
                $('#quiz_score_' + id).attr('disabled', 'disabled');
                $('#edit_quiz_' + id).css('display', 'block');
                $('#save_quiz_' + id).css('display', 'none');
                $('#cancel_quiz_' + id).css('display', 'none');
                $scope.edited = true;
            } else {
                $('#notice_quiz_score_' + id).html('* กรุณาระบุคะแนนไม่เกินคะแนนเต็มของคำถาม').show();
                $('#quiz_score_' + id).focus();
            }
        } else {
            $('#notice_quiz_score_' + id).html('* กรุณาระบุคะแนนให้ถูกต้อง').show();
            $('#quiz_score_' + id).focus();
        }
    }
    //----------------------------------------------------------------------
    $scope.viewSheet = function () {
        window.open(url + 'detail-sheet-' + $scope.thisSheet.id, '', 'scrollbars=1, width=1000, height=600');
    };
}]);