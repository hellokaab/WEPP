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

class CompileCppController extends Controller
{
    public function sendExamCpp(Request $request){
        $no_comment = true;
        $folder_ans = "";
        $resExamID = "";
        $completeInsRes = false;
        // ถ้าพิมพ์โค้ดส่ง
        if($request->mode === "key") {
            $code = $request->code;
            // ตรวจสอบว่ามี comment หรือไม่
            $no_comment = $this->check_comment($code);
            if ($no_comment) {
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

                // ตั้งชื่อว่า resexam
                $file_name = "resexam";
                $file_ans = "$file_name.cpp";

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
                // ลูปเช็คทุกไฟล์ที่มีนามสกุล .c
                $file_ans = $f;
                if (strpos($f, '.cpp') && $no_comment) {
                    $handle = fopen("$folder_ans/$f", "r");
                    $code_in_file = fread($handle, filesize("$folder_ans/$f"));
                    fclose($handle);
                    $no_comment = $this->check_comment($code_in_file);
                }

                if(!$no_comment){
                    // ลบไฟล์ที่ถูกส่งมา
                    $files = scandir($folder_ans);
                    foreach ($files as $f) {
                        @unlink("$folder_ans/$f");
                    }
                    rmdir($folder_ans);
                }
            }
        }

        try{
            if ($no_comment) {
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
                $pathExam = new PathExam();
                $pathExam->res_exam_id = $resExamID;
                $pathExam->path = $folder_ans;
                $pathExam->status = "q";
                $pathExam->send_date_time = $request->send_date_time;
                $pathExam->file_type = "cpp";
                $pathExam->ip = $_SERVER['REMOTE_ADDR'];
                $pathExam->save();
                $insertedId = $pathExam->id;
                $pathExamID = $insertedId;

                // บันทึกลงฐานข้อมูล queue_exams
                $Queue = new QueueExam();
                $Queue->path_exam_id = $pathExamID;
                $Queue->file_type = "cpp";
                $Queue->save();
                return response()->json($pathExamID);
            } else {
                return response()->json(['error' => 'Error msg'], 209);
            }
        } catch( \Exception $e ){
            if($completeInsRes){
                $delResExam = ResExam::find($resExamID);
                $delResExam->delete();

                // ลบไฟล์ที่ส่งมา
                $files = scandir($folder_ans);
                foreach ($files as $f) {
                    @unlink("$folder_ans/$f");
                }
                rmdir($folder_ans);
                return response()->json(['error' => 'Error msg'], 210);
            }
        }
    }

