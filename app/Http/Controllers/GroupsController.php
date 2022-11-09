<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Groups;
use App\Models\Member;
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
            $userGroup = $this->getUserGroup();
        }
        else {
            $userGroup = NULL;
        }

        //get all groups
        $allGroups = $this->getAllGroups();

        //Get all members of the group
        $users = $this->getGroupMembers($passGroupID);

        //Get all students that are not in the group
        $students = DB::table('users')
        ->select('user_id', 'username')
        ->where('role','=',0)
        ->get();

        //Get all quizzes related to group
        $quiz = $this->getAssignedQuiz($passGroupID);
        
        return view("groups")->with(compact('userGroup', 'allGroups', 'quiz', 'users', 'students'));
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
            $getGroupId = Groups::orderBy('group_id', 'desc')->where('user_id','=',Auth::id())->value('group_id');
            $member = new Member();
            $member->user_id = Auth::id();
            $member->group_id = $getGroupId;
            $member->save();

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
        $member = new Member();
        $member->user_id = Auth::id();
        $member->group_id = $passGroupID;
        $res = $member->save();

        if ($res){
            Session::flash('message','Joined a new group!');
            return redirect()->route('groups-view', $passGroupID);
        } else {
            Session::flash('message','Failed to join a group.');
            return redirect()->route('groups-view', 0);
        }
    }

    public function addToGroup(Request $request, $passGroupID) {
        $member = new Member();
        $member->user_id = $request->user_id;
        $member->group_id = $passGroupID;
        $res = $member->save();

        if ($res) {
            Session::flash('message','Added student to group!');
            return redirect()->route('groups-view', $passGroupID);
        }else {
            Session::flash('message','Failed to add student to group.');
            return redirect()->route('groups-view', $passGroupID);
        }
    }

    //Function to leave a group
    public function leaveGroup($passGroupID) {
        $res = DB::table('group_members')
        ->where('user_id','=', Auth::id())
        ->where('group_id','=', $passGroupID)
        ->delete();

        if ($res){
            Session::flash('message','Left your group!');
            return redirect()->route('groups-view', 0);
        } else {
            Session::flash('message','Failed to leave a group.');
            return redirect()->route('groups-view', $passGroupID);
        }
    }

    //Function to kick out of a group
    public function kickGroup($passUserID) {
        //Used in case res fails
        $getGroupID = Groups::where('user_id', '=', Auth::id())->value('group_id');
        
        $res = DB::table('group_members')
        ->where('user_id','=', $passUserID)
        ->where('group_id','=', $getGroupID)
        ->delete();

        if ($res){
            Session::flash('message','Removed student from group.');
            return redirect()->route('groups-view', $getGroupID);
        } else {
            Session::flash('message','Failed to remove from group.');
            return redirect()->route('groups-view', $getGroupID);
        }
    }

    //Function to delete a group, instructors only
    public function deleteGroup($passGroupID) {
        //Find count of users assigned to the group 
        $userCount = DB::table('users')
        ->where('group_id', '=', $passGroupID)
        ->count();

        //Delete group members data
        $deleteMembers = DB::table('group_members')
        ->where('group_id','=', $passGroupID)
        ->delete();

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
    /************************************************************************************************************/    
    //Functions to get necessary data
    public function getUserGroup() {
        $userGroup = DB::table('groups')
        ->select('groups.group_id', 'groups.group_name', 'groups.group_desc', 'groups.public', 'groups.subject_id', 'groups.user_id', 'subject.subject_name')
        ->join('subject', 'subject.subject_id', '=', 'groups.subject_id')
        ->where('groups.group_id','=',$passGroupID)
        ->first();
        return $userGroup;
    }

    public function getAllGroups() {
        $allGroups = DB::table('groups')
        ->select('groups.group_id', 'groups.group_name', 'groups.group_desc', 'groups.public', 'subject.subject_name')
        ->join('subject', 'subject.subject_id', '=', 'groups.subject_id')
        ->get();
        return $allGroups;
    }
    public function getGroupMembers($passGroupID) {
        $groupMembers = DB::table('users')
        ->select('users.user_id', 'users.username', 'users.email', 'users.role')
        ->join('group_members', 'group_members.user_id','=','users.user_id')
        ->where('group_members.group_id','=',$passGroupID)
        ->orderBy('users.role', 'desc')
        ->get();
        return $groupMembers;
    }
    public function getAssignedQuiz($passGroupID) {
        $quiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.time_limit', 'quiz.user_id', 
        'quiz.group_id','subject.subject_name', 'game_mode.gamemode_name', 'game_mode.gamemode_id')
        ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
        ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
        ->where('quiz.group_id','=', $passGroupID)
        ->orderBy('quiz.quiz_id', 'asc')
        ->get();
        return $quiz;
    }

    /************************************************************************************************************/ 
}