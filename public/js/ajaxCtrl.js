//--------------------------- UserController ---------------------------
function checkAdmin() {
    var admin = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-find-admin',
        async: false,
    }).responseJSON;
    return admin;
}

function checkUser() {
    var user = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-check-user',
        async: false,
    }).responseJSON;
    return user;
}

function keepHistory(UID,page,time) {
    var user = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-add-history',
        data: { user_id : UID, page : page, time_stamp : time },
        async: false,
    }).responseJSON;
    return user;
}

function findWebHistory() {
    var user = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-find-history',
        async: false,
    }).responseJSON;
    return user;
}

function findAllTeacher() {
    var teachers = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-find-teacher-all',
        async: false,
    }).responseJSON;
    return teachers;
}

function findAllStudent() {
    var students = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-find-student-all',
        async: false,
    }).responseJSON;
    return students;
}

function deleteTeacher(UID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-delete-teacher',
        data: { user_id : UID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_teacher_part').waitMe('hide');
                    $('#delete_teacher_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else {
                    $('#delete_teacher_part').waitMe('hide');
                    $('#delete_teacher_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function deleteStudent(UID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-delete-student',
        data: { user_id : UID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_student_part').waitMe('hide');
                    $('#delete_student_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else {
                    $('#delete_student_part').waitMe('hide');
                    $('#delete_student_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findUserByID(UID) {
    var user = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-find-user-id',
        data: {id:UID},
        async: false,
    }).responseJSON;
    return user;
}

function findMyEvent(UID,UT) {
    var event = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'user-find-event',
        data : { user_id : UID,user_type : UT},
        async: false,
    }).responseJSON;
    return event;
}

//--------------------------- GroupController ---------------------------

function findMyGroup(UID) {
    var groupMyData = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'group-find-group-my',
        data: { user_id : UID },
        async: false,
    }).responseJSON;
    return groupMyData;
}

function addGroup(data) {
    var addGroup = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'group-add-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#add_group_part').waitMe('hide');
                    $('#add_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else if (xhr.status == 209){
                    $('#add_group_part').waitMe('hide');
                    $('#notice_name_add_grp').html('* กลุ่มเรียนนี้มีอยู่แล้ว').show();
                    $('[ng-model=groupName]').focus();
                } else {
                    $('#add_group_part').waitMe('hide');
                    $('#add_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    }).responseJSON;
    return addGroup;
}

function editGroup(data) {
    var editGroup = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'group-edit-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#edit_group_part').waitMe('hide');
                    $('#edit_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else if (xhr.status == 209){
                    $('#edit_group_part').waitMe('hide');
                    $('#notice_name_edit_grp').html('* กลุ่มเรียนนี้มีอยู่แล้ว').show();
                    $('[ng-model=groupName]').focus();
                } else {
                    $('#edit_group_part').waitMe('hide');
                    $('#edit_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    }).responseJSON;
    return editGroup;
}

function deleteGroup(GID) {
    var deleteGroup =$.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'group-delete-group',
        data: {id : GID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_group_part').waitMe('hide');
                    $('#delete_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else {
                    $('#delete_group_part').waitMe('hide');
                    $('#delete_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    }).responseJSON;
    return deleteGroup
}

function findAllGroup() {
    var allGroup = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'group-find-group-all',
        async: false,
    }).responseJSON;
    return allGroup;
}

function checkJoinGroup(UID,GID) {
    var checker = true;
    var test = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'group-check-join',
        data:{user_id:UID,group_id:GID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    checker = true;
                }  else {
                    checker = false;
                }
            }
        }
    });
    return checker;
}

function createJoinGroup(UID,GID,status) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'group-add-join',
        data:{user_id:UID,group_id:GID,status:status},
        async: false,
    });
}

function findMyJoinGroup(UID) {
    var myGroup = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'group-find-join-my',
        data:{user_id:UID},
        async: false,
    }).responseJSON;
    return myGroup
}

function exitGroup(UID,GID,UT) {
    var exit = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'group-delete-join',
        data:{
            user_id:UID,
            group_id:GID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    if(UT === 's'){
                        window.location.href = url+'student-group-all';
                    }else if(UT === 't'){
                        window.location.href = url+'teacher-group-all';
                    }
                }  else {
                    $('#exit_group_part').waitMe('hide');
                    alert("ผิดพลาด");
                }
            }
        }
    }).responseJSON;
    return exit;
}

