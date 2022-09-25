<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use DB;
use Auth;

class QuizController extends Controller
{
    //Get quiz table data
    public function obtainQuiz() {
        if(Auth::check()){
            // Get User Id
            $userID = Auth::id();
            // Get quiz table data with subject data
            $quiz = DB::table('quiz')
            ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.items','quiz.time_limit', 'quiz.group_id', 'subject.subject_name', 'game_mode.gamemode_name')
            ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
            ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
            ->where('quiz.user_id', '=', $userID)
            ->get();

            return view("managequiz")->with(compact('quiz'));
        }
    }

    //Upon initial page button press
    public function addQuiz(Request $request) {
        // Get User Id
        $userID = Auth::id();
        $request->validate([
            'quiz_title'=>'required',
            'quiz_desc' =>'required',
            'gamemode_id'=>'required',
            'subject_id'=>'required',
            'items'=>'required',
        ]);
        $quiz = new Quiz();
        $quiz->quiz_title = $request->quiz_title;
        $quiz->quiz_summary = $request->quiz_desc;
        $quiz->gamemode_id = $request->gamemode_id;
        $quiz->group_id = $request->group_id;
        $quiz->items = $request->items;
        $quiz->subject_id = $request->subject_id;
        $quiz->time_limit = 0;
        $quiz->user_id = $userID;

        $res = $quiz->save();
        if($res){
            return redirect('createquiz2')->with('success', 'A new quiz has been added.');
        }
        else {
            return with('fail', 'Failed to add quiz.');
        } 
    }

    public function addQuizView(){
        return view ('createquiz2');
    }
}