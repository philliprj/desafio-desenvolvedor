<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UploadHistoryController;
use App\Http\Controllers\UploadDataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/upload', [UploadController::class, 'store']);
Route::get('/uploads', [UploadDataController::class, 'index']);
Route::get('/upload/{id}/status', [UploadHistoryController::class, 'status']);
Route::get('/upload-history', [UploadHistoryController::class, 'index']);
