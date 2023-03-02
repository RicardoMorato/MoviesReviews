<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(MovieController::class)->group(function () {
    Route::get('/movies', 'index');
    Route::get('/movies/{id}', 'show');
    Route::post('/movies', 'store');
    Route::put('/movies/{id}', 'update');
    Route::delete('/movies/{id}', 'destroy');
});

