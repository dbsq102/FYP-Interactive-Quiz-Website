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
        //Get current question count
        $quesCount = DB::table('question_bank')
        ->where('quiz_id', '=', $passQuizID)
        ->count();

        //Put sessions in case of use
        Session::put('playQuizId', $passQuizID);
        Session::put('playQuesNo', 1);
        return view('standby')->with(compact('quiz', 'quesCount'));
    }

    public function playView($passQuizID) {
        //Find question ID, question type and question
        $currQues = DB::table('question_bank')
        ->where('ques_no','=', 1)
        ->where('quiz_id','=', $passQuizID)
        ->select('type_id', 'ques_id', 'question')
        ->first();
        //Get current question's answers
        $currQuesAns = DB::table('answer_bank')
        ->where('ques_id', '=', $currQues->ques_id)
        ->orderBy('ans_no', 'asc')
        ->select('answer', 'ans_no', 'correct')
        ->get();
        //Get current question count
        $quesCount = DB::table('question_bank')
        ->where('quiz_id', '=', $passQuizID)
        ->count();
        //Put session data for later
        Session::put('playQuesID', $currQues->ques_id);

        return view ('playquiz')->with(compact('currQues', 'quesCount', 'currQuesAns'));
    }
}