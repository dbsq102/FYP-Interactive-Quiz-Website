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
    //Reports view function
    public function reportsView($reportState) {
        if (Auth::user()->role == 0) {
            $history = DB::table('history')
            ->select('history.history_id','history.user_id', 'history.quiz_id', 'history.score', 
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
        } else {
            $history = DB::table('history')
            ->select('history.history_id', 'history.user_id', 'history.quiz_id', 'history.score', 
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
        }

        $groups = DB::table('groups')
        ->select('groups.group_id', 'groups.group_name', 'groups.group_desc', 'subject.subject_name')
        ->join('subject', 'subject.subject_id', '=', 'groups.subject_id')
        ->where('groups.user_id','=', Auth::id())
        ->get();

        return view('reports')->with(compact('history', 'countMath', 'countSci', 'countQuesMath', 'countQuesSci', 
        'sumMathScore', 'sumScienceScore', 'mathHistory', 'sciHistory', 'mathCountAttempt', 'sciCountAttempt', 
        'reportState', 'groups'));
    }

    //Group charts view function
    public function groupChartsView($passGroupID) {
        //Get group data
        $getGroupName = DB::table('groups')
        ->where('group_id', '=', $passGroupID)
        ->value('group_name');

        $getGroupSub = DB::table('groups')
        ->where('group_id','=', $passGroupID)
        ->value('subject_id');
        
        $groupSubName = DB::table('subject')
        ->where('subject_id','=', $getGroupSub)
        ->value('subject_name');
        
        $groupCountAttempt = $this->countGroupAttempt($getGroupSub, $passGroupID);

        //Get chart data
        //Bar chart data
        $cntGroupMem = $this->countGroupMembers($passGroupID);
        $cntGroupMemAttempts = $this->countGroupAttempts($passGroupID);
        //Line chart data
        $groupMember = $this->getGroupMemNames($passGroupID);
        if ($groupCountAttempt != 0) {
            for($i = 0; $i < $cntGroupMem; $i++) {
                $avgScore[$i] = $this->getGroupMemAvgScore($groupMember[$i]->user_id);    
            }
        } else {
            for($i = 0; $i < $cntGroupMem; $i++) {
                $avgScore[$i] = 0;  
            }
        }

        //Pie chart data
        $countGroupQues = $this->countGroupQues($passGroupID);
        $sumGroupScore = $this->sumGroupScore($passGroupID);

        return view('groupcharts')->with(compact('getGroupName','groupSubName','cntGroupMem','cntGroupMemAttempts',
        'groupMember','avgScore','countGroupQues','sumGroupScore', 'groupCountAttempt'));
    }

    //Quiz charts view function
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
/************************************************************************************************************/
    //Functions to get necessary data
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
        }

        return $countSub;
    }

    public function countGroupMembers($passGroupID){
        $countMembers = DB::table('group_members')
        ->join('users', 'users.user_id','=', 'group_members.user_id')
        ->where('group_members.group_id','=', $passGroupID)
        ->where('users.role','=',0)
        ->count();
        return $countMembers;
    }

    public function countGroupAttempts($passGroupID) {
        $distinctAttempts = DB::table('history')
        ->join('group_members', 'group_members.user_id','=', 'history.user_id')
        ->join('quiz', 'quiz.quiz_id','=','history.quiz_id')
        ->where('group_members.group_id', '=', $passGroupID)
        ->where('quiz.group_id','=', $passGroupID)
        ->distinct()
        ->count('history.user_id');
        return $distinctAttempts;
    }

    public function getGroupMemNames($passGroupID) {
        $getNames = DB::table('users')
        ->join('group_members', 'group_members.user_id','=','users.user_id')
        ->where('group_members.group_id','=', $passGroupID)
        ->where('users.role','=',0)
        ->select('users.user_id','users.username')
        ->get();
        return $getNames;
    }

    public function getGroupMemAvgScore($passUserID){
        $sumScore = DB::table('history')
        ->join('quiz','quiz.quiz_id','=','history.quiz_id')
        ->where('history.user_id','=',$passUserID)
        ->sum('score');
        $countAttempts = DB::table('history')
        ->where('user_id','=',$passUserID)
        ->count();
        $avgScore = $sumScore / $countAttempts; 
        return $avgScore;
    }

    public function sumGroupScore($passGroupID){
        $sumScore = DB::table('history')
        ->join('group_members', 'group_members.user_id', '=','history.user_id')
        ->join('quiz', 'quiz.quiz_id','=','history.quiz_id')
        ->where('group_members.group_id', '=', $passGroupID)
        ->where('quiz.group_id','=', $passGroupID)
        ->sum('history.score');
        
        return $sumScore;
    }

    public function countGroupQues($passGroupID){
        $countQuestion = DB::table('history')
        ->join('group_members', 'group_members.user_id','=','history.user_id')
        ->join('quiz', 'quiz.quiz_id','=','history.quiz_id')
        ->join('question_bank', 'question_bank.quiz_id', '=', 'quiz.quiz_id')
        ->where('group_members.group_id', '=', $passGroupID)
        ->where('quiz.group_id','=', $passGroupID)
        ->count();
        return $countQuestion;
    }

    public function countGroupAttempt($subject_id, $passGroupID){
        $countAttempt = DB::table('history')
        ->join('group_members', 'group_members.user_id','=','history.user_id')
        ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
        ->where('group_members.group_id', '=', $passGroupID)
        ->where('quiz.group_id','=', $passGroupID)
        ->count();

        return $countAttempt;
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
        }

        return $sumScore;
    }

    public function getSubHistory($subject_id, $role) {
        if ($role == 0) {
            //If student, get own attempts on quizzes of a specific subject
            $subHistory = DB::table('history')
            ->select('history.user_id', 'history.quiz_id', 'history.score', 
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
            ->select('history.user_id', 'history.quiz_id', 'history.score', 
            'history.date_taken', 'quiz.quiz_title')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
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
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('history.user_id','=', Auth::id())
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        } elseif ($role == 1) {
            //Count all attempts on quizzes of a specific subject for all students
            $countAttempt = DB::table('history')
            ->join('quiz', 'quiz.quiz_id','=', 'history.quiz_id')
            ->where('quiz.subject_id','=', $subject_id)
            ->count();
        }

        return $countAttempt;
    }
/************************************************************************************************************/
}