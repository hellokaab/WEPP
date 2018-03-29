<?php

namespace App\Http\Controllers;

use App\JoinGroup;
use App\QueueSheet;
use App\Quiz;
use App\ResQuiz;
use App\ResSheet;
use App\Sheeting;
use App\SheetSheeting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DirectoryIterator;

use App\Http\Requests;

class SheetingController extends Controller
{
    public function findSheetingByNameAndGroup(Request $request)
    {
        $sheeting = Sheeting::where('sheeting_name',$request->sheeting_name)
            ->where('group_id',$request->group_id)
            ->first();
        if ($sheeting === NULL) {
//             return true;
        }else{
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function createSheeting(Request $request){
        $sheeting = new Sheeting;
        $sheeting->user_id = $request->user_id;
        $sheeting->group_id = $request->group_id;
        $sheeting->sheeting_name = $request->sheeting_name;
        $sheeting->start_date_time = $request->start_date_time;
        $sheeting->end_date_time = $request->end_date_time;
        $sheeting->allowed_file_type = $request->allowed_file_type;
        $sheeting->send_late = $request->send_late;
        $sheeting->hide_sheeting = $request->hide_sheeting;
        $sheeting->save();

        return response()->json($sheeting);
    }

    public function createSheetSheeting(Request $request){
        $sheetSheeting = new SheetSheeting;
        $sheetSheeting->sheet_id = $request->sheet_id;
        $sheetSheeting->sheeting_id = $request->sheeting_id;
        $sheetSheeting->save();
    }

    public function findSheetingByUserID(Request $request){
        $sheeting = Sheeting::where('user_id',$request->user_id)
            ->orderBy('start_date_time','ASC')
            ->orderBy('end_date_time','ASC')
            ->orderBy('sheeting_name','ASC')
            ->get();
        return response()->json($sheeting);
    }

    public function findSheetingByGroupID(Request $request){
        $sheeting = Sheeting::where('group_id',$request->group_id)
            ->orderBy('start_date_time','ASC')
            ->orderBy('end_date_time','ASC')
            ->get();
        return response()->json($sheeting);
    }

    public function deleteSheeting(Request $request){
        $sheeting = Sheeting::find($request->id);
        $sheetingFolder = "Sheeting_".$sheeting->id;
        $this->rrmdir("../upload/res_sheet/".$sheetingFolder);
        $sheeting->delete();
    }

    public function findSheetSheetingBySheetingID(Request $request){
        $sheetSheeting = SheetSheeting::where('sheeting_id',$request->sheeting_id)->get();
        return response()->json($sheetSheeting);
    }

    public function findSheetingByID(Request $request){
        $sheeting = Sheeting::find($request->id);
        return response()->json($sheeting);
    }

    public function updateSheeting(Request $request){
        $sheeting = Sheeting::find($request->id);
        $sheeting->group_id = $request->group_id;
        $sheeting->sheeting_name = $request->sheeting_name;
        $sheeting->start_date_time = $request->start_date_time;
        $sheeting->end_date_time = $request->end_date_time;
        $sheeting->allowed_file_type = $request->allowed_file_type;
        $sheeting->send_late = $request->send_late;
        $sheeting->hide_sheeting = $request->hide_sheeting;
        $sheeting->save();

        return response()->json($sheeting);
    }

    public function deleteSheetSheeting(Request $request){
        $sheetSheeting = DB::table('sheet_sheetings')
            ->where('sheet_id', $request->sheet_id)
            ->where('sheeting_id', $request->sheeting_id)
            ->first();
        $delEEM = SheetSheeting::find($sheetSheeting->id);
        $delEEM->delete();
    }

    public function updateSheetSheeting(Request $request)
    {
        $sheetSheeting = SheetSheeting::where('sheet_id', $request->sheet_id)
            ->where('sheeting_id', $request->sheeting_id)->first();
        if ($sheetSheeting === NULL) {
            $newSST = new SheetSheeting;
            $newSST->sheet_id = $request->sheet_id;
            $newSST->sheeting_id = $request->sheeting_id;
            $newSST->save();
        }
    }

    public function findSTDSheetingByGroupID(Request $request){
        $sheeting = Sheeting::where('group_id',$request->group_id)
            ->where('hide_sheeting','1')
            ->orderBy('start_date_time','ASC')
            ->orderBy('end_date_time','ASC')
            ->get();
        return response()->json($sheeting);
    }

    public function findSheetSheetingInViewSheet(Request $request)
    {
        $sheetSheeting = DB::select('SELECT w.sheet_name,a.* 
                                    FROM sheets AS w 
                                    INNER JOIN (
	                                    SELECT st.*,re.current_status 
	                                    FROM sheet_sheetings AS st LEFT JOIN(
                                            SELECT * 
		                                    FROM res_sheets
		                                    WHERE res_sheets.user_id = ? ) AS re
	                                    ON (st.sheet_id = re.sheet_id AND st.sheeting_id = re.sheeting_id) 
	                                    WHERE st.sheeting_id = ?) AS a
                                    ON w.id = a.sheet_id', [$request->user_id,$request->sheeting_id]);
        return response()->json($sheetSheeting);
    }

    public function findOldCodeInResSheet(Request $request){
        $code = array();
        $resSheet = ResSheet::where('user_id',$request->user_id)
            ->where('sheet_id',$request->sheet_id)
            ->where('sheeting_id',$request->sheeting_id)
            ->first();
        if ($resSheet === NULL) {
            array_push($code, 'ไม่พบโค้ดโปรแกรมที่เคยส่ง');
            $data = array(
                'resSheetID' => 0,
                'code' => $code
            );
            return response()->json($data);
        }else{
            $folder_ans = $resSheet->path;
            //ค้นหาไฟล์ในโฟลเดอร์ข้อสอบที่ส่ง
            $files = scandir($folder_ans);
            foreach ($files as $f) {
                // ลูปหาไฟล์นามสกุล.java ที่ไม่ใช่ Main.java
                if (strpos($f, '.java') && $f != 'Main.java') {
                    $handle = fopen("$folder_ans/$f", "r");
                    $codeInFile = fread($handle, filesize("$folder_ans/$f"));
                    array_push($code, $codeInFile);
                    fclose($handle);
                }

                else if (strpos($f, '.c') && $f != 'ex.c' && $f != 'ex.cpp' && $f != 'Main.cs') {
                    $handle = fopen("$folder_ans/$f", "r");
                    $codeInFile = fread($handle, filesize("$folder_ans/$f"));
                    array_push($code, $codeInFile);
                    fclose($handle);
                }
            }

            $data = array(
                'resSheetID' => $resSheet->id,
                'code' => $code
            );
            return response()->json($data);
        }

    }

    public function findResQuizByRSID(Request $request){
        $resQuiz = ResQuiz::where('res_sheet_id',$request->res_sheet_id)->get();
        return response()->json($resQuiz);
    }

    public function deleteFirstQueueSh(){
        $first = QueueSheet::orderBy('id')->first();
        $first->delete();
    }

    public function sendQuiz(Request $request){
        $score = 0;
        $quiz = Quiz::find($request->quiz_id);
        if($quiz->quiz_ans === $request->quiz_ans){
            $score = $quiz->quiz_score;
        }

        $resQuiz = ResQuiz::where('res_sheet_id',$request->res_sheet_id)
            ->where('quiz_id',$request->quiz_id)
            ->first();
        if ($resQuiz === NULL) {
            $resQuiz = new ResQuiz;
            $resQuiz->res_sheet_id = $request->res_sheet_id;
            $resQuiz->quiz_id = $request->quiz_id;
            $resQuiz->quiz_ans = $request->quiz_ans;
            $resQuiz->score = $score;
            $resQuiz->save();
        } else {
            if($resQuiz->quiz_ans != $request->quiz_ans){
                $resQuiz->quiz_ans = $request->quiz_ans;
                $resQuiz->score = $score;
                $resQuiz->save();
            }
        }
    }

    public function uploadSheetFile($STID,$SID,$UID,Request $request)
    {
        $user = User::find($UID);
        $userFolder = $user->stu_id."_".$user->fname_en."_".$user->lname_en;
        $sheetingFolder = "Sheeting_".$STID;
        $sheetFolder = "Sheet_".$SID;
        $path = "../upload/res_sheet/";

//        สร้างโฟลเดอร์เก็บข้อสอบที่ส่ง
        $this->makeFolder("../upload/","res_sheet");
//        สร้างโฟลเดอร์ของการสอบ
        $this->makeFolder($path,$sheetingFolder);
//        สร้างโฟลเดอร์ของข้อสอบในการสอบ
        $this->makeFolder($path.$sheetingFolder."/",$sheetFolder);
//        สร้างโฟลเดอร์ของนักเรียนที่ส่งข้อสอบ
        $this->makeFolder($path.$sheetingFolder."/".$sheetFolder."/",$userFolder);

        $folderName = date('Ymd-His')."_".rand(1, 9999);
        $folder = $path.$sheetingFolder."/".$sheetFolder."/".$userFolder."/".$folderName;
        mkdir($folder, 0777, true);
        $files = $request->file('file_ans');
        foreach ($files as &$file) {
            $file->move($folder,$file->getClientOriginalName());
        }
        return response()->json($folder);
    }

    public function checkQueueSh(Request $request){
        $first = QueueSheet::orderBy('id')->first();
        if($first->res_sheet_id == $request->pathSheetID){
            return response()->json($first->file_type, 200);
        } else {
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function findSheetSheetingInSheetBoard(Request $request){
        $sheetSheeting = DB::table('sheet_sheetings')
            ->join('sheets', 'sheet_sheetings.sheet_id', '=', 'sheets.id')
            ->select('sheet_sheetings.*', 'sheets.sheet_name')
            ->where('sheet_sheetings.sheeting_id',$request->sheeting_id)
            ->get();
        return response()->json($sheetSheeting);

    }

    public function dataInSheetBoard(Request $request)
    {
        $data = DB::select('SELECT * FROM (
                                SELECT join_groups.user_id,join_groups.group_id,CONCAT(users.prefix,users.fname_th," ",users.lname_th) AS full_name,users.stu_id
                                FROM join_groups INNER JOIN users 
                                WHERE users.id = join_groups.user_id 
                                AND join_groups.group_id = ?
                                AND join_groups.status = \'s\' ) as a 
                            LEFT JOIN (
                                SELECT re.id as res_sheet_id,re.user_id,re.sheeting_id,re.sheet_id,re.score,sq.sum_score_quiz,re.send_late 
                                FROM res_sheets as re LEFT JOIN (
                                    SELECT res_quizzes.res_sheet_id,SUM(res_quizzes.score) as sum_score_quiz
                                    FROM res_quizzes 
                                    GROUP BY res_quizzes.res_sheet_id ) as sq 
	                            ON re.id = sq.res_sheet_id
                                WHERE re.sheeting_id = ?
                            AND re.sheet_id = ?) as b 
                            ON a.user_id = b.user_id', [$request->group_id,$request->sheeting_id,$request->sheet_id]);
        return response()->json($data);
    }

    public function findResSheetByID(Request $request){
        $resSheet = ResSheet::find($request->res_sheet_id);
        return response()->json($resSheet);
    }

    public function editTrialScore(Request $request){
        $ressheet = ResSheet::find($request->res_sheet_id);
        $ressheet->score = $request->score;
        $ressheet->save();
    }

    public function editQuizScore(Request $request){
        $resQuiz = ResQuiz::find($request->id);
        $resQuiz->score = $request->score;
        $resQuiz->save();
    }

    public function checkPermissionEditSheeting(Request $request){
        $exam = Sheeting::where('id',$request->sheeting_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($exam === NULL) {
            return 404;
        }
    }

    public function checkPermissionDoingSheeting(Request $request){
        $sheeting = Sheeting::find($request->sheeting_id);
        $join_group = JoinGroup::where('group_id',$sheeting->group_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($join_group === NULL) {
            return 404;
        } else {
            if($join_group->status != 's'){
                return 404;
            }
        }
    }

    public function checkPermissionBoardSheeting(Request $request){
        $sheeting = Sheeting::find($request->sheeting_id);
        $join_group = JoinGroup::where('group_id',$sheeting->group_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($join_group === NULL) {
            return 404;
        }
    }

    public function rrmdir($path) {
        // Open the source directory to read in files
        try {
            $i = new DirectoryIterator($path);
            foreach ($i as $f) {
                if ($f->isFile()) {
                    unlink($f->getRealPath());
                } else if (!$f->isDot() && $f->isDir()) {
                    $this->rrmdir($f->getRealPath());
                }
            }
            rmdir($path);
        } catch(\Exception $e ){}
    }

    function makeFolder($path,$folder) {
        $dirList = scandir($path);
        if (!in_array((string) $folder, (array) $dirList)) {
            mkdir($path.$folder, 0777, true);
        }
    }
}
