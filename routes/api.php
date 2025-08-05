<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::middleware('auth.mongodb')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);

// Rota para obter o token CSRF
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware('auth.mongodb')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index']);
    Route::get('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'show']);
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store']);
    Route::put('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'destroy']);
});
