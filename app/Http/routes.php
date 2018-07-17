<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//--------------------------- Pages ---------------------------

Route::get('/', function () {
    return view('pages/login');
});

Route::get('/admin-list-teacher', function () {
    return view('pages/admin/teaList');
});

Route::get('/admin-list-personnel', function () {
    return view('pages/admin/psnList');
});

Route::get('/admin-list-student', function () {
    return view('pages/admin/stdList');
});

Route::get('/home', function () {
    return view('pages/home');
});

Route::get('/profile', function () {
    return view('pages/profile');
});

Route::get('/detail-exam-{id}', function ($id) {
    $data = array(
        'examID' => $id
    );
    return view('pages/detailExam', $data);
});

Route::get('/detail-sheet-{id}', function ($id) {
    $data = array(
        'sheetID' => $id
    );
    return view('pages/detailSheet', $data);
});

Route::get('/get-now', function () {
    return response()->json(date("Y-m-d H:i:s"));
});

// -------- Teacher --------

Route::get('/teacher-group-my', function () {
    return view('pages/teacher/teaMyGroup');
});

Route::get('/teacher-group-my-in-{id}', function ($id) {
    $data = array(
        'groupID' => $id
    );
    return view('pages/teacher/teaInGroup', $data);
});

Route::get('/teacher-group-other-in-{id}', function ($id) {
    $data = array(
        'groupID' => $id
    );
    return view('pages/student/stdInGroup',$data);
});

Route::get('/teacher-group-all', function () {
    return view('pages/student/stdAllGroup');
});

Route::get('/teacher-group-join', function () {
    return view('pages/student/stdMyGroup');
});

Route::get('/teacher-exam-my', function () {
    return view('pages/teacher/teaMyExam');
});

Route::get('/teacher-exam-share', function () {
    return view('pages/teacher/teaShareExam');
});

Route::get('/teacher-exam-add-{id}', function ($id) {
    $data = array(
        'groupID' => $id
    );
    return view('pages/teacher/teaAddExam',$data);
});

Route::get('/teacher-exam-edit-{id}', function ($id) {
    $data = array(
        'examID' => $id
    );
    return view('pages/teacher/teaEditExam',$data);
});

Route::get('/teacher-exam-copy-{id}', function ($id) {
    $data = array(
        'examID' => $id
    );
    return view('pages/teacher/teaCopyExam',$data);
});

Route::get('/teacher-examing-add', function () {
    return view('pages/teacher/teaAddExaming');
});

Route::get('/teacher-examing-edit-{id}', function ($id) {
    $data = array(
        'examingID' => $id
    );
    return view('pages/teacher/teaEditExaming',$data);
});

Route::get('/teacher-examing-history', function () {
    return view('pages/teacher/teaExamingHistory');
});

Route::get('/teacher-board-exam-{id}', function ($id) {
    $data = array(
        'examingID' => $id
    );
    return view('pages/teacher/teaPointBoard',$data);
});

Route::get('/teacher-sheet-my', function () {
    return view('pages/teacher/teaMySheet');
});

Route::get('/teacher-sheet-share', function () {
    return view('pages/teacher/teaShareSheet');
});

Route::get('/teacher-sheet-add-{id}', function ($id) {
    $data = array(
        'groupID' => $id
    );
    return view('pages/teacher/teaAddSheet',$data);
});

Route::get('/teacher-sheet-edit-{id}', function ($id) {
    $data = array(
        'sheetID' => $id
    );
    return view('pages/teacher/teaEditSheet',$data);
});

Route::get('/teacher-sheet-copy-{id}', function ($id) {
    $data = array(
        'sheetID' => $id
    );
    return view('pages/teacher/teaCopySheet',$data);
});

Route::get('/teacher-sheeting-add', function () {
    return view('pages/teacher/teaAddSheeting');
});

Route::get('/teacher-sheeting-edit-{id}', function ($id) {
    $data = array(
        'sheetingID' => $id
    );
    return view('pages/teacher/teaEditSheeting',$data);
});

