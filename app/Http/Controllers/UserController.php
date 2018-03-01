<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Examing;
use App\ResExam;
use App\ResSheet;
use App\Sheeting;
use App\User;
use App\WebHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DirectoryIterator;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $admin = Admin::where('username',$request->username)
            ->where('password',$request->password)
            ->first();
        if ($admin === NULL) {
            return response()->json(['error' => 'Error msg'], 209);
        }else{
            Session::set('weppAdmin',$admin);
        }
    }

    public function findAdmin()
    {
        if(Session::has('weppAdmin')){
            $admin = Session::get('weppAdmin');
            return response()->json($admin);
        } else {
            return 500;
        }
    }

    public function adminLogout(){
        Session::forget('weppAdmin');
    }

    public function loggedIn(){
        session_start();
//        ---------- For Product ---------------
//        if(isset($_SESSION['ssoUserdata'])){
//            $findUser = User::where('personal_id', $_SESSION['ssoUserdata']['personalId'][0])->first();
//            if ($findUser === NULL) {
//                $users = new User;
//                $users->personal_id = $_SESSION['ssoUserdata']['personalId'][0];
//                $users->prefix = $_SESSION['ssoUserdata']['prename'][0];
//                $users->fname_en = $_SESSION['ssoUserdata']['cn'][0];
//                $users->fname_th = $_SESSION['ssoUserdata']['firstNameThai'][0];
//                $users->lname_en = $_SESSION['ssoUserdata']['sn'][0];
//                $users->lname_th = $_SESSION['ssoUserdata']['lastNameThai'][0];
//
//                if(isset($_SESSION['ssoUserdata']['studentId'])){
//                    $users->stu_id = $_SESSION['ssoUserdata']['studentId'][0];
//                } else {
//                    $users->stu_id = "";
//                }
//
//                $users->faculty = $_SESSION['ssoUserdata']['faculty'][0];
//                $users->department = $_SESSION['ssoUserdata']['program'][0];
//                $users->email = $_SESSION['ssoUserdata']['mail'][0];
//                if($_SESSION['ssoUserdata']['gidNumber'][0] == "4500"){
//                    $users->user_type = 's';
//                }elseif ($_SESSION['ssoUserdata']['gidNumber'][0] == "2800"){
//                    $users->user_type = 't';
//                }else{
//                    $users->user_type = 'o';
//                }
//                $users->save();
////                return response()->json($users);
//            }else{
//                $users = User::find($findUser->id);
//                $users->personal_id = $_SESSION['ssoUserdata']['personalId'][0];
//                $users->prefix = $_SESSION['ssoUserdata']['prename'][0];
//                $users->fname_en = $_SESSION['ssoUserdata']['cn'][0];
//                $users->fname_th = $_SESSION['ssoUserdata']['firstNameThai'][0];
//                $users->lname_en = $_SESSION['ssoUserdata']['sn'][0];
//                $users->lname_th = $_SESSION['ssoUserdata']['lastNameThai'][0];
//
//                if(isset($_SESSION['ssoUserdata']['studentId'])){
//                    $users->stu_id = $_SESSION['ssoUserdata']['studentId'][0];
//                } else {
//                    $users->stu_id = "";
//                }
//
//                $users->faculty = $_SESSION['ssoUserdata']['faculty'][0];
//                $users->department = $_SESSION['ssoUserdata']['program'][0];
//                $users->email = $_SESSION['ssoUserdata']['mail'][0];
//                if($_SESSION['ssoUserdata']['gidNumber'][0] == "4500"){
//                    $users->user_type = 's';
//                }elseif ($_SESSION['ssoUserdata']['gidNumber'][0] == "2500"){
//                    $users->user_type = 't';
//                }else{
//                    $users->user_type = 'o';
//                } $users->save();
////                return response()->json($users);
//            }
//
//            return redirect('/home');
//        } else {
//            return redirect('/');
//        }

//        ---------- For Dev ---------------
        if(isset($_SESSION['ssoUserData'])){
            $findUser = User::where('personal_id', $_SESSION['ssoUserData']['personalId'])->first();
            if ($findUser === NULL) {
                $users = new User;
                $users->personal_id = $_SESSION['ssoUserData']['personalId'];
                $users->prefix = $_SESSION['ssoUserData']['prename'];
                $users->fname_en = $_SESSION['ssoUserData']['cn'];
                $users->fname_th = $_SESSION['ssoUserData']['firstNameThai'];
                $users->lname_en = $_SESSION['ssoUserData']['sn'];
                $users->lname_th = $_SESSION['ssoUserData']['lastNameThai'];

                if(isset($_SESSION['ssoUserData']['studentId'])){
                    $users->stu_id = $_SESSION['ssoUserData']['studentId'];
                } else {
                    $users->stu_id = "";
                }

                $users->faculty = $_SESSION['ssoUserData']['faculty'];
                $users->department = $_SESSION['ssoUserData']['program'];
                $users->email = $_SESSION['ssoUserData']['mail'];
                if($_SESSION['ssoUserData']['gidNumber'] == "4500"){
                    $users->user_type = 's';
                }elseif ($_SESSION['ssoUserData']['gidNumber'] == "2500"){
                    $users->user_type = 't';
                }else{
                    $users->user_type = 'o';
                }
                $users->save();
//                return response()->json($users);
            }else{
                $users = User::find($findUser->id);
                $users->personal_id = $_SESSION['ssoUserData']['personalId'];
                $users->prefix = $_SESSION['ssoUserData']['prename'];
                $users->fname_en = $_SESSION['ssoUserData']['cn'];
                $users->fname_th = $_SESSION['ssoUserData']['firstNameThai'];
                $users->lname_en = $_SESSION['ssoUserData']['sn'];
                $users->lname_th = $_SESSION['ssoUserData']['lastNameThai'];

                if(isset($_SESSION['ssoUserData']['studentId'])){
                    $users->stu_id = $_SESSION['ssoUserData']['studentId'];
                } else {
                    $users->stu_id = "";
                }

                $users->faculty = $_SESSION['ssoUserData']['faculty'];
                $users->department = $_SESSION['ssoUserData']['program'];
                $users->email = $_SESSION['ssoUserData']['mail'];
                if($_SESSION['ssoUserData']['gidNumber'] == "4500"){
                    $users->user_type = 's';
                }elseif ($_SESSION['ssoUserData']['gidNumber'] == "2500"){
                    $users->user_type = 't';
                }else{
                    $users->user_type = 'o';
                } $users->save();

            }

            return redirect('/home');
        } else {
            return redirect('/');
        }
    }

    public function checkUser(){
        session_start();

//        ------------- For Product -------------
//        if(isset($_SESSION['ssoUserdata'])){
////            return 200;
//            $findUser = User::where('personal_id', $_SESSION['ssoUserdata']['personalId'][0])->first();
//            return $findUser;
//        } else {
//            return 404;
//        }

//        ------------- For Dev -------------
        if(isset($_SESSION['ssoUserData'])){
//            return 200;
            $findUser = User::where('personal_id', $_SESSION['ssoUserData']['personalId'])->first();
            return $findUser;
        } else {
            return 404;
        }
    }

    public function userLogOut(){
        session_start();
        unset($_SESSION['ssoUserData']);

        header( "location: http://localhost/WEPP2/public/" );
        exit(0);
    }

    public function keepHistory(Request $request){

        $history = new WebHistory();
        $history->user_id = $request->user_id;
        $history->page = $request->page;
        $history->ip = $_SERVER['REMOTE_ADDR'];
        $history->time_stamp = $request->time_stamp;
        $history->timestamps = false;
        $history->save();
    }

    public function findWebHistory(){
        $history = DB::select(' SELECT * 
                                FROM users as u 
                                INNER JOIN 
                                    ( SELECT user_id 
                                      FROM web_histories 
                                      WHERE time_stamp >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
                                      GROUP BY user_id) as h 
                                ON u.id = h.user_id');
        return response()->json($history);
    }

    public function findAllTeacher(){
        $teacher = User::where('user_type','t')
            ->orderBy('fname_th', 'asc')
            ->orderBy('lname_th', 'asc')
            ->get();
        return response()->json($teacher);
    }

    public function findAllStudent(){
        $student = User::where('user_type','s')
            ->orderBy('stu_id', 'asc')
            ->get();
        return response()->json($student);
    }

    public function deleteTeacher(Request $request){
        $user = User::find($request->user_id);
        $userFolder = $user->id."_".$user->fname_en."_".$user->lname_en;
        $this->rrmdir("../upload/exam/".$userFolder);
        $this->rrmdir("../upload/sheet/".$userFolder);
//
        $examings = Examing::where('user_id',$request->user_id)->get();
        foreach ($examings as $examing) {
            $examingFolder = "Examing_".$examing->id;
            $this->rrmdir("../upload/res_exam/".$examingFolder);
        }
//
        $sheetings = Sheeting::where('user_id',$request->user_id)->get();
        foreach ($sheetings as $sheeting) {
            $sheetingFolder = "Sheeting_".$sheeting->id;
            $this->rrmdir("../upload/res_sheet/".$sheetingFolder);
        }
        $user->delete();
    }

    public function deleteStudent(Request $request){
        $user = User::find($request->user_id);
        $userFolder = $user->stu_id."_".$user->fname_en."_".$user->lname_en;
//
        $result_exams = ResExam::where('user_id',$request->user_id)->get();
        foreach ($result_exams as $result_exam) {
            $this->rrmdir("../upload/res_exam/Examing_".$result_exam->examing_id
                ."/Exam_".$result_exam->exam_id."/".$userFolder);
        }

        $result_sheets = ResSheet::where('user_id',$request->user_id)->get();
        foreach ($result_sheets as $result_sheet) {
            $this->rrmdir("../upload/res_sheet/Sheeting_".$result_sheet->sheeting_id
                ."/Sheet_".$result_sheet->sheet_id."/".$userFolder);
        }
        $user->delete();
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

    public function findUserByID(Request $request){
        $findUser = User::find($request->id);
        return response()->json($findUser);
    }

    public function findMyEvent(Request $request){

        $event = array();
        if($request->user_type === 't'){
            $examing = DB::select('SELECT CONCAT(examing_name,"(",group_name,")") as event_name,start_date_time,end_date_time,CONCAT("E") AS type 
                                   FROM (
                                      SELECT id as group_id,group_name 
                                      FROM groups 
                                      WHERE groups.user_id = ? ) as a 
                                      INNER JOIN examings as b 
                                      ON a.group_id = b.group_id',[$request->user_id]);

            $sheeting = DB::select('SELECT CONCAT(sheeting_name,"(",group_name,")") as event_name,start_date_time,end_date_time,CONCAT("S") AS type 
                                   FROM (
                                      SELECT id as group_id,group_name 
                                      FROM groups 
                                      WHERE groups.user_id = ? ) as a 
                                      INNER JOIN sheetings as b 
                                      ON a.group_id = b.group_id',[$request->user_id]);

            if(!is_null($examing)){
                foreach ($examing as $e) {
                    array_push($event,$e);
                }
            }

            if(!is_null($sheeting)) {
                foreach ($sheeting as $s) {
                    array_push($event, $s);
                }
            }

        } else if ($request->user_type === 's'){
            $examing = DB::select('SELECT CONCAT(examing_name,"(",group_name,")") as event_name,start_date_time,end_date_time,CONCAT("E") AS type 
                                   FROM (
                                      SELECT a.group_id,b.group_name 
                                      FROM (
                                          SELECT group_id 
                                          FROM join_groups 
                                          WHERE user_id = ? ) as a 
                                      INNER JOIN groups as b 
                                      ON a.group_id = b.id ) as c 
                                   INNER JOIN examings as d 
                                   ON c.group_id = d.group_id',[$request->user_id]);

            $sheeting = DB::select('SELECT CONCAT(sheeting_name,"(",group_name,")") as event_name,start_date_time,end_date_time,CONCAT("S") AS type 
                                   FROM (
                                      SELECT a.group_id,b.group_name 
                                      FROM (
                                          SELECT group_id 
                                          FROM join_groups 
                                          WHERE user_id = ? ) as a 
                                      INNER JOIN groups as b 
                                      ON a.group_id = b.id ) as c 
                                   INNER JOIN sheetings as d 
                                   ON c.group_id = d.group_id',[$request->user_id]);

            if(!is_null($examing)){
                foreach ($examing as $e) {
                    array_push($event,$e);
                }
            }

            if(!is_null($sheeting)) {
                foreach ($sheeting as $s) {
                    array_push($event, $s);
                }
            }
        }
        return response()->json($event);
    }
}
