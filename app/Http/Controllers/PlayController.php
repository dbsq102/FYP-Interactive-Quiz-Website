<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class PlayController extends Controller
{
    public function standbyView($passQuizID) {
        //Consider items if addable
        $quiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.subject_id', 'quiz.time_limit', 'subject.subject_name', 'game_mode.gamemode_name', 'game_mode.gamemode_id')
        ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
        ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
        ->where('quiz_id','=',$passQuizID)
        ->first();

        //Put sessions in case of use
        Session::put('playQuizId', $passQuizID);
        Session::put('playQuesNo', 1);
        return view('standby')->with(compact('quiz'));
    }
}