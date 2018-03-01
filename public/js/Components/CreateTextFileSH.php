<?php
ini_set('memory_limit', '512M');
date_default_timezone_set('Asia/Bangkok');
header("Content-type:text/html; charset=UTF-8");

$userFolder = $_POST['userID']."_".$_POST['userName'];
$sheetGroupFolder = "SheetGroup_".$_POST['sheet_group_id'];
$trial = $_POST['trial'];
$path = "../../../upload/sheet/";

// สร้างโฟลเดอร์เพื่อเก็บใบงาน
makeFolder("../../../upload/","sheet");
//สร้างโฟลเดอร์ของอาจารย์เพื่อเก็บใบงาน
makeFolder($path,$userFolder);
//สร้างโฟลเดอร์กลุ่มใบงาน
makeFolder($path.$userFolder.'/',$sheetGroupFolder);

//สร้างโฟลเดอร์ใบงาน จากวันที่สร้าง
$sheetFolder = date('Ymd-His') . "_" . rand(1, 9999);
mkdir($path.$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder, 0777, true);

//สร้างไฟล์ trial.txt
$trial_file = "trial.txt";
$handle = fopen($path.$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$trial_file, 'w') or die('Cannot open file:  ' . $trial_file);
fwrite($handle, $trial);
$trial_file = "../upload/sheet/".$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$trial_file;

//สร้างไฟล์ input.txt
$input_file="";
if($_POST['input']){
    $input_file = "input.txt";
    $handle = fopen($path.$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$input_file, 'w') or die('Cannot open file:  ' . $input_file);
    fwrite($handle, $_POST['input']);
    $input_file = "../upload/sheet/".$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$input_file;
}

//สร้างไฟล์ output.txt
$output_file = "output.txt";
$handle = fopen($path.$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$output_file, 'w') or die('Cannot open file:  ' . $output_file);
fwrite($handle, $_POST['output']);
$output_file = "../upload/sheet/".$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$output_file;

//สร้างไฟล์ main.txt
$main_file="";
if($_POST['main']){
    $main_file = "main.txt";
    $handle = fopen($path.$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$main_file, 'w') or die('Cannot open file:  ' . $main_file);
    fwrite($handle, $_POST['main']);
    $main_file = "../upload/sheet/".$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$main_file;
}

//สร้างไฟล์ objective.txt
$objective_file="";
if($_POST['objective']){
    $objective_file = "objective.txt";
    $handle = fopen($path.$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$objective_file, 'w') or die('Cannot open file:  ' . $objective_file);
    fwrite($handle, $_POST['objective']);
    $objective_file = "../upload/sheet/".$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$objective_file;
}

//สร้างไฟล์ theory.txt
$theory_file="";
if($_POST['theory']){
    $theory_file = "theory.txt";
    $handle = fopen($path.$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$theory_file, 'w') or die('Cannot open file:  ' . $theory_file);
    fwrite($handle, $_POST['theory']);
    $theory_file = "../upload/sheet/".$userFolder.'/'.$sheetGroupFolder.'/'.$sheetFolder.'/'.$theory_file;
}

echo json_encode(array("trial_path" => $trial_file, "input_path" => $input_file, "output_path" => $output_file, "main_path" => $main_file, "objective_path" => $objective_file, "theory_path" => $theory_file));

function makeFolder($path,$folder) {
    $dirList = scandir($path);
    if (!in_array((string) $folder, (array) $dirList)) {
        mkdir($path.$folder, 0777, true);
    }
}