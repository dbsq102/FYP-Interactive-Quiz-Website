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
            ->select('history.history_id','history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->orderBy('date_taken', 'desc')
            ->limit(10)
            ->get();
            $countMath = $this->countSubject(1, 0);
            $countSci = $this->countSubject(2, 0);
            $countQuesMath = $this->countQues(1, 0);
            $countQuesSci = $this->countQues(2, 0);
            $sumMathScore = $this->sumScore(1, 0);
            $sumScienceScore = $this->sumScore(2, 0);
            $mathHistory = $this->getSubHistory(1, 0);
            $sciHistory = $this->getSubHistory(2, 0);
            $mathCountAttempt = $this->countAttempt(1, 0);
            $sciCountAttempt = $this->countAttempt(2, 0);
        } else {
            $history = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title', 'users.username')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->join('users', 'users.user_id','=', 'history.user_id')
            ->where('quiz.user_id','=', Auth::id())
            ->orderBy('date_taken', 'desc')
            ->limit(10)
            ->get();;
            $countMath = $this->countSubject(1, 1);
            $countSci = $this->countSubject(2, 1);
            $countQuesMath = $this->countQues(1, 1);
            $countQuesSci = $this->countQues(2, 1);
            $sumMathScore = $this->sumScore(1, 1);
            $sumScienceScore = $this->sumScore(2, 1);
            $mathHistory = $this->getSubHistory(1, 1);;
            $sciHistory = $this->getSubHistory(2, 1);;
            $mathCountAttempt = $this->countAttempt(1, 1);
            $sciCountAttempt = $this->countAttempt(2, 1);
        }

        return view('reports')->with(compact('history', 'countMath', 'countSci', 'countQuesMath', 'countQuesSci', 'sumMathScore', 'sumScienceScore', 'mathHistory', 
        'sciHistory', 'mathCountAttempt', 'sciCountAttempt'));
    }

    public function countSubject($subject_id, $role){
        if ($role == 0) {
            $countSub = DB::table('history')
            ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        } else {
            $countSub = DB::table('history')
            ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        }

        return $countSub;
    }

    public function countQues($subject_id, $role) {
        if ($role == 0) {
            //If student, count the amount of questions of quizzes of a certain subject that are attempted by themselves
            $countQuestion = DB::table('question_bank')
            ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
            ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->where('history.user_id','=',Auth::id())
            ->count();
        } else {
            //If instructor, count the amount of questions of quizzes of a certain subject that are attempted
            $countQuestion = DB::table('question_bank')
            ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
            ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        }

        return $countQuestion;
    }

    public function sumScore($subject_id, $role) {
        if ($role == 0) {
            //If student, get sum of their score on a certain subject
            $sumScore = DB::table('history')
            ->join('quiz','quiz.quiz_id','=','history.quiz_id')
            ->where('history.user_id','=',Auth::id())
            ->where('quiz.subject_id','=', $subject_id)
            ->sum('score');
        } else {
            //If instructor, get sum of all scores on a certain subject
            $sumScore = DB::table('history')
            ->join('quiz','quiz.quiz_id','=','history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->sum('score');            
        }

        return $sumScore;
    }

    public function getSubHistory($subject_id, $role) {
        if ($role == 0) {
            //If student, get own attempts on quizzes of a specific subject
            $subHistory = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->where('quiz.subject_id','=', $subject_id)
            ->orderBy('date_taken', 'desc')
            ->limit(10)
            ->get();
        } else {
            //If instructor, get all attempts of quizzes of a specific subject
            $subHistory = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->orderBy('date_taken', 'desc')
            ->limit(10)
            ->get();
        }

        return $subHistory;
    }

    public function countAttempt($subject_id, $role) {
        if ($role == 0) {
            //If student, count own attempts on quizzes of a specific subject
            $countAttempt = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->where('quiz.subject_id','=', $subject_id)
            ->orderBy('date_taken', 'desc')
            ->limit(10)
            ->count();
        } else {
            //If instructor, count all attempts on quizzes of a specific subject
            $countAttempt = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->orderBy('date_taken', 'desc')
            ->limit(10)
            ->count();
        }

        return $countAttempt;
    }

    public function quizChartsView($passHistoryID) {
        $quiz = DB::table('history')
        ->select('history.history_id','history.quiz_id', 'history.score', 'quiz.quiz_title')
        ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
        ->where('history.quiz_id','=',$passHistoryID)
        ->first();
        
        $countQues = DB::table('question_bank')
        ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
        ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
        ->where('history.history_id','=', $passHistoryID)
        ->count();

        return view('quizcharts')->with(compact('quiz', 'countQues'));
    }
}