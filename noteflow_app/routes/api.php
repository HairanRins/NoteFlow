<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
    ]);
});

Route::post('/auth/register', [RegisteredUserController::class, 'store']);
Route::post('/auth/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::get('/user', function () {
        return response()->json(request()->user());
    });

    Route::get('/workspace', WorkspaceController::class);
    Route::post('/sync', SyncController::class);

    Route::prefix('/notes')->group(function () {
        Route::post('/', [NoteController::class, 'store']);
        Route::get('/{id}', [NoteController::class, 'show']);
        Route::put('/{id}', [NoteController::class, 'update']);
        Route::delete('/{id}', [NoteController::class, 'destroy']);
    });
});
