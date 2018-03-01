<?php

namespace App\Http\Controllers;

use App\Exam;
use App\PathExam;
use App\QueueExam;
use App\QueueSheet;
use App\ResExam;
use App\ResSheet;
use App\Sheet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DirectoryIterator;

use App\Http\Requests;

class CompileCsController extends Controller
{
    public function sendExamCs(Request $request){
        $no_namespace = TRUE;
        $folder_ans = "";
        $resExamID = "";
        $completeInsRes = false;
        // ถ้าพิมพ์โค้ดส่ง
        if($request->mode === "key") {
            $code = $request->code;
            // ตรวจสอบว่ามี namespace หรือเปล่า
            $no_namespace = $this->check_namespace($code);
            if($no_namespace){
                // สร้างโฟลเดอร์เก็บไฟล์ที่ส่ง
                $user = User::find($request->UID);
                $userFolder = $user->stu_id . "_" . $user->fname_en . "_" . $user->lname_en;
                $examingFolder = "Examing_" . $request->EMID;
                $examFolder = "Exam_" . $request->EID;
                $path = "../upload/res_exam/";
                // สร้างโฟลเดอร์เก็บข้อสอบที่ส่ง
                $this->makeFolder("../upload/","res_exam");
                // สร้างโฟลเดอร์ของการสอบ
                $this->makeFolder($path, $examingFolder);
                // สร้างโฟลเดอร์ของข้อสอบในการสอบ
                $this->makeFolder($path . $examingFolder . "/", $examFolder);
                // สร้างโฟลเดอร์ของนักเรียนที่ส่งข้อสอบ
                $this->makeFolder($path . $examingFolder . "/" . $examFolder . "/", $userFolder);
                $folderName = date('Ymd-His') . "_" . rand(1, 9999);
                $folder_ans = $path . $examingFolder . "/" . $examFolder . "/" . $userFolder . "/" . $folderName;
                mkdir($folder_ans, 0777, true);

                $code = stripslashes($code);
                // ตั้งชื่อไฟล์ให้เหมือนชื่อคลาส
                $file_name = $this->get_class_name($code);
                $file_ans = "$file_name.cs";

                // เขียนไฟล์
                $handle = fopen("$folder_ans/$file_ans", 'w') or die('Cannot open file:  ' . $file_ans);
                fwrite($handle, $code);
                fclose($handle);
            }
        } else {
            // แต่ถ้าส่งไฟล์โค้ดมา
            $folder_ans = $request->path;
            $files = scandir($folder_ans);
            foreach ($files as $f) {
                // ลูปเช็คทุกไฟล์ที่มีนามสกุล .cs
                if (strpos($f, '.cs') && $no_namespace) {
                    $handle = fopen("$folder_ans/$f", "r");
                    $code_in_file = fread($handle, filesize("$folder_ans/$f"));
                    fclose($handle);
                    $no_namespace = $this->check_namespace($code_in_file);
                }
            }

            if(!$no_namespace){
                // ลบไฟล์ที่ถูกส่งมา
                $files = scandir($folder_ans);
                foreach ($files as $f) {
                    @unlink("$folder_ans/$f");
                }
                rmdir($folder_ans);
            }
        }

        try{
            if ($no_namespace) {
                // บันทึกลงฐานข้อมูล ตาราง res_exams
                $resExam = ResExam::where('examing_id',$request->EMID)
                    ->where('exam_id',$request->EID)
                    ->where('user_id',$request->UID)
                    ->first();
                if($resExam === NULL){
                    $resExam = new ResExam;
                    $resExam->examing_id = $request->EMID;
                    $resExam->exam_id = $request->EID;
                    $resExam->user_id = $request->UID;
                    $resExam->current_status = "q";
                    $resExam->sum_accep = 0;
                    $resExam->sum_imp = 0;
                    $resExam->sum_wrong = 0;
                    $resExam->sum_comerror = 0;
                    $resExam->sum_overtime = 0;
                    $resExam->sum_overmem = 0;
                    $resExam->save();
                    $insertedId = $resExam->id;
                    $resExamID = $insertedId;
                } else {
                    $resExamID = $resExam->id;
                }
                $completeInsRes = true;

                // บันทึกลงฐานข้อมูล ตาราง path_exams
                $pathExam = new PathExam;
                $pathExam->res_exam_id = $resExamID;
                $pathExam->path = $folder_ans;
                $pathExam->status = "q";
                $pathExam->send_date_time = $request->send_date_time;
                $pathExam->file_type = "cs";
                $pathExam->ip = $_SERVER['REMOTE_ADDR'];
                $pathExam->save();
                $insertedId = $pathExam->id;
                $pathExamID = $insertedId;

                // บันทึกลงฐานข้อมูล queue_exams
                $Queue = new QueueExam;
                $Queue->path_exam_id = $pathExamID;
                $Queue->file_type = "cs";
                $Queue->save();
                return response()->json($pathExamID);
            } else {
                return response()->json(['error' => 'Error msg'], 209);
            }
        } catch(\Exception $e ){
            if($completeInsRes){
                $delResExam = ResExam::find($resExamID);
                $delResExam->delete();
            }

            // ลบไฟล์ที่ส่งมา
            $files = scandir($folder_ans);
            foreach ($files as $f) {
                @unlink("$folder_ans/$f");
            }
            rmdir($folder_ans);

            return response()->json(['error' => 'Error msg'], 210);
        }
    }

