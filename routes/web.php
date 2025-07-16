<?php

use App\Http\Controllers\Api\{
    AuthController,
    SongController,
    ArtistController,
    FavoriteController
};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    /* ---------- Auth ---------- */
    // Route::post('auth/login',  [AuthController::class, 'login']);
    // Route::middleware('auth:sanctum')->post('auth/logout', [AuthController::class, 'logout']);

    /* ---------- Songs ---------- */
    Route::get('songs',        [SongController::class, 'index']);
    Route::get('songs/{song}', [SongController::class, 'show']);

    Route::middleware(['auth:sanctum', 'role:CONTRIBUTOR,ADMIN'])->group(function () {
        Route::post('songs',           [SongController::class, 'store']);
        Route::patch('songs/{song}',   [SongController::class, 'update']);
        Route::delete('songs/{song}',  [SongController::class, 'destroy']);
    });

    /* ---------- Artists ---------- */
    Route::get('artists',                 [ArtistController::class, 'index']);
    Route::get('artists/{artist}/songs',  [ArtistController::class, 'songs']);

    Route::middleware(['auth:sanctum', 'role:CONTRIBUTOR,ADMIN'])
          ->post('artists', [ArtistController::class, 'store']);

    /* ---------- Favorites ---------- */
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('users/{user}/favorites',    [FavoriteController::class, 'index'])->middleware('can:view,user');
        Route::post('favorites',                [FavoriteController::class, 'store']);
        Route::delete('favorites/{song}',       [FavoriteController::class, 'destroy']);
    });
});
