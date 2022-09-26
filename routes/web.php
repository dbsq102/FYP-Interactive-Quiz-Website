<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\GroupsController;

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
    
    Route::get('/createquiz1',[GroupsController::class,'obtainData'])->name('createquiz1');
    Route::post('/addquiz', [QuizController::class, 'addQuiz'])->name('addquiz');
    Route::get('/add-quiz-view', [QuizController::class, 'addQuizView'])->name('add-quiz-view');
    Route::post('/update-ques-type', [QuizController::class, 'updateQuesType'])->name('update-ques-type');
    Route::post('/updatequiz', [QuizController::class, 'updateQuiz'])->name('updatequiz');
    Route::get('/editquiz/{passQuizID}', [QuizController::class, 'editQuizView'])->name('editquiz');
    Route::get('/save-multi-choice', [QuizController::class, 'saveMultiChoice'])->name('save-multi-choice');
    Route::post('/next-question', [QuizController::class, 'nextQuestion'])->name('next-question');
    Route::post('/prev-question', [QuizController::class, 'prevQuestion'])->name('prev-question');
    Route::get('/add-question', [QuizController::class, 'addNewQuestion'])->name('add-question');
});