    public function sendSheetCs(Request $request){
        $no_namespace = TRUE;
        $folder_ans = "";
        $resSheetID = "";
        $completeInsRes = false;
        // ถ้าพิมพ์โค้ดส่ง
        if($request->mode === "key") {
            $code = $request->code;
            // ตรวจสอบว่ามี namespace หรือเปล่า
            $no_namespace = $this->check_namespace($code);
            if ($no_namespace) {
                // สร้างโฟลเดอร์เก็บไฟล์ที่ส่ง
                $user = User::find($request->UID);
                $userFolder = $user->stu_id . "_" . $user->fname_en . "_" . $user->lname_en;
                $sheetingFolder = "Sheeting_" . $request->STID;
                $sheetFolder = "Sheet_" . $request->SID;
                $path = "../upload/res_sheet/";
                // สร้างโฟลเดอร์เก็บใบงานที่ส่ง
                $this->makeFolder("../upload/","res_sheet");
                // สร้างโฟลเดอร์ของการสั่งงาน
                $this->makeFolder($path, $sheetingFolder);
                // สร้างโฟลเดอร์ของใบงานในการสั่งงาน
                $this->makeFolder($path . $sheetingFolder . "/", $sheetFolder);
                // สร้างโฟลเดอร์ของนักเรียนที่ส่งใบงาน
                $this->makeFolder($path . $sheetingFolder . "/" . $sheetFolder . "/", $userFolder);
                $folderName = date('Ymd-His') . "_" . rand(1, 9999);
                $folder_ans = $path . $sheetingFolder . "/" . $sheetFolder . "/" . $userFolder . "/" . $folderName;
                mkdir($folder_ans, 0777, true);

                // สร้างไฟล์เก็บโค้ดที่ส่งมา
                $code = stripslashes($code);

                // ตั้งชื่อไฟล์ให้เหมือนชื่อคลาส
                $file_name = $this->get_class_name($code);
                $file_ans = "$file_name.cs";

                // เขียนไฟล์
                $handle = fopen("$folder_ans/$file_ans", 'w') or die('Cannot open file:  ' . $file_ans);
                fwrite($handle, $code);
                fclose($handle);
            }
        } else {
            // แต่ถ้าส่งไฟล์โค้ดมา
            $folder_ans = $request->path;
            $files = scandir($folder_ans);
            foreach ($files as $f) {
                // ลูปเช็คทุกไฟล์ที่มีนามสกุล .cs
                if (strpos($f, '.cs') && $no_namespace) {
                    $handle = fopen("$folder_ans/$f", "r");
                    $code_in_file = fread($handle, filesize("$folder_ans/$f"));
                    fclose($handle);
                    $no_namespace = $this->check_namespace($code_in_file);
                }
            }

            if(!$no_namespace){
                // ลบไฟล์ที่ถูกส่งมา
                $files = scandir($folder_ans);
                foreach ($files as $f) {
                    @unlink("$folder_ans/$f");
                }
                rmdir($folder_ans);
            }
        }

        try{
            if ($no_namespace) {
                // บันทึกลงฐานข้อมูล ตาราง res_sheets
                $resSheet = ResSheet::where('sheeting_id', $request->STID)
                    ->where('sheet_id', $request->SID)
                    ->where('user_id', $request->UID)
                    ->first();
                if ($resSheet === NULL) {
                    $resSheet = new ResSheet;
                    $resSheet->sheeting_id = $request->STID;
                    $resSheet->sheet_id = $request->SID;
                    $resSheet->user_id = $request->UID;
                    $resSheet->file_type = "cs";
                    $resSheet->current_status = "q";
                    $resSheet->send_late = $request->send_late;
                    $resSheet->path = $folder_ans;
                    $resSheet->send_date_time = $request->send_date_time;
                    $resSheet->save();
                    $insertedId = $resSheet->id;
                    $resSheetID = $insertedId;
                } else {
                    $this->rrmdir($resSheet->path);
                    $resSheetID = $resSheet->id;
                    $resSheet->current_status = "q";
                    $resSheet->file_type = "cs";
                    $resSheet->send_late = $request->send_late;
                    $resSheet->path = $folder_ans;
                    $resSheet->send_date_time = $request->send_date_time;
                    $resSheet->save();
                }
                $completeInsRes = true;

                // บันทึกลงฐานข้อมูล ready_queue_shes
                $readyQueue = new QueueSheet;
                $readyQueue->res_sheet_id = $resSheetID;
                $readyQueue->file_type = "cs";
                $readyQueue->save();
                return response()->json($resSheetID);
            } else {
                return response()->json(['error' => 'Error msg'], 209);
            }
        } catch(\Exception $e ){
            if($completeInsRes){
                $delResExam = ResSheet::find($resSheetID);
                $delResExam->delete();
            }

            // ลบไฟล์ที่ส่งมา
            $files = scandir($folder_ans);
            foreach ($files as $f) {
                @unlink("$folder_ans/$f");
            }
            rmdir($folder_ans);

            return response()->json(['error' => 'Error msg'], 210);
        }
    }

