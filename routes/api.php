<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);


Route::middleware(['auth:sanctum','checkAuth'])->group(function() {
    Route::post('/candidate',[CandidateController::class, 'store']);
    Route::get('/candidate',[CandidateController::class, 'getCandidates']);
    Route::get('/candidate/{id}',[CandidateController::class, 'getCandidate']);

    Route::delete('/logout',[AuthController::class, 'logout']);
});
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
