<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;

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

    Route::post('/users/login', [UserController::class, 'login'])->withoutMiddleware('auth:sanctum');
    
    Route::get('/reviews', [ReviewController::class, 'index'])->withoutMiddleware('auth:sanctum');
    Route::get('/reviews/{id}', [ReviewController::class, 'show'])->withoutMiddleware('auth:sanctum');
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
});

Route::controller(MovieController::class)->group(function () {
    Route::get('/movies', 'index');
    Route::get('/movies/{id}', 'show');
    Route::post('/movies', 'store');
    Route::put('/movies/{id}', 'update');
    Route::delete('/movies/{id}', 'destroy');
});
