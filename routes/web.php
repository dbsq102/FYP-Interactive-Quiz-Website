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
    Route::get('/createquiz2', [QuizController::class, 'addQuizView'])->name('createquiz2');
});