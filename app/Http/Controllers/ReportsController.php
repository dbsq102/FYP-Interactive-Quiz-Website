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
            ->orderBy('history_id', 'desc')
            ->limit(10)
            ->get();
            $countMath = $this->countQuiz(1, 0);
            $countSci = $this->countQuiz(2, 0);
            $countQuesMath = $this->countQues(1, 0);
            $countQuesSci = $this->countQues(2, 0);
            $sumMathScore = $this->sumScore(1, 0);
            $sumScienceScore = $this->sumScore(2, 0);
            $mathHistory = $this->getSubHistory(1, 0);
            $sciHistory = $this->getSubHistory(2, 0);
            $mathCountAttempt = $this->countAttempt(1, 0);
            $sciCountAttempt = $this->countAttempt(2, 0);
            
            //Group data is unused, therefore pass empty
            $countGroup = $this->countQuiz(0, 2);
            $countGroupQues = $this->countQues(0, 2);
            $sumGroupScore = $this->sumScore(0, 2);
            $groupHistory = $this->getSubHistory(0, 2);
            $groupCountAttempt = $this->countAttempt(0, 2);

        } else {
            $history = DB::table('history')
            ->select('history.history_id', 'history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title', 'users.username')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->join('users', 'users.user_id','=', 'history.user_id')
            ->where('quiz.user_id','=', Auth::id())
            ->orderBy('history_id', 'desc')
            ->limit(10)
            ->get();
            $countMath = $this->countQuiz(1, 1);
            $countSci = $this->countQuiz(2, 1);
            $countQuesMath = $this->countQues(1, 1);
            $countQuesSci = $this->countQues(2, 1);
            $sumMathScore = $this->sumScore(1, 1);
            $sumScienceScore = $this->sumScore(2, 1);
            $mathHistory = $this->getSubHistory(1, 1);;
            $sciHistory = $this->getSubHistory(2, 1);;
            $mathCountAttempt = $this->countAttempt(1, 1);
            $sciCountAttempt = $this->countAttempt(2, 1);

            $getGroup = DB::table('users')->where('user_id','=', Auth::id())->value('group_id');
            if (!$getGroup == NULL) {
                //Get group data
                $getGroupSub = DB::table('groups')
                ->where('user_id','=', Auth::id())
                ->value('subject_id');
                
                $groupSubName = DB::table('subject')
                ->where('subject_id','=', $getGroupSub)
                ->value('subject_name');

                $countGroup = $this->countQuiz($getGroupSub, 2);
                $countGroupQues = $this->countQues($getGroupSub, 2);
                $sumGroupScore = $this->sumScore($getGroupSub, 2);
                $groupHistory = $this->getSubHistory($getGroupSub, 2);
                $groupCountAttempt = $this->countAttempt($getGroupSub, 2);
            } else {
                $groupSubName = NULL;
                //Group data is unused, therefore pass empty
                $countGroup = $this->countQuiz(0, 2);
                $countGroupQues = $this->countQues(0, 2);
                $sumGroupScore = $this->sumScore(0, 2);
                $groupHistory = $this->getSubHistory(0, 2);
                $groupCountAttempt = $this->countAttempt(0, 2);
            }
        }

        return view('reports')->with(compact('history', 'countMath', 'countSci', 'countQuesMath', 'countQuesSci', 'sumMathScore', 'sumScienceScore', 'mathHistory', 
        'sciHistory', 'mathCountAttempt', 'sciCountAttempt', 'countGroup', 'countGroupQues', 'sumGroupScore', 
        'groupHistory', 'groupCountAttempt', 'groupSubName'));
    }

    public function countQuiz($subject_id, $role){
        if ($role == 0) {
            //Count quiz attempts made by student in a certain subject
            $countSub = DB::table('history')
            ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        } elseif ($role == 1) {
            //Count quiz attempts made by all students in a certain subject
            $countSub = DB::table('history')
            ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        } else {
            //Get group data
            $getGroupId = DB::table('groups')
            ->where('user_id', '=', Auth::id())
            ->value('group_id');
            //Count quiz attempts made by all students of a group
            $countSub = DB::table('history')
            ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
            ->join('users', 'users.user_id', '=', 'history.user_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->where('users.group_id','=', $getGroupId)
            ->count();
        }

        return $countSub;
    }

    public function countQues($subject_id, $role) {
        if ($role == 0) {
            //If student, count the total amount of questions of quizzes of a certain subject that are attempted by themselves
            $countQuestion = DB::table('question_bank')
            ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
            ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->where('history.user_id','=',Auth::id())
            ->count();
        } elseif ($role == 1) {
            //Count the total amount of questions of quizzes of a certain subject that are attempted all students
            $countQuestion = DB::table('question_bank')
            ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
            ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        } else {
            //Get group data
            $getGroupId = DB::table('groups')
            ->where('user_id', '=', Auth::id())
            ->value('group_id');
            //Count the total amount of questions of quizzes of a certain subject that are attempted by group members
            $countQuestion = DB::table('question_bank')
            ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
            ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
            ->join('users', 'users.user_id', '=', 'history.user_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->where('users.group_id','=', $getGroupId)
            ->count();
        }

        return $countQuestion;
    }

    public function sumScore($subject_id, $role) {
        if ($role == 0) {
            //If student, get sum of their scores on a certain subject
            $sumScore = DB::table('history')
            ->join('quiz','quiz.quiz_id','=','history.quiz_id')
            ->where('history.user_id','=',Auth::id())
            ->where('quiz.subject_id','=', $subject_id)
            ->sum('score');
        } elseif ($role == 1) {
            //Get sum of all scores on a certain subject
            $sumScore = DB::table('history')
            ->join('quiz','quiz.quiz_id','=','history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->sum('score');            
        } else {
            //Get group data
            $getGroupId = DB::table('groups')
            ->where('user_id', '=', Auth::id())
            ->value('group_id');
            $sumScore = DB::table('history')
            ->join('quiz','quiz.quiz_id','=','history.quiz_id')
            ->join('users', 'users.user_id', '=', 'history.user_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->where('users.group_id','=', $getGroupId)
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
            ->orderBy('history_id', 'desc')
            ->limit(10)
            ->get();
        } elseif ($role == 1) {
            //Get all attempts from all students of quizzes of a specific subject
            $subHistory = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->orderBy('history_id', 'desc')
            ->limit(10)
            ->get();
        } else {
            $getGroupId = DB::table('groups')
            ->where('user_id', '=', Auth::id())
            ->value('group_id');
            //Get all attempts from all students in a group of quizzes of a specific subject
            $subHistory = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->join('users', 'users.user_id', '=', 'history.user_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->where('users.group_id','=', $getGroupId)
            ->orderBy('history_id', 'desc')
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
            ->orderBy('history_id', 'asc')
            ->count();
        } elseif ($role == 1) {
            //Count all attempts on quizzes of a specific subject for all students
            $countAttempt = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->orderBy('history_id', 'asc')
            ->count();
        } else {
            //Count all attempts on quizzes of a specific subject for all students in a group
            //Get group data
            $getGroupId = DB::table('groups')
            ->where('user_id', '=', Auth::id())
            ->value('group_id');
            $countAttempt = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 'history.time_taken', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->join('users', 'users.user_id', '=', 'history.user_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->where('users.group_id','=', $getGroupId)
            ->orderBy('history_id', 'asc')
            ->count();
        }

        return $countAttempt;
    }

    public function quizChartsView($passHistoryID) {
        $quiz = DB::table('history')
        ->select('history.history_id','history.quiz_id', 'history.score', 'quiz.quiz_title', 'users.username')
        ->join('users', 'users.user_id', '=', 'history.user_id')
        ->join('quiz', 'quiz.quiz_id', '=', 'history.quiz_id')
        ->where('history.history_id','=',$passHistoryID)
        ->first();
        
        $countQues = DB::table('question_bank')
        ->join('history', 'history.quiz_id','=','question_bank.quiz_id')
        ->join('quiz', 'quiz.quiz_id','=','question_bank.quiz_id')
        ->where('history.history_id','=', $passHistoryID)
        ->count();

        return view('quizcharts')->with(compact('quiz', 'countQues'));
    }
}