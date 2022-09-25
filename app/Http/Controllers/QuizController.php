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
            return redirect('createquiz2')->with('success', 'A new quiz has been added.');
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
        $gamemodeID = DB::table('quiz')->orderBy('quiz_id', 'desc')->value('gamemode_id');
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
                
        return view ('createquiz2')->with(compact('quiz'))->with(compact('currQuiz'))->with(compact('groups'))
        ->with(compact('gamemodes'))->with(compact('subjects'))->with(compact('questype'));
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
        ->select('quiz_id', 'quiz_title', 'quiz_summary', 'items','time_limit', 'group_id')
        ->where('quiz_id', '=', $passQuizID)
        ->first();

        $groups = DB::select('select * from groups');
        $gamemodes = DB::select('select * from game_mode');
        $subjects = DB::select('select * from subject');
        $questype = DB::table('question_type')
        ->select('question_type.type_id')
        ->join('quiz', 'quiz.gamemode_id', '=', 'question_type.gamemode_id')
        ->get();
                
        return view ('createquiz2')->with(compact('quiz'))->with(compact('currQuiz'))->with(compact('groups'))
        ->with(compact('gamemodes'))->with(compact('subjects'))->with(compact('questype'));
    }

    public function updateQuiz(Request $request){
        //validate updatable information
        $userID = Auth::id();
        //If select ID from manage quizzes page, get quiz ID from there
        if(!empty($request->quiz_id)) {
            $quizID = DB::table('quiz')->where('quiz.quiz_id', '=', $request->quiz_id)->value('quiz_id');
        }
        //Otherwise, get latest quiz ID
        else {
            $quizID = DB::table('quiz')->orderBy('quiz_id', 'desc')->value('quiz_id');
        }
        $request->validate([
            'quiz_title'=>'required',
            'quiz_desc' =>'required',
            'gamemode_id'=>'required',
            'time_limit'=>'required',
            'subject_id'=>'required',
            'items'=>'required',
        ]);
        //Check if quiz exists
        $checkQuiz = Quiz::where('quiz_id','=',$quizID)->first();
        //Update quiz details
        if($checkQuiz){
            $checkQuiz->quiz_title = $request->quiz_title;
            $checkQuiz->quiz_summary = $request->quiz_desc;
            $checkQuiz->gamemode_id = $request->gamemode_id;
            $checkQuiz->group_id = $request->group_id;
            $checkQuiz->items = $request->items;
            $checkQuiz->subject_id = $request->subject_id;
            $checkQuiz->time_limit = $request->time_limit;

            $res = $checkStock->save();
        //Return message if quiz not found
        }else {
            return redirect('managequiz')->with('fail','Quiz does not exist, somehow...');
        } 

        if($res){
            return redirect('createquiz2')->with('success', 'Quiz has been updated successfully!');
        }

        else{
            return redirect('createquiz2')->with('fail','Unable to update quiz.');
        }
    }

    public function updateQuesType(Request $request) {
        //Check if question exists
        $checkQuestion = Question::where('ques_no','=', Session::get('quesNo'))->first();
        //Update question type
        if($checkQuestion){
            $checkQuestion->type_id = $request ->type_id;
            
            $res = $checkQuestion->save();

            if ($res) {
                return view ('createquiz2')->with('success', 'Question type updated.');
            }
        }
    }
}