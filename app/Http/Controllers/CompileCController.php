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

class CompileCController extends Controller
{
    public function sendExamC(Request $request){
        $folder_ans = "";
        $resExamID = "";
        $completeInsRes = false;
        // ถ้าพิมพ์โค้ดส่ง
        if($request->mode === "key") {
            $code = $request->code;

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
            $file_name = "wepp_res_exam";
            $file_ans = "$file_name.c";

            // เขียนไฟล์
            $handle = fopen("$folder_ans/$file_ans", 'w') or die('Cannot open file:  ' . $file_ans);
            fwrite($handle, $code);
            fclose($handle);
        } else {
            // แต่ถ้าส่งไฟล์โค้ดมา
            $folder_ans = $request->path;
        }

        try{
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
            $pathExam->file_type = "c";
            $pathExam->ip = $_SERVER['REMOTE_ADDR'];
            $pathExam->save();
            $insertedId = $pathExam->id;
            $pathExamID = $insertedId;

            // บันทึกลงฐานข้อมูล queue_exams
            $Queue = new QueueExam;
            $Queue->path_exam_id = $pathExamID;
            $Queue->file_type = "c";
            $Queue->save();
            return response()->json($pathExamID);

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

    public function sendSheetC(Request $request){
        $folder_ans = "";
        $resSheetID = "";
        $completeInsRes = false;
        // ถ้าพิมพ์โค้ดส่ง
        if($request->mode === "key") {
            $code = $request->code;

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
            $file_ans = "$file_name.c";

            // เขียนไฟล์
            $handle = fopen("$folder_ans/$file_ans", 'w') or die('Cannot open file:  ' . $file_ans);
            fwrite($handle, $code);
            fclose($handle);
        } else {
            // แต่ถ้าส่งไฟล์โค้ดมา
            $folder_ans = $request->path;
        }

        try{
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
                $resSheet->file_type = "c";
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
                $resSheet->file_type = "c";
                $resSheet->send_late = $request->send_late;
                $resSheet->path = $folder_ans;
                $resSheet->send_date_time = $request->send_date_time;
                $resSheet->save();
            }
            $completeInsRes = true;

            // บันทึกลงฐานข้อมูล ready_queue_shes
            $readyQueue = new QueueSheet;
            $readyQueue->res_sheet_id = $resSheetID;
            $readyQueue->file_type = "c";
            $readyQueue->save();
            return response()->json($resSheetID);

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

    public function compileAndRunC(Request $request){
        $status = "";
        $folder_ans = "";
        // คิวรี่ ที่อยู่ของไฟล์ที่ส่ง
        if($request->mode == "exam"){
            $pathExam = PathExam::find($request->pathExamID);
            $folder_ans = $pathExam->path;
        } else if($request->mode == "sheet"){
            $resSheet = ResSheet::find($request->pathSheetID);
            $folder_ans = $resSheet->path;
        }

        // คอมไพล์โค้ดที่ส่ง
        $this->compile_code($folder_ans);

        // ตรวจสอบการคอมไพล์(มีไฟล์ wepp_ans.exe ไหม)
        if (file_exists("$folder_ans/wepp_ans.exe")) {
            $input_file = "";
            // คิวรี่ ไฟล์อินพุทของข้อสอบ
            if($request->mode == "exam") {
                $exam = Exam::find($request->exam_id);
                $input_file = $exam->exam_input_file;
            } else if ($request->mode == "sheet"){
                $sheet = Sheet::find($request->sheet_id);
                $input_file = $sheet->sheet_input_file;
            }

            // รันโค้ด
            $lines_run =  $this->run_code($input_file,$folder_ans,$request->mode,$request->exam_id);
//            return response()->json($lines_run);

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
            // ไม่พบไฟล์ wepp_ans.exe
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

    function compile_code($folder_code) {
        // ดึงข้อมูลโค้ดจากไฟล์ที่ส่ง
        $files = scandir($folder_code);
        $file = $files[2];

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
        $file_bat = 'compile_ans.bat';
        $openfile = fopen("$folder_code/$file_bat", 'w');
        fwrite($openfile, $cmd . " \n gcc ".$file." -o wepp_ans");
        fclose($openfile);

        exec($dir_code.$file_bat);
    }

    function run_code($input_file,$folder_ans,$mode,$exam_id) {
        // กำหนดเวลาในการรันไว้ 5 วินาที
        $ruutimeIn = 5;
        // ถ้าเป็นการสอบ
        if($mode == "exam") {
            // คิวรี่ runtime ของข้อสอบ
            $exam = Exam::find($exam_id);
            $ruutimeIn = $exam->time_limit;
        }

        $amount_input = 1;
        if($input_file){
            // อ่านไฟล์ input
            $handle = fopen("$input_file", "r");
            $input = trim(fread($handle, filesize("$input_file")));
            fclose($handle);

            // แบ่ง input ด้วยเครื่องหมาย ",,"
            $input_split = explode(",,",$input);
            $amount_input = sizeof($input_split);

            // เขียนไฟล์อินพุตใหม่ ตามจำนวนอินพุต
            for($i = 0;$i<$amount_input;$i++){
                $new_input_file = 'input'.$i;
                $handle = fopen("$folder_ans/$new_input_file.txt", 'w') or die('Cannot open file:  ' . $new_input_file);
                fwrite($handle, $input_split[$i]);
                fclose($handle);
            }
        }

        // แปลงรูปแบบที่อยู่ของโฟลเดอร์ข้อสอบที่ส่ง
        $dir = getcwd();
        $dir_split = explode("\\",$dir);
        $dir_code = "";
        $dir_in_check_code = "";
        for($i = 0;$i<sizeof($dir_split)-1;$i++){
            $dir_code = $dir_code.$dir_split[$i]."\\";
            $dir_in_check_code = $dir_in_check_code.$dir_split[$i]."\\\\";
        }
        $dir_split = explode("/",$folder_ans);
        for($i = 1;$i<sizeof($dir_split);$i++){
            $dir_code = $dir_code.$dir_split[$i]."\\";
            $dir_in_check_code = $dir_in_check_code.$dir_split[$i]."\\\\";
        }

        $code_checker = '#include <time.h>
            #include <process.h>
            #include <io.h>
            #include <fcntl.h>
            #include <stdlib.h>
            #include <windows.h>
            #include <stdio.h>
            #include <string.h>
            

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
                printf("OverTime\n");
                (void)system("'.$dir_in_check_code.'kill.bat");
                exit(0);
            }
            main()
            {
                HANDLE loop_thread[1];
                loop_thread[0] = (HANDLE) _beginthread( loop1,0,NULL);
                 if (loop_thread[0] == INVALID_HANDLE_VALUE)
                    error("creating read thread");
                    clock_t begin, end;
                    double time_spent;
                    begin = clock();
                    
                    FILE *fp;
                    char path[1035];
                    int count = 0;
                    
                    char ch[300] = "'.$dir_in_check_code.'";
                	char ch2[9] = "run_ans_";
                	char ch3[5] = ".bat";
                	char ch4[300] = "\0";
                	
                	int i = 0;
                	while(i < '.$amount_input.'){
                		char ch4[300] = "\0";
                		strcpy(ch4, ch);
                		strcat(ch4, ch2);
                		int index = 0;
                		char iToChar[10] = "\0";
                		int j = i;
                		while(j >= 10){
                			iToChar[index++] = (j % 10)+\'0\';
                			j /= 10 ;
                		}
                		iToChar[index++] = j+\'0\';
                		strrev(iToChar);
                		strcat(ch4, iToChar);
                		strcat(ch4, ch3);

                        fp = popen(ch4, "r");
                        if (fp == NULL) {
                          printf("Failed to run command\n" );
                          exit(1);
                        }

                        count = 0;
                        while (fgets(path, sizeof(path)-1, fp) != NULL) {
                            if(count >= 4 ){                               
                                printf("%s", path);
                            }
                            count++;
                        }
                
                        pclose(fp);
                        i++;
                	}

                    end = clock();time_spent = (double)(end - begin) / CLOCKS_PER_SEC;printf("RunTime:%f",time_spent); return 0;
            }';

        // เขียนไฟล์สำหรับเช็คเวลา
        $file = 'wepp_check';
        $handle = fopen("$folder_ans/$file.c", 'w') or die('Cannot open file:  ' . $file);
        fwrite($handle, $code_checker);
        fclose($handle);

        // เขียนไฟล์ bat เพื่อคอมไพล์ ไฟล์ wepp_check
        $compile_file_check = "cd ".$dir_code." \n gcc wepp_check.c -o wepp_check";
        $file = 'compile_check';
        $handle = fopen("$folder_ans/$file.bat", 'w') or die('Cannot open file:  ' . $file);
        fwrite($handle, $compile_file_check);
        fclose($handle);

        // เขียนไฟล์ bat เพื่อรันไฟล์ wepp_check
        $run_file_check = "cd ".$dir_code." \n wepp_check";
        $file = 'run_check';
        $handle = fopen("$folder_ans/$file.bat", 'w') or die('Cannot open file:  ' . $file);
        fwrite($handle, $run_file_check);
        fclose($handle);


        // เขียนไฟล์ bat เพื่อรันไฟล์ wepp_ans
        $run_file_ans = "";
        if($input_file){
            for($i = 0 ; $i < $amount_input ; $i++){
                $run_file_ans = "cd ".$dir_code." \n wepp_ans < ".$dir_code."input".$i.".txt";

                $file = 'run_ans_'.$i;
                $handle = fopen("$folder_ans/$file.bat", 'w') or die('Cannot open file:  ' . $file);
                fwrite($handle, $run_file_ans);
                fclose($handle);
            }
        } else {
            $run_file_ans = "cd ".$dir_code." \n wepp_ans";

            $file = 'run_ans_0';
            $handle = fopen("$folder_ans/$file.bat", 'w') or die('Cannot open file:  ' . $file);
            fwrite($handle, $run_file_ans);
            fclose($handle);
        }


        // เขียนไฟล์ bat เพื่อปิด wepp_ans ที่รันค้างอยู่
        $kill_file_ans = "Taskkill /IM wepp_ans.exe /F";
        $file = 'kill';
        $handle = fopen("$folder_ans/$file.bat", 'w') or die('Cannot open file:  ' . $file);
        fwrite($handle, $kill_file_ans);
        fclose($handle);

        exec($dir_code."compile_check.bat");
        $lines_run = array();
        exec($dir_code."run_check.bat",$lines_run);
        return $lines_run;

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
        $iTime = $iOverTime = -1;
        $res_run = '';

        for ($i = 4; $i < count($lines_run); $i++) {
            $line = $lines_run[$i];
            if (strpos($line, "RunTime:") > -1) {
                $iTime = $i;
            } else if (strpos($line, "OverTime") > -1) {
                $iOverTime = $i;
            }
        }

        if ($iOverTime > -1) {
            return "OverTime";
        } else if ($iTime > -1) {

            $ar_res_run = array_slice($lines_run, 4, $iTime - 4);
            $i = 0;
            foreach ($ar_res_run as $val) {
                $ar_res_run[$i] = iconv(mb_detect_encoding($val), "utf-8", $val);
                $res_run .= $ar_res_run[$i++]."\n";
            }

            $time = substr($lines_run[$iTime], 8);

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

    function clearFolderAns($folder_ans) {
        $files = scandir($folder_ans);

        // ลูปลบไฟล์ที่นามสกุลไม่ใช่ .c
        foreach ($files as $f) {
            if (!strpos($f, '.c') || $f == 'wepp_check.c') {
                @unlink("$folder_ans/$f");
            }
        }
    }

    function calculate_memory($folder_ans){
        $code_in_file= '';
        $files = scandir($folder_ans);
        foreach ($files as $f) {
            if (strpos($f, '.c') && $f != 'wepp_check.c') {
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
