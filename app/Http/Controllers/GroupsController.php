<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Groups;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class GroupsController extends Controller
{
    //Get groups and game mode table data for create quiz use
    public function obtainData() {
        $groups = DB::select('select * from groups');
        $gamemodes = DB::select('select * from game_mode');
        $subjects = DB::select('select * from subject');
        Session::forget('quizID');
        Session::forget('quesNo');
        Session::forget('quesID');
        return view("createquiz")->with(compact('groups'))->with(compact('gamemodes'))->with(compact('subjects'));
    }
    
    //Return groups view
    public function groupsView($passGroupID) {
        //Get user's group
        if ($passGroupID != 0) {
            $userGroup = DB::table('groups')
            ->select('groups.group_id', 'groups.group_name', 'groups.group_desc', 'groups.public', 'groups.subject_id', 'subject.subject_name')
            ->join('subject', 'subject.subject_id', '=', 'groups.subject_id')
            ->join('users', 'groups.group_id', '=', 'users.group_id')
            ->where('groups.group_id','=',$passGroupID)
            ->first();
        }
        else {
            $userGroup = NULL;
        }

        //Get all students that don't have a group
        $noGroup = DB::table('users')
        ->select('user_id', 'username')
        ->where('group_id','=', NULL)
        ->where('role','=', 0)
        ->get();

        //get all groups that are public
        $allGroups = DB::table('groups')
        ->select('groups.group_id', 'groups.group_name', 'groups.group_desc', 'subject.subject_name')
        ->join('subject', 'subject.subject_id', '=', 'groups.subject_id')
        ->where('public','=', 1)->get();

        //Get all members of the group
        $users = DB::table('users')
        ->select('user_id', 'username', 'email', 'role')
        ->where('group_id','=',$passGroupID)
        ->orderBy('role', 'desc')
        ->get();

        //Get all quizzes related to group
        $quiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.items','quiz.time_limit', 'quiz.user_id', 
        'quiz.group_id','subject.subject_name', 'game_mode.gamemode_name', 'game_mode.gamemode_id')
        ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
        ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
        ->where('quiz.group_id','=', $passGroupID)
        ->orderBy('quiz.quiz_id', 'asc')
        ->get();
        
        return view("groups")->with(compact('userGroup', 'allGroups', 'quiz', 'users', 'noGroup'));
    }

    //Get subject data for add group page
    public function createGroupView() {
        $subjects = DB::select('select * from subject');
        return view("creategroup")->with(compact('subjects'));
    }

    //Function to create a new group
    public function createGroup(Request $request) {
        $request->validate([
            'group_name'=>'required',
            'group_desc' =>'required',
            'subject_id'=>'required'
        ]);
        $group = new Groups();
        $group->group_name = $request->group_name;
        $group->group_desc = $request->group_desc;
        $group->subject_id = $request->subject_id;
        $group->public = $request->public;
        $group->user_id = Auth::id();

        $res = $group->save();
        if($res){
            $getGroupId = Groups::where('user_id', '=', Auth::id())->value('group_id');
            Auth::user()->group_id = $getGroupId;
            Auth::user()->save();

            Session::flash('message','Added a new group!');
            return redirect()->route('groups-view', $getGroupId);
        }
        else {
            Session::flash('message','Failed to add a new group.');
            return redirect()->route('groups-view', 0);
        } 
    }

    //Function to join a group
    public function joinGroup($passGroupID) {
        $res = DB::table('users')
        ->where('user_id','=', Auth::id())
        ->update(['group_id'=> $passGroupID]);

        if ($res){
            Session::flash('message','Joined a group!');
            return redirect()->route('groups-view', $passGroupID);
        } else {
            Session::flash('message','Failed to join a group.');
            return redirect()->route('groups-view', 0);
        }
    }

    public function addToGroup(Request $request, $passGroupID) {
        $res = DB::table('users')
        ->where('user_id','=', $request->user_id)
        ->update(['group_id'=> $passGroupID]);
        if ($res) {
            Session::flash('message','Added student to group!');
            return redirect()->route('groups-view', $passGroupID);
        }else {
            Session::flash('message','Failed to add student to group.');
            return redirect()->route('groups-view', $passGroupID);
        }
    }

    //Function to leave a group, instructors can also use to kick student out of group
    public function leaveGroup($passUserID) {
        //Used in case res fails
        $getGroupId = Groups::where('user_id', '=', Auth::id())->value('group_id');
        
        $res = DB::table('users')
        ->where('user_id','=', $passUserID)
        ->update(['group_id' => NULL]);

        if ($res){
            if (Auth::user()->role == 0) {
                Session::flash('message','Left your group!');
                return redirect()->route('groups-view', 0);
            } else {
                Session::flash('message','Removed student from group.');
                return redirect()->route('groups-view', $getGroupId);
            }
        } else {
            Session::flash('message','Failed to leave a group.');
            return redirect()->route('groups-view', $getGroupId);
        }
    }

    //Function to delete a group, instructors only
    public function deleteGroup($passGroupID) {
        //Find count of users assigned to the group 
        $userCount = DB::table('users')
        ->where('group_id', '=', $passGroupID)
        ->count();

        //Set all of the user group ID to null
        $updateUserGroup = DB::table('users')
        ->where('group_id','=', $passGroupID)
        ->update(['group_id' => NULL]);

        //Set all quiz that share that group ID to null
        $updateQuizGroup = DB::table('quiz')
        ->where('group_id','=', $passGroupID)
        ->update(['group_id' => NULL]);
        
        //Then, delete group
        $deleteGroup = DB::table('groups')
        ->where('group_id','=',$passGroupID)
        ->delete();

        if ($deleteGroup) {
            Session::flash('message','Deleted group!');
            return redirect()->route('groups-view', 0);
        }else {
            Session::flash('message','Failed to leave a group.');
            return redirect()->route('groups-view', $passGroupID);
        }
    }
}