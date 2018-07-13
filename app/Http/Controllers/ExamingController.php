<?php

namespace App\Http\Controllers;

use App\ExamExaming;
use App\Examing;
use App\ExamRandom;
use App\JoinGroup;
use App\PathExam;
use App\QueueExam;
use App\ResExam;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DirectoryIterator;

use App\Http\Requests;

class ExamingController extends Controller
{
    public function findExamingByNameAndGroup(Request $request)
    {
        $examing = Examing::where('examing_name',$request->examing_name)
            ->where('group_id',$request->group_id)
            ->first();
        if ($examing === NULL) {
//             return true;
        }else{
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function createExaming(Request $request)
    {
        $examing = new Examing;
        $examing->user_id = $request->user_id;
        $examing->group_id = $request->group_id;
        $examing->examing_mode = $request->examing_mode;
        $examing->amount = $request->amount;
        $examing->start_date_time = $request->start_date_time;
        $examing->end_date_time = $request->end_date_time;
        $examing->examing_pass = $request->examing_pass;
        $examing->examing_name = $request->examing_name;
        $examing->ip_group = $request->ip_group;
        $examing->allowed_file_type = $request->allowed_file_type;
        $examing->hide_examing = $request->hide_examing;
        $examing->hide_history = $request->hide_history;
        $examing->save();

        return response()->json($examing);
    }

    public function createExamExaming(Request $request)
    {
        $examExaming = new ExamExaming;
        $examExaming->exam_id = $request->exam_id;
        $examExaming->examing_id = $request->examing_id;
        $examExaming->save();
    }

    public function findExamingByUserID(Request $request){
        $examing = Examing::where('user_id',$request->user_id)
            ->orderBy('start_date_time','ASC')
            ->orderBy('end_date_time','ASC')
            ->orderBy('examing_name','ASC')
            ->get();
        return response()->json($examing);
    }

    public function findExamExamingByExamingID(Request $request)
    {
        $examExaming = ExamExaming::where('examing_id', $request->examing_id)->get();
        return response()->json($examExaming);
    }

    public function findExamingByID(Request $request){
        $examing = Examing::find($request->id);
        return response()->json($examing);
    }

    public  function  editExaming(Request $request){
        $examing = Examing::find($request->id);
        $examing->group_id = $request->group_id;
        $examing->examing_mode = $request->examing_mode;
        $examing->amount = $request->amount;
        $examing->start_date_time = $request->start_date_time;
        $examing->end_date_time = $request->end_date_time;
        $examing->examing_pass = $request->examing_pass;
        $examing->examing_name = $request->examing_name;
        $examing->ip_group = $request->ip_group;
        $examing->allowed_file_type = $request->allowed_file_type;
        $examing->hide_examing = $request->hide_examing;
        $examing->hide_history = $request->hide_history;
        $examing->save();
    }

    public function deleteExamExaming(Request $request)
    {
        $examExaming = DB::table('exam_examings')
            ->where('exam_id', $request->exam_id)
            ->where('examing_id', $request->examing_id)
            ->first();
        $delEEM = ExamExaming::find($examExaming->id);
        $delEEM->delete();
    }

    public function editExamExaming(Request $request)
    {
        $examExaming = ExamExaming::where('exam_id', $request->exam_id)
            ->where('examing_id', $request->examing_id)->first();
        if ($examExaming === NULL) {
            $newEEM = new ExamExaming;
            $newEEM->exam_id = $request->exam_id;
            $newEEM->examing_id = $request->examing_id;
            $newEEM->save();
        }
    }

    public function deleteExaming(Request $request)
    {
        $examing = Examing::find($request->id);
        $examingFolder = "Examing_".$examing->id;
        $this->rrmdir("../upload/res_exam/".$examingFolder);
        $examing->delete();
    }

    public function findExamingItsComing(Request $request){
        $examing = Examing::where('group_id',$request->group_id)
            ->where('end_date_time','>',DB::raw('NOW()'))
            ->orderBy('start_date_time','ASC')
            ->orderBy('end_date_time','ASC')
            ->orderBy('examing_name','ASC')
            ->get();
        return response()->json($examing);
    }

    public function findSTDExamingItsComing(Request $request){
        $examing = Examing::where('group_id',$request->group_id)
            ->where('end_date_time','>',DB::raw('NOW()'))
            ->where('hide_examing','1')
            ->orderBy('start_date_time','ASC')
            ->orderBy('end_date_time','ASC')
            ->orderBy('examing_name','ASC')
            ->get();
        return response()->json($examing);
    }

    public function findExamingItsEnding(Request $request){
        $examing = Examing::where('group_id',$request->group_id)
            ->where('end_date_time','<',DB::raw('NOW()'))
            ->orderBy('start_date_time','ASC')
            ->orderBy('end_date_time','ASC')
            ->orderBy('examing_name','ASC')
            ->get();
        return response()->json($examing);
    }

    public function checkIP(Request $request){
        $my_ip = $_SERVER['REMOTE_ADDR'];
        $ip_groups = explode("\n", $request->ip_group);
        $in_network = FALSE;
        foreach ($ip_groups as $check_ip) {
            $check_ip_sp = explode('.', $check_ip);
            $ip = explode('.', $my_ip);
            $count = 0;
            for($i = 0; $i < count($check_ip_sp) ; $i++){
                if($check_ip_sp[$i] === $ip[$i]){
                    $count++;
                }
            }
            if($count === count($check_ip_sp)){
                $in_network = TRUE;
            }
        }
        if ($in_network) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function findExamRandomByUID(Request $request)
    {
        $examRandom = ExamRandom::where('user_id',$request->user_id)
            ->where('examing_id',$request->examing_id)
            ->get();
        return response()->json($examRandom);
    }

    public function addRandomExam(Request $request)
    {
        $examRandom = new ExamRandom;
        $examRandom->examing_id = $request->examing_id;
        $examRandom->user_id = $request->user_id;
        $examRandom->exam_id = $request->exam_id;
        $examRandom->save();
    }

    public function findExamExamingInViewExam(Request $request)
    {
        $examExaming = DB::select('SELECT e.exam_name,a.* 
                                    FROM exams AS e 
                                    INNER JOIN (
	                                    SELECT ex.*,re.current_status 
	                                    FROM exam_examings AS ex LEFT JOIN(
                                            SELECT * 
		                                    FROM res_exams
		                                    WHERE res_exams.user_id = ? ) AS re
	                                    ON (ex.examing_id = re.examing_id AND ex.exam_id = re.exam_id) 
	                                    WHERE ex.examing_id = ?) AS a
                                    ON e.id = a.exam_id', [$request->user_id,$request->examing_id]);
        return response()->json($examExaming);
    }

    public function findExamRandomInViewExam(Request $request){
        $examExaming = DB::select(' SELECT ex.exam_name,a.* 
                                    FROM exams AS ex INNER JOIN(
                                        SELECT er.*,re.current_status 
                                        FROM exam_randoms AS er LEFT JOIN(
                                            SELECT * 
                                            FROM res_exams 
                                            WHERE res_exams.user_id = ? ) AS re
                                        ON er.examing_id = re.examing_id
                                        WHERE er.user_id = ?
                                        AND er.examing_id = ? ) AS a
                                        ON ex.id = a.exam_id', [$request->user_id,$request->user_id,$request->examing_id]);
        return response()->json($examExaming);
    }

    public function uploadExamFile($EMID,$EID,$UID,Request $request)
    {
        $user = User::find($UID);
        $userFolder = $user->stu_id."_".$user->fname_en."_".$user->lname_en;
        $examingFolder = "Examing_".$EMID;
        $examFolder = "Exam_".$EID;
        $path = "../upload/res_exam/";

//        สร้างโฟลเดอร์เก็บข้อสอบที่ส่ง
        $this->makeFolder("../upload/","res_exam");
//        สร้างโฟลเดอร์ของการสอบ
        $this->makeFolder($path,$examingFolder);
//        สร้างโฟลเดอร์ของข้อสอบในการสอบ
        $this->makeFolder($path.$examingFolder."/",$examFolder);
//        สร้างโฟลเดอร์ของนักเรียนที่ส่งข้อสอบ
        $this->makeFolder($path.$examingFolder."/".$examFolder."/",$userFolder);

        $folderName = date('Ymd-His')."_".rand(1, 9999);
        $folder = $path.$examingFolder."/".$examFolder."/".$userFolder."/".$folderName;
        mkdir($folder, 0777, true);
        $files = $request->file('file_ans');
        foreach ($files as &$file) {
            $file->move($folder,$file->getClientOriginalName());
        }
        return response()->json($folder);
    }

    public function checkQueueEx(Request $request){
        $first = QueueExam::orderBy('id')->first();
        if($first->path_exam_id == $request->pathExamID){
            return response()->json($first->file_type, 200);
        } else {
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function deleteFirstQueueEx(){
        $first = QueueExam::orderBy('id')->first();
        $first->delete();
    }

    public function examInScoreboard(Request $request){
        $examInScoreboard = DB::table('exam_examings')
            ->join('exams', 'exam_examings.exam_id', '=', 'exams.id')
            ->select('exam_examings.*', 'exams.exam_name','exams.full_score')
            ->where('exam_examings.examing_id',$request->examing_id)
            ->orderBy('exam_examings.exam_id','ASC')
            ->get();
        return response()->json($examInScoreboard);
    }

    public function dataInScoreboard(Request $request){
        $listStd = DB::table('users')
            ->join('join_groups', 'users.id', '=', 'join_groups.user_id')
            ->select('users.id','users.stu_id', DB::raw('CONCAT(users.prefix,users.fname_th," ",users.lname_th) AS full_name'))
            ->where('join_groups.group_id',$request->group_id)
            ->where('join_groups.status','s')
            ->orderBy('users.stu_id','ASC')
            ->get();

        $examExamig = ExamExaming::where('examing_id',$request->id)
            ->orderBy('exam_id','ASC')
            ->get();

        $score = array();
        $i = 0;
        foreach ($listStd as $stu) {
            $score[$i]["stu_id"] = $stu->stu_id;
            $score[$i]["full_name"] = $stu->full_name;
            $score[$i]["res_status"] = array();
            foreach ($examExamig as $eem) {
                $status = DB::select('SELECT res_exams.* 
                                      FROM exam_examings,res_exams 
                                      WHERE exam_examings.examing_id = res_exams.examing_id
                                      AND exam_examings.exam_id = res_exams.exam_id
                                      AND exam_examings.examing_id = ?
                                      AND exam_examings.exam_id = ?
                                      AND res_exams.user_id = ?',[$request->id,$eem->exam_id,$stu->id]);
                array_push($score[$i]["res_status"],$status);
            }
            $i++;
        }
        return response()->json($score);
    }

    public function findMySendExamHistory(Request $request){
        $myHistory = DB::table('res_exams')
            ->join('path_exams', 'res_exams.id', '=', 'path_exams.res_exam_id')
            ->join('exams', 'res_exams.exam_id', '=', 'exams.id')
            ->select('exams.exam_name','path_exams.id','path_exams.path','path_exams.res_run','path_exams.status','path_exams.send_date_time','path_exams.time','path_exams.memory')
            ->where('res_exams.user_id',$request->user_id)
            ->where('res_exams.examing_id',$request->examing_id)
            ->get();

        return response()->json($myHistory);
    }

    public function readFileResRun(Request $request){
        $file_resrun = $request->path;
        $handle = fopen("$file_resrun", "r");
        $resrun = filesize("$file_resrun") > 0 ? trim(fread($handle, filesize("$file_resrun"))) : "";
        fclose($handle);
        $resrun = $this->modify_output($resrun);
        return response()->json($resrun);
    }

    public function getCode(Request $request){
        $folder_ans = $request->path;
        $code = array();
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

//            else if (strpos($f, '.c') && $f != 'ex.c' && $f != 'ex.cpp') {
            else if (strpos($f, '.c') && $f != 'ex.c' && $f != 'ex.cpp' && $f != 'Main.cs') {
                $handle = fopen("$folder_ans/$f", "r");
                $codeInFile = fread($handle, filesize("$folder_ans/$f"));
                array_push($code, $codeInFile);
                fclose($handle);
            }

        }

        return response()->json($code);
    }

    public function findPathExamByResExamID(Request $request){
        $pathExam = PathExam::where('res_exam_id',$request->res_exam_id)->get();
        return response()->json($pathExam);
    }

    public function editScore(Request $request){
        $resexam = ResExam::find($request->res_exam_id);
        $resexam->score = $request->score;
        $resexam->save();
    }

    public function changeHiddenExaming(Request $request){
        $examing = Examing::find($request->id);
        $examing->hide_examing = $request->hide_examing;
        $examing->save();
    }

    public function changeHistoryExaming(Request $request){
        $examing = Examing::find($request->id);
        $examing->hide_history = $request->hide_history;
        $examing->save();
    }

    public function checkPermissionEditExaming(Request $request){
        $exam = Examing::where('id',$request->examing_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($exam === NULL) {
            return 404;
        }
    }

    public function checkPermissionDoingExaming(Request $request){
        $examing = Examing::find($request->examing_id);
        $join_group = JoinGroup::where('group_id',$examing->group_id)
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

    public function checkPermissionBoardExaming(Request $request){
        $examing = Examing::find($request->examing_id);
        $join_group = JoinGroup::where('group_id',$examing->group_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($join_group === NULL) {
            return 404;
        }
    }

    function makeFolder($path,$folder) {
        $dirList = scandir($path);
        if (!in_array((string) $folder, (array) $dirList)) {
            mkdir($path.$folder, 0777, true);
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

    function modify_output($output){
        $modified_output = "";
        for($i=0;$i<strlen($output);$i++){
            if(ord($output[$i]) != 13){
                $modified_output = $modified_output.$output[$i];
            }
        }

        return $modified_output;
    }
}
