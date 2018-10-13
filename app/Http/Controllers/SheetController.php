<?php

namespace App\Http\Controllers;

use App\Quiz;
use App\ShareSheet;
use App\Sheet;
use App\SheetGroup;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DirectoryIterator;

use App\Http\Requests;

class SheetController extends Controller
{
    public function findMySheetGroup(Request $request)
    {
        $dataSheetGroup = DB::table('sheet_groups')
            ->join('users', 'sheet_groups.user_id', '=', 'users.id')
            ->select('sheet_groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('sheet_groups.user_id', $request->user_id)
            ->orderBy('sheet_group_name', 'ASC')
            ->get();
        return response()->json($dataSheetGroup);
    }

    public function addSheetGroup(Request $request)
    {
        $find_sheet_group = SheetGroup::where('sheet_group_name',$request->sheet_group_name)
            ->where('user_id',$request->user_id)
            ->first();
        if ($find_sheet_group === NULL) {
            $sheet_group = new SheetGroup();
            $sheet_group->user_id = $request->user_id;
            $sheet_group->sheet_group_name = $request->sheet_group_name;
            $sheet_group->save();
        } else {
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function findSheetBySGID(Request $request){
        $sheets = Sheet::where('sheet_group_id',$request->sheet_group_id)->get();
        return response()->json($sheets);
    }

    public function editSheetGroup(Request $request)
    {
        $findSheetGroupName = SheetGroup::where('sheet_group_name', $request->sheet_group_name)
            ->where('user_id', $request->user_id)
            ->first();

        if ($findSheetGroupName === NULL) {
            $eg = SheetGroup::find($request->id);
            $eg->sheet_group_name = $request->sheet_group_name;
            $eg->save();
        } else {
            if($findSheetGroupName->id == $request->id){
                $eg = SheetGroup::find($request->id);
                $eg->sheet_group_name = $request->sheet_group_name;
                $eg->save();
            }else {
                return response()->json(['error' => 'Error msg'], 209);
            }

        }
    }

    public function deleteSheetGroup(Request $request)
    {
        $sheet_group = SheetGroup::find($request->id);
        $user = User::find($sheet_group->user_id);
        $userFolder = $user->id."_".$user->fname_en."_".$user->lname_en;
        $sheetGroupFolder = "SheetGroup_".$sheet_group->id;
        $this->rrmdir("../upload/sheet/".$userFolder."/".$sheetGroupFolder);
        $sheet_group->delete();
    }

    public function uploadFileSh($path,Request $req)
    {
        $pathSheet = str_replace("*","/",$path);

        if($req->hasFile('sheet_file_input')) {
            $file = $req->file('sheet_file_input');
            $fileName = "input.txt";
            $file->move($pathSheet , $fileName);
            return response()->json($pathSheet.$fileName);
        }

        if($req->hasFile('sheet_file_output')) {
            $file = $req->file('sheet_file_output');
            $fileName = "output.txt";
            $file->move($pathSheet , $fileName);
            return response()->json($pathSheet.$fileName);
        }
    }

    public function addSheet(Request $request){
        $sheet = new Sheet;
        $sheet->user_id = $request->user_id;
        $sheet->sheet_group_id = $request->sheet_group_id;
        $sheet->sheet_name = $request->sheet_name;
        $sheet->objective = $request->objective;
        $sheet->theory = $request->theory;
        $sheet->notation = $request->notation;
        $sheet->sheet_trial = $request->sheet_trial;
        $sheet->sheet_input_file = $request->sheet_input_file;
        $sheet->sheet_output_file = $request->sheet_output_file;
        $sheet->main_code = $request->main_code;
        $sheet->case_sensitive = $request->case_sensitive;
        $sheet->full_score = $request->full_score;
        $sheet->save();
        $insertedId = $sheet->id;
        $sheetID = $insertedId;

        return response()->json($sheet);
    }

    public function addQuiz(Request $request){
        $quiz = new Quiz;
        $quiz->sheet_id = $request->sheet_id;
        $quiz->quiz_data = $request->quiz_data;
        $quiz->quiz_ans = $request->quiz_ans;
        $quiz->quiz_score = $request->quiz_score;
        $quiz->save();
    }

    public function addShareSheet(Request $request){
        $shared = new ShareSheet();
        $shared->sheet_id = $request->sheet_id;
        $shared->user_id = $request->user_id;
        $shared->save();
    }

    public function findSheetByName(Request $request)
    {
        $findSheet = Sheet::where('sheet_name',$request->sheet_name)
            ->where('sheet_group_id',$request->sheet_group_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($findSheet === NULL) {
//             return true;
        }else{
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function findSheetByID(Request $request){
        $sheet = Sheet::find($request->id);
        return response()->json($sheet);
    }

    public function findQuizBySID(Request $request){
        $quiz = Quiz::where('sheet_id',$request->sheet_id)->get();
        return response()->json($quiz);
    }

    public function findSheetSharedUserNotMe(Request $request){
        $shared = DB::table('share_sheets')
            ->where('sheet_id',$request->sheet_id)
            ->where('user_id', '!=',$request->my_id)
            ->get();
        return response()->json($shared);
    }

    public function readFileSh(Request $request)
    {
        if($request->objective != NULL){
            $objectiveFile = fopen("$request->objective", "r") or die("Unable to open file!");
            $objective = fread($objectiveFile,filesize("$request->objective"));
            fclose($objectiveFile);
        }else {
            $objective = "";
        }

        if($request->theory != NULL){
            $theoryFile = fopen("$request->theory", "r") or die("Unable to open file!");
            $theory = fread($theoryFile,filesize("$request->theory"));
            fclose($theoryFile);
        }else {
            $theory = "";
        }

        $trialFile = fopen("$request->sheet_trial", "r") or die("Unable to open file!");
        $trial = fread($trialFile,filesize("$request->sheet_trial"));
        fclose($trialFile);

        if($request->sheet_input_file != NULL){
            $inputFile = fopen("$request->sheet_input_file", "r") or die("Unable to open file!");
            $input = fread($inputFile,filesize("$request->sheet_input_file"));
            fclose($inputFile);
        }else {
            $input = "";
        }

        $outputFile = fopen("$request->sheet_output_file", "r") or die("Unable to open file!");
        $output = fread($outputFile,filesize("$request->sheet_output_file"));
        fclose($outputFile);
        $output = $this->modify_output($output);

        if($request->main_code != NULL){
            $mainCodeFile = fopen("$request->main_code", "r") or die("Unable to open file!");
            $main_code = fread($mainCodeFile,filesize("$request->main_code"));
        } else {
            $main_code = "";
        }

        $data = array(
            'objective' => $objective,
            'theory' => $theory,
            'trial' => $trial,
            'input' => $input,
            'output' => $output,
            'main' => $main_code
        );
        return response()->json($data);
    }

    public function editQuiz(Request $request){
        $quiz = Quiz::find($request->id);
        $quiz->quiz_data = $request->quiz_data;
        $quiz->quiz_ans = $request->quiz_ans;
        $quiz->quiz_score = $request->quiz_score;
        $quiz->save();
    }

    public function deleteQuiz(Request $request){
        $quiz = Quiz::find($request->id);
        $quiz->delete();
    }

    public function editSheet(Request $request){
        $folderPath = "";
        $sheet = Sheet::find($request->id);
        $getpath = explode("/",$sheet->sheet_trial);
        for($i=0;$i<sizeof($getpath)-1;$i++){
            $folderPath = $folderPath.$getpath[$i]."/";
        }
        $this->rrmdir($folderPath);
        $sheet->sheet_group_id = $request->sheet_group_id;
        $sheet->sheet_name = $request->sheet_name;
        $sheet->objective = $request->objective;
        $sheet->theory = $request->theory;
        $sheet->notation = $request->notation;
        $sheet->sheet_trial = $request->sheet_trial;
        $sheet->sheet_input_file = $request->sheet_input_file;
        $sheet->sheet_output_file = $request->sheet_output_file;
        $sheet->main_code = $request->main_code;
        $sheet->case_sensitive = $request->case_sensitive;
        $sheet->full_score = $request->full_score;
        $sheet->save();
    }

    public function deleteSharedSheet(Request $request){
        $selectShared = DB::table('share_sheets')
            ->where('sheet_id',$request->sheet_id)
            ->where('user_id',$request->user_id)
            ->first();
        $delShared = ShareSheet::find($selectShared->id);
        $delShared->delete();
    }

    public function updateSharedSheet(Request $request){
        $shared = ShareSheet::where('sheet_id',$request->sheet_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($shared === NULL) {
            $newShared = new ShareSheet;
            $newShared->sheet_id = $request->sheet_id;
            $newShared->user_id = $request->user_id;
            $newShared->save();
        }
    }

    public function deleteSheet(Request $request){
        $sheet = Sheet::find($request->id);
        $folderPath = "";
        $getpath = explode("/",$sheet->sheet_trial);
        for($i=0;$i<sizeof($getpath)-1;$i++){
            $folderPath = $folderPath.$getpath[$i]."/";
        }
        $this->rrmdir($folderPath);
        $sheet->delete();
    }

    public function findSheetGroupSharedNotMe(Request $request){
        $sheet_groups = DB::table('share_sheets')
            ->join('sheets', 'share_sheets.sheet_id', '=', 'sheets.id')
            ->join('sheet_groups', 'sheets.sheet_group_id', '=', 'sheet_groups.id')
            ->join('users', 'sheet_groups.user_id', '=', 'users.id')
            ->select('sheet_groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('share_sheets.user_id',$request->my_id)
            ->where('sheet_groups.user_id','!=',$request->my_id)
            ->groupBy('sheet_groups.id')
            ->orderBy('creater', 'asc')
            ->orderBy('sheet_groups.sheet_group_name', 'asc')
            ->get();
        return response()->json($sheet_groups);
    }

    public function findSheetSharedToMe(Request $request){
        $sheet = DB::table('share_sheets')
            ->join('sheets', 'share_sheets.sheet_id', '=', 'sheets.id')
            ->select('sheets.*')
            ->where('share_sheets.user_id',$request->user_id)
            ->where('sheets.sheet_group_id',$request->sheet_group_id)
            ->orderBy('sheets.sheet_name', 'asc')
            ->get();
        return response()->json($sheet);
    }

    public function findSheetGroupSharedToMe(Request $request){
        $section = DB::table('share_sheets')
            ->join('sheets', 'share_sheets.sheet_id', '=', 'sheets.id')
            ->join('sheet_groups', 'sheets.sheet_group_id', '=', 'sheet_groups.id')
            ->join('users', 'sheet_groups.user_id', '=', 'users.id')
            ->select('sheet_groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('share_sheets.user_id',$request->my_id)
            ->groupBy('sheet_groups.id')
            ->orderBy('creater', 'asc')
            ->orderBy('sheet_groups.sheet_group_name', 'asc')
            ->get();
        return response()->json($section);
    }

    public function findAllSheetSharedToMe(Request $request){
        $sheet = DB::table('share_sheets')
            ->join('sheets', 'share_sheets.sheet_id', '=', 'sheets.id')
            ->select('sheets.*')
            ->where('share_sheets.user_id',$request->my_id)
            ->orderBy('sheets.sheet_name', 'asc')
            ->get();
        return response()->json($sheet);
    }

    public function checkPermissionEditSheet(Request $request){
        $exam = Sheet::where('id',$request->sheet_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($exam === NULL) {
            return 404;
        }
    }

    public function checkPermissionCopySheet(Request $request){
        $exam = ShareSheet::where('sheet_id',$request->sheet_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($exam === NULL) {
            return 404;
        }
    }

    public function findSheetUsed(Request $request){
        $sheetUsed = DB::select('SELECT groups.group_name
                                FROM groups INNER JOIN (
                                    SELECT b.group_id
                                    FROM (
                                      SELECT sheetings.group_id,a.sheeting_id
                                      FROM sheetings INNER JOIN (
	                                    SELECT sheet_sheetings.sheeting_id 
                                        FROM sheet_sheetings 
                                        WHERE sheet_sheetings.sheet_id = ? ) AS a ON sheetings.id = a.sheeting_id ) AS b
                                    GROUP BY b.group_id ) AS c ON groups.id = c.group_id', [$request->sheet_id]);
        return response()->json($sheetUsed);
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