Route::get('/teacher-sheeting-history', function () {
    return view('pages/teacher/teaSheetingHistory');
});

Route::get('/teacher-board-sheet-{id}', function ($id) {
    $data = array(
        'sheetingID' => $id
    );
    return view('pages/teacher/teaSheetBoard',$data);
});

// -------- Student --------

Route::get('/student-group-all', function () {
    return view('pages/student/stdAllGroup');
});

Route::get('/student-group-my', function () {
    return view('pages/student/stdMyGroup');
});

Route::get('/student-group-in-{id}', function ($id) {
    $data = array(
        'groupID' => $id
    );
    return view('pages/student/stdInGroup',$data);
});

Route::get('/student-examing-doing-{id}', function ($id) {
    $data = array(
        'examingID' => $id
    );
    return view('pages/student/stdViewExam',$data);
});

Route::get('/student-board-exam-{id}', function ($id) {
    $data = array(
        'examingID' => $id
    );
    return view('pages/student/stdPointBoard',$data);
});

Route::get('/student-sheeting-doing-{id}', function ($id) {
    $data = array(
        'sheetingID' => $id
    );
    return view('pages/student/stdViewSheet',$data);
});

Route::get('/student-board-sheet-{id}', function ($id) {
    $data = array(
        'sheetingID' => $id
    );
    return view('pages/student/stdSheetBoard',$data);
});

//--------------------------- UserController ---------------------------

Route::get('/user-login-admin', 'UserController@loginAdmin');

Route::get('/user-login-user','UserController@loggedIn' );

Route::get('/user-logout-admin', 'UserController@adminLogout');

Route::get('/user-logout-user', 'UserController@userLogOut');

Route::get('/user-add-history','UserController@keepHistory' );

Route::get('/user-find-admin', 'UserController@findAdmin');

Route::get('/user-find-history','UserController@findWebHistory' );

Route::get('/user-find-teacher-all', 'UserController@findAllTeacher');

Route::get('/user-find-student-all', 'UserController@findAllStudent');

Route::get('/user-find-personnel-all', 'UserController@findAllPersonnel');

Route::get('/user-find-user-id', 'UserController@findUserByID');

Route::get('/user-find-event','UserController@findMyEvent' );

Route::get('/user-delete-teacher', 'UserController@deleteTeacher');

Route::get('/user-delete-student', 'UserController@deleteStudent');

Route::get('/user-delete-personnel', 'UserController@deletePersonnel');

Route::get('/user-check-user','UserController@checkUser' );

Route::get('/user-will-delete-teacher','UserController@teacherWillBeDelete' );

Route::get('/user-will-delete-student','UserController@studentWillBeDelete' );

Route::get('/user-manual-teacher','UserController@downloadManualTeacher' );

Route::get('/user-manual-student','UserController@downloadManualStudent' );

Route::get('/user-manual-other','UserController@downloadManualOther' );

//--------------------------- GroupController ---------------------------

Route::get('/group-add-group', 'GroupController@addGroup');

Route::get('/group-add-join', 'GroupController@createJoinGroup');

Route::get('/group-find-group-my', 'GroupController@findMyGroup');

Route::get('/group-find-group-all', 'GroupController@findAllGroup');

Route::get('/group-find-group-id', 'GroupController@findGroupDataByID');

Route::get('/group-find-join-my', 'GroupController@findMyJoinGroup');

Route::get('/group-find-join-member', 'GroupController@findMemberGroup');

Route::get('/group-find-permission-my', 'GroupController@findMyPermissionsInGroup');

Route::get('/group-edit-group', 'GroupController@editGroup');

Route::get('/group-edit-join-permission', 'GroupController@managePermissions');

Route::get('/group-delete-group', 'GroupController@deleteGroup');

Route::get('/group-delete-join', 'GroupController@exitGroup');

Route::get('/group-check-join', 'GroupController@checkJoinGroup');

