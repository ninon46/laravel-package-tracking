<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrackingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/track/{trackingNumber}', [TrackingController::class, 'track']);
Route::post('/scan-qr', [TrackingController::class, 'scanQRCode']);