function findGroupDataByID(id) {
    var groupData = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'group-find-group-id',
        data: {id: id},
        async: false,
    }).responseJSON;
    return groupData;

}

function findMemberGroup(GID) {
    var member = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'group-find-join-member',
        data:{group_id:GID},
        async: false,
    }).responseJSON;
    return member;
}

function managePermissions(data) {
    var JG = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'group-edit-join-permission',
        data:data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    location.reload();
                }  else {
                    alert("ผิดพลาด");
                }
            }
        }
    }).responseJSON;
    return JG;
};

function findMyPermissionsInGroup(UID,GID) {
    var myGroup = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'group-find-permission-my',
        data:{user_id:UID,group_id:GID},
        async: false,
    }).responseJSON;
    return myGroup
}

//--------------------------- ExamController ---------------------------

function findMyExamGroup(UID) {
    var examGroup = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-find-group-my',
        data:{user_id : UID},
        async: false,
    }).responseJSON;
    return examGroup;
}

function createExamGroup(data) {
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-add-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#add_exam_group_part').waitMe('hide');
                    $('#add_exam_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else if (xhr.status == 209){
                    $('#add_exam_group_part').waitMe('hide');
                    $('#notice_add_exam_grp').html('* กลุ่มข้อสอบนี้มีอยู่แล้ว').show();
                    $('[ng-model=examGroupName]').focus();
                } else {
                    $('#add_exam_group_part').waitMe('hide');
                    $('#add_exam_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findExamByEGID(EGID) {
    var exams = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-find-exam-egid',
        data: {exam_group_id : EGID},
        async: false,
    }).responseJSON;
    return exams;
}

function findExamByName(name,EGID,UID) {
    var checked = false;
    var test = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + '/exam-find-exam-name',
        data: {
            exam_name :name,
            user_id :UID,
            exam_group_id:EGID
        },
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    checked = true;
                }
            }
        }
    }).responseJSON;
    return checked;
}

function createExam(data) {
    var createExamSuccess = false;
    var exam = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-add-exam',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    createExamSuccess = true;
                } else {
                    $('#add_exam_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    }).responseJSON;
    if (createExamSuccess) {
        for (i = 0; i < data.keyword.length; i++) {
            createKeyword(exam.id, data.keyword[i]);
        }
        createSharedExam(exam.id,data.user_id);
        for (i = 0; i < data.shared.length; i++) {
            createSharedExam(exam.id, data.shared[i].id);
        }
        $('#add_exam_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }
}

function createKeyword(examID,keyword) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-add-keyword',
        data:{exam_id:examID,keyword:keyword},
        async: false,
    });

}

function createSharedExam(examID,userID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-add-share',
        data:{exam_id:examID,user_id:userID},
        async: false,
    });
}

function editExamGroup(data) {
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-edit-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#edit_exam_group_part').waitMe('hide');
                    $('#edit_exam_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else if (xhr.status == 209){
                    $('#edit_exam_group_part').waitMe('hide');
                    $('#notice_edit_exam_grp').html('* กลุ่มข้อสอบนี้มีอยู่แล้ว').show();
                    $('[ng-model=examGroupName]').focus();
                } else {
                    $('#edit_exam_group_part').waitMe('hide');
                    $('#edit_exam_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function deleteExamGroup(data) {
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-delete-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_exam_group_part').waitMe('hide');
                    $('#delete_exam_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                }  else {
                    $('#delete_exam_group_part').waitMe('hide');
                    $('#delete_exam_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findKeywordByEID(EID) {
    var keyword = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-find-keyword-eid',
        data:{exam_id:EID},
        async: false,
    }).responseJSON;
    return keyword;
}

function readFileEx(data) {
    var test = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-read-file',
        data: data,
        async: false,
    }).responseJSON;
    return test;
}

function deleteExam(EID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-delete-exam',
        data: {exam_id: EID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_exam_part').waitMe('hide');
                    $('#delete_exam_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else {
                    $('#delete_exam_part').waitMe('hide');
                    $('#delete_exam_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findExamByID(ID) {
    var exam = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-find-exam-id',
        data: {id : ID},
        async: false,
    }).responseJSON;
    return exam;
}

function findSharedUserNotMe(EID,MyID) {
    var shared = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-find-share-not',
        data:{exam_id:EID,my_id:MyID},
        async: false,
    }).responseJSON;
    return shared;
}

