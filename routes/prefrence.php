<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferenceController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/preferences', [PreferenceController::class, 'index']);
    Route::post('/user/preferences', [PreferenceController::class, 'store']);
    Route::get('/user/preferences/feed', [PreferenceController::class, 'fetchPersonalizedFeed']);
});