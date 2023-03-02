<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/test-login', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store'])->withoutMiddleware('auth:sanctum');
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::post('/users/login', [UserController::class, 'login'])->withoutMiddleware('auth:sanctum');;
});

Route::controller(MovieController::class)->group(function () {
    Route::get('/movies', 'index');
    Route::get('/movies/{id}', 'show');
    Route::post('/movies', 'store');
    Route::put('/movies/{id}', 'update');
    Route::delete('/movies/{id}', 'destroy');
});

