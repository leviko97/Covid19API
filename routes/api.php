<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('auth')->group(function (){
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('user', [\App\Http\Controllers\AuthController::class, 'user'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->prefix('countries')->group(function () {
    Route::get('/', [\App\Http\Controllers\CovidStatsController::class, 'index']);
    Route::get('statistics', [\App\Http\Controllers\CovidStatsController::class, 'statistics']);
    Route::get('{id}/statistic', [\App\Http\Controllers\CovidStatsController::class, 'statistic']);
});




