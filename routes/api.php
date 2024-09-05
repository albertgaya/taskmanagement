<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('tasks')->group(function () {
    Route::post('/', [TaskController::class, 'store']);
    Route::put('/{taskId}', [TaskController::class, 'update']);
    Route::delete('/{taskId}', [TaskController::class, 'destroy']);
    Route::get('/', [TaskController::class, 'index']);
});
