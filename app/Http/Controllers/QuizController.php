<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class QuizController extends Controller
{
    //Get quiz table data
    public function obtainQuiz() {
        if (Auth::check()) {
            // Get User Id
            $userID = Auth::id();
            // Get quiz table data with subject data
            $quiz = DB::table('quiz')
            ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.items','quiz.time_limit', 'quiz.group_id', 'subject.subject_name', 'game_mode.gamemode_name')
            ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
            ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
            ->where('quiz.user_id', '=', $userID)
            ->orderBy('quiz.user_id', 'asc')
            ->get();

            return view("managequiz")->with(compact('quiz'));
        }
    }

    //Upon initial page button press
    public function addQuiz(Request $request) {
        // Get User Id
        $userID = Auth::id();
        $request->validate([
            'quiz_title'=>'required',
            'quiz_desc' =>'required',
            'gamemode_id'=>'required',
            'time_limit'=>'required',
            'subject_id'=>'required',
            'items'=>'required',
        ]);
        $quiz = new Quiz();
        $quiz->quiz_title = $request->quiz_title;
        $quiz->quiz_summary = $request->quiz_desc;
        $quiz->gamemode_id = $request->gamemode_id;
        $quiz->group_id = $request->group_id;
        $quiz->items = $request->items;
        $quiz->subject_id = $request->subject_id;
        $quiz->time_limit = $request->time_limit;
        $quiz->user_id = $userID;

        $res = $quiz->save();
        if($res){
            return redirect()->route('add-quiz-view')->with('success', 'A new quiz has been added.');
        }
        else {
            return with('fail', 'Failed to add quiz.');
        } 
    }

    //Used to display quiz editor immediately after creating a new quiz
    public function addQuizView(){
        //Add the first question to the database
        //Get most recent quiz ID
        $quizID = DB::table('quiz')->orderBy('quiz_id', 'desc')->value('quiz_id');
        //Get most recent quiz's set gamemode
        $gamemodeID = DB::table('quiz')->where('quiz_id', '=', $quizID)->value('gamemode_id');
        //Check if question exists via quiz ID in question table
        $checkQuestion = Question::where('quiz_id', '=', $quizID)->first();
        //Add question 1 if no such thing already exists
        if(!$checkQuestion){
            $firstQues = new Question();
            $firstQues->quiz_id = $quizID;
            if($gamemodeID == 1 || $gamemodeID == 3) {
                $firstQues->type_id = 1;
            }else {
                $firstQues->type_id = 3;
            }
            $firstQues->question = NULL;
            $firstQues->ques_img = NULL;
            $firstQues->ques_no = 1;
            
            $res = $firstQues->save();
        }
        //Get necessary database data for next page
        $quiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.items','quiz.time_limit', 'quiz.group_id', 'subject.subject_name', 'game_mode.gamemode_name', 'game_mode.gamemode_id')
        ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
        ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
        ->get();

        $currQuiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.gamemode_id', 'quiz.items','quiz.time_limit', 
        'quiz.subject_id', 'quiz.group_id', 'question_bank.type_id')
        ->join('question_bank', 'question_bank.quiz_id', '=', 'quiz.quiz_id')
        ->where('quiz.quiz_id', '=', $quizID)
        ->first();

        $groups = DB::select('select * from groups');
        $gamemodes = DB::select('select * from game_mode');
        $subjects = DB::select('select * from subject');
        $questype = DB::table('question_type')
        ->select('question_type.type_id', 'question_type.type_name', 'question_type.gamemode_id')
        ->join('game_mode', 'question_type.gamemode_id', '=','game_mode.gamemode_id')
        ->get();

        Session::put('quesNo', 1);
        Session::put('quizID', $quizID);
                
        return view('quizeditor')->with(compact('quiz', 'currQuiz', 'groups', 'gamemodes', 'subjects', 'questype'));
    }

    //Used to display quiz editor via manage quiz tab
    public function editQuizView($passQuizID){    
        //Get necessary database data for next page
        $quiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.items','quiz.time_limit', 'quiz.group_id', 'subject.subject_name', 'game_mode.gamemode_name', 'game_mode.gamemode_id')
        ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
        ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
        ->get();

        $currQuiz = DB::table('quiz')
        ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.gamemode_id', 'quiz.items','quiz.time_limit', 
        'quiz.subject_id', 'quiz.group_id', 'question_bank.type_id')
        ->join('question_bank', 'question_bank.quiz_id', '=', 'quiz.quiz_id')
        ->where('quiz.quiz_id', '=', $passQuizID)
        ->first();

        $groups = DB::select('select * from groups');
        $gamemodes = DB::select('select * from game_mode');
        $subjects = DB::select('select * from subject');
        $questype = DB::table('question_type')
        ->select('question_type.type_id', 'question_type.type_name', 'question_type.gamemode_id')
        ->join('game_mode', 'question_type.gamemode_id', '=','game_mode.gamemode_id')
        ->get();

        Session::put('quesNo', 1);
        Session::put('quizID', $passQuizID);
                
        return view('quizeditor')->with(compact('quiz', 'currQuiz', 'groups', 'gamemodes', 'subjects', 'questype'));
    }

    public function updateQuiz(Request $request){
        //validate updatable information
        $request->validate([
            'update_quiz_title'=>'required',
            'update_quiz_desc' =>'required',
            'update_gamemode_id'=>'required',
            'update_time_limit'=>'required',
            'update_subject_id'=>'required',
            'update_items'=>'required',
        ]);
        //Find quiz via ID
        $checkQuiz = Quiz::find(Session::get('quizID'));
        //Update quiz details
        if($checkQuiz){
            $checkQuiz->quiz_title = $request->update_quiz_title;
            $checkQuiz->quiz_summary = $request->update_quiz_desc;
            $checkQuiz->gamemode_id = $request->update_gamemode_id;
            $checkQuiz->group_id = $request->update_group_id;
            $checkQuiz->items = $request->update_items;
            $checkQuiz->subject_id = $request->update_subject_id;
            $checkQuiz->time_limit = $request->update_time_limit;

            $res = $checkQuiz->save();

            if($res){
                Session::flash('success', 'Quiz has been updated successfully!');
                return redirect()->route('editquiz', Session::get('quizID'));
            }

            else{
                Session::flash('success', 'fail','Unable to update quiz.');
                return redirect()->route('editquiz', Session::get('quizID'));
            }
        //Return message if quiz not found
        }else {
            return redirect('managequiz')->with('fail','Quiz does not exist, somehow...');
        } 
    }

    public function updateQuesType(Request $request) {
        //Check if question exists
        $checkQuestion = Question::where('ques_no','=', Session::get('quesNo'))->where( 'quiz_id', '=', Session::get('quizID'))->first();
        //Update question type
        if($checkQuestion){
            $checkQuestion->type_id = $request->question_type;
            
            $res = $checkQuestion->save();

            if ($res) {
                return redirect()->route('editquiz', Session::get('quizID'))->with('success', 'Question type updated.');
            }
            else {
                return redirect()->route('editquiz', Session::get('quizID'))->with('fail', 'Question type failed to update.');
            }
        }
    }

    public function addMultiChoice(Request $request) {
        switch($request->input('action')) {
            case 'previous':
                //Save to database

                Session::put('quizID', Session::get('quizID') - 1);
                return redirect()->route('editquiz', Session::get('quizID'));
                break;
            case 'next':
                //Save to database
                
                Session::put('quizID', Session::get('quizID') + 1);
                return redirect()->route('editquiz', Session::get('quizID'));
                break;
        }
    }
}