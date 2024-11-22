<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AIResponseController;

Route::middleware(['auth:sanctum'])->group(function () {

   
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/ai-responses', [AIResponseController::class, 'store']);
    Route::get('/ai-responses', [AIResponseController::class, 'index']);

    Route::put('/profile', [ProfileController::class, 'updateProfile']);
});
