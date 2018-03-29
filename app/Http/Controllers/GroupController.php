<?php

namespace App\Http\Controllers;

use App\Group;
use App\JoinGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

class GroupController extends Controller
{
    public function findMyGroup(Request $request)
    {
        $groupList = DB::table('groups')
            ->join('users', 'groups.user_id', '=', 'users.id')
            ->select('groups.*', 'users.prefix','users.fname_th','users.lname_th')
            ->where('groups.user_id',$request->user_id)
            ->orderBy('group_name','ASC')
            ->get();
        return response()->json($groupList);
    }

    public function addGroup(Request $request){
        $findGroup = Group::where('group_name', $request->group_name)
            ->where('user_id', $request->user_id)
            ->first();
        if ($findGroup === NULL) {
            $group = new Group;
            $group->user_id = $request->user_id;
            $group->group_name = $request->group_name;
            $group->group_password = $request->group_password;
            $group->save();
        } else {
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function editGroup(Request $request){
        $findGroup = Group::where('group_name',$request->group_name)
            ->where('user_id', $request->user_id)
            ->first();
        if($findGroup === NULL){
            $group = Group::find($request->id);
            $group->group_name = $request->group_name;
            $group->group_password = $request->group_password;
            $group->save();
        } else {
            if ($findGroup->id == $request->id){
                $group = Group::find($request->id);
                $group->group_name = $request->group_name;
                $group->group_password = $request->group_password;
                $group->save();
            } else {
                return response()->json(['error' => 'Error msg'], 209);
            }
        }
    }

    public function deleteGroup(Request $request){
        $section = Group::find($request->id);
        $section->delete();
    }

    public function findAllGroup()
    {
        $groupList = DB::table('groups')
            ->join('users', 'groups.user_id', '=', 'users.id')
            ->select('groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->orderBy('fname_th','ASC')
            ->orderBy('group_name','ASC')
            ->get();
        return response()->json($groupList);
    }

    public function checkJoinGroup(Request $request){
        $join = JoinGroup::where('user_id',$request->user_id)
            ->where('group_id',$request->group_id)
            ->first();
        if($join === NULL){
            return response()->json(['error' => 'Error msg'], 209);
        }
    }

    public function createJoinGroup(Request $request)
    {
        $join = new JoinGroup;
        $join->user_id = $request->user_id;
        $join->group_id = $request->group_id;
        $join->status = $request->status;
        $join->view_sheet = 0;
        $join->edit_sheet = 0;
        $join->view_exam = 0;
        $join->edit_exam = 0;
        $join->save();
    }

    public function findMyJoinGroup(Request $request){
        $myGroup = DB::table('join_groups')
            ->join('groups', 'join_groups.group_id', '=', 'groups.id')
            ->join('users', 'groups.user_id', '=', 'users.id')
            ->select('join_groups.*','groups.group_name', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('join_groups.user_id',$request->user_id)
            ->orderBy('fname_th','ASC')
            ->orderBy('group_name','ASC')
            ->get();
        return response()->json($myGroup);
    }

    public function exitGroup(Request $request){
        $join = JoinGroup::where('user_id',$request->user_id)
            ->where('group_id',$request->group_id)->first();
        $join->delete();
    }

    public function findGroupDataByID(Request $request){
        $groupData = DB::table('groups')
            ->join('users', 'groups.user_id', '=', 'users.id')
            ->select('groups.*', DB::raw('CONCAT("อ.",users.fname_th," ", users.lname_th) AS creater'))
            ->where('groups.id',$request->id)
            ->first();
        return response()->json($groupData);
    }

    public function findMemberGroup(Request $request){
        $memberList = DB::table('join_groups')
            ->join('users', 'join_groups.user_id', '=', 'users.id')
            ->select('join_groups.*', DB::raw('CONCAT(users.prefix,users.fname_th," ", users.lname_th) AS fullName'),'users.stu_id')
            ->where('join_groups.group_id',$request->group_id)
            ->orderBy('stu_id','ASC')
            ->orderBy('status','ASC')
            ->get();
        return response()->json($memberList);
    }

    public function managePermissions(Request $request){
        $joinGroup = JoinGroup::find($request->id);
        $joinGroup->view_exam = $request->view_exam;
        $joinGroup->edit_exam = $request->edit_exam;
        $joinGroup->view_sheet = $request->view_sheet;
        $joinGroup->edit_sheet = $request->edit_sheet;
        $joinGroup->status = $request->status;
        $joinGroup->save();
    }

    public function findMyPermissionsInGroup(Request $request){
        $joinGroup = JoinGroup::where('user_id',$request->user_id)
            ->where('group_id',$request->group_id)
            ->first();
        return response()->json($joinGroup);
    }

    public function checkPermissionGroupTeacher(Request $request){
        $exam = Group::where('id',$request->group_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($exam === NULL) {
            return 404;
        }
    }

    public function checkPermissionGroupStudent(Request $request){
        $exam = JoinGroup::where('group_id',$request->group_id)
            ->where('user_id',$request->user_id)
            ->first();
        if ($exam === NULL) {
            return 404;
        }
    }

}
