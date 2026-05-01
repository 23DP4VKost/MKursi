<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MathematicsPartController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TheoryController;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;

Route::middleware([
	EncryptCookies::class,
	AddQueuedCookiesToResponse::class,
	StartSession::class,
])->group(function () {
	Route::get('/tasks', [TaskController::class, 'index']);
	Route::post('/register', [AuthController::class, 'register']);
	Route::post('/login', [AuthController::class, 'login']);

	Route::get('/topics', [TopicController::class, 'index']);
	Route::get('/topics/{topic}/theories', [TheoryController::class, 'index']);
	Route::get('/theories/{theory}', [TheoryController::class, 'show']);

	Route::middleware('auth')->group(function () {
		Route::post('/logout', [AuthController::class, 'logout']);
		Route::get('/profile', [AuthController::class, 'profile']);

		Route::post('/admin/mathematics-parts', [MathematicsPartController::class, 'store']);
		Route::put('/admin/mathematics-parts/{mathematicsPart}', [MathematicsPartController::class, 'update']);
		Route::delete('/admin/mathematics-parts/{mathematicsPart}', [MathematicsPartController::class, 'destroy']);
		Route::post('/admin/topics', [TopicController::class, 'store']);
		Route::put('/admin/topics/{topic}', [TopicController::class, 'update']);
		Route::delete('/admin/topics/{topic}', [TopicController::class, 'destroy']);
		Route::get('/questions/my', [QuestionController::class, 'myQuestions']);
		Route::post('/questions', [QuestionController::class, 'ask']);
		Route::put('/questions/{question}', [QuestionController::class, 'update']);
		Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);
		Route::get('/admin/questions', [QuestionController::class, 'adminList']);
		Route::post('/admin/tasks', [TaskController::class, 'store']);
		Route::patch('/admin/questions/{question}/answer', [QuestionController::class, 'answer']);
		Route::delete('/admin/questions/{question}/answer', [QuestionController::class, 'deleteAnswer']);
	});
});