function editExam(data) {
    var createExamSuccess = false;
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-edit-exam',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    createExamSuccess = true;
                } else {
                    $('#edit_exam_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    });
    if (createExamSuccess) {
        for (i = 0; i < data.keyword.length; i++) {
            createKeyword(data.id, data.keyword[i]);
        }
        for (i =0; i < data.deleteShared.length;i++){
            deleteUserShared(data.id,data.deleteShared[i]);
        }
        for (i = 0; i < data.shared.length;i++){
            updateSharedExam(data.id,data.shared[i].id);
        }
        $('#edit_exam_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }
}

function deleteUserShared(EID,UID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-delete-share',
        data:{exam_id:EID,user_id:UID},
        async: false,
    });
}

function updateSharedExam(examID,userID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-edit-share',
        data:{exam_id:examID,user_id:userID},
        async: false,
    });
}

function findExamGroupSharedToMe(MyID) {
    var EGSharedNotMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-find-group-share-me',
        data:{my_id:MyID},
        async: false,
    }).responseJSON;
    return EGSharedNotMe
}

function findExamGroupSharedNotMe(MyID) {
    var EGSharedNotMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-find-group-share-not',
        data:{my_id:MyID},
        async: false,
    }).responseJSON;
    return EGSharedNotMe
}

function findAllExamSharedToMe(UID) {
    var examSharedToMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-find-exam-share-all',
        data:{user_id : UID},
        async: false,
    }).responseJSON;
    return examSharedToMe
}

function findExamSharedToMe(UID,EGID) {
    var examSharedToMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'exam-find-exam-share-me',
        data:{user_id : UID, exam_group_id : EGID},
        async: false,
    }).responseJSON;
    return examSharedToMe
}

function readExamContent(data) {
    var test = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'exam-read-content',
        data: data,
        async: false,
    }).responseJSON;
    return test;
}

//--------------------------- ExamingController ---------------------------

function findExamingByNameAndGroup(name,GID) {
    var checked = false;
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examing-name',
        data:{examing_name:name,group_id:GID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    checked = true;
                }
            }
        }
    });
    return checked;
}

function createExaming(data) {
    var createExamingSuccess = false;
    var examing = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-add-examing',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    createExamingSuccess = true;
                } else {
                    $('#open_exam_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    }).responseJSON;
    if (createExamingSuccess) {
        for(i=0;i<data.exam.length;i++){
            createExamExaming(parseInt(data.exam[i]),examing.id);
        }
        $('#add_exam_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }

}

function createExamExaming(examID,examingID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-add-examexaming',
        data:{exam_id:examID,examing_id:examingID},
        async: false,
    });
}

function findExamingByUserID(UID) {
    var examing = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examing-uid',
        data:{user_id:UID},
        async: false,
    }).responseJSON;
    return examing;
}

function findExamExamingByExamingID(EMID) {
    var examExaming =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examexaming-emid',
        data:{examing_id:EMID},
        async: false,
    }).responseJSON;
    return examExaming
}

function findExamingByID(EMID) {
    var examing = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examing-id',
        data:{id:EMID},
        async: false,
    }).responseJSON;
    return examing;
}

function updateExaming(data) {
    var updateExamingSuccess = false;
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-edit-examing',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    updateExamingSuccess = true;
                } else {
                    $('#edit_examing_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    });
    if (updateExamingSuccess) {
        for(i=0;i<data.deleteExamExaming.length;i++){
            daleteExamExaming(data.deleteExamExaming[i],data.id) ;
        }
        for(i=0;i<data.exam.length;i++){
            updateExamExaming(data.exam[i],data.id);
        }
        $('#edit_examing_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }

}

function daleteExamExaming(examID,examingID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-delete-examexaming',
        data:{exam_id:examID,examing_id:examingID},
        async: false,
    });

}

function updateExamExaming(examID,examingID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-edit-examexaming',
        data:{exam_id:examID,examing_id:examingID},
        async: false,
    });
}

function deleteExaming(EMID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-delete-examing',
        data:{ id : EMID },
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_part').waitMe('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                }  else {
                    $('#delete_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findExamingItsComing(GID) {
    var examing = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examing-teacher-come',
        data:{group_id:GID},
        async: false,
    }).responseJSON;
    return examing;
}

function findSTDExamingItsComing(GID) {
    var examing = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examing-student-come',
        data:{group_id:GID},
        async: false,
    }).responseJSON;
    return examing;
}

