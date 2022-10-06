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
        ->limit(3)
        ->get();

        Session::forget('quizID');
        Session::forget('quesNo');
        Session::forget('quesID');
        return view('home')->with(compact('quiz'));
    }
}
