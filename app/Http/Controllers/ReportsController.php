<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Groups;
use App\Models\Questions;
use App\Models\History;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class ReportsController extends Controller
{
    public function reportsView() {
        if (Auth::user()->role == 0) {
            $history = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->orderBy('date_taken', 'desc')
            ->limit(10)
            ->get();
            $countMath = DB::table('history')
            ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->where('quiz.subject_id','=', 1)
            ->count();
            $countSci = DB::table('history')
            ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->where('quiz.subject_id','=', 2)
            ->count();
            $countQuesMath = DB::table('question_bank')
            ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
            ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
            ->where('quiz.subject_id','=', 1)
            ->where('history.user_id','=',Auth::id())
            ->count();
            $countQuesSci = DB::table('question_bank')
            ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
            ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
            ->where('quiz.subject_id','=', 2)
            ->where('history.user_id','=',Auth::id())
            ->count();
            $sumMathScore = DB::table('history')
            ->join('quiz','quiz.quiz_id','=','history.quiz_id')
            ->where('history.user_id','=',Auth::id())
            ->where('quiz.subject_id','=', 1)
            ->sum('score');
            $sumScienceScore = DB::table('history')
            ->join('quiz','quiz.quiz_id','=','history.quiz_id')
            ->where('history.user_id','=',Auth::id())
            ->where('quiz.subject_id','=', 2)
            ->sum('score');
        } else {
            $history = NULL;
        }

        return view('reports')->with(compact('history', 'countMath', 'countSci', 'countQuesMath', 'countQuesSci', 'sumMathScore', 'sumScienceScore'));
    }

    public function changeView() {
        return view('reports');
    }
}