//--------------------------- ExamController ---------------------------

Route::get('/exam-find-group-my', 'ExamController@findMyExamGroup');

Route::get('/exam-find-group-share-me', 'ExamController@findExamGroupSharedToMe');

Route::get('/exam-find-group-share-not', 'ExamController@findExamGroupSharedNotMe');

Route::get('/exam-find-exam-id', 'ExamController@findExamByID');

Route::get('/exam-find-exam-egid', 'ExamController@findExamByEGID');

Route::get('/exam-find-exam-share-all', 'ExamController@findAllExamSharedToMe');

Route::get('/exam-find-exam-share-me', 'ExamController@findExamSharedToMe');

Route::get('/exam-find-exam-name', 'ExamController@checkExamByName');

Route::get('/exam-find-keyword-eid', 'ExamController@findKeywordByEID');

Route::get('/exam-find-share-not', 'ExamController@findSharedUserNotMe');

Route::get('/exam-add-group', 'ExamController@addExamGroup');

Route::get('/exam-add-exam', 'ExamController@addExam');

Route::get('/exam-add-keyword', 'ExamController@addKeyword');

Route::get('/exam-add-share', 'ExamController@addShareExam');

Route::get('/exam-edit-group', 'ExamController@editExamGroup');

Route::get('/exam-edit-exam', 'ExamController@editExam');

Route::get('/exam-edit-keyword', 'ExamController@editKeyword');

Route::get('/exam-edit-share', 'ExamController@editShareExam');

Route::get('/exam-delete-group', 'ExamController@deleteExamGroup');

Route::get('/exam-delete-exam', 'ExamController@deleteExam');

Route::get('/exam-delete-keyword', 'ExamController@deleteKeyword');

Route::get('/exam-delete-share', 'ExamController@deleteShareExam');

Route::get('/exam-read-file', 'ExamController@readFileEx');

Route::get('/exam-read-content', 'ExamController@readExamContent');

Route::post('/uploadFile/{path}', 'ExamController@uploadFile');

//--------------------------- ExamingController ---------------------------

Route::get('/examing-find-examing-name', 'ExamingController@findExamingByNameAndGroup');

Route::get('/examing-find-examing-id', 'ExamingController@findExamingByID');

Route::get('/examing-find-examing-uid', 'ExamingController@findExamingByUserID');

Route::get('/examing-find-examing-uid-gid', 'ExamingController@findExamingsByUserIDAndGroup');

Route::get('/examing-find-examing-teacher-come', 'ExamingController@findExamingItsComing');

Route::get('/examing-find-examing-student-come', 'ExamingController@findSTDExamingItsComing');

Route::get('/examing-find-examing-end', 'ExamingController@findExamingItsEnding');

Route::get('/examing-find-examexaming-emid', 'ExamingController@findExamExamingByExamingID');

Route::get('/examing-find-examexaming-view', 'ExamingController@findExamExamingInViewExam');

Route::get('/examing-find-random-view', 'ExamingController@findExamRandomInViewExam');

Route::get('/examing-find-random-uid', 'ExamingController@findExamRandomByUID');

Route::get('/examing-find-path-reid', 'ExamingController@findPathExamByResExamID');

Route::get('/examing-find-exam-scoreboard', 'ExamingController@examInScoreboard');

Route::get('/examing-find-data-scoreboard', 'ExamingController@dataInScoreboard');

Route::get('/examing-find-history-exam', 'ExamingController@findMySendExamHistory');

Route::get('/examing-add-examing', 'ExamingController@createExaming');

Route::get('/examing-add-examexaming', 'ExamingController@createExamExaming');

Route::get('/examing-add-random', 'ExamingController@addRandomExam');

Route::get('/examing-edit-examing', 'ExamingController@editExaming');

Route::get('/examing-edit-examexaming', 'ExamingController@editExamExaming');

Route::get('/examing-edit-score', 'ExamingController@editScore');

