<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\History;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class PlayController extends Controller
{
    public function standbyView($passQuizID) {
        //Consider items if addable
        $quiz = $this->getQuiz($passQuizID);
        //Get current question count
        $quesCount = $this->getQuesCount($passQuizID);

        //Put sessions in case of use, reset some sessions again just in case
        Session::forget('ansPhase');
        Session::forget('score');
        echo '<script>localStorage.removeItem("timelimit")</script>';
        Session::put('timelimit', $quiz->time_limit);
        Session::put('playQuizId', $passQuizID);
        Session::put('playQuesNo', 1);
        return view('standby')->with(compact('quiz', 'quesCount'));
    }
    
    public function playView($passQuizID) {
        //Find question ID, question type and question
        $currQues = $this->getCurrQues($passQuizID);
        //Get current question's answers
        $currQuesAns = $this->getCurrQuesAns($currQues);
        //Get current question count
        $quesCount = $this->getQuesCount($passQuizID);
        //Get gamemode ID for quiz
        $getGamemode = $this->getGamemode($passQuizID);
        //Put session data for later
        Session::put('playQuesID', $currQues->ques_id);
        return view ('playquiz')->with(compact('currQues', 'quesCount', 'currQuesAns', 'getGamemode'));
    }

    //Function to check answer
    public function checkAnswer($passCorrect) {
        //Forget alert in case
        Session::forget('alert');
        if($passCorrect == 1) {
            if(!Session::has('score')) {
                Session::put('score', 1);
            } else {
                Session::put('score', Session::get('score') + 1);
            }
        } else {
            if(!Session::has('score')) {
                Session::put('score', 0);
            }
        }
        //Get current question count
        $quesCount = $this->getQuesCount(Session::get('playQuizId'));
        //Put next question in session
        Session::put('playQuesNo', Session::get('playQuesNo') + 1);
        Session::forget('ansPhase');
        if ($quesCount >= Session::get('playQuesNo')) {
            return redirect()->route('play-quiz', Session::get('playQuizId'));
        }else {
            return redirect()->route('finish-quiz');
        }
    }

    //Function to check select multiple answer questions
    public function checkMultiAnswer(Request $request) {
        //Forget alert in case
        Session::forget('alert');
        $point = 0;
        //Get current question
        $currQues = $this->getCurrQues2();
        //Get correct values for answers of current question
        $checkAns = $this->checkMultiSelAns($currQues);
        
        //If check matches, move to next, if incorrect, set 0
        if ($request->has('ans1')) {
            if ($request->ans1 == 1) {
                $point++;
            }
        }
        if ($request->has('ans2')) {
            if ($request->ans2 == 1) {
                $point++;
            }
            
        }
        if ($request->has('ans3')) {
            if ($request->ans3 == 1) {
                $point++;
            }
        }
        if ($request->has('ans4')) {
            if ($request->ans4 == 1) {
                $point++;
            }
        }
        //Compare points
        if ($checkAns == $point) {
            if(!Session::has('score')) {
                Session::put('score', 1);
            } else {
                Session::put('score', Session::get('score') + 1);                
            }
            Session::put('alert', 'Correct!');
        } else {
            if(!Session::has('score')) {
                Session::put('score', 0);
            }
            Session::put('alert', 'Sorry, your answer was incorrect.');
        }
        //Get current question count
        $quesCount = $this->getQuesCount(Session::get('playQuizId'));
        //Put next question in session
        Session::put('playQuesNo', Session::get('playQuesNo') + 1);
        Session::forget('ansPhase');
        if ($quesCount >= Session::get('playQuesNo')) {
            return redirect()->route('play-quiz', Session::get('playQuizId'));
        }else {
            return redirect()->route('finish-quiz');
        }
    }

    //Function to finish quiz
    public function finishQuiz() {

        $userID = Auth::id();
        $quizID = Session::get('playQuizId');
        $score = Session::get('score');
        $getGamemodeID = DB::table('quiz')
        ->where('quiz_id','=', $quizID)
        ->value('gamemode_id');

        $history = new History();
        $history->user_id = $userID;
        $history->quiz_id = $quizID;
        $history->score = $score;
        $history->date_taken = date('Y-m-d H:i:s');

        $res = $history->save();
        if ($res) {
            Session::flash('message', 'You have finished the quiz. Your attempt has been saved!');
            Session::forget('score');
            return redirect()->route('home');
        } else {
            Session::flash('message', 'You have finished the quiz. Your attempt could not be saved...');
            Session::forget('score');
            return redirect()->route('home');
        }
    }
/************************************************************************************************************/
    //Functions to get all the necessary data
    //Function to get current quiz
    public function getQuiz($passQuizID) {
        $getQuiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.subject_id', 'quiz.time_limit', 'subject.subject_name', 'game_mode.gamemode_name', 'game_mode.gamemode_id')
        ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
        ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
        ->where('quiz_id','=',$passQuizID)
        ->first();
        return $getQuiz;
    }
    //Function to get current question count
    public function getQuesCount($passQuizID) {
        $quesCount = DB::table('question_bank')
        ->where('quiz_id', '=', $passQuizID)
        ->count();
        return $quesCount;
    }
    //Function to get current gamemode
    public function getGamemode($passQuizID) {
        $getGamemode = DB::table('quiz')
        ->where('quiz_id','=',$passQuizID)
        ->value('gamemode_id');
        return $getGamemode;
    }
    //Function to get current question
    public function getCurrQues($passQuizID) {
        $currQues = DB::table('question_bank')
        ->where('ques_no','=', Session::get('playQuesNo'))
        ->where('quiz_id','=', $passQuizID)
        ->select('type_id', 'ques_id', 'question')
        ->first();
        return $currQues;
    }
    //Function to get current question buut with different conditions
    public function getCurrQues2() {
        $currQues = DB::table('question_bank')
        ->where('ques_no', '=', Session::get('playQuesNo'))
        ->where('quiz_id','=', Session::get('playQuizId'))
        ->value('ques_id');
        return $currQues;
    }
    //Function to get current question's answers
    public function getCurrQuesAns($currQues) {
        $currQuesAns = DB::table('answer_bank')
        ->where('ques_id', '=', $currQues->ques_id)
        ->orderBy('ans_no', 'asc')
        ->select('answer', 'ans_no', 'correct')
        ->get();
        return $currQuesAns;
    }
    //Function to check multi select answers
    public function checkMultiSelAns($currQues){ 
        $checkAns = DB::table('answer_bank')
        ->where('ques_id', '=', $currQues)
        ->where('correct','=', 1)
        ->count();
        return $checkAns;
    }
/************************************************************************************************************/
}