function findExamingItsEnding(GID) {
    var examing = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examing-end',
        data:{group_id:GID},
        async: false,
    }).responseJSON;
    return examing;
}

function findExamRandomByUID(UID,EMID) {
    var examRandom = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-find-random-uid',
        data:{user_id:UID,examing_id:EMID},
        async: false,
    }).responseJSON;
    return examRandom;
}

function addRandomExam(data) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-add-random',
        data : data,
        async: false,
    });
}

function findExamExamingInViewExam(EMID,UID) {
    var examExaming =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'examing-find-examexaming-view',
        data:{examing_id:EMID,user_id:UID},
        async: false,
    }).responseJSON;
    return examExaming
}

function findExamRandomInViewExam(EXID,UID) {
    var examRandom = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-find-random-view',
        data:{examing_id:EXID,user_id:UID},
        async: false,
    }).responseJSON;
    return examRandom;
}

function findExamInScoreboard(EXID) {
    var examScoreboard = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-find-exam-scoreboard',
        data:{examing_id:EXID},
        async: false,
    }).responseJSON;
    return examScoreboard;
}

function dataInScoreboard(data) {
    var scoreBoard = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-find-data-scoreboard',
        data:data,
        async: false,
    }).responseJSON;
    return scoreBoard;
}

function findMySendExamHistory(UID,EMID) {
    var examScoreboard = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-find-history-exam',
        data:{user_id:UID,examing_id:EMID},
        async: false,
    }).responseJSON;
    return examScoreboard;
}

function readFileResRun(path) {
    var resrun = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-read-run',
        data: {path:path},
        async: false,
    }).responseJSON;
    return resrun;
}

function getCode(path) {
    var code = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-get-code',
        data:{path:path},
        async: false,
    }).responseJSON;
    return code;
}

function findPathExamByResExamID(REID) {
    var examScoreboard = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-find-path-reid',
        data:{res_exam_id:REID},
        async: false,
    }).responseJSON;
    return examScoreboard;
}

function editScore(REID,score){
    var editScore = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'examing-edit-score',
        data:{
            res_exam_id:REID,
            score : score
        },
        async: false,
    }).responseJSON;
    return editScore;
}

//--------------------------- SheetController ---------------------------

function findMySheetGroup(UID) {
    var dataSheet = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-find-group-my',
        data: {user_id : UID},
        async: false,
    }).responseJSON;
    return dataSheet;
}

function createSheetGroup(data) {
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-add-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#add_sheet_group_part').waitMe('hide');
                    $('#add_sheet_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else if (xhr.status == 209){
                    $('#add_sheet_group_part').waitMe('hide');
                    $('#notice_add_sheet_grp').html('* กลุ่มใบงานนี้มีอยู่แล้ว').show();
                    $('[ng-model=sheetGroupName]').focus();
                } else {
                    $('#add_sheet_group_part').waitMe('hide');
                    $('#add_sheet_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findSheetByEGID(SGID) {
    var sheets = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-find-sheet-sgid',
        data: {sheet_group_id : SGID},
        async: false,
    }).responseJSON;
    return sheets;
}

function editSheetGroup(data) {
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-edit-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#edit_sheet_group_part').waitMe('hide');
                    $('#edit_sheet_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else if (xhr.status == 209){
                    $('#edit_sheet_group_part').waitMe('hide');
                    $('#notice_edit_sheet_grp').html('* กลุ่มใบงานนี้มีอยู่แล้ว').show();
                    $('[ng-model=sheetGroupName]').focus();
                } else {
                    $('#edit_sheet_group_part').waitMe('hide');
                    $('#edit_sheet_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function deleteSheetGroup(data) {
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-delete-group',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_sheet_group_part').waitMe('hide');
                    $('#delete_sheet_group_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                }  else {
                    $('#delete_sheet_group_part').waitMe('hide');
                    $('#delete_sheet_group_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function createSheet(data) {
    var createSheetSuccess = false;
    var sheet = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-add-sheet',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    createSheetSuccess = true;
                } else {
                    $('#add_sheet_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    }).responseJSON;
    if (createSheetSuccess) {
        for (i = 0; i < data.quiz.length; i++) {
            createQuiz(sheet.id, data.quiz[i]);
        }
        createSharedSheet(sheet.id,data.user_id);
        for (i = 0; i < data.shared.length; i++) {
            createSharedSheet(sheet.id, data.shared[i].id);
        }
        $('#add_sheet_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }

}

function createQuiz(sheetID,quiz) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-add-quiz',
        data:{
            sheet_id:sheetID,
            quiz_data:quiz.quiz,
            quiz_ans:quiz.answer,
            quiz_score:quiz.score,
        },
        async: false,
    });

}

function createSharedSheet(sheetID,userID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-add-shear',
        data:{sheet_id:sheetID,user_id:userID},
        async: false,
    });
}

function findSheetByName(data,sheet_group_id,user_id) {
    var checked = false;
    var test = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-find-sheet-name',
        data: {
            sheet_name :data,
            user_id :user_id,
            sheet_group_id:sheet_group_id
        },
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    checked = true;
                }
            }
        }
    }).responseJSON;
    return checked;
}