Route::get('/examing-delete-examing', 'ExamingController@deleteExaming');

Route::get('/examing-delete-examexaming', 'ExamingController@deleteExamExaming');

Route::get('/examing-delete-queue-exam', 'ExamingController@deleteFirstQueueEx');

Route::get('/examing-check-ip', 'ExamingController@checkIP');

Route::get('/examing-check-queue-exam','ExamingController@checkQueueEx');

Route::get('/examing-change-hidden','ExamingController@changeHiddenExaming');

Route::get('/examing-change-history','ExamingController@changeHistoryExaming');

Route::get('/examing-read-run','ExamingController@readFileResRun');

Route::get('/examing-get-code','ExamingController@getCode');

Route::post('/uploadExamFile/{EMID}/{EID}/{UID}','ExamingController@uploadExamFile');

//--------------------------- CompileCController ---------------------------

Route::post('/c-send-exam', 'CompileCController@sendExamC');

Route::post('/c-send-sheet', 'CompileCController@sendSheetC');

Route::get('/c-compile', 'CompileCController@compileAndRunC');

//--------------------------- CompileCppController ---------------------------

Route::post('/cpp-send-exam', 'CompileCppController@sendExamCpp');

Route::post('/cpp-send-sheet', 'CompileCppController@sendSheetCpp');

Route::get('/cpp-compile', 'CompileCppController@compileAndRunCpp');

//--------------------------- CompileJavaController ---------------------------

Route::post('/java-send-exam', 'CompileJavaController@sendExamJava');

Route::post('/java-send-sheet', 'CompileJavaController@sendSheetJava');

Route::get('/java-compile', 'CompileJavaController@compileAndRunJava');

//--------------------------- CompileCsController ---------------------------

Route::post('/cs-send-exam', 'CompileCsController@sendExamCs');

Route::post('/cs-send-sheet', 'CompileCsController@sendSheetCs');

Route::get('/cs-compile', 'CompileCsController@compileAndRunCs');

Route::get('/cs-create-bat', 'CompileCsController@createBatFile');

//--------------------------- SheetController ---------------------------

Route::get('/sheet-find-group-my', 'SheetController@findMySheetGroup');

Route::get('/sheet-find-group-share-not', 'SheetController@findSheetGroupSharedNotMe');

Route::get('/sheet-find-group-share-me', 'SheetController@findSheetGroupSharedToMe');

Route::get('/sheet-find-sheet-id', 'SheetController@findSheetByID');

Route::get('/sheet-find-sheet-sgid', 'SheetController@findSheetBySGID');

Route::get('/sheet-find-sheet-name', 'SheetController@findSheetByName');

Route::get('/sheet-find-sheet-share-me', 'SheetController@findSheetSharedToMe');

Route::get('/sheet-find-sheet-share-all', 'SheetController@findAllSheetSharedToMe');

Route::get('/sheet-find-quiz-sid', 'SheetController@findQuizBySID');

Route::get('/sheet-find-share-not', 'SheetController@findSheetSharedUserNotMe');

Route::get('/sheet-add-group', 'SheetController@addSheetGroup');

Route::get('/sheet-add-sheet', 'SheetController@addSheet');

Route::get('/sheet-add-quiz', 'SheetController@addQuiz');

Route::get('/sheet-add-shear', 'SheetController@addShareSheet');

Route::get('/sheet-edit-group', 'SheetController@editSheetGroup');

Route::get('/sheet-edit-sheet', 'SheetController@editSheet');

Route::get('/sheet-edit-quiz', 'SheetController@editQuiz');

Route::get('/sheet-edit-share', 'SheetController@updateSharedSheet');

Route::get('/sheet-delete-group', 'SheetController@deleteSheetGroup');

Route::get('/sheet-delete-sheet', 'SheetController@deleteSheet');

Route::get('/sheet-delete-quiz', 'SheetController@deleteQuiz');

Route::get('/sheet-delete-share', 'SheetController@deleteSharedSheet');