    public function createBatFile(Request $request){
        $folder_ans = "";
        if($request->mode == "exam"){
            $pathExam = PathExam::find($request->pathExamID);
            $folder_ans = $pathExam->path;
        } else if($request->mode == "sheet"){
            $resSheet = ResSheet::find($request->pathSheetID);
            $folder_ans = $resSheet->path;
        }

        // ตรวจหาเมธอด Main() ในโฟลเดอร์ แล้วเก็บ ชื่อไฟล์ที่มีเมธอด Main() ใน $file_main
        $files = scandir($folder_ans);
        $file_main = null;
        $str_file = "";
        foreach ($files as $f) {
            if (strpos($f, '.cs')) {
                // ถ้าในไฟล์นี้ มีเมธอด Main()
                if ($this->is_main("$folder_ans/$f")) {
                    $file_main = $f;
                } else {
                    $str_file = $str_file.$f." ";
                }
            }
        }

        // ถ้าเจอเมธอด Main() ในโฟลเดอร์
        if ($file_main) {
            // อ่านโค้ดในไฟล์
            $myfile = fopen("$folder_ans/$file_main", 'r');
            $origin_code = fread($myfile, filesize("$folder_ans/$file_main"));
            fclose($myfile);

            // แก้ชื่อคลาสเป็น WEPP_Main
            $class_name = $this->get_class_name($origin_code);
            $pos_begin_class_name = strpos($origin_code, $class_name);
            $new_class_code = substr_replace($origin_code, 'WEPP', $pos_begin_class_name, strlen($class_name));

            // เพิ่มโค้ดส่วนการเช็คลูป เช็คเมมโมรี่ เช็คเวลา
            $path_input = "";
            if($request->mode == "exam") {
                $exam = Exam::find($request->exam_id);
                $path_input = $exam->exam_input_file;
            } else if($request->mode == "sheet") {
                $sheet = Sheet::find($request->sheet_id);
                $path_input = $sheet->sheet_input_file;
            }
            $code_add_checker = $this->add_check_code($new_class_code,$path_input);

            // เก็บไว้ในไฟล์ชื่อ Main.cs
            $file = 'Main';
            $handle = fopen("$folder_ans/$file.cs", 'w') or die('Cannot open file:  ' . $file);
            fwrite($handle, $code_add_checker);

            // สร้าง bat file เพื่อ compile
            $bat = $this->create_bat($folder_ans,$file,$str_file);
            return response()->json($bat);
        } else {
            // ถ้าไม่พบเมธอด main() ในโค้ดที่ส่ง
            // ตรวจสอบว่า เป็นข้อสอบเขียนคลาส หรือไม่
            $file_main = "";
            if($request->mode == "exam") {
                $exam = Exam::find($request->exam_id);
                $file_main = $exam->main_code;
            } else if($request->mode == "sheet") {
                $sheet = Sheet::find($request->sheet_id);
                $file_main = $sheet->main_code;
            }

            if ($file_main) {
                // อ่านโค้ดในไฟล์
                $myfile = fopen($file_main, 'r');
                $origin_code = fread($myfile, filesize($file_main));
                fclose($myfile);

                // แก้ชื่อคลาสเป็น WEPP
                $class_name = $this->get_class_name($origin_code);
                $pos_begin_class_name = strpos($origin_code, $class_name);
                $new_class_code = substr_replace($origin_code, "WEPP", $pos_begin_class_name, strlen($class_name));

                // เพิ่มโค้ดส่วนการเช็คลูป เช็คเมมโมรี่ เช็คเวลา
                $path_input = "";
                if($request->mode == "exam") {
                    $exam = Exam::find($request->exam_id);
                    $path_input = $exam->exam_input_file;
                } else if($request->mode == "sheet") {
                    $sheet = Sheet::find($request->sheet_id);
                    $path_input = $sheet->sheet_input_file;
                }
                $code_add_checker = $this->add_check_code($new_class_code,$path_input);

                // เก็บไว้ในไฟล์ชื่อ Main.cs
                $file = 'Main';
                $handle = fopen("$folder_ans/$file.cs", 'w') or die('Cannot open file:  ' . $file);
                fwrite($handle, $code_add_checker);

                // สร้าง bat file เพื่อ compile
                $bat = $this->create_bat($folder_ans,$file,$str_file);
                return response()->json($bat);
            }
        }
    }

