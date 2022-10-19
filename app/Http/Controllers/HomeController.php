<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get user ID
        $userID = Auth::id();
        // Get quiz table data with subject data
        $quiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.user_id', 
        'quiz.group_id', 'subject.subject_name', 'game_mode.gamemode_name', 'game_mode.gamemode_id')
        ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
        ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
        ->orderBy('quiz.quiz_id', 'desc')
        ->limit(5)
        ->get();

        $getGroup = DB::table('groups')
        ->join('users', 'users.group_id','=','groups.group_id')
        ->where('users.user_id','=',Auth::id())
        ->value('groups.group_name');

        //Check if quiz is complete
        for ($i = 0; $i < count($quiz); $i++) {
            //Check if every question of every quiz has question filled
            $checkQuestions = DB::table('question_bank')
            ->where('quiz_id','=',$quiz[$i]->quiz_id)
            ->value('question');
            //If they are filled, check if every answer for every question has answer filled
            if($checkQuestions != NULL) {
                $question = DB::table('question_bank')->select('*')->where('quiz_id','=',$quiz[$i]->quiz_id)->get();
                for ($j = 0; $j < count($question); $j++) {
                    $checkAnswers = DB::table('answer_bank')
                    ->where('ques_id','=', $question[$j]->ques_id)
                    ->value('answer');
                    //If so, append a boolean check.
                    if ($checkAnswers != NULL) {
                        $completeCheck[$i] = 1;
                    } else {
                        $completeCheck[$i] = NULL;
                    }
                }
            } else {
                $completeCheck[$i] = NULL;
            }
        }

        Session::forget('quizID');
        Session::forget('quesNo');
        Session::forget('quesID');
        return view('home')->with(compact('quiz', 'completeCheck', 'getGroup'));
    }
}
