<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TheoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Auth\AuthController;

/*API Routes*/


//Auth
Route::post('/register', [AuthController::class, 'register']); //reg
Route::post('/login', [AuthController::class, 'login']);       //login
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');//logout

// Protect. routes
Route::middleware('auth:sanctum')->group(function () {

    //students
    Route::get('/topics', [TopicController::class, 'index']); //
    Route::get('/topics/{topic}/theories', [TheoryController::class, 'index']); 
    Route::get('/topics/{topic}/tasks', [TaskController::class, 'index']);      
    Route::post('/results', [ResultController::class, 'store']);           
    Route::post('/questions', [QuestionController::class, 'store']);            

 
    //Admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/topics', [TopicController::class, 'store']);          
        Route::put('/topics/{topic}', [TopicController::class, 'update']);  
        Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);

        Route::post('/theories', [TheoryController::class, 'store']);       
        Route::put('/theories/{theory}', [TheoryController::class, 'update']); 
        Route::delete('/theories/{theory}', [TheoryController::class, 'destroy']); 

        Route::post('/tasks', [TaskController::class, 'store']);          
        Route::put('/tasks/{task}', [TaskController::class, 'update']); 
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']); 
    });

});
