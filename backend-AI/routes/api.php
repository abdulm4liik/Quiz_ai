<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Api\AIResponseController;

Route::middleware(['auth:sanctum'])->group(function () {

   
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/ai-responses', [AIResponseController::class, 'store']);
    Route::get('/ai-responses', [AIResponseController::class, 'index']);
    Route::delete('/ai-responses/{response}', [AIResponseController::class, 'destroy']);
    Route::put('/ai-responses', [AIResponseController::class, 'answer']);

    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::put('/profile/name', [ProfileController::class, 'updateName']);
    
});