    public function sendSheetCpp(Request $request){
        $no_comment = true;
        $folder_ans = "";
        $resSheetID = "";
        $completeInsRes = false;
        // ถ้าพิมพ์โค้ดส่ง
        if($request->mode === "key") {
            $code = $request->code;
            // ตรวจสอบว่ามี comment หรือไม่
            $no_comment = $this->check_comment($code);
            if ($no_comment) {
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

                // ตั้งชื่อว่า ressheet
                $file_name = "ressheet";
                $file_ans = "$file_name.cpp";

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
                // ลูปเช็คทุกไฟล์ที่มีนามสกุล .cpp
                $file_ans = $f;
                if (strpos($f, '.cpp') && $no_comment) {
                    $handle = fopen("$folder_ans/$f", "r");
                    $code_in_file = fread($handle, filesize("$folder_ans/$f"));
                    fclose($handle);
                    $no_comment = $this->check_comment($code_in_file);
                }

                if(!$no_comment){
                    // ลบไฟล์ที่ถูกส่งมา
                    $files = scandir($folder_ans);
                    foreach ($files as $f) {
                        @unlink("$folder_ans/$f");
                    }
                    rmdir($folder_ans);
                }
            }
        }

        try{
            if ($no_comment) {
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
                    $resSheet->file_type = "cpp";
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
                    $resSheet->file_type = "cpp";
                    $resSheet->send_late = $request->send_late;
                    $resSheet->path = $folder_ans;
                    $resSheet->send_date_time = $request->send_date_time;
                    $resSheet->save();
                }
                $completeInsRes = true;

                // บันทึกลงฐานข้อมูล ready_queue_shes
                $readyQueue = new QueueSheet;
                $readyQueue->res_sheet_id = $resSheetID;
                $readyQueue->file_type = "cpp";
                $readyQueue->save();
                return response()->json($resSheetID);
            } else {
                return response()->json(['error' => 'Error msg'], 209);
            }
        } catch( \Exception $e ){
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

    public function compileAndRunCpp(Request $request){
        $status = "";
        $folder_ans = "";
        $code_add_checker = "";
        // คิวรี่ ที่อยู่ของไฟล์ที่ส่ง
        if($request->mode == "exam"){
            $pathExam = PathExam::find($request->pathExamID);
            $folder_ans = $pathExam->path;
        } else if($request->mode == "sheet"){
            $resSheet = ResSheet::find($request->pathSheetID);
            $folder_ans = $resSheet->path;
        }

        // ดึงข้อมูลโค้ดจากไฟล์ที่ส่ง
        $files = scandir($folder_ans);
        $file = $files[2];
        $handle = fopen("$folder_ans/$file", "r");
        $code_in_file = fread($handle, filesize("$folder_ans/$file"));
        fclose($handle);

        // เพิ่มโค้ดส่วนของการเช็คเวลา เช็คเมมโมรี่
        if($request->mode == "exam") {
            $code_add_checker = $this->add_check_code($code_in_file, $request->exam_id,$request->mode);
        } else if($request->mode == "sheet"){
            $code_add_checker = $this->add_check_code($code_in_file, $request->sheet_id,$request->mode);
        }

        // เก็บไว้ในไฟล์ชื่อ ex.cpp
        $file = 'ex';
        $handle = fopen("$folder_ans/$file.cpp", 'w') or die('Cannot open file:  ' . $file);
        fwrite($handle, $code_add_checker);

        // คอมไพล์โค้ดที่ส่ง
        $this->compile_code($folder_ans);

        // ตรวจสอบการคอมไพล์(มีไฟล์ weep_ex.exe ไหม)
        if (file_exists("$folder_ans/wepp_ex.exe")) {
            // คิวรี่ input,output ของข้อสอบ
            $input = "";
            $output = "";
            if($request->mode == "exam") {
                $exam = Exam::find($request->exam_id);
                if (strlen($exam->exam_input_file) > 0) {
                    $handle = fopen($exam->exam_input_file, "r");
                    $input = fread($handle, filesize($exam->exam_input_file));
                    fclose($handle);
                }

                $handle = fopen($exam->exam_output_file, "r");
                $output = fread($handle, filesize($exam->exam_output_file));
                fclose($handle);
            } else if($request->mode == "sheet"){
                $sheet = Sheet::find($request->sheet_id);
                if (strlen($sheet->sheet_input_file) > 0) {
                    $handle = fopen($sheet->sheet_input_file, "r");
                    $input = fread($handle, filesize($sheet->sheet_input_file));
                    fclose($handle);
                }

                $handle = fopen($sheet->sheet_output_file, "r");
                $output = fread($handle, filesize($sheet->sheet_output_file));
                fclose($handle);
            }

            // รันโค้ดที่ส่ง
            $lines_run = $this->run_code($input,$folder_ans);

            // ตรวจสอบคำตอบ
            $checker = "";
            if($request->mode == "exam") {
                $checker = $this->check_correct_ans_ex($lines_run, $request->exam_id,$folder_ans);
            } else if($request->mode == "sheet") {
                $checker = $this->check_correct_ans_sh($lines_run, $request->sheet_id,$folder_ans);
            }

            // เครียร์ไฟล์ขยะ (*.exe, *.bat)
            $this->clearFolderAns($folder_ans);

            // อัพเดตสถานะการส่ง เป็นสถานะที่เช็คได้
            if($request->mode == "exam"){
                $status = $this->update_resexam($request->pathExamID,$request->exam_id,$checker,$folder_ans);
            } else if($request->mode == "sheet") {
                $status = $this->update_resworksheet($request->pathSheetID,$request->sheet_id,$checker,$folder_ans);
            }
        } else {
            // ไม่พบไฟล์ weep_ex.exe
            // อัพเดตสถานะการส่ง เป็น complie error
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

    function check_comment($code){
        if(strpos($code,'//')){
            return false;
        }
        if(strpos($code,'/*')){
            return false;
        }
        return true;
    }

    function add_check_code($code,$exam_id,$mode) {
        // กำหนดเวลาในการรันไว้ 5 วินาที
        $ruutimeIn = 5;
        // ถ้าเป็นการสอบ
        if($mode == "exam") {
            // คิวรี่ runtime ของข้อสอบ
            $exam = Exam::find($exam_id);
            $ruutimeIn = $exam->time_limit;
        }

        $resmodifile = '#include <time.h>
    #include <process.h>
    #include <io.h>
    #include <fcntl.h>
    #include <stdlib.h>
    #include <windows.h>
    ' . $code;
        $ismoethan = FALSE;
        $beforvoid = FALSE;
        $seemain = FALSE;
        $inputbegintime = FALSE;
        $couflybird = 0;
        for ($i = 8; $i < strlen($resmodifile); $i++) {
            if ($resmodifile[$i - 6] == "#" && $resmodifile[$i - 5] == "d" && $resmodifile[$i - 4] == "e" && $resmodifile[$i - 3] == "f" && $resmodifile[$i - 2] == "i" && $resmodifile[$i - 1] == "n" && $resmodifile[$i] == "e") {
                $ismoethan = TRUE;
                while (TRUE) {
                    $i++;
                    if (ord($resmodifile[$i]) == 13 || ord($resmodifile[$i]) == 10) {
                        break;
                    }
                }
            }
            if (!$seemain && $resmodifile[$i] == ">" && !$beforvoid || $ismoethan) {
                if (!$ismoethan) {
                    $ismoethan = TRUE;
                } else {

                    if ($resmodifile[$i] != " " && $resmodifile[$i] == "#" && ord($resmodifile[$i]) != 13 && ord($resmodifile[$i]) != 10) {
                        $ismoethan = FALSE;
                    } elseif ($resmodifile[$i] != " " && $resmodifile[$i] != "#" && ord($resmodifile[$i]) != 13 && ord($resmodifile[$i]) != 10) {
//echo "<br/>res[i] =|" . ord($resmodifile[$i]) . "|blakc";
                        $resmodifile = substr_replace($resmodifile, '
                static void error(char *action)
                {
                fprintf(stderr, "Error %s: %d\n", action, GetLastError());
                exit(EXIT_FAILURE);
                }
                void loop1(void *param)
                {
                int i=0;
                for(i=0;i<'.$ruutimeIn.';i++)
                {
                Sleep(1000);
                }
                exit(0);}
                '
                            , $i, 0);
                        $beforvoid = TRUE;
                        $ismoethan = FALSE;
                    }
                }
            }
            if ($beforvoid && $resmodifile[$i - 3] == "m" && $resmodifile[$i - 2] == "a" && $resmodifile[$i - 1] == "i" && $resmodifile[$i] == "n" && !$seemain) {
                $seemain = TRUE;
            }
            if ($seemain && $resmodifile[$i] == "{" && !$inputbegintime) {
                $i++;
                $resmodifile = substr_replace($resmodifile, '
            HANDLE loop_thread[1];
                loop_thread[0] = (HANDLE) _beginthread( loop1,0,NULL);
                 if (loop_thread[0] == INVALID_HANDLE_VALUE)
                    error("creating read thread");
                    clock_t begin, end;
                    double time_spent;
                    begin = clock();'
                    . '', $i, 0);
                $inputbegintime = TRUE;
                $couflybird++;
            }
            if ($inputbegintime && $resmodifile[$i - 5] == "r" && $resmodifile[$i - 4] == "e" && $resmodifile[$i - 3] == "t" && $resmodifile[$i - 2] == "u" && $resmodifile[$i - 1] == "r" && $resmodifile[$i] == "n") {
                $resmodifile = substr_replace($resmodifile, ' '
                    . ' end = clock();'
                    . 'time_spent = (double)(end - begin) / CLOCKS_PER_SEC;printf("###%f",time_spent); ', ($i - 5), 0);
                break;
            }
            if ($inputbegintime && $resmodifile[$i] == "{") {
                $couflybird++;
            }
            if ($inputbegintime && $resmodifile[$i] == "}") {
                $couflybird--;
                if ($couflybird <= 0) {
                    $resmodifile = substr_replace($resmodifile, ''
                        . 'end = clock();'
                        . 'time_spent = (double)(end - begin) / CLOCKS_PER_SEC;printf("###%f",time_spent);'
                        . 'exit(0);', $i, 0);
                    break;
                }
            }
        }

        return $resmodifile;
    }

    function compile_code($folder_code) {

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
            $dir_code = $dir_code.$dir_split[$i]."\\";
        }
        $cmd = "cd $dir_code";

        // สร้างไฟล์ .bat สำหรับการคอมไพล์
        $file_bat = 'compile.bat';
        $openfile = fopen("$folder_code/$file_bat", 'w');
        fwrite($openfile, $cmd . " \n g++ ex.cpp -o wepp_ex");
        fclose($openfile);

        exec($dir_code.$file_bat);
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

    function check_correct_ans_ex($lines_run, $exam_id,$folder_ans){
        $exam = Exam::find($exam_id);
        $run = $this->prepare_result($lines_run,$folder_ans);


        if ($run == 'OverTime') {
            return array("status" => "t", "res_run" => 'Over time', "time" => 0, "mem" => 0);
        } else if ($run['mem'] > $exam->memory_size) {
            return array("status" => "m", "res_run" => 'Over memory', "time" => $run['time'], "mem" => $run['mem']);
        } else if ($run['time'] > $exam->time_limit) {
            return array("status" => "t", "res_run" => 'Over time', "time" => $run['time'], "mem" => $run['mem']);
        } else {
            // อ่านไฟล์ output ของ Teacher
            $file_output = $exam->exam_output_file;
//            $output_teacher = file($file_output);
            $handle = fopen("$file_output", "r");
            $output_teacher = trim(fread($handle, filesize("$file_output")));
            fclose($handle);

//            return array("output_teacher" => $output_teacher, "res_run" => $run['res_run']);

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

    function check_correct_ans_sh($lines_run, $sheet_id,$folder_ans){
        $sheet = Sheet::find($sheet_id);
        $run = $this->prepare_result($lines_run,$folder_ans);


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

    function prepare_result($lines_run,$folder_ans){
        $mem = $this->calculate_memory($folder_ans);
        $outputmodi = explode("###", $lines_run);
        // ตรวจสอบกรณีที่ โปรแกรมเกิดการวนลูป
        if(strlen($outputmodi[0])> 1283276){
            return "OverTime";
        } else {
            $res_run = $outputmodi[0];
            $time = $outputmodi[1];
            return array('res_run' => trim($res_run), 'mem' => $mem, 'time' => $time);
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

        // ลูปลบไฟล์ที่นามสกุลไม่ใช่ .c
        foreach ($files as $f) {
            if (!strpos($f, '.c')) {
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

    function calculate_memory($folder_ans){
        $code_in_file= '';
        $files = scandir($folder_ans);
        foreach ($files as $f) {
            if (strpos($f, '.c') && $f != 'ex.c') {
                $handle = fopen("$folder_ans/$f", "r");
                $code_in_file = fread($handle, filesize("$folder_ans/$f"));
                fclose($handle);
            }
        }

        $bufchar = $code_in_file;

        $countint = 0;
        $countcahr = 0;
        $countlong = 0;
        $countfloat = 0;
        $countshort = 0;
        $countdouble = 0;

        for ($i = 6; $i < strlen($bufchar); $i++) {
            if ($bufchar[$i - 3] == 'i' && $bufchar[$i - 2] == 'n' && $bufchar[$i - 1] == 't' && $bufchar[$i] != 'f') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i] == ',') {
                        $countint++;
                    } elseif ($bufchar[$i] == ';') {
                        $countint++;
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 3] == 'c' && $bufchar[$i - 2] == 'h' && $bufchar[$i - 1] == 'a' && $bufchar[$i] == 'r') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i] == ',') {
                        $countcahr++;
                    } elseif ($bufchar[$i] == ';') {
                        $countcahr++;
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 3] == 'l' && $bufchar[$i - 2] == 'o' && $bufchar[$i - 1] == 'n' && $bufchar[$i] == 'g') { // long Howlong = 50; strpos($str,'long ') int int1 = 5; int int2 = 10; int int3 = 15;
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i] == ',') {
                        $countlong++;
                    } elseif ($bufchar[$i] == ';') {
                        $countlong++;
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 4] == 'f' && $bufchar[$i - 3] == 'l' && $bufchar[$i - 2] == 'o' && $bufchar[$i - 1] == 'a' && $bufchar[$i] == 't') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i] == ',') {
                        $countfloat++;
                    } elseif ($bufchar[$i] == ';') {
                        $countfloat++;
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 4] == 's' && $bufchar[$i - 3] == 'h' && $bufchar[$i - 2] == 'o' && $bufchar[$i - 1] == 'r' && $bufchar[$i] == 't') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i] == ',') {
                        $countshort++;
                    } elseif ($bufchar[$i] == ';') {
                        $countshort++;
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 5] == 'd' && $bufchar[$i - 4] == 'o' && $bufchar[$i - 3] == 'u' && $bufchar[$i - 2] == 'b' && $bufchar[$i - 1] == 'l' && $bufchar[$i] == 'e') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i] == ',') {
                        $countdouble++;
                    } elseif ($bufchar[$i] == ';') {
                        $countdouble++;
                        break;
                    }
                    $i++;
                }
            }
        }
        $countintarray = 0;
        $countcahrarray = 0;
        $countlongarrray = 0;
        $countfloatarray = 0;
        $countshortarray = 0;
        $countdoublearray = 0;
        for ($i = 6; $i < strlen($bufchar); $i++) {
            if ($bufchar[$i - 3] == 'i' && $bufchar[$i - 2] == 'n' && $bufchar[$i - 1] == 't' && $bufchar[$i] != 'f') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i - 1] == '[') {
                        $burrefsize = "";
                        while (TRUE) {
                            if ($bufchar[$i] != ']') {
                                $burrefsize = $burrefsize . $bufchar[$i];
                            } else {
                                $countint --;
                                $countintarray += number_format($burrefsize);
                                break;
                            }
                            $i++;
                        }
                    } elseif ($bufchar[$i] == ';') {
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 3] == 'c' && $bufchar[$i - 2] == 'h' && $bufchar[$i - 1] == 'a' && $bufchar[$i] == 'r') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i - 1] == '[') {
                        $burrefsize = "";
                        while (TRUE) {
                            if ($bufchar[$i] != ']') {
                                $burrefsize = $burrefsize . $bufchar[$i];
                            } else {
                                $countcahr --;
                                $countcahrarray += number_format($burrefsize);
                                break;
                            }
                            $i++;
                        }
                    } elseif ($bufchar[$i] == ';') {
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 3] == 'l' && $bufchar[$i - 2] == 'o' && $bufchar[$i - 1] == 'n' && $bufchar[$i] == 'g') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i - 1] == '[') {
                        $burrefsize = "";
                        while (TRUE) {
                            if ($bufchar[$i] != ']') {
                                $burrefsize = $burrefsize . $bufchar[$i];
                            } else {
                                $countlong --;
                                $countlongarrray += number_format($burrefsize);
                                break;
                            }
                            $i++;
                        }
                    } elseif ($bufchar[$i] == ';') {
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 4] == 'f' && $bufchar[$i - 3] == 'l' && $bufchar[$i - 2] == 'o' && $bufchar[$i - 1] == 'a' && $bufchar[$i] == 't') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i - 1] == '[') {
                        $burrefsize = "";
                        while (TRUE) {
                            if ($bufchar[$i] != ']') {
                                $burrefsize = $burrefsize . $bufchar[$i];
                            } else {
                                $countfloat --;
                                $countfloatarray += number_format($burrefsize);
                                break;
                            }
                            $i++;
                        }
                    } elseif ($bufchar[$i] == ';') {
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 4] == 's' && $bufchar[$i - 3] == 'h' && $bufchar[$i - 2] == 'o' && $bufchar[$i - 1] == 'r' && $bufchar[$i] == 't') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i - 1] == '[') {
                        $burrefsize = "";
                        while (TRUE) {
                            if ($bufchar[$i] != ']') {
                                $burrefsize = $burrefsize . $bufchar[$i];
                            } else {
                                $countshort --;
                                $countshortarray += number_format($burrefsize);
                                break;
                            }
                            $i++;
                        }
                    } elseif ($bufchar[$i] == ';') {
                        break;
                    }
                    $i++;
                }
            }
            if ($bufchar[$i - 5] == 'd' && $bufchar[$i - 4] == 'o' && $bufchar[$i - 3] == 'u' && $bufchar[$i - 2] == 'b' && $bufchar[$i - 1] == 'l' && $bufchar[$i] == 'e') {
                while (TRUE) {
                    if($bufchar[$i]=="{"||$bufchar[$i]=="("){
                        break;
                    }
                    if ($bufchar[$i - 1] == '[') {
                        $burrefsize = "";
                        while (TRUE) {
                            if ($bufchar[$i] != ']') {
                                $burrefsize = $burrefsize . $bufchar[$i];
                            } else {
                                $countdouble --;
                                $countdoublearray += number_format($burrefsize);
                                break;
                            }
                            $i++;
                        }
                    } elseif ($bufchar[$i] == ';') {
                        break;
                    }
                    $i++;
                }
            }
        }

        $memory_used = ($countint + $countintarray) * 2 + ($countcahr + $countcahrarray) +
            ($countlong + $countlongarrray) * 4 +
            ($countfloat + $countfloatarray) * 4 + ($countshort + $countshortarray) * 2 +
            ($countdouble + $countdoublearray) * 8;

        return $memory_used;
    }
}