Route::get('/sheet-read-file', 'SheetController@readFileSh');

Route::post('/uploadFileSh/{path}', 'SheetController@uploadFileSh');

//--------------------------- SheetingController ---------------------------

Route::get('/sheeting-find-sheeting-id', 'SheetingController@findSheetingByID');

Route::get('/sheeting-find-sheeting-name', 'SheetingController@findSheetingByNameAndGroup');

Route::get('/sheeting-find-sheeting-uid', 'SheetingController@findSheetingByUserID');

Route::get('/sheeting-find-sheeting-gid', 'SheetingController@findSheetingByGroupID');

Route::get('/sheeting-find-sheeting-uid-gid', 'SheetingController@findSheetingByUserIDAndGroup');

Route::get('/sheeting-find-sheetsheeting-stid', 'SheetingController@findSheetSheetingBySheetingID');

Route::get('/sheeting-find-sheetsheeting-view', 'SheetingController@findSheetSheetingInViewSheet');

Route::get('/sheeting-find-sheeting-std', 'SheetingController@findSTDSheetingByGroupID');

Route::get('/sheeting-find-sheetsheeting-board', 'SheetingController@findSheetSheetingInSheetBoard');

Route::get('/sheeting-find-ressheet-id', 'SheetingController@findResSheetByID');

Route::get('/sheeting-find-ressheet-old', 'SheetingController@findOldCodeInResSheet');

Route::get('/sheeting-find-resquiz-rsid', 'SheetingController@findResQuizByRSID');

Route::get('/sheeting-find-data-board', 'SheetingController@dataInSheetBoard');

Route::get('/sheeting-add-sheeting', 'SheetingController@createSheeting');

Route::get('/sheeting-add-sheetsheeting', 'SheetingController@createSheetSheeting');

Route::get('/sheeting-edit-sheeting', 'SheetingController@updateSheeting');

Route::get('/sheeting-edit-sheetsheeting', 'SheetingController@updateSheetSheeting');

Route::get('/sheeting-edit-score-trial', 'SheetingController@editTrialScore');

Route::get('/sheeting-edit-score-quiz', 'SheetingController@editQuizScore');

Route::get('/sheeting-delete-sheeting', 'SheetingController@deleteSheeting');

Route::get('/sheeting-delete-sheetsheeting', 'SheetingController@deleteSheetSheeting');

Route::get('/sheeting-delete-queue-sheet', 'SheetingController@deleteFirstQueueSh');

Route::get('/sheeting-check-queue-sheet','SheetingController@checkQueueSh');

Route::get('/sheeting-change-hidden','SheetingController@changeHiddenSheeting');

Route::get('/sheeting-send-quiz','SheetingController@sendQuiz');

Route::post('/uploadSheetFile/{STID}/{SID}/{UID}','SheetingController@uploadSheetFile');



//--------------------------- Page Permission ---------------------------

Route::get('/permission-exam-edit','ExamController@checkPermissionEditExam');

Route::get('/permission-exam-copy','ExamController@checkPermissionCopyExam');

Route::get('/permission-sheet-edit','SheetController@checkPermissionEditSheet');

Route::get('/permission-sheet-copy','SheetController@checkPermissionCopySheet');

Route::get('/permission-group-teacher','GroupController@checkPermissionGroupTeacher');

Route::get('/permission-group-student','GroupController@checkPermissionGroupStudent');

Route::get('/permission-examing-edit','ExamingController@checkPermissionEditExaming');

Route::get('/permission-examing-doing','ExamingController@checkPermissionDoingExaming');

Route::get('/permission-examing-board','ExamingController@checkPermissionBoardExaming');

Route::get('/permission-sheeting-edit','SheetingController@checkPermissionEditSheeting');

Route::get('/permission-sheeting-doing','SheetingController@checkPermissionDoingSheeting');

Route::get('/permission-sheeting-board','SheetingController@checkPermissionBoardSheeting');