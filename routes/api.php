<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\MatchTaskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('member/login', [MemberAuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::put('me', [AuthController::class, 'updateMe']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('member/logout', [MemberAuthController::class, 'logout']);

    Route::get('members', [MemberController::class, 'fanzone_index']);
    Route::post('members', [MemberController::class, 'fanzone_create']);
    Route::get('members/{id}', [MemberController::class, 'fanzone_show']);
    Route::put('members/{id}', [MemberController::class, 'fanzone_edit']);
    Route::delete('members/{id}', [MemberController::class, 'fanzone_delete']);

    Route::get('admins', [AdminController::class, 'fanzone_index']);
    Route::post('admins', [AdminController::class, 'fanzone_create']);
    Route::get('admins/{id}', [AdminController::class, 'fanzone_show']);
    Route::put('admins/{id}', [AdminController::class, 'fanzone_edit']);
    Route::delete('admins/{id}', [AdminController::class, 'fanzone_delete']);

    Route::get('matches', [MatchesController::class, 'fanzone_index']);
    Route::post('matches', [MatchesController::class, 'fanzone_create']);
    Route::get('matches/{id}', [MatchesController::class, 'fanzone_show']);
    Route::put('matches/{id}', [MatchesController::class, 'fanzone_edit']);
    Route::delete('matches/{id}', [MatchesController::class, 'fanzone_delete']);

    Route::get('tasks', [TaskController::class, 'fanzone_index']);
    Route::post('tasks', [TaskController::class, 'fanzone_create']);
    Route::get('tasks/{id}', [TaskController::class, 'fanzone_show']);
    Route::put('tasks/{id}', [TaskController::class, 'fanzone_edit']);
    Route::delete('tasks/{id}', [TaskController::class, 'fanzone_delete']);

    Route::get('match-tasks', [MatchTaskController::class, 'fanzone_index']);
    Route::post('match-tasks', [MatchTaskController::class, 'fanzone_create']);
    Route::get('match-tasks/{id}', [MatchTaskController::class, 'fanzone_show']);
    Route::put('match-tasks/{id}', [MatchTaskController::class, 'fanzone_edit']);
    Route::delete('match-tasks/{id}', [MatchTaskController::class, 'fanzone_delete']);
});