    public function compileAndRunCs(Request $request){
        $status = "";
        $folder_ans = "";
        if($request->mode == "exam"){
            $pathExam = PathExam::find($request->pathExamID);
            $folder_ans = $pathExam->path;
        } else if($request->mode == "sheet"){
            $resSheet = ResSheet::find($request->pathSheetID);
            $folder_ans = $resSheet->path;
        }

        // ตรวจสอบว่ามีไฟล์ .bat หรือเปล่า
        $files = scandir($folder_ans);
        $bat = false;
        foreach ($files as $f) {
            if (strpos($f, '.bat')) {
                $bat = true;
            }
        }

        $ans = "";
        if($bat){
            // ถ้ามีให้คอมไพล์โค้ดที่ส่ง
            $this->compile_code($request->pathBat);

            // ตรวจสอบการคอมไพล์(มีไฟล์ weep_ex.exe ไหม)
            if (file_exists("$folder_ans/wepp_ex.exe")) {
                // คิวรี่ ไฟล์อินพุทของข้อสอบ
                $input = "";
                if($request->mode == "exam") {
                    $exam = Exam::find($request->exam_id);
                    if (strlen($exam->exam_input_file) > 0) {
                        $handle = fopen($exam->exam_input_file, "r");
                        $input = fread($handle, filesize($exam->exam_input_file));
                        fclose($handle);

//                        $input_spilt = explode("\n",$input);
//                        $pos_count_loop = strpos($input,$input_spilt[0]);
//                        $input = substr_replace($input,"",$pos_count_loop,strlen($input_spilt[0])+1);
                    }
                } else if($request->mode == "sheet"){
                    $sheet = Sheet::find($request->sheet_id);
                    if (strlen($sheet->sheet_input_file) > 0) {
                        $handle = fopen($sheet->sheet_input_file, "r");
                        $input = fread($handle, filesize($sheet->sheet_input_file));
                        fclose($handle);

//                        $input_spilt = explode("\n",$input);
//                        $pos_count_loop = strpos($input,$input_spilt[0]);
//                        $input = substr_replace($input,"",$pos_count_loop,strlen($input_spilt[0])+1);
                    }
                }

                // รันโค้ดที่ส่ง
                $lines_run = $this->run_code($input,$folder_ans);

                // ตรวจสอบคำตอบ
                $checker = "";
                if($request->mode == "exam") {
                    $checker = $this->check_correct_ans_ex($lines_run, $request->exam_id);
                } else if($request->mode == "sheet") {
                    $checker = $this->check_correct_ans_sh($lines_run, $request->sheet_id);
                }

                // เครียร์ไฟล์ขยะ (*.bat)
                $this->clearFolderAns($folder_ans);

                // อัพเดตสถานะการส่ง เป็นสถานะที่เช็คได้
                if($request->mode == "exam"){
                    $status = $this->update_resexam($request->pathExamID,$request->exam_id,$checker,$folder_ans);
                } else if($request->mode == "sheet") {
                    $status = $this->update_resworksheet($request->pathSheetID,$request->sheet_id,$checker,$folder_ans);
                }
            } else {
                // ไม่เเจอ wepp_ex.exe
                if($request->mode == "exam"){
                    $checker = array("status" => "c", "res_run" => null, "time" => null, "mem" => null);
                    $status = $this->update_resexam($request->pathExamID,$request->exam_id,$checker,$folder_ans);
                } else if($request->mode == "sheet") {
                    $checker = array("status" => "c", "res_run" => null, "time" => null, "mem" => null);
                    $status = $this->update_resworksheet($request->pathSheetID,$request->sheet_id,$checker,$folder_ans);
                }
            }
        } else {
            // ไม่เจอไฟล์ bat
            if($request->mode == "exam"){
                $checker = array("status" => "c", "res_run" => null, "time" => null, "mem" => null);
                $status = $this->update_resexam($request->pathExamID,$request->exam_id,$checker,$folder_ans);
            } else if($request->mode == "sheet") {
                $checker = array("status" => "c", "res_run" => null, "time" => null, "mem" => null);
                $status = $this->update_resworksheet($request->pathSheetID,$request->sheet_id,$checker,$folder_ans);
            }
        }
        return response()->json($status);
    }