function findSheetByID(id) {
    var sheet = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-find-sheet-id',
        data : { id : id},
        async: false,
    }).responseJSON;
    return sheet;
}

function findQuizBySID(SID) {
    var quiz =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-find-quiz-sid',
        data:{sheet_id:SID},
        async: false,
    }).responseJSON;
    return quiz;
}

function findSheetSharedUserNotMe(SHID,MyID) {
    var shared = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-find-share-not',
        data:{sheet_id:SHID,my_id:MyID},
        async: false,
    }).responseJSON;
    return shared;
}

function readFileSh(data) {
    var test = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-read-file',
        data: data,
        async: false,
    }).responseJSON;
    return test;
}

function editSheet(data) {
    var createSheetSuccess = false;
    var sheet = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-edit-sheet',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    createSheetSuccess = true;
                } else {
                    $('#edit_sheet_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    }).responseJSON;
    if (createSheetSuccess) {
        for (i = 0; i < data.quiz.length; i++) {
            createQuiz(data.id, data.quiz[i]);
        }
        for (i =0; i < data.deleteShared.length;i++){
            deleteUserSharedSheet(data.id,data.deleteShared[i]);
        }
        for (i = 0; i < data.shared.length; i++) {
            updateSharedSheet(data.id, data.shared[i].id);
        }
        $('#edit_sheet_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }

}

function deleteUserSharedSheet(SHID,UID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-delete-shared',
        data:{sheet_id:SHID,user_id:UID},
        async: false,
    });
}

function updateSharedSheet(SHID,userID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-edit-share',
        data:{sheet_id:SHID,user_id:userID},
        async: false,
    });
}

function deleteSheet(ID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheet-delete-sheet',
        data :{ id : ID },
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_sheet_part').waitMe('hide');
                    $('#delete_sheet_modal').modal('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                } else {
                    $('#delete_sheet_part').waitMe('hide');
                    $('#delete_sheet_modal').modal('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findSheetGroupSharedNotMe(MyID) {
    var sheetGroupSharedNotMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-find-group-share-not',
        data:{my_id:MyID},
        async: false,
    }).responseJSON;
    return sheetGroupSharedNotMe
}

function findSheetSharedToMe(UID,SGID) {
    var sheetSharedToMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-find-sheet-share-me',
        data:{user_id : UID, sheet_group_id : SGID},
        async: false,
    }).responseJSON;
    return sheetSharedToMe
}

function findSheetGroupSharedToMe(MyID) {
    var sheetGroupSharedToMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-find-group-share-me',
        data:{my_id:MyID},
        async: false,
    }).responseJSON;
    return sheetGroupSharedToMe
}

function findAllSheetSharedToMe(MyID) {
    var sheetSharedToMe = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheet-find-sheet-share-all',
        data:{my_id:MyID},
        async: false,
    }).responseJSON;
    return sheetSharedToMe
}

//--------------------------- SheetingController ---------------------------

function findSheetingByNameAndGroup(name,GID) {
    var checked = false;
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-sheeting-name',
        data:{sheeting_name:name,sheet_group_id:GID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    checked = true;
                }
            }
        }
    });
    return checked;
}

function createSheeting(data) {
    var createSheetingSuccess = false;
    var sheeting = $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheeting-add-sheeting',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    createSheetingSuccess = true;
                } else {
                    $('#add_sheeting_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    }).responseJSON;
    if (createSheetingSuccess) {
        for(i=0;i<data.sheet.length;i++){
            createSheetSheeting(parseInt(data.sheet[i]),sheeting.id);
        }
        $('#add_sheeting_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }

}

function createSheetSheeting(sheetID,sheetingID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-add-sheetsheeting',
        data:{sheet_id:sheetID,sheeting_id:sheetingID},
        async: false,
    });
}

function findSheetingByUserID(UID) {
    var sheeting = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-sheeting-uid',
        data:{user_id:UID},
        async: false,
    }).responseJSON;
    return sheeting;
}

