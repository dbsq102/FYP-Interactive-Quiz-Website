<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\ReportsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// All Routes which needs account login to access
Route::middleware('auth')->group(function(){
    Route::get('/managequiz',[QuizController::class,'obtainQuiz'])->name('managequiz');
    //Create a new quiz, then move to quiz editor
    Route::get('/createquiz',[GroupsController::class,'obtainData'])->name('createquiz');
    Route::post('/addquiz', [QuizController::class, 'addQuiz'])->name('addquiz');
    Route::get('/add-quiz-view', [QuizController::class, 'addQuizView'])->name('add-quiz-view');
    //Access quiz editor from managequiz, then update quiz properties and question types
    Route::post('/update-ques-type', [QuizController::class, 'updateQuesType'])->name('update-ques-type');
    Route::post('/updatequiz', [QuizController::class, 'updateQuiz'])->name('updatequiz');
    Route::get('/editquiz/{passQuizID}', [QuizController::class, 'editQuizView'])->name('editquiz');
    Route::get('/delete-quiz/{passQuizID}', [QuizController::class, 'deleteQuiz'])->name('delete-quiz');
    //Save different question types
    Route::get('/save-multi-choice', [QuizController::class, 'saveMultiChoice'])->name('save-multi-choice');
    Route::get('/save-sel-multi-ans', [QuizController::class, 'saveSelMultiAns'])->name('save-sel-multi-ans');
    Route::get('/save-card', [QuizController::class, 'saveCard'])->name('save-card');
    //Move to prev/next questions, also delete
    Route::post('/next-question', [QuizController::class, 'nextQuestion'])->name('next-question');
    Route::post('/prev-question', [QuizController::class, 'prevQuestion'])->name('prev-question');
    Route::post('/delete-question', [QuizController::class, 'deleteQuestion'])->name('delete-question');
    //Add a new question
    Route::get('/add-question', [QuizController::class, 'addNewQuestion'])->name('add-question');
    //Attempt a quiz
    Route::get('/standby/{passQuizID}', [PlayController::class, 'standbyView'])->name('standby');
    Route::get('/play-quiz/{passQuizID}', [PlayController::class, 'playView'])->name('play-quiz');
    //Check Answer
    Route::get('/check-answer/{passCorrect}', [PlayController::class, 'checkAnswer'])->name('check-answer');
    Route::get('/finish-quiz', [PlayController::class, 'finishQuiz'])->name('finish-quiz');
    //Groups related
    Route::get('/groups-view/{passGroupID}', [GroupsController::class, 'groupsView'])->name('groups-view');
    Route::get('/create-group-view', [GroupsController::class,'createGroupView'])->name('create-group-view');
    Route::post('/create-group', [GroupsController::class, 'createGroup'])->name('create-group');
    Route::get('/join-group/{passGroupID}', [GroupsController::class, 'joinGroup'])->name('join-group');
    Route::post('/add-to-group/{passGroupID}', [GroupsController::class, 'addToGroup'])->name('add-to-group');
    Route::get('/leave-group/{passUserID}', [GroupsController::class, 'leaveGroup'])->name('leave-group');
    Route::get('/delete-group/{passGroupID}', [GroupsController::class, 'deleteGroup'])->name('delete-group');
    //Reports related
    Route::get('/reports-view', [ReportsController::class, 'reportsView'])->name('reports-view');
});