    function check_namespace($code) {
        $class = $this->get_class_name($code);
        if ($class) {
            $pos_begin_class = strpos($code, $class);
            $code_before_class = substr($code, 0, $pos_begin_class);

            if (is_int(strpos($code_before_class, 'namespace '))) {
                return FALSE;
            }
        }
        return TRUE;
    }

    function get_class_name($code) {
        $tmp = explode('class', $code);
        $tmp2 = explode('{', $tmp[1]);
        $class = trim($tmp2[0]);
        return $class ? $class : FALSE;
    }

    function is_main($file) {
        $myfile = fopen($file, 'r') or die('ERROR_CODE : UNABLE_TO_OPEN_FILE');
        $code = fread($myfile, filesize($file));
        fclose($myfile);

        if (strpos($code, 'Main') != FALSE) {
            return true;
        }
        return false;
    }

    function add_check_code($code,$path_input) {
//        $count_loop = 1;
//
//        if (strlen($path_input) > 0) {
//            $handle = fopen($path_input, "r");
//            $input = fread($handle, filesize($path_input));
//            fclose($handle);
//
//            $input_split = explode('\n',$input);
//            $count_loop = (int)trim($input_split[0]);
//        }
//
//        $sss = 'for (int i = 0 ; i < '.$count_loop.' ; i++){
//
//                            }';

        $using_code = 'using System.Management;
                       using System.Threading;
                       using System.Diagnostics;
                       ';

        $str_check_code = 'static Thread timeThr = new Thread(new ThreadStart(TimerThread));
                        static Thread runThr = new Thread(new ThreadStart(RunThread));		       
		                static void TimerThread(){
			                Thread.Sleep(3000);
			                runThr.Abort();
			                Console.WriteLine("OverTime");
			                System.Environment.Exit(0);
		                }
                        static void RunThread(){
                            var watch = System.Diagnostics.Stopwatch.StartNew();
                            long memoryBefore = System.Diagnostics.Process.GetCurrentProcess().PrivateMemorySize64;
                            long memoryAfter = System.Diagnostics.Process.GetCurrentProcess().PrivateMemorySize64;
                            timeThr.Abort();
                            watch.Stop();
                            var elapsedMs = watch.ElapsedMilliseconds;
                            Console.WriteLine("UsedMem : {0}",(memoryAfter-memoryBefore)/1024);
                            Console.WriteLine("Runtime : {0}",elapsedMs/1000.00);
                        }';

        // เก็บโค้ด ที่อยู่ในเมธอด main
        $code_in_main = $this->get_code_in_main($code);

        // นำโค้ดที่อยู่ในเมธอด main ใส่ในเทรดการรัน
        $pos_add_code = strpos($str_check_code, "long memoryAfter") - 1;
//        $pos_add_code = strpos($str_check_code, "}long memoryAfter") - 1;
        $check_code = substr_replace($str_check_code, $code_in_main["code"], $pos_add_code, 0);

        // ตัดโค้ด ที่อยู่ในเมธอด main ออก
        $code_cut_in_main = str_replace($code_in_main["code"], "", $code);

        // เพิ่มโค้ด สั่งรันเทรด ให้เมธอด main
        $code_add_in_main = substr_replace($code_cut_in_main, "timeThr.Start(); runThr.Start();", $code_in_main["begin"], 0);

        // เพิ่มโค้ดที่ใช้เช็คหน่วยความจำ เวลา และลูปไม่รู้จบ
        $pos_bracket_class = strpos($code_add_in_main, "{");
        $full_code = substr_replace($code_add_in_main, $check_code, $pos_bracket_class + 1, 0);
        $complete_code = substr_replace($full_code,$using_code,0,0);

        return $complete_code;
    }

