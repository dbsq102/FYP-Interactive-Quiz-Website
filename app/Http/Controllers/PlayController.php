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
        $currQues = DB::table('question_bank')
        ->where('ques_no','=', Session::get('playQuesNo'))
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
        //Get gamemode ID for quiz
        $getGamemode = DB::table('quiz')
        ->where('quiz_id','=',$passQuizID)
        ->value('gamemode_id');
        //Put session data for later
        Session::put('playQuesID', $currQues->ques_id);
        return view ('playquiz')->with(compact('currQues', 'quesCount', 'currQuesAns', 'getGamemode'));
    }

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
        if($getGamemodeID == 2 || $getGamemodeID == 3) {
            $history->time_taken == NULL;
        } else {
            $history->time_taken == Session::get('time');
        }
        $history->date_taken = date('Y-m-d H:i:s');

        $res = $history->save();
        if ($res) {
            Session::flash('message', 'You have finished the quiz. Your attempt has been saved!');
            return redirect()->route('home');
        } else {
            Session::flash('message', 'You have finished the quiz. Your attempt could not be saved...');
            return redirect()->route('home');
        }
    }

    public function checkAnswer($passCorrect) {
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
        $quesCount = DB::table('question_bank')
        ->where('quiz_id', '=', Session::get('playQuizId'))
        ->count();
        //Put next question in session
        Session::put('playQuesNo', Session::get('playQuesNo') + 1);
        Session::forget('ansPhase');
        if ($quesCount >= Session::get('playQuesNo')) {
            return redirect()->route('play-quiz', Session::get('playQuizId'));
        }else {
            return redirect()->route('finish-quiz');
        }
    }

    public function checkMultiAnswer(Request $request) {
        $point = 0;
        //Get current question
        $currQues = DB::table('question_bank')
        ->where('ques_no', '=', Session::get('playQuesNo'))
        ->where('quiz_id','=', Session::get('playQuizId'))
        ->value('ques_id');
        //Get correct values for answers of current question
        $checkAns = DB::table('answer_bank')
        ->where('ques_id', '=', $currQues)
        ->where('correct','=', 1)
        ->count();
        
        //If check matches, move to next, if incorrect, set 0
        if ($request->has('answer1')) {
            if ($request->answer1 == 1) {
                $point++;
            }
        }
        if ($request->has('answer2')) {
            if ($request->answer2 == 1) {
                $point++;
            }
            
        }
        if ($request->has('answer3')) {
            if ($request->answer3 == 1) {
                $point++;
            }
        }
        if ($request->has('answer4')) {
            if ($request->answer4 == 1) {
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
        } else {
            if(!Session::has('score')) {
                Session::put('score', 0);
            }
        }
        //Get current question count
        $quesCount = DB::table('question_bank')
        ->where('quiz_id', '=', Session::get('playQuizId'))
        ->count();
        //Put next question in session
        Session::put('playQuesNo', Session::get('playQuesNo') + 1);
        Session::forget('ansPhase');
        if ($quesCount >= Session::get('playQuesNo')) {
            return redirect()->route('play-quiz', Session::get('playQuizId'));
        }else {
            return redirect()->route('finish-quiz');
        }
    }
}