function deleteSheeting(STID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-delete-sheeting',
        data:{id:STID},
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#delete_part').waitMe('hide');
                    $('#success_modal').modal({backdrop: 'static'});
                }  else {
                    $('#delete_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }
            }
        }
    });
}

function findSheetingByGroupID(GID) {
    var sheeting = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + '/sheeting-find-sheeting-gid',
        data:{group_id:GID},
        async: false
    }).responseJSON;
    return sheeting;
}

function findSheetSheetingBySheetingID(STID) {
    var sheetSheeting =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-sheetsheeting-stid',
        data:{sheeting_id:STID},
        async: false,
    }).responseJSON;
    return sheetSheeting
}

function findSheetingByID(STID) {
    var sheeting =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-sheeting-id',
        data:{id:STID},
        async: false,
    }).responseJSON;
    return sheeting
}

function updateSheeting(data) {
    var updateSheetingSuccess = false;
    $.ajax ({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheeting-edit-sheeting',
        data: data,
        async: false,
        complete: function (xhr) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    updateSheetingSuccess = true;
                } else {
                    $('#edit_sheeting_part').waitMe('hide');
                    $('#unsuccess_modal').modal({backdrop: 'static'});
                }

            }
        }
    });
    if (updateSheetingSuccess) {
        for(i=0;i<data.deleteSheetSheeting.length;i++){
            deleteSheetSheeting(data.deleteSheetSheeting[i],data.id) ;
        }
        for(i=0;i<data.sheet.length;i++){
            updateSheetSheeting(data.sheet[i],data.id);
        }
        $('#edit_sheeting_part').waitMe('hide');
        $('#success_modal').modal({backdrop: 'static'});
    }

}

function deleteSheetSheeting(sheetID,sheetingID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-delete-sheetsheeting',
        data:{sheet_id:sheetID,sheeting_id:sheetingID},
        async: false,
    });
}

function updateSheetSheeting(sheetID,sheetingID) {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-edit-sheetsheeting',
        data:{sheet_id:sheetID,sheeting_id:sheetingID},
        async: false,
    });
}

function findSTDSheetingByGroupID(GID) {
    var sheeting = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-sheeting-std',
        data:{group_id:GID},
        async: false
    }).responseJSON;
    return sheeting;
}

function findSheetSheetingInViewSheet(STID,UID) {
    var sheetSheeting =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-sheetsheeting-view',
        data:{sheeting_id:STID,user_id:UID},
        async: false,
    }).responseJSON;
    return sheetSheeting
}

function findOldCodeInResSheet(STID,SID,UID) {
    var resSheet =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-ressheet-old',
        data:{sheeting_id:STID,sheet_id:SID,user_id:UID},
        async: false,
    }).responseJSON;
    return resSheet
}

function findResQuizByRSID(RSID) {
    var resQuiz =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-resquiz-rsid',
        data:{res_sheet_id:RSID},
        async: false,
    }).responseJSON;
    return resQuiz;
}

function findSheetSheetingInSheetBoard(STID) {
    var sheetSheeting =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-sheetsheeting-board',
        data:{sheeting_id:STID},
        async: false,
    }).responseJSON;
    return sheetSheeting
}

function dataInSheetBoard(GID,SID,STID) {
    var data =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-data-board',
        data:{
            group_id:GID,
            sheet_id:SID,
            sheeting_id:STID,
        },
        async: false
    }).responseJSON;
    return data;
}

function findResSheetByID(RSID) {
    var data =  $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url:url + 'sheeting-find-ressheet-id',
        data:{
            res_sheet_id:RSID,
        },
        async: false
    }).responseJSON;
    return data;
}

function editTrialScore(RSID,score){
    var editTrialScore = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheeting-edit-score-trial',
        data:{
            res_sheet_id:RSID,
            score : score
        },
        async: false,
    }).responseJSON;
    return editTrialScore;
}

function editQuizScore(RQID,score){
    var editQuizScore = $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            Accept: "application/json"
        },
        url: url + 'sheeting-edit-score-quiz',
        data:{
            id:RQID,
            score : score
        },
        async: false,
    }).responseJSON;
    return editQuizScore;
}