    function get_code_in_main($code) {
        $res = array();

        $pos_main = strpos($code, "Main");
        $code_from_main = substr($code, $pos_main);

        $pos_bracket_main = strpos($code_from_main, "{") + $pos_main;
        $braket = array();

        for ($i = $pos_bracket_main; $i < strlen($code); $i++) {
            if ($code[$i] == "{") {
                array_push($braket, "{");
            } else if ($code[$i] == "}") {
                array_pop($braket);
                if (empty($braket)) {
                    $res["begin"] = $pos_bracket_main + 1;
                    $res["length"] = $i - $pos_bracket_main - 1;
                    $res["code"] = substr($code, $res["begin"], $res["length"]);
                    return $res;
                }
            }
        }
        return FALSE;
    }

    function create_bat($folder_code,$file_main,$str_file){
        exec("Taskkill /IM wepp_ex.exe /F");

        // ค้าหาพาร์ทของไฟล์ที่จะคอมไฟล์
        $dir = getcwd();
        $dir_split = explode("\\",$dir);
        $dir_code = "";
        for($i = 0;$i<sizeof($dir_split)-1;$i++){
            $dir_code = $dir_code.$dir_split[$i]."\\";
        }
        $dir_split = explode("/",$folder_code);
        for($i = 1;$i<sizeof($dir_split);$i++){
            $dir_code = $dir_code.$dir_split[$i].'\\';
        }
        $cmd = "cd $dir_code";

        // สร้างไฟล์ .bat สำหรับการคอมไพล์
        $file_bat = 'compile.bat';
        $openfile = fopen("$folder_code/$file_bat", 'w');
        fwrite($openfile, $cmd . " \n csc -t:exe -out:wepp_ex.exe $file_main.cs ".$str_file."");
        fclose($openfile);

        exec($dir_code.$file_bat);
        return $dir_code.$file_bat;
    }

    function compile_code($path_bat) {
        exec($path_bat);
    }

    function run_code($input,$folder_ans){
        $resoutput = "";
        $descriptorspec = array(
            0 => array("pipe", "r"), // stdin is a pipe that the child will read from
            1 => array("pipe", "w"), // stdout is a pipe that the child will write to
            2 => array("file", "ex.txt", "a") // stderr is a file to write to
        );

        $dir = getcwd();
        $dir_split = explode("\\",$dir);
        $dir_code = "";
        for($i = 0;$i<sizeof($dir_split)-1;$i++){
            $dir_code = $dir_code.$dir_split[$i]."\\";
        }
        $dir_split = explode("/",$folder_ans);
        for($i = 1;$i<sizeof($dir_split);$i++){
            $dir_code = $dir_code.$dir_split[$i]."\\";
        }
        $cwd = $dir_code;
        $process = proc_open('wepp_ex.exe', $descriptorspec, $pipes, $cwd);
        if (is_resource($process)) {
            fwrite($pipes[0], $input);
            fclose($pipes[0]);
            $resoutput = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $return_value = proc_close($process);
        }

        unlink('ex.txt');

        return $resoutput;
    }

