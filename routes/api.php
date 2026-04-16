<?php

use App\Http\Controllers\Api\V1\AssetController;
use App\Http\Controllers\Api\V1\AssetTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — v1
| Base URL: /api/v1
|--------------------------------------------------------------------------
|
| Role matrix:
|   admin      → full CRUD (assets & asset_types)
|   supervisor → read all + update asset status (PATCH)
|   technician → read only
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // ── Read (admin, supervisor, technician) ──────────────────────────────
    Route::middleware('role:admin|supervisor|technician')->group(function () {
        Route::get('assets',            [AssetController::class, 'index']);
        Route::get('assets/{asset}',    [AssetController::class, 'show']);
        Route::get('asset-types',             [AssetTypeController::class, 'index']);
        Route::get('asset-types/{asset_type}', [AssetTypeController::class, 'show']);
    });

    // ── Partial update — status only (admin, supervisor) ──────────────────
    Route::middleware('role:admin|supervisor')->group(function () {
        Route::patch('assets/{asset}', [AssetController::class, 'update']);
    });

    // ── Full write (admin only) ────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::post('assets',              [AssetController::class, 'store']);
        Route::put('assets/{asset}',       [AssetController::class, 'update']);
        Route::delete('assets/{asset}',    [AssetController::class, 'destroy']);

        Route::post('asset-types',                  [AssetTypeController::class, 'store']);
        Route::put('asset-types/{asset_type}',      [AssetTypeController::class, 'update']);
        Route::delete('asset-types/{asset_type}',   [AssetTypeController::class, 'destroy']);
    });
});
