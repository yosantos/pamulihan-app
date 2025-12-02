<?php

use App\Http\Controllers\Api\CampaignApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Campaign API endpoint with rate limiting
Route::post('/campaigns/send', [CampaignApiController::class, 'send'])
    ->middleware('throttle:60,1');