    function check_correct_ans_ex($lines_run, $exam_id){
        $exam = Exam::find($exam_id);
        $run = $this->prepare_result($lines_run);

        if ($run == 'OverTime') {
            return array("status" => "t", "res_run" => 'Over time', "time" => 0, "mem" => 0);
        } else {
            // อ่านไฟล์ output ของ Teacher
            $file_output = $exam->exam_output_file;
            $handle = fopen("$file_output", "r");
            $output_teacher = trim(fread($handle, filesize("$file_output")));
            fclose($handle);

            // คิดคำตอบเหมือน output กี่เปอร์เซ็นต์
            $percent_equal = $this->check_percentage_ans($this->modify_output($output_teacher), $this->modify_output($run['res_run']), $exam->case_sensitive);

            if ($percent_equal == 100) {
                return array("status" => "a", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 89) {
                return array("status" => "9", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 79) {
                return array("status" => "8", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 69) {
                return array("status" => "7", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 59) {
                return array("status" => "6", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 49) {
                return array("status" => "5", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            }

            // ถ้าน้อยกว่า 50% ถือว่า wrong answer
            return array("status" => "w", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
        }
    }

    function check_correct_ans_sh($lines_run, $sheet_id) {
        $sheet = Sheet::find($sheet_id);
        $run = $this->prepare_result($lines_run);

        if ($run == 'OverTime') {
            return array("status" => "t", "res_run" => 'Over time', "time" => 0, "mem" => 0);
        } else {
            // อ่านไฟล์ output ของ Teacher
            $file_output = $sheet->sheet_output_file;
//            $output_teacher = file($file_output);
            $handle = fopen("$file_output", "r");
            $output_teacher = trim(fread($handle, filesize("$file_output")));
            fclose($handle);

            // คิดคำตอบเหมือน output กี่เปอร์เซ็นต์
            $percent_equal = $this->check_percentage_ans($this->modify_output($output_teacher), $this->modify_output($run['res_run']), $sheet->case_sensitive);

            if ($percent_equal == 100) {
                return array("status" => "a", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 89) {
                return array("status" => "9", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 79) {
                return array("status" => "8", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 69) {
                return array("status" => "7", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 59) {
                return array("status" => "6", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            } else if ($percent_equal > 49) {
                return array("status" => "5", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
            }

            // ถ้าน้อยกว่า 50% ถือว่า wrong answer
            return array("status" => "w", "res_run" => $run['res_run'], "time" => $run['time'], "mem" => $run['mem']);
        }
    }

    function prepare_result($result_run) {
        $checkSuccess = $checkOverTime = false;
        $res_run = '';
        $time = 0;
        $mem = 0;

        if(strpos($result_run,'UsedMem :') && strpos($result_run,'Runtime :')){
            $checkSuccess = true;
        } else if(strpos($result_run,'OverTime')){
            $checkOverTime = true;
        }

        if ($checkOverTime){
            return "OverTime";
        } else if($checkSuccess){
            $arr_res_run1 = explode('UsedMem : ',$result_run);
            $res_run = trim($arr_res_run1[0]);
            $arr_res_run2 = explode('Runtime : ',$arr_res_run1[1]);
            $mem = trim($arr_res_run2[0]);
            $time = trim($arr_res_run2[1]);
            return array('res_run' => $res_run, 'mem' => $mem, 'time' => $time);
        }
    }

    function check_percentage_ans($output_teacher, $output_run, $is_case_sensitive) {
        $count_check = 0;
        for ($i = 0; ($i < strlen($output_run) || $i < strlen($output_teacher)); $i++ ){
            try {
                // ในกรณีไม่คิด Case sensitive
                if (!$is_case_sensitive) {
                    $output_run[$i] = strtolower($output_run[$i]);
                    $output_teacher[$i] = strtolower($output_teacher[$i]);
                }
                if (($output_teacher[$i]) && ($output_run[$i])) {
                    if ($output_teacher[$i] == $output_run[$i]) {
                        $count_check++;
                    }
                }
            } catch(\Exception $e ){}
        }

        if (strlen($output_run) > strlen($output_teacher)) {
            return $count_check * 100 / strlen($output_run);
        } else {
            return $count_check * 100 / strlen($output_teacher);
        }
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

    function update_resexam($path_exam_id, $exam_id, $checker,$folder_ans) {
        // เขียนไฟล์ผลการรันลงในโฟลเดอร์
        $handle = fopen("$folder_ans/resrun.txt", 'w') or die('Cannot open file:  resrun.txt');
        fwrite($handle, $checker["res_run"]);
        fclose($handle);

        $exam = Exam::find($exam_id);

        $resExamID = "";
        // อัพเดทข้อมูลใน table path_exam
        $pathExam = PathExam::find($path_exam_id);
        $resExamID = $pathExam->res_exam_id;
        $pathExam->res_run = "$folder_ans/resrun.txt";
        $pathExam->status = $checker["status"];
        $pathExam->time = $checker["time"];
        $pathExam->memory = $checker["mem"];
        $pathExam->save();

        // ค้นคำตอบที่มีเปอร์เซ็นถูกต้องเยอะที่สุด จากที่เคยส่ง
        $statusImp = DB::select('SELECT status 
                                  FROM (SELECT * FROM path_exams WHERE path_exams.res_exam_id = ?) AS s 
                                  WHERE s.status = "9" 
                                  OR s.status = "8" 
                                  OR s.status = "7" 
                                  OR s.status = "6" 
                                  OR s.status = "5" 
                                  GROUP BY s.status',[$resExamID]);
        $maxPercent = 0;
        if($statusImp){
            foreach($statusImp as $status){
                if($status->status == 9) {
                    if($maxPercent < 9) $maxPercent = 9;
                } else if ($status->status == 8){
                    if($maxPercent < 8) $maxPercent = 8;
                } else if ($status->status == 7){
                    if($maxPercent < 7) $maxPercent = 7;
                } else if ($status->status == 6){
                    if($maxPercent < 6) $maxPercent = 6;
                } else if ($status->status == 5){
                    if($maxPercent < 5) $maxPercent = 5;
                }
            }
        }

        $cutScore = 0;
        // ค้นหาการส่งข้อสอบ
        $resExam = ResExam::find($resExamID);
        $resExam->current_status = $checker["status"];

        // คำนวนคะแนนที่ต้องถูกหัก
        $cutScore = ($exam->cut_wrongans*$resExam->sum_wrong)+($exam->cut_comerror*$resExam->sum_comerror)+($exam->cut_overmemory*$resExam->sum_overmem)+($exam->cut_overtime*$resExam->sum_overtime);

        // ถ้าสถานะเป็น ผ่าน หรือ ถูกต้องบางส่วน
        $score = 0;
        if ($checker['status'] == 'a' || is_numeric($checker['status'])) {
            if($checker['status'] == 'a'){
                $score = $exam->full_score;
                $resExam->sum_accep = $resExam->sum_accep+1;
            } else {
                if($checker['status'] > $maxPercent){
                    $score = $exam->full_score * $checker['status'] / 10;
                } else {
                    $score = $exam->full_score * $maxPercent / 10;
                }
                $resExam->sum_imp = $resExam->sum_imp+1;
            }
        } else {
            $score = $exam->full_score * $maxPercent / 10;
            if($checker['status'] == 'w'){
                $cutScore += $exam->cut_wrongans;
                $resExam->sum_wrong = $resExam->sum_wrong+1;
            }
            else if($checker['status'] == 't'){
                $cutScore += $exam->cut_overtime;
                $resExam->sum_overtime = $resExam->sum_overtime+1;
            }
            else if($checker['status'] == 'm'){
                $cutScore += $exam->cut_overmemory;
                $resExam->sum_overmem = $resExam->sum_overmem+1;
            }
            else if($checker['status'] == 'c'){
                $cutScore += $exam->cut_comerror;
                $resExam->sum_comerror = $resExam->sum_comerror+1;
            }
        }
        $resExam->score = $score - $cutScore > 0 ? $score - $cutScore : 0;
        $resExam->save();
        return $checker['status'];
    }

    function update_resworksheet($path_sheet_id, $sheet_id, $checker,$folder_ans) {
        // เขียนไฟล์ผลการรันลงในโฟลเดอร์
        $handle = fopen("$folder_ans/resrun.txt", 'w') or die('Cannot open file:  resrun.txt');
        fwrite($handle, $checker["res_run"]);
        fclose($handle);

        $sheet = Sheet::find($sheet_id);

        $resSheetID = $path_sheet_id;

        // ค้นหาการส่งข้อสอบ
        $resSheet = ResSheet::find($resSheetID);
        $resSheet->current_status = $checker["status"];
        $resSheet->res_run = "$folder_ans/resrun.txt";

        // ถ้าสถานะเป็น ผ่าน หรือ ถูกต้องบางส่วน
        $score = 0;
        if ($checker['status'] == 'a' || is_numeric($checker['status'])) {
            if($checker['status'] == 'a'){
                $score = $sheet->full_score;
            } else {
                $score = $sheet->full_score * $checker['status'] / 10;
            }
        }
        $resSheet->score = $score;
        $resSheet->save();
        return $checker['status'];
    }

    function makeFolder($path,$folder) {
        $dirList = scandir($path);
        if (!in_array((string) $folder, (array) $dirList)) {
            mkdir($path.$folder, 0777, true);
        }
    }

    function clearFolderAns($folder_ans) {
        $files = scandir($folder_ans);

        // ลูปลบไฟล์ที่นามสกุลไม่ใช่ .cs และ Main.cs
        foreach ($files as $f) {
            if (!strpos($f, '.cs') || $f == 'Main.cs') {
                @unlink("$folder_ans/$f");
            }
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
}
