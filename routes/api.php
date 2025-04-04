<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetCategoryController;
use App\Http\Controllers\Api\AssetConditionController;
use App\Http\Controllers\Api\AssetTypeController;
use App\Http\Controllers\Api\AssetLocationController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\AssetDocumentController;
use App\Http\Controllers\Api\AssetStatisticController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AssetLoanController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('asset-categories', AssetCategoryController::class);
    Route::apiResource('asset-conditions', AssetConditionController::class);
    Route::apiResource('asset-types', AssetTypeController::class);
    Route::apiResource('asset-locations', AssetLocationController::class);
    Route::apiResource('assets', AssetController::class);
    // Route::get('assets/{id}', [AssetController::class, 'show'])->name('api.assets.show');
    Route::get('assets/{id}/documents', [AssetDocumentController::class, 'index']);
    Route::post('assets/documents', [AssetDocumentController::class, 'store']);
    Route::delete('assets/documents/{id}', [AssetDocumentController::class, 'destroy']);
    Route::get('assets/{id}/documents', [AssetDocumentController::class, 'showByAsset']);
    Route::get('asset-statistics', [AssetStatisticController::class, 'index']);

    //get activity-logs
    Route::get('activity-logs', [ActivityLogController::class, 'index']);

    //Get users
    Route::get('users', [UserController::class, 'index']);

    // Peminjaman Aset
    Route::get('asset-loans', [AssetLoanController::class, 'index']);
    Route::post('asset-loans', [AssetLoanController::class, 'store']);
    Route::put('asset-loans/{id}', [AssetLoanController::class, 'update']);
    Route::delete('asset-loans/{id}', [AssetLoanController::class, 'destroy']);
});
