<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class QuizController extends Controller
{
    //Get quiz table data
    public function obtainQuiz() {
        if (Auth::check()) {
            // Get user ID
            $userID = Auth::id();
            // Get quiz table data with subject data
            $quiz = DB::table('quiz')
            ->select('quiz.quiz_id', 'quiz.quiz_title', 'quiz.quiz_summary', 'quiz.items','quiz.time_limit', 'quiz.group_id','subject.subject_name', 'game_mode.gamemode_name')
            ->join('subject', 'quiz.subject_id', '=', 'subject.subject_id')
            ->join('game_mode', 'quiz.gamemode_id', '=', 'game_mode.gamemode_id')
            ->orderBy('quiz.quiz_id', 'asc')
            ->get();
            Session::forget('quizID');
            Session::forget('quesNo');
            Session::forget('quesID');
            return view("managequiz")->with(compact('quiz'));
        }
    }

    //Upon initial page button press
    public function addQuiz(Request $request) {
        // Get user ID
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

        //Find first question based on ques no and quiz ID
        $currQues = DB::table('question_bank')
        ->where('ques_no','=', 1)
        ->where('quiz_id','=', $quizID)
        ->select('type_id', 'ques_id')
        ->first();

        //Put necessary sessions for future use
        Session::put('quesNo', 1);
        Session::put('quizID', $quizID);
        Session::put('quesID', $currQues->ques_id);

        return view('quizeditor')->with(compact('quiz', 'currQuiz', 'currQues', 'groups', 'gamemodes', 'subjects', 'questype'));
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

        //Update necessary sessions if it doesn't already exist
        if (!Session::has('quesNo') || !Session::has('quizID') || !Session::has('quesID')) {
            //Find question ID
            $currQues = DB::table('question_bank')
            ->where('ques_no','=', 1)
            ->where('quiz_id','=', $passQuizID)
            ->select('type_id', 'ques_id')
            ->first();

            Session::put('quesNo', 1);
            Session::put('quizID', $passQuizID);
            Session::put('quesID', $currQues->ques_id);
        } else{
            
            //Find question ID
            $currQues = DB::table('question_bank')
            ->where('ques_no','=', Session::get('quesNo'))
            ->where('quiz_id','=', $passQuizID)
            ->select('type_id', 'ques_id')
            ->first();
            Session::put('quesID', $currQues->ques_id);
        }

        return view('quizeditor')->with(compact('quiz', 'currQuiz', 'currQues', 'groups', 'gamemodes', 'subjects', 'questype'));
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
            //update question type automatically
            if ($request->update_gamemode_id == 1 || $request->update_gamemode_id == 3) {
                $checkQuestion = Question::where('ques_no','=', Session::get('quesNo'))->where( 'quiz_id', '=', Session::get('quizID'))->first();
                //Update question type
                if($checkQuestion){
                    $checkQuestion->type_id = 1;
            
                    $checkQuestion->save();
                }
            }
            else if ($request->update_gamemode_id == 2) {
                $checkQuestion = Question::where('ques_no','=', Session::get('quesNo'))->where( 'quiz_id', '=', Session::get('quizID'))->first();
                //Update question type
                if($checkQuestion){
                    $checkQuestion->type_id = 3;
            
                    $checkQuestion->save();
                }
            }
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
                Session::flash('fail','Unable to update quiz.');
                return redirect()->route('editquiz', Session::get('quizID'));
            }
        //Return message if quiz not found
        }else {
            Session::flash('fail','Quiz does not exist.');
            return redirect('managequiz');
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

    //function to save multi choice question
    public function saveMultiChoice(Request $request) {
        //Check if question exists in database
        $checkQuestion = Question::where('ques_no','=', Session::get('quesNo'))->where( 'quiz_id', '=', Session::get('quizID'))->first();
        if($checkQuestion) {
            $checkQuestion->question = $request->question_title;
            //Save question info
            $res = $checkQuestion->save();
            if ($res) {
                for ($i = 1; $i <= 4; $i++) {
                    $checkAnswer = Answer::where('ques_id', '=', Session::get('quesID'))->where('ans_no', '=' , $i)->first();
                    if($checkAnswer) {
                        $checkAnswer->ques_id = Session::get('quesID');
                        //Values are 1, 2, 3, 4. If it matches with the loop, set as such.
                        if ($request->correct == $i) {
                            $checkAnswer->correct = 1;
                        }
                        else {
                            $checkAnswer->correct = 0;
                        }
                        switch($i) {
                            case '1':                        
                                $tempAns = $request->answer1;
                                break;
                            case '2':
                                $tempAns = $request->answer2;
                                break;
                            case '3':
                                $tempAns = $request->answer3;
                                break;
                            case '4':
                                $tempAns = $request->answer4;
                                break;
                        }
                        $checkAnswer->answer = $tempAns;
                        $checkAnswer->ans_no = $i;
                        $checkAnswer->save(); 
                    }
                    else {
                        $newAns = new Answer();
                        $newAns->ques_id = Session::get('quesID');
                        //Values are 1, 2, 3, 4. If it matches with the loop, set as such.
                        if ($request->correct == $i) {
                            $newAns->correct = 1;
                        }
                        else {
                            $newAns->correct = 0;
                        }
                        switch($i) {
                            case '1':                        
                                $tempAns = $request->answer1;
                                break;
                            case '2':
                                $tempAns = $request->answer2;
                                break;
                            case '3':
                                $tempAns = $request->answer3;
                                break;
                            case '4':
                                $tempAns = $request->answer4;
                                break;
                        }
                        $newAns->answer = $tempAns;
                        $newAns->ans_no = $i;
                        $newAns->save(); 
                    }
                }
                return redirect()->route('editquiz', Session::get('quizID'));
            }   
        }
    }

    public function prevQuestion() {
        //decrease session number
        Session::put('quesNo', Session::get('quesNo') - 1);
        return redirect()->route('editquiz', Session::get('quizID'));
    }

    public function nextQuestion(){
        //increase session number
        Session::put('quesNo', Session::get('quesNo') + 1);
        //Check if question exists, if not, add question
        $checkQuestion = Question::where('ques_no','=', Session::get('quesNo'))->where('quiz_id', '=', Session::get('quizID'))->first();
        if (!$checkQuestion) {
            return redirect()->route('add-question');
        }
        else {
            return redirect()->route('editquiz', Session::get('quizID'));
        }
    }

    public function addNewQuestion() {
        //Get most recent quiz's set gamemode
        $gamemodeID = DB::table('quiz')->where('quiz_id', '=', Session::get('quizID'))->value('gamemode_id');
        $newQues = new Question();
        $newQues->quiz_id = Session::get('quizID');
        if($gamemodeID == 1 || $gamemodeID == 3) {
            $newQues->type_id = 1;
        }else {
            $newQues->type_id = 3;
        }
        $newQues->question = NULL;
        $newQues->ques_img = NULL;
        $newQues->ques_no = Session::get('quesNo');
        
        $res = $newQues->save();
        
        if ($res) {
            Session::flash('success','Question added successfully.');
            return redirect()->route('editquiz', Session::get('quizID'));
        }
        else {
            Session::flash('fail','Unable to add question.');
            return redirect()->route('editquiz', Session::get('quizID'));
        }
    }
}