<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TheoryController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/profile', [AuthController::class, 'profile']);
Route::post('/kontakti', [ContactController::class, 'store']);
Route::get('/topics', [TopicController::class, 'index']);
Route::get('/topics/{topic}/theories', [TheoryController::class, 'index']);
Route::get('/theories/{theory}', [TheoryController::class, 'show']);

