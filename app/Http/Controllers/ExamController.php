<?php

namespace App\Http\Controllers;

use App\Exam;
use App\ExamGroup;
use App\Keyword;
use App\ShareExam;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DirectoryIterator;

use App\Http\Requests;

class ExamController extends Controller
{
    public function findMyExamGroup(Request $request)
    {
        $exam_groups = DB::table('exam_groups')
            ->join('users', 'exam_groups.user_id', '=', 'users.id')
            ->select('exam_groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('user_id',$request->user_id)
            ->orderBy('exam_group_name','ASC')
            ->get();
        return response()->json($exam_groups);
    }

    public function addExamGroup(Request $request)
    {
        $find_exam_group = ExamGroup::where('exam_group_name',$request->exam_group_name)
            ->where('user_id',$request->user_id)
            ->first();
        if ($find_exam_group === NULL) {
            $exam_group = new ExamGroup();
            $exam_group->user_id = $request->user_id;
            $exam_group->exam_group_name = $request->exam_group_name;
            $exam_group->save();
        } else {
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function findExamByEGID(Request $request){
        $exams = Exam::where('exam_group_id',$request->exam_group_id)->get();
        return response()->json($exams);
    }

    public function uploadFile($path,Request $req)
    {
        $pathExam = str_replace("*","/",$path);

        if($req->hasFile('exam_file_input')) {
            $file = $req->file('exam_file_input');
            $fileName = "input.txt";
            $file->move($pathExam , $fileName);
            return response()->json($pathExam.$fileName);
        }

        if($req->hasFile('exam_file_output')) {
            $file = $req->file('exam_file_output');
            $fileName = "output.txt";
            $file->move($pathExam , $fileName);
            return response()->json($pathExam.$fileName);
        }
    }

    public function addExam(Request $request)
    {
        $exam = new Exam;
        $exam->user_id = $request->user_id;
        $exam->exam_group_id = $request->exam_group_id;
        $exam->exam_name = $request->exam_name;
        $exam->exam_data = $request->exam_data;
        $exam->exam_input_file = $request->exam_inputfile;
        $exam->exam_output_file = $request->exam_outputfile;
        $exam->memory_size = $request->memory_size;
        $exam->time_limit = $request->time_limit;
        $exam->full_score = $request->full_score;
        $exam->cut_wrongans = $request->cut_wrongans;
        $exam->cut_comerror = $request->cut_comerror;
        $exam->cut_overmemory = $request->cut_overmemory;
        $exam->cut_overtime = $request->cut_overtime;
        $exam->main_code = $request->main_code;
        $exam->case_sensitive = $request->case_sensitive;
        $exam->save();

        return response()->json($exam);

    }

    public function addKeyword(Request $request)
    {
        $keyword = new Keyword;
        $keyword->exam_id = $request->exam_id;
        $keyword->keyword_data = $request->keyword;
        $keyword->save();
        return response()->json($request->keyword);
    }

    public function addShareExam(Request $request)
    {
        $share = new ShareExam;
        $share->exam_id = $request->exam_id;
        $share->user_id = $request->user_id;
        $share->save();
    }

    public function checkExamByName(Request $request)
    {
        $findExam = Exam::where('exam_name',$request->exam_name)
            ->where('exam_group_id',$request->exam_group_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($findExam === NULL) {

        }else{
            return response()->json(['error' => 'Error msg'], 209);
        }

    }

    public function editExamGroup(Request $request)
    {
        $findExamGroupName = ExamGroup::where('exam_group_name', $request->exam_group_name)
            ->where('user_id', $request->user_id)
            ->first();

        if ($findExamGroupName === NULL) {
            $eg = ExamGroup::find($request->id);
            $eg->exam_group_name = $request->exam_group_name;
            $eg->save();
        } else {
            if($findExamGroupName->id == $request->id){
                $eg = ExamGroup::find($request->id);
                $eg->exam_group_name = $request->exam_group_name;
                $eg->save();
            }else {
                return response()->json(['error' => 'Error msg'], 209);
            }

        }
    }

    public function deleteExamGroup(Request $request)
    {
        $exam_group = ExamGroup::find($request->id);
        $user = User::find($exam_group->user_id);
        $userFolder = $user->id."_".$user->fname_en."_".$user->lname_en;
        $examGroupFolder = "ExamGroup_".$exam_group->id;
        $this->rrmdir("../upload/exam/".$userFolder."/".$examGroupFolder);
        $exam_group->delete();
    }

    public function findKeywordByEID(Request $request)
    {
        $keyword = Keyword::where('exam_id',$request->exam_id)->get();
        return response()->json($keyword);
    }

    public function readFileEx(Request $request)
    {
        $contentFile = fopen("$request->exam_data", "r") or die("Unable to open file!");
        $content = fread($contentFile,filesize("$request->exam_data"));
        fclose($contentFile);

        if($request->exam_input_file != NULL){
            $inputFile = fopen("$request->exam_input_file", "r") or die("Unable to open file!");
            $input = fread($inputFile,filesize("$request->exam_input_file"));
            fclose($inputFile);
            $input = $this->modify_output($input);
        }else {
            $input = "";
        }

        $outputFile = fopen("$request->exam_output_file", "r") or die("Unable to open file!");
        $output = fread($outputFile,filesize("$request->exam_output_file"));
        fclose($outputFile);
        $output = $this->modify_output($output);

        if($request->main_code != NULL){
            $mainCodeFile = fopen("$request->main_code", "r") or die("Unable to open file!");
            $main_code = fread($mainCodeFile,filesize("$request->main_code"));
        } else {
            $main_code = "";
        }

        $data = array(
            'content' => $content,
            'input' => $input,
            'output' => $output,
            'main' => $main_code
        );

        return response()->json($data);
    }

    public function deleteExam(Request $request)
    {
        $exam = Exam::find($request->exam_id);
        $folderPath = "";
        $getpath = explode("/",$exam->exam_data);
        for($i=0;$i<sizeof($getpath)-1;$i++){
            $folderPath = $folderPath.$getpath[$i]."/";
        }
        $this->rrmdir($folderPath);

        $exam->delete();
    }

    public function findExamByID(Request $request)
    {
        $exam = Exam::find($request->id);
        return response()->json($exam);
    }

    public function findSharedUserNotMe(Request $request){
        $shared = DB::table('share_exams')
            ->where('exam_id',$request->exam_id)
            ->where('user_id', '!=',$request->my_id)
            ->get();
        return response()->json($shared);
    }

    public function editKeyword(Request $request)
    {
        $keyword = Keyword::find($request->id);
        $keyword->keyword_data = $request->keyword_data;
        $keyword->save();
    }

    public function deleteKeyword(Request $request)
    {
        $keyword = Keyword::find($request->id);
        $keyword->delete();
    }

    public function editExam(Request $request)
    {
        $folderPath = "";
        $exam = Exam::find($request->id);
        $getpath = explode("/",$exam->exam_data);
        for($i=0;$i<sizeof($getpath)-1;$i++){
            $folderPath = $folderPath.$getpath[$i]."/";
        }
        $this->rrmdir($folderPath);
        $exam->exam_group_id = $request->exam_group_id;
        $exam->exam_name = $request->exam_name;
        $exam->exam_data = $request->exam_data;
        $exam->exam_input_file = $request->exam_inputfile;
        $exam->exam_output_file = $request->exam_outputfile;
        $exam->memory_size = $request->memory_size;
        $exam->time_limit = $request->time_limit;
        $exam->full_score = $request->full_score;
        $exam->cut_wrongans = $request->cut_wrongans;
        $exam->cut_comerror = $request->cut_comerror;
        $exam->cut_overmemory = $request->cut_overmemory;
        $exam->cut_overtime = $request->cut_overtime;
        $exam->main_code = $request->main_code;
        $exam->case_sensitive = $request->case_sensitive;
        $exam->save();

    }

    public function editShareExam(Request $request){
        $shared = ShareExam::where('exam_id',$request->exam_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($shared === NULL) {
            $newShared = new ShareExam;
            $newShared->exam_id = $request->exam_id;
            $newShared->user_id = $request->user_id;
            $newShared->save();
        }
    }

    public function deleteShareExam(Request $request){
        $selectShared = DB::table('share_exams')
            ->where('exam_id',$request->exam_id)
            ->where('user_id',$request->user_id)
            ->first();
        $delShared = ShareExam::find($selectShared->id);
        $delShared->delete();
    }

    public function findExamGroupSharedToMe(Request $request){
        $section = DB::table('share_exams')
            ->join('exams', 'share_exams.exam_id', '=', 'exams.id')
            ->join('exam_groups', 'exams.exam_group_id', '=', 'exam_groups.id')
            ->join('users', 'exam_groups.user_id', '=', 'users.id')
            ->select('exam_groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('share_exams.user_id',$request->my_id)
            ->groupBy('exam_groups.id')
            ->orderBy('creater', 'asc')
            ->orderBy('exam_groups.exam_group_name', 'asc')
            ->get();
        return response()->json($section);
    }

    public function findExamGroupSharedNotMe(Request $request){
        $exam_group = DB::table('share_exams')
            ->join('exams', 'share_exams.exam_id', '=', 'exams.id')
            ->join('exam_groups', 'exams.exam_group_id', '=', 'exam_groups.id')
            ->join('users', 'exam_groups.user_id', '=', 'users.id')
            ->select('exam_groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('share_exams.user_id',$request->my_id)
            ->where('exam_groups.user_id','!=',$request->my_id)
            ->groupBy('exam_groups.id')
            ->orderBy('creater', 'asc')
            ->orderBy('exam_groups.exam_group_name', 'asc')
            ->get();
        return response()->json($exam_group);
    }

    public function findAllExamSharedToMe(Request $request){
        $exam = DB::table('share_exams')
            ->join('exams', 'share_exams.exam_id', '=', 'exams.id')
            ->select('exams.*')
            ->where('share_exams.user_id',$request->user_id)
            ->orderBy('exams.exam_name', 'asc')
            ->get();
        return response()->json($exam);
    }

    public function findExamSharedToMe(Request $request){
        $exam = DB::table('share_exams')
            ->join('exams', 'share_exams.exam_id', '=', 'exams.id')
            ->select('exams.*')
            ->where('share_exams.user_id',$request->user_id)
            ->where('exams.exam_group_id',$request->exam_group_id)
            ->orderBy('exams.exam_name', 'asc')
            ->get();
        return response()->json($exam);
    }

    public function readExamContent(Request $request)
    {
        $contentFile = fopen("$request->exam_data", "r") or die("Unable to open file!");
        $content = fread($contentFile,filesize("$request->exam_data"));

        return response()->json($content);
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
