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
}