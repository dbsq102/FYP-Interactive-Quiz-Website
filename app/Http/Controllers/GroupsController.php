<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class GroupsController extends Controller
{
    //Get groups and game mode table data
    public function obtainData() {
        $groups = DB::select('select * from groups');
        $gamemodes = DB::select('select * from game_mode');
        $subjects = DB::select('select * from subject');
        Session::forget('quizID');
        Session::forget('quesNo');
        Session::forget('quesID');
        return view("createquiz1")->with(compact('groups'))->with(compact('gamemodes'))->with(compact('subjects'));
    }
}