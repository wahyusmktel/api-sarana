<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetCategoryController;
use App\Http\Controllers\Api\AssetConditionController;
use App\Http\Controllers\Api\AssetTypeController;
use App\Http\Controllers\Api\AssetLocationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('asset-categories', AssetCategoryController::class);
    Route::apiResource('asset-conditions', AssetConditionController::class);
    Route::apiResource('asset-types', AssetTypeController::class);
    Route::apiResource('asset-locations', AssetLocationController::class);
});
