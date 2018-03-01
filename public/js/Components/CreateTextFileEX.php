<?php
ini_set('memory_limit', '512M');
date_default_timezone_set('Asia/Bangkok');
header("Content-type:text/html; charset=UTF-8");

$userFolder = $_POST['userID']."_".$_POST['userName'];
$sectionFolder = "ExamGroup_".$_POST['exam_group_id'];
$content = $_POST['Content'];
$path = "../../../upload/exam/";

// สร้างโฟลเดอร์เก็บข้อสอบ
makeFolder("../../../upload/","exam");
//สร้างโฟลเดอร์ของอาจารย์เพื่อเก็บข้อสอบ
makeFolder($path,$userFolder);
//สร้างโฟลเดอร์กลุ่มข้อสอบ
makeFolder($path.$userFolder.'/',$sectionFolder);

//สร้างโฟลเดอร์ข้อสอบ จากวันที่สร้าง
$examFolder = date('Ymd-His') . "_" . rand(1, 9999);
mkdir($path.$userFolder.'/'.$sectionFolder.'/'.$examFolder, 0777, true);

//สร้างไฟล์ content.txt
$content_file = "content.txt";
$handle = fopen($path.$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$content_file, 'w') or die('Cannot open file:  ' . $content_file);
fwrite($handle, $content);
$content_file = "../upload/exam/".$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$content_file;

//สร้างไฟล์ input.txt
$input_file="";
if($_POST['input']){
    $input_file = "input.txt";
    $handle = fopen($path.$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$input_file, 'w') or die('Cannot open file:  ' . $input_file);
    fwrite($handle, $_POST['input']);
    $input_file = "../upload/exam/".$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$input_file;
}

//สร้างไฟล์ output.txt
$output_file = "output.txt";
$handle = fopen($path.$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$output_file, 'w') or die('Cannot open file:  ' . $output_file);
fwrite($handle, $_POST['output']);
$output_file = "../upload/exam/".$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$output_file;

//สร้างไฟล์ main.txt
$main_file="";
if($_POST['main']){
    $main_file = "main.txt";
    $handle = fopen($path.$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$main_file, 'w') or die('Cannot open file:  ' . $main_file);
    fwrite($handle, $_POST['main']);
    $main_file = "../upload/exam/".$userFolder.'/'.$sectionFolder.'/'.$examFolder.'/'.$main_file;
}

echo json_encode(array("content_path" => $content_file, "input_path" => $input_file, "output_path" => $output_file, "main_path" => $main_file));

function makeFolder($path,$folder) {
    $dirList = scandir($path);
    if (!in_array((string) $folder, (array) $dirList)) {
        mkdir($path.$folder, 0777, true);
    }
}